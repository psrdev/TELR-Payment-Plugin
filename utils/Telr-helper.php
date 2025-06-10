<?php
if (!defined('ABSPATH'))
    exit;
class Telr_helper
{
    public $countries;
    public function __construct()
    {
        if (file_exists(TELR_PLUGIN_DIR . '/data/countries.json')) {
            $countries_data = file_get_contents(TELR_PLUGIN_DIR . '/data/countries.json');
            $data = json_decode($countries_data, true);
            if (json_last_error() === JSON_ERROR_NONE) {
                $this->countries = $data;
            } else {
                error_log('Invalid JSON in countries.json');
                $this->countries = [];
            }
        }



    }
    public function generate_cart_id()
    {
        $cart_id = wp_generate_uuid4();
        return $cart_id;
    }
    public function get_country_from_code($code)
    {
        $code = sanitize_text_field($code);
        $code = strtoupper($code);

        foreach ($this->countries as $country) {
            if ($country['code'] === $code) {
                return $country['name'];
            }
        }

        return null;
    }
    public function get_country_code_from_name($name)
    {
        $name = sanitize_text_field($name);
        $name = strtolower($name);
        $name = ucwords($name);
        foreach ($this->countries as $country) {
            if ($country['name'] === $name) {
                return $country['code'];
            }
        }

        return null;


    }
    public function get_all_countries()
    {

        $data = $this->countries;
        $exclude = ['IR', 'CU', 'KP', 'SD', 'SS', 'UA', 'SY', 'RU', 'MM', 'YE']; // country codes to exclude

        $countries = [];
        foreach ($data as $country) {
            if (
                isset($country['name'], $country['code']) &&
                !in_array(strtoupper($country['code']), $exclude, true)
            ) {
                $countries[] = [
                    'name' => esc_html($country['name']),
                    'code' => esc_html($country['code']),
                ];
            }
        }

        return $countries;
    }
    public function send_payment_email($data)
    {
        $subject = 'Payment Confirmation | Outmazed Design';
        $headers = ['Content-Type: text/html; charset=UTF-8'];

        $template_path = TELR_PLUGIN_DIR . 'templates/mail/payment_confirm.html';

        if (!file_exists($template_path)) {
            error_log('Email template not found');
            return false;
        }

        $template = file_get_contents($template_path);

        // Optional: Format date nicely
        $formatted_date = date('F j, Y, g:i a', strtotime($data['actual_payment_date']));

        $placeholders = [
            '{{fullName}}' => esc_html($data['bill_fname'] . ' ' . $data['bill_sname']),
            '{{payment_amount}}' => esc_html($data['tran_amount'] . ' ' . $data['tran_currency']),
            '{{payment_date}}' => esc_html($formatted_date),
        ];

        $message = strtr($template, $placeholders);
        $to = sanitize_email($data['bill_email']);

        return wp_mail($to, $subject, $message, $headers);
    }
}