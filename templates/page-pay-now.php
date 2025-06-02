<?php
/**
 * Template Name: Pay Now
 */

get_header('two'); ?>
<div class="banner d-flex justify-content-center align-items-center"
    style="background-color: #0061AB; min-height: 400px;">
    <h1>Payment Page</h1>
</div>

<div class="mb-4" style=" margin-top: -50px;">
    <div class="container">
        <div class="card p-5 shadow-sm">


            <?php if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['telr_payment_nonce']) && wp_verify_nonce($_POST['telr_payment_nonce'], 'submit_telr_payment')):
            // Handle the form submission here (we'll do this in next step)
        else: ?>
                <form method="POST">
                    <?php wp_nonce_field('submit_telr_payment', 'telr_payment_nonce'); ?>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="payment-form-group">
                                <label>First Name <b>*</b></label>
                                <span class="inputfield">
                                    <input type="text" name="customer_first_name" class="form-control payment-input"
                                        placeholder="Please input first name" required>
                                </span>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="payment-form-group">
                                <label>Last Name <b>*</b></label>
                                <span class="inputfield">
                                    <input type="text" name="customer_last_name" class="form-control payment-input"
                                        placeholder="Please input last name" required>
                                </span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="payment-form-group">
                                <label>Email <b>*</b></label>
                                <span class="inputfield">
                                    <input type="email" name="customer_email" class="form-control payment-input"
                                        placeholder="Please input your email" required>
                                </span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="payment-form-group">
                                <label>Phone <b>*</b></label>
                                <span class="inputfield">
                                    <input type="text" name="customer_phone" id="customer_phone"
                                        class="form-control payment-input" placeholder="Please input phone number" required>
                                </span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="payment-form-group">
                                <label>Nationality
                                    <b>*</b></label>
                                <span class="inputfield">
                                    <select type="text" name="customer_nationality" id="customer_nationality"
                                        class="form-select form-control payment-input" required>
                                        <option value="" disabled selected>Please select your nationality </option>
                                    </select>
                                </span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="payment-form-group">
                                <label>Country of residence
                                    <b>*</b></label>
                                <span class="inputfield">
                                    <select type="text" name="customer_country_of_residence"
                                        id="customer_country_of_residence" class="form-select form-control payment-input"
                                        required>
                                        <option value="" disabled selected>Please select country of residence</option>
                                    </select>
                                </span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="payment-form-group">
                                <label>Assigned Agent
                                    <b>*</b></label>
                                <span class="inputfield">
                                    <input type="text" name="customer_assigned_agent" class=" form-control payment-input"
                                        placeholder="Assigned agent name" required>


                                </span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="payment-form-group">
                                <label>Special Note
                                </label>
                                <span class="inputfield">
                                    <textarea name="customer_special_note" class=" form-control payment-input"
                                        placeholder="Special note"></textarea>
                                </span>
                            </div>
                        </div>


                    </div>
                    <div class="row">
                        <div class="col-12">
                            <div class="payment-form-group">
                                <label for="">Payable amount (in AED) <b>*</b></label>
                                <input type="text" name="cutomer_payable_amount" id="cutomer_payable_amount"
                                    class="form-control payment-input payable-amount">

                            </div>
                        </div>

                    </div>





                    <input type="submit" class="btn btn-primary ripple-button" value="Proceed to Pay">
                </form>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php get_footer('two'); ?>