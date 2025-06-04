<?php
if (!defined('ABSPATH'))
    exit;
class Telr_Templates
{

    public function __construct()
    {
        add_filter('theme_page_templates', [$this, 'register_custom_template']);
        add_filter('template_include', [$this, 'load_custom_template']);
    }

    public function register_custom_template($templates)
    {
        $templates['page-pay-now.php'] = 'Pay Now';
        return $templates;
    }

    public function load_custom_template($template)
    {
        if (is_page()) {
            $page_template = get_page_template_slug();
            if ($page_template === 'page-pay-now.php') {
                $custom_template = TELR_PLUGIN_DIR . 'templates/page-pay-now.php';
                if (file_exists($custom_template)) {
                    return $custom_template;
                }
            }
        }
        return $template;
    }
}
