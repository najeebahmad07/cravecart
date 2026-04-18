<?php
require_once '../config/config.php';

if (!isLoggedIn()) {
    echo '<div class="alert alert-danger">Please login first</div>';
    exit;
}

$order_id = (int)$_GET['order_id'];
$user_id = getUserId();

// Fetch order
$order_query = "SELECT * FROM orders WHERE id = ? AND user_id = ?";
$stmt = $conn->prepare($order_query);
$stmt->bind_param("ii", $order_id, $user_id);
$stmt->execute();
$order = $stmt->get_result()->fetch_assoc();

if (!$order) {
    echo '<div class="alert alert-danger">Order not found</div>';
    exit;
}

// Fetch order items
$items_query = "SELECT oi.*, mi.name, mi.image, r.name as restaurant_name
                FROM order_items oi
                JOIN menu_items mi ON oi.menu_item_id = mi.id
                JOIN restaurants r ON oi.restaurant_id = r.id
                WHERE oi.order_id = ?";
$stmt = $conn->prepare($items_query);
$stmt->bind_param("i", $order_id);
$stmt->execute();
$items = $stmt->get_result();
?>

<div class="order-details">
    <div class="row mb-3">
        <div class="col-6">
            <p class="text-muted mb-1">Order ID</p>
            <p class="fw-bold">#<?php echo str_pad($order['id'], 6, '0', STR_PAD_LEFT); ?></p>
        </div>
        <div class="col-6 text-end">
            <p class="text-muted mb-1">Status</p>
            <span class="order-status status-<?php echo strtolower($order['status']); ?>">
                <?php echo $order['status']; ?>
            </span>
        </div>
    </div>

    <div class="mb-3">
        <p class="text-muted mb-1">Order Date</p>
        <p class="fw-bold"><?php echo date('M d, Y - h:i A', strtotime($order['created_at'])); ?></p>
    </div>

    <hr>

    <h6 class="fw-bold mb-3">Items Ordered</h6>
    <?php while ($item = $items->fetch_assoc()): ?>
    <div class="d-flex align-items-center mb-3">
        <img src="<?php echo $item['image']; ?>" alt="<?php echo htmlspecialchars($item['name']); ?>"
             class="rounded" style="width: 60px; height: 60px; object-fit: cover;">
        <div class="ms-3 flex-grow-1">
            <h6 class="mb-0"><?php echo htmlspecialchars($item['name']); ?></h6>
            <small class="text-muted"><?php echo htmlspecialchars($item['restaurant_name']); ?></small>
        </div>
        <div class="text-end">
            <p class="mb-0">x<?php echo $item['quantity']; ?></p>
            <p class="mb-0 fw-bold"><?php echo formatPrice($item['price'] * $item['quantity']); ?></p>
        </div>
    </div>
    <?php endwhile; ?>

    <hr>

    <div class="mb-3">
        <p class="text-muted mb-1">Delivery Address</p>
        <p class="fw-bold"><?php echo htmlspecialchars($order['delivery_address']); ?></p>
    </div>

    <div class="mb-3">
        <p class="text-muted mb-1">Phone Number</p>
        <p class="fw-bold"><?php echo htmlspecialchars($order['phone']); ?></p>
    </div>

    <div class="mb-3">
        <p class="text-muted mb-1">Payment Method</p>
        <p class="fw-bold"><?php echo htmlspecialchars($order['payment_method']); ?></p>
    </div>

    <hr>

    <div class="d-flex justify-content-between">
        <span class="fw-bold">Total Amount</span>
        <span class="fw-bold text-primary fs-5"><?php echo formatPrice($order['total_amount']); ?></span>
    </div>
</div>