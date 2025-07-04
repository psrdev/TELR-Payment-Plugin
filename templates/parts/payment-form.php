<?php
if (!defined('ABSPATH')) {
    exit;
} ?>
<form method="POST">
    <?php wp_nonce_field('submit_telr_payment', 'telr_payment_nonce'); ?>
    <?php
    require_once TELR_PLUGIN_DIR . 'utils/Telr-helper.php';
    $telr_helper = new Telr_Helper();
    $all_countries = $telr_helper->get_all_countries();
    ?>
    <input type="hidden" name="cart_id"
        value="<?php echo isset($payment_details->cart_id) ? esc_attr($payment_details->cart_id) : ''; ?>">

    <div class="row">
        <div class="col-md-6">
            <div class="payment-form-group">
                <label>First Name <b>*</b></label>
                <span class="inputfield">
                    <input type="text" name="customer_first_name" class="form-control payment-input"
                        placeholder="Please input first name" required
                        value="<?php echo isset($payment_details->first_name) ? esc_attr($payment_details->first_name) : ""; ?>">
                </span>
            </div>
        </div>

        <div class="col-md-6">
            <div class="payment-form-group">
                <label>Last Name <b>*</b></label>
                <span class="inputfield">
                    <input type="text" name="customer_last_name" class="form-control payment-input"
                        placeholder="Please input last name" required
                        value="<?php echo isset($payment_details->last_name) ? esc_attr($payment_details->last_name) : ""; ?>">
                </span>
            </div>
        </div>
        <div class="col-md-6">
            <div class="payment-form-group">
                <label>Email <b>*</b></label>
                <span class="inputfield">
                    <input type="email" name="customer_email" class="form-control payment-input"
                        placeholder="Please input your email" required
                        value="<?php echo isset($payment_details->email) ? esc_attr($payment_details->email) : ""; ?>">
                </span>
            </div>
        </div>
        <div class="col-md-6">
            <div class="payment-form-group">
                <label>Phone <b>*</b></label>
                <span class="inputfield">
                    <input type="text" name="customer_phone" id="customer_phone" class="form-control payment-input"
                        placeholder="Please input phone number" required
                        value="<?php echo isset($payment_details->phone) ? esc_attr($payment_details->phone) : ""; ?>">
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
                        <option
                            value="<?php echo isset($payment_details->nationality) ? $telr_helper->get_country_code_from_name(esc_attr($payment_details->nationality)) : ""; ?>"
                            selected>
                            <?php echo isset($payment_details->nationality) ? esc_attr($payment_details->nationality) : "Please select your nationality"; ?>
                        </option>
                        <?php foreach ($all_countries as $country): ?>
                            <option value="<?php echo esc_attr($country['code']); ?>">
                                <?php echo esc_html($country['name']); ?>
                            </option>
                        <?php endforeach; ?>

                    </select>
                </span>
            </div>
        </div>
        <div class="col-md-6">
            <div class="payment-form-group">
                <label>Country of residence
                    <b>*</b></label>
                <span class="inputfield">
                    <select type="text" name="customer_country_of_residence" id="customer_country_of_residence"
                        class="form-select form-control payment-input" required>
                        <option
                            value="<?php echo isset($payment_details->country_of_residence) ? $telr_helper->get_country_code_from_name(esc_attr($payment_details->country_of_residence)) : ""; ?>"
                            selected>
                            <?php echo isset($payment_details->country_of_residence) ? esc_attr($payment_details->country_of_residence) : "Please select country of residence"; ?>
                        </option>
                        <?php foreach ($all_countries as $country): ?>
                            <option value="<?php echo esc_attr($country['code']); ?>">
                                <?php echo esc_html($country['name']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </span>
            </div>
        </div>
        <div class="col-md-6">
            <div class="payment-form-group">
                <label>Assigned Agent
                </label>
                <span class="inputfield">
                    <input type="text" name="customer_assigned_agent" class=" form-control payment-input"
                        placeholder="Assigned agent name"
                        value="<?php echo isset($payment_details->assigned_agent) ? esc_attr($payment_details->assigned_agent) : ""; ?>"
                        <?php echo (isset($payment_details->assigned_agent) && !empty($payment_details->assigned_agent)) ? 'readonly' : ""; ?>>


                </span>
            </div>
        </div>
        <div class="col-md-6">
            <div class="payment-form-group">
                <label>Special Note
                </label>
                <span class="inputfield">
                    <textarea name="customer_special_note" class=" form-control payment-input"
                        placeholder="Special note"><?php echo isset($payment_details->special_note) ? $payment_details->special_note : "" ?></textarea>
                </span>
            </div>
        </div>


    </div>
    <div class="row">
        <div class="col-12">
            <div class="payment-form-group">
                <label for="cutomer_payable_amount">Payable amount (in AED) <b>*</b></label>
                <input type="text" name="cutomer_payable_amount" id="cutomer_payable_amount"
                    class="form-control payment-input payable-amount" placeholder="Please input payable amount" required
                    value="<?php echo isset($payment_details->payable_amount) ? esc_attr($payment_details->payable_amount) : ""; ?>"
                    <?php echo isset($payment_details->payable_amount) > 0 ? 'readonly' : ''; ?>>


            </div>
        </div>

    </div>





    <input type="submit" class="btn btn-primary ripple-button" value="Proceed to Pay">
</form>