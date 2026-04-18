<?php
$pageTitle = "Shopping Cart - CraveCart";
require_once '../config/config.php';
require_once '../includes/header.php';
require_once '../includes/navbar.php';

if (!isLoggedIn()) {
    header("Location: login.php");
    exit();
}

$cart = $_SESSION['cart'] ?? [];
$total = 0;
$delivery_fee = 50;

// Calculate total
foreach ($cart as $item) {
    $total += $item['price'] * $item['quantity'];
}

$grand_total = $total + $delivery_fee;
?>

<section class="py-5">
    <div class="container">
        <!-- Cart Header -->
        <div class="row mb-4">
            <div class="col-12">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="home.php">Home</a></li>
                        <li class="breadcrumb-item active">Shopping Cart</li>
                    </ol>
                </nav>
                <h2 class="fw-bold mb-2">
                    <i class="bi bi-cart3"></i> Shopping Cart
                    <?php if (!empty($cart)): ?>
                        <span class="badge bg-primary ms-2"><?php echo count($cart); ?> items</span>
                    <?php endif; ?>
                </h2>
            </div>
        </div>

        <?php if (empty($cart)): ?>
        <!-- Empty Cart State -->
        <div class="row">
            <div class="col-lg-8 mx-auto">
                <div class="text-center py-5">
                    <div class="mb-4">
                        <i class="bi bi-cart-x display-1 text-muted"></i>
                    </div>
                    <h3 class="fw-bold mb-3">Your cart is empty</h3>
                    <p class="text-muted mb-4">Looks like you haven't added anything to your cart yet. Start by browsing our amazing restaurants!</p>

                    <div class="d-flex gap-3 justify-content-center flex-wrap">
                        <a href="home.php#restaurants" class="btn btn-primary btn-lg">
                            <i class="bi bi-shop"></i> Browse Restaurants
                        </a>
                        <a href="home.php" class="btn btn-outline-primary btn-lg">
                            <i class="bi bi-house"></i> Go Home
                        </a>
                    </div>

                    <!-- Popular Restaurants Suggestion -->
                    <div class="mt-5">
                        <h5 class="fw-bold mb-3">Popular Restaurants</h5>
                        <div class="row g-3">
                            <?php
                            // Fetch 3 popular restaurants
                            $popular_query = "SELECT * FROM restaurants WHERE is_active = 1 ORDER BY rating DESC LIMIT 3";
                            $popular_result = $conn->query($popular_query);

                            while ($restaurant = $popular_result->fetch_assoc()):
                                $restaurant_image = $restaurant['image'];
                                if (strpos($restaurant_image, 'http') === 0) {
                                    $image_src = $restaurant_image;
                                } else {
                                    $image_src = 'uploads/restaurants/' . basename($restaurant_image);
                                }
                            ?>
                            <div class="col-md-4">
                                <div class="card h-100 border-0 shadow-sm">
                                    <img src="<?php echo $image_src; ?>"
                                         class="card-img-top"
                                         style="height: 120px; object-fit: cover;"
                                         alt="<?php echo htmlspecialchars($restaurant['name']); ?>"
                                         onerror="this.src='https://images.unsplash.com/photo-1517248135467-4c7edcad34c4?w=300'">
                                    <div class="card-body text-center p-3">
                                        <h6 class="card-title mb-1"><?php echo htmlspecialchars($restaurant['name']); ?></h6>
                                        <small class="text-muted">
                                            <i class="bi bi-star-fill text-warning"></i> <?php echo $restaurant['rating']; ?>
                                        </small>
                                        <div class="mt-2">
                                            <a href="restaurant.php?id=<?php echo $restaurant['id']; ?>" class="btn btn-sm btn-outline-primary">
                                                View Menu
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php endwhile; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <?php else: ?>
        <!-- Cart with Items -->
        <div class="row">
            <!-- Cart Items -->
            <div class="col-lg-8">
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-white">
                        <h5 class="mb-0 fw-bold">
                            <i class="bi bi-bag"></i> Cart Items (<?php echo count($cart); ?>)
                        </h5>
                    </div>
                    <div class="card-body p-0">
                        <?php foreach ($cart as $item_id => $item):
                            $subtotal = $item['price'] * $item['quantity'];

                            // Handle image URL
                            $item_image = $item['image'];
                            if (strpos($item_image, 'http') === 0) {
                                $image_src = $item_image;
                            } else {
                                $image_src = '../uploads/menu/' . basename($item_image);
                            }
                        ?>
                        <div class="cart-item border-bottom">
                            <div class="row align-items-center p-3">
                                <!-- Item Image -->
                                <div class="col-md-2 col-sm-3">
                                    <img src="<?php echo $image_src; ?>"
                                         alt="<?php echo htmlspecialchars($item['name']); ?>"
                                         class="img-fluid rounded shadow-sm"
                                         style="height: 80px; width: 80px; object-fit: cover;"
                                         onerror="this.src='https://images.unsplash.com/photo-1546833999-b9f581a1996d?w=200'">
                                </div>

                                <!-- Item Details -->
                                <div class="col-md-4 col-sm-9">
                                    <h6 class="fw-bold mb-1"><?php echo htmlspecialchars($item['name']); ?></h6>
                                    <p class="text-muted small mb-1">
                                        <i class="bi bi-tag"></i> <?php echo formatPrice($item['price']); ?> each
                                    </p>
                                    <p class="text-muted small mb-0">
                                        <i class="bi bi-shop"></i> Restaurant ID: <?php echo $item['restaurant_id']; ?>
                                    </p>
                                </div>

                                <!-- Quantity Controls -->
                                <div class="col-md-3">
                                    <div class="quantity-control d-flex align-items-center justify-content-center">
                                        <button class="btn btn-outline-secondary btn-sm btn-quantity-decrease"
                                                data-id="<?php echo $item_id; ?>"
                                                <?php echo $item['quantity'] <= 1 ? 'disabled' : ''; ?>>
                                            <i class="bi bi-dash"></i>
                                        </button>
                                        <span class="quantity mx-3 fw-bold" id="qty-<?php echo $item_id; ?>">
                                            <?php echo $item['quantity']; ?>
                                        </span>
                                        <button class="btn btn-outline-secondary btn-sm btn-quantity-increase"
                                                data-id="<?php echo $item_id; ?>">
                                            <i class="bi bi-plus"></i>
                                        </button>
                                    </div>
                                </div>

                                <!-- Subtotal -->
                                <div class="col-md-2 text-center">
                                    <p class="fw-bold mb-0 text-primary fs-5"><?php echo formatPrice($subtotal); ?></p>
                                </div>

                                <!-- Remove Button -->
                                <div class="col-md-1 text-center">
                                    <button class="btn btn-outline-danger btn-sm btn-remove-item"
                                            data-id="<?php echo $item_id; ?>"
                                            data-name="<?php echo htmlspecialchars($item['name']); ?>"
                                            title="Remove item">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; ?>

                        <!-- Continue Shopping -->
                        <div class="p-3 bg-light">
                            <a href="home.php#restaurants" class="btn btn-outline-primary">
                                <i class="bi bi-plus-circle"></i> Add More Items
                            </a>
                            <button class="btn btn-outline-danger ms-2" onclick="clearCart()">
                                <i class="bi bi-trash"></i> Clear Cart
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Order Summary -->
            <div class="col-lg-4">
                <div class="cart-summary sticky-top" style="top: 100px;">
                    <div class="card shadow-sm">
                        <div class="card-header bg-primary text-white">
                            <h5 class="mb-0 fw-bold">
                                <i class="bi bi-receipt"></i> Order Summary
                            </h5>
                        </div>
                        <div class="card-body">
                            <!-- Items List -->
                            <div class="mb-3">
                                <h6 class="fw-bold">Items (<?php echo count($cart); ?>)</h6>
                                <?php foreach ($cart as $item): ?>
                                <div class="d-flex justify-content-between small mb-1">
                                    <span><?php echo htmlspecialchars(substr($item['name'], 0, 20)); ?>... x<?php echo $item['quantity']; ?></span>
                                    <span><?php echo formatPrice($item['price'] * $item['quantity']); ?></span>
                                </div>
                                <?php endforeach; ?>
                            </div>

                            <hr>

                            <!-- Pricing Breakdown -->
                            <div class="d-flex justify-content-between mb-2">
                                <span>Subtotal:</span>
                                <span class="fw-bold"><?php echo formatPrice($total); ?></span>
                            </div>

                            <div class="d-flex justify-content-between mb-2">
                                <span>
                                    <i class="bi bi-truck"></i> Delivery Fee:
                                </span>
                                <span class="fw-bold text-success"><?php echo formatPrice($delivery_fee); ?></span>
                            </div>

                            <div class="d-flex justify-content-between mb-2 small text-muted">
                                <span>Tax & Service:</span>
                                <span>Included</span>
                            </div>

                            <hr>

                            <div class="d-flex justify-content-between mb-3">
                                <span class="fw-bold fs-5">Total:</span>
                                <span class="fw-bold text-primary fs-4"><?php echo formatPrice($grand_total); ?></span>
                            </div>

                            <!-- Promo Code -->
                            <div class="mb-3">
                                <div class="input-group">
                                    <input type="text" class="form-control" placeholder="Promo code" id="promoCode">
                                    <button class="btn btn-outline-secondary" type="button" onclick="applyPromo()">
                                        Apply
                                    </button>
                                </div>
                                <small class="text-muted">Enter FIRST10 for 10% off your first order!</small>
                            </div>

                            <!-- Checkout Button -->
                            <a href="checkout.php" class="btn btn-primary w-100 btn-lg mb-2">
                                <i class="bi bi-credit-card"></i> Proceed to Checkout
                            </a>

                            <!-- Security Badge -->
                            <div class="text-center mt-3">
                                <small class="text-muted">
                                    <i class="bi bi-shield-check text-success"></i>
                                    Secure checkout powered by SSL encryption
                                </small>
                            </div>
                        </div>
                    </div>

                    <!-- Delivery Info Card -->
                    <div class="card shadow-sm mt-3">
                        <div class="card-body">
                            <h6 class="fw-bold mb-3">
                                <i class="bi bi-info-circle"></i> Delivery Information
                            </h6>
                            <ul class="list-unstyled small mb-0">
                                <li class="mb-2">
                                    <i class="bi bi-clock text-primary"></i>
                                    Estimated delivery: 30-45 minutes
                                </li>
                                <li class="mb-2">
                                    <i class="bi bi-geo-alt text-primary"></i>
                                    Free delivery on orders over Rs. 500
                                </li>
                                <li class="mb-0">
                                    <i class="bi bi-telephone text-primary"></i>
                                    Track your order in real-time
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php endif; ?>
    </div>
