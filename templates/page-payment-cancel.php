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
    <h1>Payment Cancelled</h1>
</div>

<div class="mb-4" style=" margin-top: -50px;">
    <div class="container">
        <div class="card p-5 shadow-sm">
            <h3>❌ Payment Cancelled</h3>
            <p>Your payment process was cancelled or not completed.</p>

            <p>No charges were made. If this was a mistake or you’d like to try again, please return to the <a
                    href="/pay-now">payment page</a>.</p>
        </div>

    </div>
</div>

<?php get_footer('two'); ?>