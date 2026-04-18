<?php
require_once 'includes/header.php';

// Get statistics
$total_users = $conn->query("SELECT COUNT(*) as count FROM users")->fetch_assoc()['count'];
$total_restaurants = $conn->query("SELECT COUNT(*) as count FROM restaurants")->fetch_assoc()['count'];
$total_orders = $conn->query("SELECT COUNT(*) as count FROM orders")->fetch_assoc()['count'];
$total_revenue = $conn->query("SELECT SUM(total_amount) as total FROM orders")->fetch_assoc()['total'] ?? 0;

// Recent orders
$recent_orders = $conn->query("SELECT o.*, u.name as user_name
                               FROM orders o
                               JOIN users u ON o.user_id = u.id
                               ORDER BY o.created_at DESC
                               LIMIT 5");
?>

<div class="admin-content">
    <h2 class="fw-bold mb-4">Dashboard</h2>

    <!-- Stats Cards -->
    <div class="row g-4 mb-5">
        <div class="col-lg-3 col-md-6">
            <div class="stat-card primary">
                <div class="icon">
                    <i class="bi bi-people-fill"></i>
                </div>
                <h3><?php echo $total_users; ?></h3>
                <p>Total Users</p>
            </div>
        </div>

        <div class="col-lg-3 col-md-6">
            <div class="stat-card success">
                <div class="icon">
                    <i class="bi bi-shop"></i>
                </div>
                <h3><?php echo $total_restaurants; ?></h3>
                <p>Restaurants</p>
            </div>
        </div>

        <div class="col-lg-3 col-md-6">
            <div class="stat-card warning">
                <div class="icon">
                    <i class="bi bi-bag-check-fill"></i>
                </div>
                <h3><?php echo $total_orders; ?></h3>
                <p>Total Orders</p>
            </div>
        </div>

        <div class="col-lg-3 col-md-6">
            <div class="stat-card danger">
                <div class="icon">
                    <i class="bi bi-cash-stack"></i>
                </div>
                <h3>Rs. <?php echo number_format($total_revenue, 2); ?></h3>
                <p>Total Revenue</p>
            </div>
        </div>
    </div>

    <!-- Recent Orders -->
    <div class="table-card">
        <h5 class="fw-bold mb-4">Recent Orders</h5>
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>Order ID</th>
                        <th>Customer</th>
                        <th>Amount</th>
                        <th>Status</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($order = $recent_orders->fetch_assoc()): ?>
                    <tr>
                        <td>#<?php echo str_pad($order['id'], 6, '0', STR_PAD_LEFT); ?></td>
                        <td><?php echo htmlspecialchars($order['user_name']); ?></td>
                        <td>Rs. <?php echo number_format($order['total_amount'], 2); ?></td>
                        <td>
                            <span class="badge bg-<?php echo $order['status'] === 'Delivered' ? 'success' : 'warning'; ?>">
                                <?php echo $order['status']; ?>
                            </span>
                        </td>
                        <td><?php echo date('M d, Y', strtotime($order['created_at'])); ?></td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>