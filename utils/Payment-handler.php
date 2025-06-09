<?php
if (!defined('ABSPATH'))
    exit;
require_once TELR_PLUGIN_DIR . 'utils/Telr-payment.php';
class Payment_handler
{
    private $wpdb;
    private $table;
    private $cart_id;

    private $payment_details;

    private $isprefilled = false;




    public function __construct()
    {

        global $wpdb;
        $this->wpdb = $wpdb;
        $this->table = $wpdb->prefix . 'payments';

    }
    public function form_submitted()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['telr_payment_nonce']) && wp_verify_nonce($_POST['telr_payment_nonce'], 'submit_telr_payment')) {
            return true;
        }
        return false;
    }


    public function get_payment_details($cartId)
    {

        if (empty($cartId)) {
            return null;
        }

        $payment = $this->wpdb->get_row(
            $this->wpdb->prepare(
                "SELECT * FROM $this->table WHERE cart_id = %s",
                $cartId
            )
        );

        $this->payment_details = $payment;
        return $payment;
    }


    public function create_new_payment($data)
    {

        $result = $this->wpdb->insert($this->table, $data);

        return $result ? $this->wpdb->insert_id : false;
    }


    public function update_payment_by_cart_id($cartId, $data)
    {
        $result = $this->wpdb->update($this->table, $data, ['cart_id' => $cartId]);
        return $result !== false;
    }

    public function process_payment($data)
    {
        $cart_id = isset($data['cart_id']) ? sanitize_text_field($data['cart_id']) : null;
        if (!empty($cart_id) && isset($this->payment_details->cart_id) && $this->payment_details->cart_id === $cart_id) {
            $this->isprefilled = true; // Set the flag to true if cart_id is provided
        }
        $telr_helper = new Telr_helper();
        $telr_payment = new Telr_Payment();


        $form_data = [
            "cart_id" => $this->isprefilled ? $this->payment_details->cart_id : $telr_helper->generate_cart_id(),
            'first_name' => isset($data['customer_first_name']) ? sanitize_text_field($data['customer_first_name']) : '',
            'last_name' => isset($data['customer_last_name']) ? sanitize_text_field($data['customer_last_name']) : '',
            'email' => isset($data['customer_email']) ? sanitize_email($data['customer_email']) : '',
            'phone' => isset($data['phone_full']) ? sanitize_text_field($data['phone_full']) : '',
            'nationality' => isset($data['customer_nationality']) ? $telr_helper->get_country_from_code(sanitize_text_field($data['customer_nationality'])) : '',
            'country_of_residence' => isset($data['customer_country_of_residence']) ? $telr_helper->get_country_from_code(sanitize_text_field($data['customer_country_of_residence'])) : '',
            'assigned_agent' => isset($data['customer_assigned_agent']) ? sanitize_text_field($data['customer_assigned_agent']) : '',
            'special_note' => isset($data['customer_special_note']) ? sanitize_textarea_field($data['customer_special_note']) : '',
            'payable_amount' => $this->isprefilled ? $this->payment_details->payable_amount : sanitize_text_field($data['cutomer_payable_amount']),

        ];
        $customer = [
            'email' => $form_data['email'],
            'phone' => $form_data['phone'],
            'name' => [
                'forenames' => $form_data['first_name'],
                'surname' => $form_data['last_name'],
            ],
            "address" => [

                'country' => $telr_helper->get_country_code_from_name($form_data['nationality']),
            ],
        ];
        $pgresult = $telr_payment->make_payment(
            $form_data['payable_amount'],
            $customer,
            $form_data['cart_id'],

        );

        if (isset($pgresult['order']['ref'])) {
            $reference_number = $pgresult['order']['ref'];
            $url = $pgresult['order']['url'] ?? '';
            $form_data['status'] = 'pending';
            $form_data['reference_number'] = $reference_number;

            if ($this->isprefilled) {
                // Update existing payment
                $this->update_payment_by_cart_id($form_data['cart_id'], $form_data);
            } else {
                // Create new payment
                $this->create_new_payment($form_data);
            }
            wp_redirect($url);
            exit;
        } else {
            // Handle error
            return ['error' => 'Payment gateway error. Please try again later.'];
        }


    }



}