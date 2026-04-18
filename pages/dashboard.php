<?php
$pageTitle = "My Dashboard - CraveCart";
require_once '../config/config.php';
require_once '../includes/header.php';
require_once '../includes/navbar.php';

if (!isLoggedIn()) {
    header("Location: login.php");
    exit();
}

$user_id = getUserId();

// Get user details
$user_query = "SELECT * FROM users WHERE id = ?";
$stmt = $conn->prepare($user_query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();

// Get order statistics
$total_orders_query = "SELECT COUNT(*) as total FROM orders WHERE user_id = ?";
$stmt = $conn->prepare($total_orders_query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$total_orders = $stmt->get_result()->fetch_assoc()['total'];

$total_spent_query = "SELECT SUM(total_amount) as total FROM orders WHERE user_id = ?";
$stmt = $conn->prepare($total_spent_query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$total_spent = $stmt->get_result()->fetch_assoc()['total'] ?? 0;

// Get recent orders
$orders_query = "SELECT o.*,
                 (SELECT COUNT(*) FROM order_items WHERE order_id = o.id) as items_count
                 FROM orders o
                 WHERE o.user_id = ?
                 ORDER BY o.created_at DESC
                 LIMIT 10";
$stmt = $conn->prepare($orders_query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$orders = $stmt->get_result();
?>

<section class="py-5 bg-light">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <h2 class="fw-bold mb-4">Welcome back, <?php echo htmlspecialchars($user['name']); ?>! 👋</h2>
            </div>
        </div>

        <!-- Stats Cards -->
        <div class="row g-4 mb-5">
            <div class="col-lg-4 col-md-6">
                <div class="dashboard-card">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <i class="bi bi-bag-check-fill text-primary" style="font-size: 2.5rem;"></i>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h3 class="fw-bold mb-0"><?php echo $total_orders; ?></h3>
                            <p class="text-muted mb-0">Total Orders</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4 col-md-6">
                <div class="dashboard-card">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <i class="bi bi-cash-stack text-success" style="font-size: 2.5rem;"></i>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h3 class="fw-bold mb-0"><?php echo formatPrice($total_spent); ?></h3>
                            <p class="text-muted mb-0">Total Spent</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4 col-md-6">
                <div class="dashboard-card">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <i class="bi bi-person-circle text-warning" style="font-size: 2.5rem;"></i>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h3 class="fw-bold mb-0">Member</h3>
                            <p class="text-muted mb-0">Since <?php echo date('M Y', strtotime($user['created_at'])); ?></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="row mb-5">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <h5 class="fw-bold mb-3">Quick Actions</h5>
                        <div class="row">
                            <div class="col-md-3 col-sm-6 mb-3">
                                <a href="home.php#restaurants" class="btn btn-outline-primary w-100">
                                    <i class="bi bi-shop"></i><br>Browse Restaurants
                                </a>
                            </div>
                            <div class="col-md-3 col-sm-6 mb-3">
                                <a href="cart.php" class="btn btn-outline-success w-100">
                                    <i class="bi bi-cart3"></i><br>View Cart
                                </a>
                            </div>
                            <div class="col-md-3 col-sm-6 mb-3">
                                <button class="btn btn-outline-info w-100" onclick="updateProfile()">
                                    <i class="bi bi-person-gear"></i><br>Update Profile
                                </button>
                            </div>
                            <div class="col-md-3 col-sm-6 mb-3">
                                <a href="../auth/logout.php" class="btn btn-outline-danger w-100">
                                    <i class="bi bi-box-arrow-right"></i><br>Logout
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Order History -->
        <div class="card shadow-sm">
            <div class="card-body">
                <h5 class="fw-bold mb-4">Recent Orders</h5>

                <?php if ($orders->num_rows > 0): ?>
                    <?php while ($order = $orders->fetch_assoc()): ?>
                    <div class="order-card">
                        <div class="row align-items-center">
                            <div class="col-md-2">
                                <h6 class="fw-bold mb-1">Order #<?php echo str_pad($order['id'], 6, '0', STR_PAD_LEFT); ?></h6>
                                <small class="text-muted"><?php echo timeAgo($order['created_at']); ?></small>
                            </div>
                            <div class="col-md-2">
                                <p class="mb-0"><strong><?php echo $order['items_count']; ?></strong> items</p>
                            </div>
                            <div class="col-md-3">
                                <p class="mb-0 fw-bold text-primary"><?php echo formatPrice($order['total_amount']); ?></p>
                            </div>
                            <div class="col-md-2">
                                <?php
                                $status_class = 'status-' . strtolower($order['status']);
                                ?>
                                <span class="order-status <?php echo $status_class; ?>">
                                    <?php echo $order['status']; ?>
                                </span>
                            </div>
                            <div class="col-md-2">
                                <small class="text-muted"><?php echo $order['payment_method']; ?></small>
                            </div>
                            <div class="col-md-1 text-end">
                                <button class="btn btn-sm btn-outline-primary view-order-details"
                                        data-order-id="<?php echo $order['id']; ?>">
                                    <i class="bi bi-eye"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    <?php endwhile; ?>
                <?php else: ?>
                    <div class="text-center py-5">
                        <i class="bi bi-inbox text-muted" style="font-size: 3rem;"></i>
                        <h5 class="mt-3">No orders yet</h5>
                        <p class="text-muted">Start exploring delicious restaurants!</p>
                        <a href="home.php#restaurants" class="btn btn-primary">
                            <i class="bi bi-shop"></i> Browse Restaurants
                        </a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>

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
document.querySelectorAll('.view-order-details').forEach(btn => {
    btn.addEventListener('click', function() {
        const orderId = this.dataset.orderId;
        const modal = new bootstrap.Modal(document.getElementById('orderDetailsModal'));

        fetch('../ajax/get-order-details.php?order_id=' + orderId)
            .then(response => response.text())
            .then(html => {
                document.getElementById('orderDetailsContent').innerHTML = html;
                modal.show();
            });
    });
});

function updateProfile() {
    // You can implement profile update functionality here
    alert('Profile update feature coming soon!');
}
</script>

<?php require_once '../includes/footer.php'; ?>