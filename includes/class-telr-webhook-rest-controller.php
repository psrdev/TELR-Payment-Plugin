<?php
if (!defined('ABSPATH'))
    exit;

class Telr_Webhook_REST_Controller
{

    public function __construct()
    {
        add_action('rest_api_init', [$this, 'register_routes']);
    }

    public function register_routes()
    {
        register_rest_route('telr/v1', '/webhook', [
            'methods' => 'GET',
            'callback' => [$this, 'handle_webhook'],
            'permission_callback' => '__return_true', // open to public, adjust if needed
        ]);
    }

    public function handle_webhook(WP_REST_Request $request)
    {
        global $wpdb;

        $cart_id = sanitize_text_field($request->get_param('cart_id'));
        $status = sanitize_text_field($request->get_param('status'));

        if (empty($cart_id) || empty($status)) {
            return new WP_REST_Response(['error' => 'Missing cart_id or status'], 400);
        }

        $table = $wpdb->prefix . 'payments';

        $updated = $wpdb->update($table, [
            'status' => $status,
        ], ['cart_id' => $cart_id]);

        if ($updated === false) {
            return new WP_REST_Response(['error' => 'Database update failed'], 500);
        }

        $payment = $wpdb->get_row($wpdb->prepare("SELECT * FROM $table WHERE cart_id = %s", $cart_id));

        if ($payment && is_email($payment->email)) {
            wp_mail(
                $payment->email,
                'Payment Status Updated',
                "Hello {$payment->first_name},\n\nYour payment status is now: {$status}.\n\nThank you."
            );
        }

        return new WP_REST_Response([
            'success' => true,
            'cart_id' => $cart_id,
            'status' => $status,
        ], 200);
    }
}
