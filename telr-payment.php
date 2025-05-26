<?php
/*
Plugin Name: TELR Payment Pages
Description: Create shareable prefilled TELR payment pages and receive webhook notifications.
Version: 1.0
Author: Your Name
*/

// Plugin constants
define('TELR_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('TELR_PLUGIN_URL', plugin_dir_url(__FILE__));
define('TELR_SECRET_TOKEN', 'your_secure_token_here'); // Change this token for webhook security

// Autoload files
require_once TELR_PLUGIN_DIR . 'includes/class-telr-cpt.php';
require_once TELR_PLUGIN_DIR . 'includes/class-telr-settings.php';
require_once TELR_PLUGIN_DIR . 'includes/class-telr-payment-form.php';
require_once TELR_PLUGIN_DIR . 'includes/class-telr-webhook.php';

// Initialize plugin functionality
add_action('plugins_loaded', function () {
    new Telr_Settings();
    new Telr_CPT();
    new Telr_Payment_Form();
    new Telr_Webhook();
});