<?php
class Payment_handler
{
    public function get_payment_details($cartId)
    {
        global $wpdb;
        $table_name = $wpdb->prefix . 'payments';
        $payment = $wpdb->get_row($wpdb->prepare("SELECT * FROM $table_name WHERE cart_id = %d", $cartId));
        return $payment;
    }

}