</section>

<!-- Custom CSS -->
<style>
.cart-item {
    transition: all 0.3s ease;
}

.cart-item:hover {
    background-color: #f8f9fa;
}

.quantity-control button {
    width: 35px;
    height: 35px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.3s ease;
}

.quantity-control button:hover:not(:disabled) {
    transform: scale(1.1);
    box-shadow: 0 2px 5px rgba(0,0,0,0.2);
}

.quantity-control button:disabled {
    opacity: 0.5;
    cursor: not-allowed;
}

.cart-summary {
    animation: slideInRight 0.5s ease;
}

@keyframes slideInRight {
    from {
        opacity: 0;
        transform: translateX(20px);
    }
    to {
        opacity: 1;
        transform: translateX(0);
    }
}

.card {
    border: none;
    transition: all 0.3s ease;
}

.card:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(0,0,0,0.1) !important;
}

.btn-remove-item:hover {
    transform: scale(1.1);
}
</style>

<!-- JavaScript -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Quantity increase buttons
    document.querySelectorAll('.btn-quantity-increase').forEach(button => {
        button.addEventListener('click', function() {
            const itemId = this.dataset.id;
            updateCartQuantity(itemId, 'increase');
        });
    });

    // Quantity decrease buttons
    document.querySelectorAll('.btn-quantity-decrease').forEach(button => {
        button.addEventListener('click', function() {
            const itemId = this.dataset.id;
            updateCartQuantity(itemId, 'decrease');
        });
    });

    // Remove item buttons
    document.querySelectorAll('.btn-remove-item').forEach(button => {
        button.addEventListener('click', function() {
            const itemId = this.dataset.id;
            const itemName = this.dataset.name || 'this item';

            if (confirm(`Are you sure you want to remove "${itemName}" from your cart?`)) {
                removeFromCart(itemId, this);
            }
        });
    });
});

