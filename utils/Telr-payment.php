<?php
if (!defined('ABSPATH'))
    exit;

class Telr_Payment
{
    private $auth_token;
    private $store_id;
    private $api_url;
    private $mode;
    private $client_domain;
    private $webhook_key;

    public function __construct()
    {
        // Fetch values from the WordPress Options API
        $this->auth_token = get_option('telr_auth_key') ?? '';
        $this->store_id = get_option('telr_store_id') ?? '';
        $this->api_url = get_option('telr_api_url') ?? '';
        $this->mode = get_option('telr_mode') ?? '1'; // default mode
        $this->webhook_key = get_option('telr_webhook') ?? '';
        $this->client_domain = get_site_url() ?? '';

        // Check required settings
        if (
            empty($this->auth_token) ||
            empty($this->store_id) ||
            empty($this->api_url) ||
            $this->mode === ''
        ) {
            // If in admin panel, show notice
            if (is_admin()) {
                add_action('admin_notices', function () {
                    echo '<div class="notice notice-error"><p><strong>Telr Payment Plugin:</strong> Missing required settings. Please configure the plugin under Settings.</p></div>';
                });
            }

            // Log the issue and prevent further usage
            error_log('Telr Payment Plugin: Missing required configuration values.');
            return; // Stop execution to avoid fatal error
        }
    }

    public function generate_cart_id()
    {
        return uniqid('', true);
    }

    public function make_payment($amount, $customer = [], $cart_id)
    {


        $payload = [
            'method' => 'create',
            'store' => $this->store_id,
            'authkey' => $this->auth_token,
            'framed' => 0,
            'order' => [
                'cartid' => $cart_id,
                'test' => $this->mode,
                'amount' => $amount,
                'currency' => 'AED',
                'description' => 'OMT Payment',
            ],
            'customer' => $customer,
            'return' => [
                'authorised' => $this->client_domain . '/payment-success',
                'declined' => $this->client_domain . '/payment-failed',
                'cancelled' => $this->client_domain . '/payment-cancelled',
            ],
        ];
        // echo "Payload: " . json_encode($payload); // Debugging line


        $response = wp_remote_post($this->api_url, [
            'headers' => [
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
            ],
            'body' => json_encode($payload),
            'timeout' => 20,
        ]);

        if (is_wp_error($response)) {
            throw new Exception('Payment Error: ' . $response->get_error_message());
        }

        return json_decode(wp_remote_retrieve_body($response), true);
    }

    public function check_payment($ref_id)
    {
        $payload = [
            'method' => 'check',
            'store' => $this->store_id,
            'authkey' => $this->auth_token,
            'framed' => 0,
            'order' => [
                'ref' => $ref_id,
            ],
        ];

        $response = wp_remote_post($this->api_url, [
            'headers' => [
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
            ],
            'body' => json_encode($payload),
            'timeout' => 20,
        ]);

        if (is_wp_error($response)) {
            throw new Exception('Check Payment Error: ' . $response->get_error_message());
        }

        return json_decode(wp_remote_retrieve_body($response), true);
    }

    public function sign_data($body_data)
    {
        $fields = [
            $this->webhook_key,
            $body_data['tran_store'] ?? '',
            $body_data['tran_type'] ?? '',
            $body_data['tran_class'] ?? '',
            $body_data['tran_test'] ?? '',
            $body_data['tran_ref'] ?? '',
            $body_data['tran_prevref'] ?? '',
            $body_data['tran_firstref'] ?? '',
            $body_data['tran_currency'] ?? '',
            $body_data['tran_amount'] ?? '',
            $body_data['tran_cartid'] ?? '',
            $body_data['tran_desc'] ?? '',
            $body_data['tran_status'] ?? '',
            $body_data['tran_authcode'] ?? '',
            $body_data['tran_authmessage'] ?? '',
        ];

        $data_string = implode(':', $fields);
        $computed_hash = sha1($data_string);
        $telr_hash = $body_data['tran_check'] ?? '';

        return $computed_hash === $telr_hash;
    }
}
