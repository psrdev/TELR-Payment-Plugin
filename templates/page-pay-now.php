<?php
/**
 * Template Name: Pay Now
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}
require_once(plugin_dir_path(__DIR__) . '/utils/Payment-handler.php');
get_header('two');
// chek if prefilled page is enabled
$id = isset($_GET['id']) ? rtrim($_GET['id'], '/') : null;
$payment_handler = new Payment_handler();
$payment_details = $payment_handler->get_payment_details($id);


?>
<div class="banner d-flex justify-content-center align-items-center"
    style="background-color: #0061AB; min-height: 400px;">
    <h1>Payment Page</h1>
</div>

<div class="mb-4" style=" margin-top: -50px;">
    <div class="container">
        <div class="card p-5 shadow-sm">


            <?php if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['telr_payment_nonce']) && wp_verify_nonce($_POST['telr_payment_nonce'], 'submit_telr_payment')):
                echo '<pre>';
                print_r($_POST);
                print_r($_POST['phone_full']);
                require_once plugin_dir_path(__DIR__) . 'utils/Telr_helper.php';
                $telr_helper = new Telr_helper();
                echo "<br>";
                print_r($telr_helper->generate_cart_id());
                echo '<br>';
                print ($telr_helper->get_country_from_code('in'));





                echo '</pre>';

            else: ?>




                <?php include(plugin_dir_path(__DIR__) . 'templates/parts/payment-form.php'); ?>

            <?php endif; ?>
        </div>
    </div>
</div>

<?php get_footer('two'); ?>