function updateCartQuantity(itemId, action) {
    const quantitySpan = document.getElementById(`qty-${itemId}`);
    const currentQty = parseInt(quantitySpan.textContent);

    // Show loading
    quantitySpan.textContent = '...';

    fetch('../ajax/update-cart.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: `item_id=${itemId}&action=${action}`,
        credentials: 'same-origin'
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Reload page to update all totals
            location.reload();
        } else {
            // Restore original quantity
            quantitySpan.textContent = currentQty;
            alert(data.message || 'Failed to update quantity');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        quantitySpan.textContent = currentQty;
        alert('Network error. Please try again.');
    });
}

function removeFromCart(itemId, button) {
    const originalText = button.innerHTML;
    button.innerHTML = '<span class="spinner-border spinner-border-sm"></span>';
    button.disabled = true;

    fetch('../ajax/remove-from-cart.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: `item_id=${itemId}`,
        credentials: 'same-origin'
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Animate removal
            const cartItem = button.closest('.cart-item');
            cartItem.style.transition = 'all 0.3s ease';
            cartItem.style.opacity = '0';
            cartItem.style.transform = 'translateX(100px)';

            // Show success message
            showToast('Item removed from cart', 'success');

            // Reload page after animation
            setTimeout(() => {
                location.reload();
            }, 300);
        } else {
            button.innerHTML = originalText;
            button.disabled = false;
            alert(data.message || 'Failed to remove item');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        button.innerHTML = originalText;
        button.disabled = false;
        alert('Network error. Please try again.');
    });
}

