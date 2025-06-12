<?php
/**
 * Template Name: Pay Now
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

require_once(plugin_dir_path(__DIR__) . '/utils/Payment-handler.php');
require_once TELR_PLUGIN_DIR . 'utils/Telr-helper.php';

$telr_helper = new Telr_helper();
$payment_handler = new Payment_handler();
$id = isset($_GET['id']) ? sanitize_text_field(rtrim($_GET['id'], '/')) : null;
$payment_details = null;
if (!empty($id)) {
    $payment_details = $payment_handler->get_payment_details($id);
}
$payment_details = $payment_handler->get_payment_details($id);
$payment_status = $payment_details->status ?? 'pending';
if ($payment_status === 'paid') {
    wp_redirect(home_url('/already-paid/'));
    exit;
}


if ($payment_handler->form_submitted()) {
    $payment_handler->process_payment($_POST);
}





get_header('two');

?>
<div class="banner d-flex justify-content-center align-items-center"
    style="background-color: #0061AB; min-height: 400px;">
    <h1>Payment Page</h1>
</div>

<div class="mb-4" style=" margin-top: -50px;">
    <div class="container">
        <div class="card p-5 shadow-sm">
            <?php include(plugin_dir_path(__DIR__) . 'templates/parts/payment-form.php'); ?>
        </div>

    </div>
</div>

<?php get_footer('two'); ?>