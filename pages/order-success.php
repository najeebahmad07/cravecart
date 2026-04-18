<?php
$pageTitle = "Order Placed Successfully - CraveCart";
require_once '../config/config.php';
require_once '../includes/header.php';
require_once '../includes/navbar.php';

if (!isLoggedIn()) {
    header("Location: login.php");
    exit();
}

$order_id = isset($_GET['order']) ? (int)$_GET['order'] : 0;

if (!$order_id) {
    header("Location: dashboard.php");
    exit();
}

// Fetch order details
$order_query = "SELECT o.*, u.name FROM orders o
                JOIN users u ON o.user_id = u.id
                WHERE o.id = ? AND o.user_id = ?";
$stmt = $conn->prepare($order_query);
$user_id = getUserId();
$stmt->bind_param("ii", $order_id, $user_id);
$stmt->execute();
$order = $stmt->get_result()->fetch_assoc();

if (!$order) {
    header("Location: dashboard.php");
    exit();
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
$order_items = $stmt->get_result();

// Calculate estimated delivery time
$estimated_time = date('h:i A', strtotime($order['created_at'] . ' +40 minutes'));
?>

<section class="py-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <!-- Success Header -->
                <div class="text-center mb-5">
                    <div class="mb-4">
                        <div class="success-animation">
                            <i class="bi bi-check-circle-fill text-success" style="font-size: 5rem;"></i>
                        </div>
                    </div>
                    <h1 class="fw-bold mb-3 text-success">Order Placed Successfully!</h1>
                    <p class="text-muted lead">Thank you for your order, <?php echo htmlspecialchars($order['name']); ?>! We're preparing your delicious meal.</p>
                </div>

                <!-- Order Summary Card -->
                <div class="card shadow-lg mb-4">
                    <div class="card-header bg-success text-white">
                        <div class="row align-items-center">
                            <div class="col">
                                <h5 class="mb-0">
                                    <i class="bi bi-receipt"></i> Order Details
                                </h5>
                            </div>
                            <div class="col-auto">
                                <span class="badge bg-light text-success">
                                    Order #<?php echo str_pad($order['id'], 6, '0', STR_PAD_LEFT); ?>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <h6 class="fw-bold text-muted">ORDER INFORMATION</h6>
                                <p class="mb-1"><strong>Order ID:</strong> #<?php echo str_pad($order['id'], 6, '0', STR_PAD_LEFT); ?></p>
                                <p class="mb-1"><strong>Order Date:</strong> <?php echo date('F j, Y - g:i A', strtotime($order['created_at'])); ?></p>
                                <p class="mb-1"><strong>Status:</strong>
                                    <span class="badge bg-warning text-dark"><?php echo $order['status']; ?></span>
                                </p>
                                <p class="mb-0"><strong>Payment Method:</strong> <?php echo htmlspecialchars($order['payment_method']); ?></p>
                            </div>
                            <div class="col-md-6">
                                <h6 class="fw-bold text-muted">DELIVERY INFORMATION</h6>
                                <p class="mb-1"><strong>Estimated Delivery:</strong> <?php echo $estimated_time; ?></p>
                                <p class="mb-1"><strong>Phone:</strong> <?php echo htmlspecialchars($order['phone']); ?></p>
                                <p class="mb-0"><strong>Address:</strong><br>
                                    <?php echo nl2br(htmlspecialchars($order['delivery_address'])); ?>
                                </p>
                            </div>
                        </div>

                        <!-- Order Items -->
                        <h6 class="fw-bold text-muted mb-3">ITEMS ORDERED</h6>
                        <?php while ($item = $order_items->fetch_assoc()):
                            $item_image = $item['image'];
                            if (strpos($item_image, 'http') === 0) {
                                $image_src = $item_image;
                            } else {
                                $image_src = '../uploads/menu/' . basename($item_image);
                            }
                        ?>
                        <div class="d-flex align-items-center border-bottom py-3">
                            <img src="<?php echo $image_src; ?>"
                                 alt="<?php echo htmlspecialchars($item['name']); ?>"
                                 class="rounded me-3"
                                 style="width: 60px; height: 60px; object-fit: cover;"
                                 onerror="this.src='https://images.unsplash.com/photo-1546833999-b9f581a1996d?w=100'">
                            <div class="flex-grow-1">
                                <h6 class="mb-1"><?php echo htmlspecialchars($item['name']); ?></h6>
                                <small class="text-muted"><?php echo htmlspecialchars($item['restaurant_name']); ?></small>
                            </div>
                            <div class="text-center me-3">
                                <span class="badge bg-light text-dark">x<?php echo $item['quantity']; ?></span>
                            </div>
                            <div class="text-end">
                                <p class="mb-0 fw-bold"><?php echo formatPrice($item['price'] * $item['quantity']); ?></p>
                            </div>
                        </div>
                        <?php endwhile; ?>

                        <!-- Total -->
                        <div class="d-flex justify-content-between align-items-center mt-3 pt-3 border-top">
                            <span class="fw-bold fs-5">Total Amount</span>
                            <span class="fw-bold text-success fs-4"><?php echo formatPrice($order['total_amount']); ?></span>
                        </div>
                    </div>
                </div>

                <!-- Status Timeline -->
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">
                            <i class="bi bi-clock-history"></i> Order Status
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="timeline">
                            <div class="timeline-item completed">
                                <div class="timeline-marker">
                                    <i class="bi bi-check-circle-fill"></i>
                                </div>
                                <div class="timeline-content">
                                    <h6 class="fw-bold">Order Placed</h6>
                                    <p class="text-muted small mb-0"><?php echo date('g:i A', strtotime($order['created_at'])); ?></p>
                                </div>
                            </div>

                            <div class="timeline-item active">
                                <div class="timeline-marker">
                                    <i class="bi bi-clock"></i>
                                </div>
                                <div class="timeline-content">
                                    <h6 class="fw-bold">Preparing Order</h6>
                                    <p class="text-muted small mb-0">Restaurant is preparing your order</p>
                                </div>
                            </div>

                            <div class="timeline-item">
                                <div class="timeline-marker">
                                    <i class="bi bi-truck"></i>
                                </div>
                                <div class="timeline-content">
                                    <h6 class="fw-bold">Out for Delivery</h6>
                                    <p class="text-muted small mb-0">Estimated: <?php echo $estimated_time; ?></p>
                                </div>
                            </div>

                            <div class="timeline-item">
                                <div class="timeline-marker">
                                    <i class="bi bi-house-door"></i>
                                </div>
                                <div class="timeline-content">
                                    <h6 class="fw-bold">Delivered</h6>
                                    <p class="text-muted small mb-0">Enjoy your meal!</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="row g-3">
                    <div class="col-md-4">
                        <a href="dashboard.php" class="btn btn-primary w-100">
                            <i class="bi bi-speedometer2"></i> View Dashboard
                        </a>
                    </div>
                    <div class="col-md-4">
                        <a href="home.php#restaurants" class="btn btn-outline-primary w-100">
                            <i class="bi bi-arrow-repeat"></i> Order Again
                        </a>
                    </div>
                    <div class="col-md-4">
                        <button class="btn btn-outline-success w-100" onclick="shareOrder()">
                            <i class="bi bi-share"></i> Share Order
                        </button>
                    </div>
                </div>

                <!-- Contact Support -->
                <div class="alert alert-info mt-4">
                    <div class="row align-items-center">
                        <div class="col">
                            <h6 class="fw-bold mb-1">
                                <i class="bi bi-headset"></i> Need Help?
                            </h6>
                            <p class="mb-0">Our customer support team is here to assist you 24/7</p>
                        </div>
                        <div class="col-auto">
                            <a href="tel:+923001234567" class="btn btn-info btn-sm">
                                <i class="bi bi-telephone"></i> Call Support
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Custom CSS -->
<style>
.success-animation {
    animation: successPulse 2s ease-in-out infinite;
}

@keyframes successPulse {
    0% {
        transform: scale(1);
    }
    50% {
        transform: scale(1.1);
    }
    100% {
        transform: scale(1);
    }
}

.timeline {
    position: relative;
}

.timeline::before {
    content: '';
    position: absolute;
    left: 20px;
    top: 0;
    bottom: 0;
    width: 2px;
    background: #e9ecef;
}

.timeline-item {
    position: relative;
    padding-left: 60px;
    padding-bottom: 30px;
}

.timeline-item:last-child {
    padding-bottom: 0;
}

.timeline-marker {
    position: absolute;
    left: 0;
    top: 0;
    width: 40px;
    height: 40px;
    background: #e9ecef;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #6c757d;
    font-size: 1.2rem;
    z-index: 1;
}

.timeline-item.completed .timeline-marker {
    background: #198754;
    color: white;
}

.timeline-item.active .timeline-marker {
    background: #ffc107;
    color: #000;
    animation: pulse 2s infinite;
}

@keyframes pulse {
    0% {
        box-shadow: 0 0 0 0 rgba(255, 193, 7, 0.7);
    }
    70% {
        box-shadow: 0 0 0 10px rgba(255, 193, 7, 0);
    }
    100% {
        box-shadow: 0 0 0 0 rgba(255, 193, 7, 0);
    }
}

.timeline-content h6 {
    margin-bottom: 5px;
}

.card {
    transition: all 0.3s ease;
}

.card:hover {
    transform: translateY(-2px);
    box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15) !important;
}
</style>

