<?php
if (!defined('ABSPATH'))
    exit;

class Telr_Templates
{
    private $custom_templates = [
        'page-pay-now.php' => 'Pay Now',
        'page-payment-success.php' => 'Payment Successful',
        'page-payment-cancel.php' => 'Payment Cancelled',
        'page-already-paid.php' => 'Already Paid',
    ];

    public function __construct()
    {
        add_filter('theme_page_templates', [$this, 'register_custom_template']);
        add_filter('template_include', [$this, 'load_custom_template']);
    }

    public function register_custom_template($templates)
    {
        return array_merge($templates, $this->custom_templates);
    }

    public function load_custom_template($template)
    {
        if (is_page()) {
            $page_template = get_page_template_slug();
            if (isset($this->custom_templates[$page_template])) {
                $custom_template_path = TELR_PLUGIN_DIR . 'templates/' . $page_template;
                if (file_exists($custom_template_path)) {
                    return $custom_template_path;
                }
            }
        }
        return $template;
    }
}
