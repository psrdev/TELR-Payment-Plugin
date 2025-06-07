<?php
/**
 * Template Name: payment-success
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}




get_header('two');

?>
<div class="banner d-flex justify-content-center align-items-center"
    style="background-color: #0061AB; min-height: 400px;">
    <h1>Payment Successful</h1>
</div>

<div class="mb-4" style=" margin-top: -50px;">
    <div class="container">
        <div class="card p-5 shadow-sm">
            <h3>✅ Payment Successful</h3>
            <p>Thank you for your payment. Your transaction has been completed successfully.</p>

            <p>A confirmation email has been sent to your registered email address. If you have any questions, please <a
                    href="/contact">contact us</a>.</p>
        </div>

    </div>
</div>

<?php get_footer('two'); ?>