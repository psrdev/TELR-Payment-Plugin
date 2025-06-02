<?php
/*
Plugin Name: TELR Payment Pages
Description: Create shareable prefilled and editable TELR payment pages and receive webhook notifications.
Version: 1.0
Author: Pravin Singh Rana
*/

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

final class Telr_Payment_Plugin
{
    private static $instance = null;

    private function __construct()
    {
        $this->define_constants();
        $this->autoload_classes();

        // Plugin loaded
        add_action('plugins_loaded', [$this, 'init_plugin']);
    }

    public static function get_instance()
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function define_constants()
    {
        define('TELR_PLUGIN_DIR', plugin_dir_path(__FILE__));
        define('TELR_PLUGIN_URL', plugin_dir_url(__FILE__));
        define('TELR_PLUGIN_BASENAME', plugin_basename(__FILE__));
        define('TELR_DB_VERSION', '1.0');
    }

    private function autoload_classes()
    {
        foreach (glob(TELR_PLUGIN_DIR . 'includes/class-*.php') as $file) {
            require_once $file;
        }
    }

    public function init_plugin()
    {
        $this->maybe_upgrade();

        if (class_exists('Telr_Loader')) {
            $loader = new Telr_Loader();
            $loader->init();
        }
    }

    private function maybe_upgrade()
    {
        $installed_version = get_option('telr_db_version');
        if ($installed_version !== TELR_DB_VERSION) {
            self::install(); // Run dbDelta with updated schema if needed
            update_option('telr_db_version', TELR_DB_VERSION);
        }
    }

    public static function install()
    {
        global $wpdb;
        $table_name = $wpdb->prefix . 'payments';

        $charset_collate = $wpdb->get_charset_collate();

        $sql = "CREATE TABLE $table_name (
            id BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
            cart_id VARCHAR(255) NOT NULL,
            customer_first_name VARCHAR(100),
            customer_last_name VARCHAR(100),
            customer_email VARCHAR(100),
            customer_phone VARCHAR(50),
            customer_nationality VARCHAR(100),
            customer_country_of_residence VARCHAR(100),
            customer_assigned_agent VARCHAR(100),
            customer_special_note TEXT,
            payable_amount DECIMAL(10,2),
            status VARCHAR(50),
            reference_number VARCHAR(255),
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY (id)
        ) $charset_collate;";

        require_once ABSPATH . 'wp-admin/includes/upgrade.php';
        dbDelta($sql);

        update_option('telr_db_version', TELR_DB_VERSION);
    }

    public static function uninstall()
    {
        global $wpdb;
        $table_name = $wpdb->prefix . 'payments';

        $wpdb->query("DROP TABLE IF EXISTS $table_name");
        delete_option('telr_db_version');
    }
}

// Initialize the plugin
Telr_Payment_Plugin::get_instance();

// Register activation and uninstall hooks
register_activation_hook(__FILE__, ['Telr_Payment_Plugin', 'install']);
register_uninstall_hook(__FILE__, ['Telr_Payment_Plugin', 'uninstall']);
