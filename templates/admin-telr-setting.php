<?php
if (!defined('ABSPATH'))
    exit;
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
                <tr>
                    <th scope="row"><label for="<?php echo esc_attr($this->option_name_webhook); ?>">Webhook</label>
                    </th>
                    <td><input type="text" id="<?php echo esc_attr($this->option_name_webhook); ?>"
                            name="<?php echo esc_attr($this->option_name_webhook); ?>"
                            value="<?php echo esc_attr(get_option($this->option_name_webhook)); ?>"
                            class="regular-text" /></td>
                </tr>
                <tr>
                    <th scope="row"><label for="<?php echo esc_attr($this->option_name_api_url); ?>">TELR API
                            URL</label>
                    </th>
                    <td><input type="text" id="<?php echo esc_attr($this->option_name_api_url); ?>"
                            name="<?php echo esc_attr($this->option_name_api_url); ?>"
                            value="<?php echo esc_attr(get_option($this->option_name_api_url)); ?>"
                            class="regular-text" /></td>
                </tr>
                <tr>
                    <th scope="row"><label for="<?php echo esc_attr($this->option_name_mode); ?>">TELR MODE</label>
                    </th>
                    <td><input type="text" id="<?php echo esc_attr($this->option_name_mode); ?>"
                            name="<?php echo esc_attr($this->option_name_mode); ?>"
                            value="<?php echo esc_attr(get_option($this->option_name_mode)); ?>" class="regular-text" />
                        <small>1 for test and 0 for production</small>
                    </td>
                </tr>
            </tbody>
        </table>
        <?php submit_button(); ?>
    </form>
</div>