<!-- JavaScript -->
<script>
// Auto-refresh order status (optional feature)
function refreshOrderStatus() {
    const orderId = <?php echo $order_id; ?>;

    fetch(`../ajax/get-order-status.php?order_id=${orderId}`)
        .then(response => response.json())
        .then(data => {
            if (data.success && data.status !== '<?php echo $order['status']; ?>') {
                // Reload page if status changed
                location.reload();
            }
        })
        .catch(error => console.error('Status check error:', error));
}

// Check status every 30 seconds
setInterval(refreshOrderStatus, 30000);

// Share order function
function shareOrder() {
    const orderDetails = `🍕 I just ordered from CraveCart!\n\nOrder #<?php echo str_pad($order['id'], 6, '0', STR_PAD_LEFT); ?>\nTotal: <?php echo formatPrice($order['total_amount']); ?>\n\nTry CraveCart for delicious food delivery! 🚀`;

    if (navigator.share) {
        navigator.share({
            title: 'My CraveCart Order',
            text: orderDetails,
            url: window.location.href
        });
    } else {
        // Fallback: copy to clipboard
        navigator.clipboard.writeText(orderDetails).then(() => {
            showToast('Order details copied to clipboard!', 'success');
        });
    }
}

// Print order function
function printOrder() {
    window.print();
}

