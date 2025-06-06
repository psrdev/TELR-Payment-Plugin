<?php
if (!defined('ABSPATH'))
    exit;

require_once TELR_PLUGIN_DIR . 'utils/Telr-payment.php';

class Payment_handler
{
    private $wpdb;
    private $table;

    public function __construct()
    {
        global $wpdb;
        $this->wpdb = $wpdb;
        $this->table = $wpdb->prefix . 'payments';
    }

    public function form_submitted(): bool
    {
        return (
            $_SERVER['REQUEST_METHOD'] === 'POST'
            && isset($_POST['telr_payment_nonce'])
            && wp_verify_nonce($_POST['telr_payment_nonce'], 'submit_telr_payment')
        );
    }

    public function get_payment_details($cartId)
    {
        return $this->wpdb->get_row(
            $this->wpdb->prepare("SELECT * FROM $this->table WHERE cart_id = %d", $cartId)
        );
    }

    public function create_new_payment(array $data)
    {
        $result = $this->wpdb->insert($this->table, $data);
        return $result ? $this->wpdb->insert_id : false;
    }

    public function update_payment_by_cart_id($cartId, array $data): bool
    {
        $result = $this->wpdb->update($this->table, $data, ['cart_id' => $cartId]);
        return $result !== false;
    }

    public function process_payment(array $data)
    {
        $telr_helper = new Telr_helper();
        $telr_payment = new Telr_Payment();

        // Always generate a fresh cart_id
        $cart_id = $telr_helper->generate_cart_id();
        $payment_details = $this->get_payment_details($cart_id);

        $form_data = $this->prepare_form_data($data, $telr_helper, $cart_id, $payment_details);

        if (!is_numeric($form_data['payable_amount']) || $form_data['payable_amount'] <= 0) {
            return ['error' => 'Invalid payable amount.'];
        }

        $customer = $this->prepare_customer_data($form_data, $telr_helper);

        $pgresult = $telr_payment->make_payment(
            $form_data['payable_amount'],
            $customer,
            $form_data['cart_id']
        );

        if (empty($pgresult['order']['ref'])) {
            return ['error' => 'Payment gateway error. Please try again later.'];
        }

        $form_data['status'] = 'pending';
        $form_data['reference_number'] = $pgresult['order']['ref'];

        if ($payment_details) {
            $this->update_payment_by_cart_id($form_data['cart_id'], $form_data);
        } else {
            $this->create_new_payment($form_data);
        }

        $this->safe_redirect($pgresult['order']['url'] ?? '');

        return true;
    }

    private function prepare_form_data(array $data, Telr_helper $telr_helper, $cart_id, $payment_details): array
    {
        return [
            'cart_id' => $cart_id,
            'first_name' => sanitize_text_field($data['customer_first_name'] ?? ''),
            'last_name' => sanitize_text_field($data['customer_last_name'] ?? ''),
            'email' => sanitize_email($data['customer_email'] ?? ''),
            'phone' => sanitize_text_field($data['phone_full'] ?? ''),
            'nationality' => $telr_helper->get_country_from_code(sanitize_text_field($data['customer_nationality'] ?? '')),
            'country_of_residence' => $telr_helper->get_country_from_code(sanitize_text_field($data['customer_country_of_residence'] ?? '')),
            'assigned_agent' => sanitize_text_field($data['customer_assigned_agent'] ?? ''),
            'special_note' => sanitize_textarea_field($data['customer_special_note'] ?? ''),
            'payable_amount' => $payment_details->payable_amount ?? sanitize_text_field($data['customer_payable_amount'] ?? 0),
        ];
    }

    private function prepare_customer_data(array $form_data, Telr_helper $telr_helper): array
    {
        return [
            'email' => $form_data['email'],
            'phone' => $form_data['phone'],
            'name' => [
                'forenames' => $form_data['first_name'],
                'surname' => $form_data['last_name'],
            ],
            'address' => [
                'country' => $telr_helper->get_country_code_from_name($form_data['nationality']),
            ],
        ];
    }

    private function safe_redirect(string $url): void
    {
        if (!empty($url)) {
            if (!headers_sent()) {
                wp_redirect($url);
            } else {
                echo "<script>window.location.href='" . esc_url($url) . "';</script>";
            }
            exit;
        }
    }
}