function clearCart() {
    if (confirm('Are you sure you want to clear your entire cart? This action cannot be undone.')) {
        // You can implement a clear cart AJAX call here
        // For now, we'll reload the page after clearing the session
        fetch('../ajax/clear-cart.php', {
            method: 'POST',
            credentials: 'same-origin'
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showToast('Cart cleared successfully', 'success');
                setTimeout(() => location.reload(), 1000);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            // Fallback: just reload the page
            location.reload();
        });
    }
}

function applyPromo() {
    const promoCode = document.getElementById('promoCode').value.trim().toUpperCase();

    if (!promoCode) {
        alert('Please enter a promo code');
        return;
    }

    if (promoCode === 'FIRST10') {
        alert('Promo code applied! You\'ll get 10% off at checkout.');
        // You can implement actual promo code logic here
    } else {
        alert('Invalid promo code. Try FIRST10 for 10% off!');
    }
}

function showToast(message, type = 'success') {
    // Create toast notification
    const toast = document.createElement('div');
    toast.className = `toast align-items-center text-white bg-${type} border-0 position-fixed bottom-0 end-0 m-3`;
    toast.style.zIndex = '9999';
    toast.setAttribute('role', 'alert');
    toast.innerHTML = `
        <div class="d-flex">
            <div class="toast-body">
                <i class="bi bi-${type === 'success' ? 'check-circle' : 'exclamation-triangle'}"></i>
                ${message}
            </div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
        </div>
    `;

    document.body.appendChild(toast);
    const bsToast = new bootstrap.Toast(toast);
    bsToast.show();

    // Remove after hidden
    toast.addEventListener('hidden.bs.toast', () => {
        toast.remove();
    });
}

// Auto-save cart on page unload (optional feature)
window.addEventListener('beforeunload', function() {
    // You can implement auto-save functionality here
    localStorage.setItem('cart_backup', JSON.stringify(<?php echo json_encode($cart); ?>));
});
</script>

<?php require_once '../includes/footer.php'; ?>