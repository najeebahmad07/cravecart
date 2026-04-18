<?php
require_once 'includes/header.php';

// Handle status update
if (isset($_POST['update_status'])) {
    $order_id = (int)$_POST['order_id'];
    $status = $conn->real_escape_string($_POST['status']);
    $conn->query("UPDATE orders SET status='$status' WHERE id=$order_id");
    header("Location: orders.php");
    exit;
}

// Handle delete
if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    $conn->query("DELETE FROM orders WHERE id = $id");
    header("Location: orders.php");
    exit;
}

// Fetch all orders
$orders = $conn->query("SELECT o.*, u.name as user_name, u.email as user_email,
                        (SELECT COUNT(*) FROM order_items WHERE order_id = o.id) as items_count
                        FROM orders o
                        JOIN users u ON o.user_id = u.id
                        ORDER BY o.created_at DESC");
?>

<div class="admin-content">
    <h2 class="fw-bold mb-4">Orders Management</h2>

    <div class="table-card">
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>Order ID</th>
                        <th>Customer</th>
                        <th>Items</th>
                        <th>Amount</th>
                        <th>Address</th>
                        <th>Status</th>
                        <th>Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($order = $orders->fetch_assoc()): ?>
                    <tr>
                        <td class="fw-bold">#<?php echo str_pad($order['id'], 6, '0', STR_PAD_LEFT); ?></td>
                        <td>
                            <?php echo htmlspecialchars($order['user_name']); ?>
                            <br><small class="text-muted"><?php echo htmlspecialchars($order['user_email']); ?></small>
                        </td>
                        <td><?php echo $order['items_count']; ?> items</td>
                        <td class="fw-bold">Rs. <?php echo number_format($order['total_amount'], 2); ?></td>
                        <td><?php echo htmlspecialchars(substr($order['delivery_address'], 0, 30)); ?>...</td>
                        <td>
                            <form method="POST" class="d-inline">
                                <input type="hidden" name="order_id" value="<?php echo $order['id']; ?>">
                                <select name="status" class="form-select form-select-sm" onchange="this.form.submit()" style="width: auto;">
                                    <option value="Pending" <?php echo $order['status'] === 'Pending' ? 'selected' : ''; ?>>Pending</option>
                                    <option value="Preparing" <?php echo $order['status'] === 'Preparing' ? 'selected' : ''; ?>>Preparing</option>
                                    <option value="Delivered" <?php echo $order['status'] === 'Delivered' ? 'selected' : ''; ?>>Delivered</option>
                                    <option value="Cancelled" <?php echo $order['status'] === 'Cancelled' ? 'selected' : ''; ?>>Cancelled</option>
                                </select>
                                <input type="hidden" name="update_status" value="1">
                            </form>
                        </td>
                        <td>
                            <?php echo date('M d, Y', strtotime($order['created_at'])); ?>
                            <br><small class="text-muted"><?php echo date('h:i A', strtotime($order['created_at'])); ?></small>
                        </td>
                        <td>
                            <button class="btn btn-action btn-sm btn-info" onclick="viewOrderDetails(<?php echo $order['id']; ?>)">
                                <i class="bi bi-eye"></i>
                            </button>
                            <a href="?delete=<?php echo $order['id']; ?>"
                               class="btn btn-action btn-sm btn-danger"
                               onclick="return confirm('Are you sure you want to delete this order?')">
                                <i class="bi bi-trash"></i>
                            </a>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Order Details Modal -->
<div class="modal fade" id="orderDetailsModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fw-bold">Order Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="orderDetailsContent">
                <div class="text-center py-5">
                    <div class="spinner-border text-primary" role="status"></div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function viewOrderDetails(orderId) {
    const modal = new bootstrap.Modal(document.getElementById('orderDetailsModal'));

    fetch('<?php echo SITE_URL; ?>/ajax/get-order-details.php?order_id=' + orderId)
        .then(response => response.text())
        .then(html => {
            document.getElementById('orderDetailsContent').innerHTML = html;
            modal.show();
        });
}
</script>

<?php require_once 'includes/footer.php'; ?>