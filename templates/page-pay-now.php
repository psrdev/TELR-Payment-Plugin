<?php
/**
 * Template Name: Pay Now
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}
get_header('two');
require_once(plugin_dir_path(__DIR__) . '/utils/Payment-handler.php');

// chek if prefilled page is enabled
$id = isset($_GET['id']) ? rtrim($_GET['id'], '/') : null;
$payment_handler = new Payment_handler();
$payment_details = $payment_handler->get_payment_details($id);
$payment_status = $payment_details->payment_status ?? 'pending';
if ($payment_status === 'paid') {
    wp_redirect(home_url('/alredy-paid'));
    exit;
}


?>
<div class="banner d-flex justify-content-center align-items-center"
    style="background-color: #0061AB; min-height: 400px;">
    <h1>Payment Page</h1>
</div>

<div class="mb-4" style=" margin-top: -50px;">
    <div class="container">
        <div class="card p-5 shadow-sm">
            <?php if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['telr_payment_nonce']) && wp_verify_nonce($_POST['telr_payment_nonce'], 'submit_telr_payment')):
                print_r($_POST);
                echo '<br>';
                print_r($payment_details);
                //    if cart id is not empty get importent payment details such as amount and assigned cart id  and assigned agent from the database
                $cart_id = $payment_details->cart_id ?? '';
                if (!empty($cart_id)) {
                    $payable_amount = $payment_details->payable_amount ?? "";
                    $assigned_agent = $payment_details->customer_assigned_agent ?? '';

                    // and update other detials from the form
                    $customer_first_name = sanitize_text_field($_POST['customer_first_name']);
                    $customer_last_name = sanitize_text_field($_POST['customer_last_name']);
                    $customer_email = sanitize_email($_POST['customer_email']);
                    $cutomer_nationality = sanitize_text_field($_POST['customer_nationality']);
                    $customer_phone = sanitize_text_field($_POST['phone_full']);
                    $customer_country_of_residence = sanitize_text_field($_POST['customer_country_of_residence']);
                    $customer_assigned_agent = sanitize_text_field($_POST['customer_assigned_agent']);
                    // $payable_amount = sanitize_text_field($_POST['payable_amount']);
            
                    $customer_special_note = sanitize_textarea_field($_POST['customer_special_note']);

                } else {
                    $cart_id = $payment_handler->generate_cart_id();
                    $payable_amount = sanitize_text_field($_POST['cutomer_payable_amount']);
                    $assigned_agent = sanitize_text_field($_POST['customer_assigned_agent'] ?? '');

                }













            else: ?>




                <?php include(plugin_dir_path(__DIR__) . 'templates/parts/payment-form.php'); ?>


            <?php endif; ?>
        </div>

    </div>
</div>

<?php get_footer('two'); ?>