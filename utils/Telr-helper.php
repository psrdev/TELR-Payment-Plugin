<?php
class Telr_helper
{
    public function generate_cart_id()
    {
        $cart_id = wp_generate_uuid4();
        return $cart_id;
    }
    public function get_country_from_code($code)
    {
        $code = strtoupper(sanitize_text_field($code));
        $url = "https://restcountries.com/v3.1/alpha/{$code}";

        $response = wp_remote_get($url);

        if (is_wp_error($response)) {
            return 'Country not found';
        }

        $body = wp_remote_retrieve_body($response);
        $data = json_decode($body, true);

        if (!empty($data) && isset($data[0]['name']['common'])) {
            return esc_html($data[0]['name']['common']);
        }

        return 'Country not found';
    }
    public function get_country_code_from_name($name)
    {
        $name = sanitize_text_field($name);
        $name = strtolower($name);
        $url = "https://restcountries.com/v3.1/name/{$name}";

        $response = wp_remote_get($url);

        if (is_wp_error($response)) {
            return 'Country code not found';
        }

        $body = wp_remote_retrieve_body($response);
        $data = json_decode($body, true);

        if (!empty($data) && isset($data[0]['cca2'])) {
            return esc_html($data[0]['cca2']);
        }

        return 'Country code not found';
    }
    public function get_all_countries()
    {
        $url = "https://restcountries.com/v3.1/independent?status=true";

        $response = wp_remote_get($url);

        if (is_wp_error($response)) {
            return [];
        }

        $body = wp_remote_retrieve_body($response);
        $data = json_decode($body, true);
        $exclude = ['IR', 'CU', 'KP', 'SD', 'SS', 'UA', 'SY', 'RU', 'MM', 'YE']; // country codes to exclude

        $countries = [];
        foreach ($data as $country) {
            if (
                isset($country['name']['common'], $country['cca2']) &&
                !in_array(strtoupper($country['cca2']), $exclude, true)
            ) {
                $countries[] = [
                    'name' => esc_html($country['name']['common']),
                    'code' => esc_html($country['cca2']),
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