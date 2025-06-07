<?php
/**
 * Template Name: alrady-paid
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}




get_header('two');

?>
<div class="banner d-flex justify-content-center align-items-center"
    style="background-color: #0061AB; min-height: 400px;">
    <h1>Already Paid</h1>
</div>

<div class="mb-4" style=" margin-top: -50px;">
    <div class="container">
        <div class="card p-5 shadow-sm">
            <h3>ℹ️ Payment Already Completed</h3>
            <p>It looks like this payment has already been made. There is no further action required.</p>

            <p>If you believe this is an error, or if you need a receipt, feel free to <a href="/contact">contact our
                    support team</a>.</p>
        </div>

    </div>
</div>

<?php get_footer('two'); ?>