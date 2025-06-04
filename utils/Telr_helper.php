<?php
class Telr_helper
{
    public function generate_cart_id()
    {
        $cart_id = uniqid('cart_', true);
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
}