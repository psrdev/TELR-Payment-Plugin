<?php
if (!defined('ABSPATH'))
    exit;

$per_page = 10;
$page = isset($_GET['paged']) ? max(1, intval($_GET['paged'])) : 1;
$offset = ($page - 1) * $per_page;

$total = $wpdb->get_var("SELECT COUNT(*) FROM $table_name");
$payments = $wpdb->get_results("SELECT * FROM $table_name ORDER BY created_at DESC LIMIT $offset, $per_page");

?>

<div class="wrap">
    <h1>Payments</h1>

    <form method="post">
        <input type="hidden" name="action" value="insert">
        <table class="form-table">
            <tr>
                <th>Cart ID</th>
                <td><input name="cart_id" required></td>
            </tr>
            <tr>
                <th>First Name</th>
                <td><input name="customer_first_name"></td>
            </tr>
            <tr>
                <th>Last Name</th>
                <td><input name="customer_last_name"></td>
            </tr>
            <tr>
                <th>Email</th>
                <td><input name="customer_email" type="email"></td>
            </tr>
            <tr>
                <th>Phone</th>
                <td><input name="customer_phone"></td>
            </tr>
            <tr>
                <th>Nationality</th>
                <td><input name="customer_nationality"></td>
            </tr>
            <tr>
                <th>Country of Residence</th>
                <td><input name="customer_country_of_residence"></td>
            </tr>
            <tr>
                <th>Assigned Agent</th>
                <td><input name="customer_assigned_agent"></td>
            </tr>
            <tr>
                <th>Special Note</th>
                <td><textarea name="customer_special_note"></textarea></td>
            </tr>
            <tr>
                <th>Payable Amount</th>
                <td><input name="payable_amount" type="number" step="0.01"></td>
            </tr>
            <tr>
                <th>Status</th>
                <td><input name="status"></td>
            </tr>
            <tr>
                <th>Reference Number</th>
                <td><input name="reference_number"></td>
            </tr>
        </table>
        <p><input type="submit" value="Add Payment" class="button button-primary"></p>
    </form>

    <h2>Payment Records</h2>
    <table class="widefat fixed striped">
        <thead>
            <tr>
                <th>ID</th>
                <th>Cart ID</th>
                <th>Name</th>
                <th>Email</th>
                <th>Phone</th>
                <th>Amount</th>
                <th>Status</th>
                <th>Created</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($payments as $payment): ?>
                <tr>
                    <td><?= esc_html($payment->id) ?></td>
                    <td><?= esc_html($payment->cart_id) ?></td>
                    <td><?= esc_html($payment->customer_first_name . ' ' . $payment->customer_last_name) ?></td>
                    <td><?= esc_html($payment->customer_email) ?></td>
                    <td><?= esc_html($payment->customer_phone) ?></td>
                    <td><?= esc_html($payment->payable_amount) ?></td>
                    <td><?= esc_html($payment->status) ?></td>
                    <td><?= esc_html($payment->created_at) ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <?php
    $total_pages = ceil($total / $per_page);
    if ($total_pages > 1): ?>
        <div class="tablenav">
            <div class="tablenav-pages">
                <?php
                $base_url = remove_query_arg('paged');
                for ($i = 1; $i <= $total_pages; $i++) {
                    $link = add_query_arg('paged', $i, $base_url);
                    if ($i === $page) {
                        echo "<span class='page-numbers current'>$i</span> ";
                    } else {
                        echo "<a class='page-numbers' href='" . esc_url($link) . "'>$i</a> ";
                    }
                }
                ?>
            </div>
        </div>
    <?php endif; ?>
</div>