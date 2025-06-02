<?php



class Admin_Page
{

    private $option_group = 'telr_settings_group';
    private $option_name_store_id = 'telr_store_id';
    private $option_name_auth_key = 'telr_auth_key';

    public function __construct()
    {
        add_action('admin_menu', [$this, 'add_admin_menus']);
        add_action('admin_init', [$this, 'register_settings']);
    }

    public function add_admin_menus()
    {
        // Top-level menu page for Telr Payment Gateway
        add_menu_page(
            'Telr Payment Gateway',          // Page title
            'Telr Settings',                  // Menu title
            'manage_options',                // Capability
            'telr-payment-settings',         // Menu slug
            [$this, 'telr_payment_page'],    // Callback
            'dashicons-cart',                // Icon
            25                              // Position
        );

        // Submenu page (placeholder)
        add_submenu_page(
            'telr-payment-settings',         // Parent slug
            'Payments',              // Page title
            'Payments',                   // Menu title
            'manage_options',                // Capability
            'telr-payment',              // Menu slug
            [$this, 'placeholder_page']      // Callback
        );
    }

    public function register_settings()
    {
        register_setting($this->option_group, $this->option_name_store_id);
        register_setting($this->option_group, $this->option_name_auth_key);
    }

    public function telr_payment_page()
    {
        ?>
        <div class="wrap">
            <h1>Telr Payment Gateway Settings</h1>
            <form method="post" action="options.php">
                <?php
                settings_fields($this->option_group);
                do_settings_sections($this->option_group);
                ?>
                <table class="form-table" role="presentation">
                    <tbody>
                        <tr>
                            <th scope="row"><label for="<?php echo esc_attr($this->option_name_store_id); ?>">Store ID</label>
                            </th>
                            <td><input type="text" id="<?php echo esc_attr($this->option_name_store_id); ?>"
                                    name="<?php echo esc_attr($this->option_name_store_id); ?>"
                                    value="<?php echo esc_attr(get_option($this->option_name_store_id)); ?>"
                                    class="regular-text" /></td>
                        </tr>
                        <tr>
                            <th scope="row"><label for="<?php echo esc_attr($this->option_name_auth_key); ?>">Auth Key</label>
                            </th>
                            <td><input type="text" id="<?php echo esc_attr($this->option_name_auth_key); ?>"
                                    name="<?php echo esc_attr($this->option_name_auth_key); ?>"
                                    value="<?php echo esc_attr(get_option($this->option_name_auth_key)); ?>"
                                    class="regular-text" /></td>
                        </tr>
                    </tbody>
                </table>
                <?php submit_button(); ?>
            </form>
        </div>
        <?php
    }

    public function placeholder_page()
    {
        ?>
        <div class="wrap">
            <h1>Payments listing </h1>
            <p>All the payments will be listed here !</p>
        </div>
        <?php
    }

}

