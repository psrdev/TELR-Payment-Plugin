<?php
if (!defined('ABSPATH'))
    exit;

class Telr_Webhook_REST_Controller
{

    public function __construct()
    {
        add_action('rest_api_init', [$this, 'register_routes']);
    }

    public function register_routes()
    {
        register_rest_route('telr/v1', '/webhook', [
            'methods' => 'POST',
            'callback' => [$this, 'handle_webhook'],
            'permission_callback' => '__return_true', // open to public, adjust if needed
        ]);
    }


    public function handle_webhook(WP_REST_Request $request)
    {
        global $wpdb;
        $params = $request->get_params();
        require_once TELR_PLUGIN_DIR . 'utils/Telr-payment.php';
        require_once TELR_PLUGIN_DIR . 'utils/Payment_handler.php';
        $telr_handler = new Payment_handler();
        $telr_payment = new Telr_Payment();
        if ($telr_payment->sign_data($params)) {
            if (isset($params['tran_status']) && $params['tran_status'] === 'A') {
                $telr_handler->update_payment_by_cart_id(
                    $params['tran_cartid'],
                    [
                        'status' => "paid",

                    ]
                );

            }


        }

        return new WP_REST_Response([
            'success' => true,
            'message' => 'Webhook processed successfully',
        ], 200);



        // wp_send_json_success(['received' => $params]);







    }
}
