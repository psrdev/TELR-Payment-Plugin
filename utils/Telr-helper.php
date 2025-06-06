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
        $exclude = ["US", "CA", "GB", "AU", "NZ", "IE", "ZA"]; // country codes to exclude

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
}