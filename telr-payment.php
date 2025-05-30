<?php
/*
Plugin Name: TELR Payment Pages
Description: Create shareable prefilled and editable TELR payment pages and receive webhook notifications.
Version: 1.0
Author: Pravin Singh Rana
*/
// Prevent direct file access
if (!defined('ABSPATH')) {
    exit('Direct script access denied.');
}
// Plugin constants
define('TELR_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('TELR_PLUGIN_URL', plugin_dir_url(__FILE__));


add_filter('theme_page_templates', 'my_plugin_register_page_template');
function my_plugin_register_page_template($templates)
{
    $templates['page-pay-now.php'] = 'Pay Now';
    return $templates;
}

add_filter('template_include', 'my_plugin_load_page_template');
function my_plugin_load_page_template($template)
{
    if (is_page()) {
        $page_template = get_page_template_slug();
        if ($page_template === 'page-pay-now.php') {
            $custom_template = plugin_dir_path(__FILE__) . 'templates/page-pay-now.php';
            if (file_exists($custom_template)) {
                return $custom_template;
            }
        }
    }
    return $template;
}

function my_custom_form_enqueue_assets()
{
    // CSS for intl-tel-input
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

    // JS for intl-tel-input
    wp_enqueue_script(
        'intl-tel-input-js',
        'https://cdn.jsdelivr.net/npm/intl-tel-input@25.3.1/build/js/intlTelInput.min.js',
        [],
        '25.3.1',
        true // load in footer
    );

    // Utils script for formatting and validation (optional but recommended)
    wp_enqueue_script(
        'intl-tel-input-utils',
        'https://cdn.jsdelivr.net/npm/intl-tel-input@25.3.1/build/js/utils.js',
        [],
        '25.3.1',
        true
    );
}
add_action('wp_enqueue_scripts', 'my_custom_form_enqueue_assets');