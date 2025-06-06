<?php
if (!defined('ABSPATH'))
    exit;
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
    public function get_payment_details($cartId)
    {


        $payment = $this->wpdb->get_row($this->wpdb->prepare("SELECT * FROM $this->table WHERE cart_id = %d", $cartId));
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

}