<?php

class Telr_Assets
{

    public function __construct()
    {
        add_action('wp_enqueue_scripts', [$this, 'enqueue_assets']);
    }

    public function enqueue_assets()
    {
        wp_enqueue_style(
            'intl-tel-input-css',
            'https://cdn.jsdelivr.net/npm/intl-tel-input@25.3.1/build/css/intlTelInput.css',
            [],
            '25.3.1'
        );

        wp_enqueue_style(
            'payment-form-css',
            TELR_PLUGIN_URL . 'assets/css/style.css',
            [],
            '1.0.0'
        );

        wp_enqueue_script(
            'payment-form-js',
            TELR_PLUGIN_URL . 'assets/js/payment.js',
            [],
            '1.0.0',
            true
        );

        wp_enqueue_script(
            'intl-tel-input-js',
            'https://cdn.jsdelivr.net/npm/intl-tel-input@25.3.1/build/js/intlTelInput.min.js',
            [],
            '25.3.1',
            true
        );

        wp_enqueue_script(
            'intl-tel-input-utils',
            'https://cdn.jsdelivr.net/npm/intl-tel-input@25.3.1/build/js/utils.js',
            [],
            '25.3.1',
            true
        );
    }
}
