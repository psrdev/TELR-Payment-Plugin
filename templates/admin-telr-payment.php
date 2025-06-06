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
                <th>First Name</th>
                <td><input name="first_name"></td>
            </tr>
            <tr>
                <th>Last Name</th>
                <td><input name="last_name"></td>
            </tr>
            <tr>
                <th>Email</th>
                <td><input name="email" type="email"></td>
            </tr>


            <tr>
                <th>Assigned Agent <b>*</b></th>
                <td><input name="assigned_agent" required></td>
            </tr>

            <tr>
                <th>Payable Amount <b>*</b></th>
                <td><input name="payable_amount" type="number" required></td>
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
                <th>URL</th>
                <th>Email</th>
                <th>Amount</th>
                <th>Status</th>
                <th>Created</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($payments as $payment): ?>
                <tr>
                    <td><?= esc_html($payment->id) ?></td>
                    <td><?= esc_html($payment->cart_id) ?></td>
                    <td><?= esc_html($payment->customer_first_name . ' ' . $payment->customer_last_name) ?></td>
                    <td><?= add_query_arg('id', $payment->cart_id, home_url('/pay-now/')); ?></td>
                    <td><?= esc_html($payment->customer_email) ?></td>
                    <td><?= esc_html($payment->payable_amount) ?></td>
                    <td><?= esc_html($payment->status) ?></td>
                    <td><?= esc_html($payment->created_at) ?></td>

                    <td>
                        <a
                            href="<?php echo esc_url(add_query_arg(['action' => 'edit', 'id' => $payment->id], remove_query_arg('paged'))); ?>">Edit</a>
                        |
                        <a href="<?php echo esc_url(add_query_arg(['action' => 'delete', 'id' => $payment->id], remove_query_arg('paged'))); ?>"
                            onclick="return confirm('Are you sure you want to delete this payment?');">Delete</a>
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