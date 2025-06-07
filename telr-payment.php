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
            self::drop_table();
            self::install();
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
            first_name VARCHAR(100),
            last_name VARCHAR(100),
            email VARCHAR(100),
            phone VARCHAR(50),
            nationality VARCHAR(100),
            country_of_residence VARCHAR(100),
            assigned_agent VARCHAR(100),
            special_note TEXT,
            payable_amount DECIMAL(10,2),
            status ENUM('pending', 'paid') DEFAULT 'pending',
            reference_number VARCHAR(255),
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY (id)
        ) $charset_collate;";

        require_once ABSPATH . 'wp-admin/includes/upgrade.php';
        dbDelta($sql);

        update_option('telr_db_version', TELR_DB_VERSION);
    }

    public static function create_payment_pages()
    {
        $pages = [
            [
                'title' => 'Pay Now',
                'slug' => 'pay-now',
                'template' => 'page-pay-now.php',
            ],
            [
                'title' => 'Payment Successful',
                'slug' => 'payment-success',
                'template' => 'page-payment-success.php',
            ],
            [
                'title' => 'Payment Cancelled',
                'slug' => 'payment-cancel',
                'template' => 'page-payment-cancel.php',
            ],
            [
                'title' => 'Already Paid',
                'slug' => 'already-paid',
                'template' => 'page-already-paid.php',
            ],
        ];

        foreach ($pages as $page) {
            $existing = get_page_by_path($page['slug']);
            if (!$existing) {
                $page_id = wp_insert_post([
                    'post_title' => $page['title'],
                    'post_name' => $page['slug'],
                    'post_status' => 'publish',
                    'post_type' => 'page',
                    'post_content' => '', // Optional: add default content here
                ]);

                if (!is_wp_error($page_id)) {
                    update_post_meta($page_id, '_wp_page_template', $page['template']);
                }
            }
        }
    }

    public static function uninstall()
    {
        global $wpdb;
        $table_name = $wpdb->prefix . 'payments';

        $wpdb->query("DROP TABLE IF EXISTS $table_name");
        delete_option('telr_db_version');
    }

    public static function drop_table()
    {
        global $wpdb;
        $table_name = $wpdb->prefix . 'payments';
        $wpdb->query("DROP TABLE IF EXISTS $table_name");
    }
}

// Initialize plugin
Telr_Payment_Plugin::get_instance();

// Run on activation
register_activation_hook(__FILE__, function () {
    Telr_Payment_Plugin::install();
    Telr_Payment_Plugin::create_payment_pages(); // Create pages on activation
});

// Run on uninstall
register_uninstall_hook(__FILE__, ['Telr_Payment_Plugin', 'uninstall']);

