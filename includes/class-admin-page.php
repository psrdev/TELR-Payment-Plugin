<?php


if (!defined('ABSPATH'))
    exit;
class Admin_Page
{

    private $option_group = 'telr_settings_group';
    private $option_name_store_id = 'telr_store_id';
    private $option_name_auth_key = 'telr_auth_key';
    private $option_name_webhook = 'telr_webhook';
    private $option_name_api_url = 'telr_api_url';
    private $option_name_mode = 'telr_mode';

    private $plugin_path;

    public function __construct()
    {
        $this->plugin_path = plugin_dir_path(__DIR__);
        add_action('admin_menu', [$this, 'add_admin_menus']);
        add_action('admin_init', [$this, 'register_settings']);
    }

    public function add_admin_menus()
    {
        // Top-level menu page for Telr Payment Gateway
        add_menu_page(
            'Telr Payment Gateway',          // Page title
            'Telr PG ',                  // Menu title
            'manage_options',                // Capability
            'telr-payment-settings',         // Menu slug
            [$this, 'telr_setting_page'],    // Callback
            'dashicons-cart',                // Icon
            25                              // Position
        );

        // Submenu page (payment)
        add_submenu_page(
            'telr-payment-settings',         // Parent slug
            'Payments',              // Page title
            'Payments',                   // Menu title
            'manage_options',                // Capability
            'telr-payment',              // Menu slug
            [$this, 'telr_payment_page']      // Callback
        );
    }

    public function register_settings()
    {
        register_setting($this->option_group, $this->option_name_store_id);
        register_setting($this->option_group, $this->option_name_auth_key);
        register_setting($this->option_group, $this->option_name_webhook);
        register_setting($this->option_group, $this->option_name_api_url);
        register_setting($this->option_group, $this->option_name_mode);


    }

    public function telr_setting_page()
    {
        include $this->plugin_path . 'templates/admin-telr-setting.php';
    }

    public function telr_payment_page()
    {
        global $wpdb;
        $table_name = $wpdb->prefix . 'payments';

        // Handle insert
        if (isset($_POST['action']) && $_POST['action'] === 'insert') {
            $wpdb->insert($table_name, [
                'cart_id' => wp_generate_uuid4(),
                'first_name' => sanitize_text_field($_POST['first_name']),
                'last_name' => sanitize_text_field($_POST['last_name']) ?? "",
                'email' => sanitize_email($_POST['email']) ?? "",
                'assigned_agent' => sanitize_text_field($_POST['assigned_agent']) ?? "",
                'payable_amount' => floatval($_POST['payable_amount']),


            ]);

            echo '<div class="updated"><p>Payment added successfully.</p></div>';
        }
        if (isset($_GET['action'], $_GET['id']) && $_GET['action'] === 'delete') {
            $id = intval($_GET['id']);
            if (wp_verify_nonce($_GET['_wpnonce'], 'delete_payment_' . $id)) {
                $wpdb->delete($table_name, ['id' => $id]);
                echo '<div class="updated"><p>Payment deleted successfully.</p></div>';
            } else {
                echo '<div class="error"><p>Security check failed. Deletion aborted.</p></div>';
            }
        }
        include $this->plugin_path . 'templates/admin-telr-payment.php';
    }

}