// Toast notification
function showToast(message, type = 'success') {
    const toast = document.createElement('div');
    toast.className = `toast align-items-center text-white bg-${type} border-0 position-fixed bottom-0 end-0 m-3`;
    toast.style.zIndex = '9999';
    toast.setAttribute('role', 'alert');
    toast.innerHTML = `
        <div class="d-flex">
            <div class="toast-body">
                <i class="bi bi-${type === 'success' ? 'check-circle' : 'info-circle'}"></i>
                ${message}
            </div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
        </div>
    `;

    document.body.appendChild(toast);
    const bsToast = new bootstrap.Toast(toast);
    bsToast.show();

    toast.addEventListener('hidden.bs.toast', () => {
        toast.remove();
    });
}

// Show welcome message
document.addEventListener('DOMContentLoaded', function() {
    showToast('Order confirmed! We\'ll keep you updated on the progress.', 'success');
});

// Confetti animation (optional)
function createConfetti() {
    for (let i = 0; i < 50; i++) {
        setTimeout(() => {
            const confetti = document.createElement('div');
            confetti.style.position = 'fixed';
            confetti.style.top = '-10px';
            confetti.style.left = Math.random() * 100 + '%';
            confetti.style.width = '10px';
            confetti.style.height = '10px';
            confetti.style.backgroundColor = ['#198754', '#ffc107', '#dc3545', '#0d6efd'][Math.floor(Math.random() * 4)];
            confetti.style.pointerEvents = 'none';
            confetti.style.zIndex = '9999';
            confetti.style.animation = 'confettiFall 3s linear forwards';

            document.body.appendChild(confetti);

            setTimeout(() => confetti.remove(), 3000);
        }, i * 100);
    }
}

// CSS for confetti animation
const style = document.createElement('style');
style.textContent = `
@keyframes confettiFall {
    to {
        transform: translateY(100vh) rotate(720deg);
        opacity: 0;
    }
}
`;
document.head.appendChild(style);

// Trigger confetti on load
setTimeout(createConfetti, 500);
</script>

<?php require_once '../includes/footer.php'; ?>