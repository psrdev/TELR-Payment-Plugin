<?php
/**
 * Template Name: Pay Now
 */

get_header('two'); ?>
<div class="banner d-flex justify-content-center align-items-center"
    style="background-color: #0061AB; min-height: 500px;">
    <h2>Make the payment</h2>
</div>

<div class="mb-4" style=" margin-top: -100px;">
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


                    </div>







                    <input type="submit" class="btn btn-primary ripple-button" value="Proceed to Pay">
                </form>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php get_footer('two'); ?>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        const input = document.querySelector("#customer_phone");
        window.intlTelInput(input, {
            initialCountry: "auto",
            fixDropdownWidth: false,
            containerClass: "phone-input-container",

            // preferredCountries: ["ae", "sa", "om", "qa", "kw", "bh"],
            // utilsScript: "https://cdn.jsdelivr.net/npm/intl-tel-input@25.3.1/build/js/utils.js?1587637608293",

        });
        const nationalitySelect = document.querySelector("#customer_nationality");
        const countryData = fetch("https://restcountries.com/v3.1/all?name,cca2")
            .then(response => response.json())
            .then(countries => {
                countries.forEach(country => {
                    const option = document.createElement("option");
                    option.value = country.cca2.toLowerCase();
                    option.textContent = country.name.common;
                    nationalitySelect.appendChild(option);
                });
            })
            .catch(error => console.error("Error fetching countries:", error));

    });

</script>