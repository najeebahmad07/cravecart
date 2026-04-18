<?php
$pageTitle = "Checkout - CraveCart";
require_once '../config/config.php';
require_once '../includes/header.php';
require_once '../includes/navbar.php';

if (!isLoggedIn()) {
    header("Location: login.php");
    exit();
}

$cart = $_SESSION['cart'] ?? [];
if (empty($cart)) {
    header("Location: cart.php");
    exit();
}

// Get user details
$user_id = getUserId();
$user_query = "SELECT * FROM users WHERE id = ?";
$stmt = $conn->prepare($user_query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();

$total = 0;
foreach ($cart as $item) {
    $total += $item['price'] * $item['quantity'];
}
$delivery_fee = 50;
$tax = round($total * 0.05, 2); // 5% tax
$grand_total = $total + $delivery_fee + $tax;
?>

<section class="py-5">
    <div class="container">
        <!-- Breadcrumb -->
        <nav aria-label="breadcrumb" class="mb-4">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="home.php">Home</a></li>
                <li class="breadcrumb-item"><a href="cart.php">Cart</a></li>
                <li class="breadcrumb-item active">Checkout</li>
            </ol>
        </nav>

        <h2 class="fw-bold mb-4">
            <i class="bi bi-credit-card"></i> Checkout
        </h2>

        <div class="row">
            <!-- Checkout Form -->
            <div class="col-lg-8">
                <!-- Delivery Information -->
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">
                            <i class="bi bi-truck"></i> Delivery Information
                        </h5>
                    </div>
                    <div class="card-body">
                        <form id="checkoutForm" method="POST">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Full Name *</label>
                                    <input type="text" class="form-control" name="name"
                                           value="<?php echo htmlspecialchars($user['name']); ?>" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Phone Number *</label>
                                    <input type="tel" class="form-control" name="phone"
                                           value="<?php echo htmlspecialchars($user['phone']); ?>" required>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Email Address</label>
                                <input type="email" class="form-control" name="email"
                                       value="<?php echo htmlspecialchars($user['email']); ?>" readonly>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Delivery Address *</label>
                                <textarea class="form-control" name="address" rows="3" required><?php echo htmlspecialchars($user['address']); ?></textarea>
                                <div class="form-text">
                                    <i class="bi bi-info-circle"></i> Please provide complete address including landmark for faster delivery
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Special Instructions (Optional)</label>
                                <textarea class="form-control" name="instructions" rows="2"
                                          placeholder="Any special delivery instructions..."></textarea>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Payment Method -->
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-success text-white">
                        <h5 class="mb-0">
                            <i class="bi bi-wallet2"></i> Payment Method
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <div class="form-check p-3 border rounded">
                                    <input class="form-check-input" type="radio" name="payment_method"
                                           id="cod" value="Cash on Delivery" checked>
                                    <label class="form-check-label" for="cod">
                                        <div class="d-flex align-items-center">
                                            <i class="bi bi-cash-coin text-success fs-4 me-3"></i>
                                            <div>
                                                <strong>Cash on Delivery</strong>
                                                <br><small class="text-muted">Pay when you receive your order</small>
                                            </div>
                                        </div>
                                    </label>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="form-check p-3 border rounded">
                                    <input class="form-check-input" type="radio" name="payment_method"
                                           id="card" value="Credit/Debit Card">
                                    <label class="form-check-label" for="card">
                                        <div class="d-flex align-items-center">
                                            <i class="bi bi-credit-card text-primary fs-4 me-3"></i>
                                            <div>
                                                <strong>Credit/Debit Card</strong>
                                                <br><small class="text-muted">Secure online payment</small>
                                            </div>
                                        </div>
                                    </label>
                                </div>
                            </div>
                        </div>

                        <!-- Card Details (Hidden by default) -->
                        <div id="cardDetails" class="mt-3" style="display: none;">
                            <div class="p-3 bg-light rounded">
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Card Number</label>
                                        <input type="text" class="form-control" placeholder="1234 5678 9012 3456"
                                               maxlength="19" id="cardNumber">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Cardholder Name</label>
                                        <input type="text" class="form-control" placeholder="John Doe">
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label">Expiry Date</label>
                                        <input type="text" class="form-control" placeholder="MM/YY" maxlength="5">
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label">CVV</label>
                                        <input type="password" class="form-control" placeholder="123" maxlength="4">
                                    </div>
                                    <div class="col-md-4 mb-3 d-flex align-items-center">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="saveCard">
                                            <label class="form-check-label small" for="saveCard">
                                                Save for future
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Order Review -->
                <div class="card shadow-sm">
                    <div class="card-header bg-info text-white">
                        <h5 class="mb-0">
                            <i class="bi bi-list-check"></i> Order Review
                        </h5>
                    </div>
                    <div class="card-body">
                        <?php foreach ($cart as $item):
                            $item_image = $item['image'];
                            if (strpos($item_image, 'http') === 0) {
                                $image_src = $item_image;
                            } else {
                                $image_src = '../uploads/menu/' . basename($item_image);
                            }
                        ?>
                        <div class="d-flex align-items-center border-bottom pb-3 mb-3">
                            <img src="<?php echo $image_src; ?>"
                                 alt="<?php echo htmlspecialchars($item['name']); ?>"
                                 class="rounded me-3"
                                 style="width: 60px; height: 60px; object-fit: cover;"
                                 onerror="this.src='https://images.unsplash.com/photo-1546833999-b9f581a1996d?w=100'">
                            <div class="flex-grow-1">
                                <h6 class="mb-1"><?php echo htmlspecialchars($item['name']); ?></h6>
                                <small class="text-muted">Quantity: <?php echo $item['quantity']; ?></small>
                            </div>
                            <div class="text-end">
                                <span class="fw-bold"><?php echo formatPrice($item['price'] * $item['quantity']); ?></span>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>

            <!-- Order Summary -->
            <div class="col-lg-4">
                <div class="sticky-top" style="top: 100px;">
                    <div class="card shadow">
                        <div class="card-header bg-dark text-white">
                            <h5 class="mb-0">
                                <i class="bi bi-receipt"></i> Order Summary
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="d-flex justify-content-between mb-2">
                                <span>Items (<?php echo count($cart); ?>):</span>
                                <span><?php echo formatPrice($total); ?></span>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <span>Delivery Fee:</span>
                                <span><?php echo formatPrice($delivery_fee); ?></span>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <span>Tax (5%):</span>
                                <span><?php echo formatPrice($tax); ?></span>
                            </div>
                            <hr>
                            <div class="d-flex justify-content-between mb-3">
                                <span class="fw-bold fs-5">Total:</span>
                                <span class="fw-bold text-primary fs-4"><?php echo formatPrice($grand_total); ?></span>
                            </div>

                            <!-- Delivery Time -->
                            <div class="alert alert-info">
                                <i class="bi bi-clock"></i>
                                <strong>Estimated Delivery:</strong><br>
                                30-45 minutes
                            </div>

                            <!-- Place Order Button -->
                            <button type="submit" form="checkoutForm" class="btn btn-success w-100 btn-lg" id="placeOrderBtn">
                                <i class="bi bi-check-circle"></i> Place Order
                            </button>

                            <div class="text-center mt-3">
                                <small class="text-muted">
                                    <i class="bi bi-shield-check text-success"></i>
                                    Your payment information is secure and encrypted
                                </small>
                            </div>
                        </div>
                    </div>

                    <!-- Contact Support -->
                    <div class="card shadow-sm mt-3">
                        <div class="card-body text-center">
                            <h6 class="fw-bold">Need Help?</h6>
                            <p class="small text-muted mb-2">Our customer support is here to help</p>
                            <a href="tel:+923001234567" class="btn btn-outline-primary btn-sm">
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
.form-check-input:checked + .form-check-label {
    color: var(--bs-primary);
}

.form-check {
    transition: all 0.3s ease;
}

.form-check:hover {
    background-color: rgba(0,0,0,0.02);
}

#cardDetails {
    transition: all 0.3s ease;
}

.card {
    transition: all 0.3s ease;
}

.card:hover {
    transform: translateY(-2px);
}
</style>

<!-- JavaScript -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Payment method toggle
    const paymentRadios = document.querySelectorAll('input[name="payment_method"]');
    const cardDetails = document.getElementById('cardDetails');

    paymentRadios.forEach(radio => {
        radio.addEventListener('change', function() {
            if (this.value === 'Credit/Debit Card') {
                cardDetails.style.display = 'block';
            } else {
                cardDetails.style.display = 'none';
            }
        });
    });

    // Card number formatting
    const cardNumberInput = document.getElementById('cardNumber');
    if (cardNumberInput) {
        cardNumberInput.addEventListener('input', function() {
            let value = this.value.replace(/\s/g, '').replace(/[^0-9]/gi, '');
            let formattedValue = value.match(/.{1,4}/g)?.join(' ');
            this.value = formattedValue || value;
        });
    }

    // Form submission
    const checkoutForm = document.getElementById('checkoutForm');
    checkoutForm.addEventListener('submit', function(e) {
        e.preventDefault();
        placeOrder();
    });
});

function placeOrder() {
    const submitBtn = document.getElementById('placeOrderBtn');
    const originalText = submitBtn.innerHTML;

    // Validate form
    const form = document.getElementById('checkoutForm');
    if (!form.checkValidity()) {
        form.reportValidity();
        return;
    }

    // Show loading state
    submitBtn.disabled = true;
    submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Processing Order...';

    // Prepare form data
    const formData = new FormData(form);

    // Get payment method
    const paymentMethod = document.querySelector('input[name="payment_method"]:checked').value;
    formData.append('payment_method', paymentMethod);

    // Submit order
    fetch('../ajax/place-order.php', {
        method: 'POST',
        body: formData,
        credentials: 'same-origin'
    })
    .then(response => {
        if (!response.ok) {
            throw new Error(`HTTP ${response.status}`);
        }
        return response.json();
    })
    .then(data => {
        if (data.success) {
            // Show success message
            showToast('Order placed successfully!', 'success');

            // Redirect to success page after short delay
            setTimeout(() => {
                window.location.href = `order-success.php?order=${data.order_id}`;
            }, 1500);
        } else {
            throw new Error(data.message || 'Failed to place order');
        }
    })
    .catch(error => {
        console.error('Checkout error:', error);
        showToast(error.message || 'Failed to place order. Please try again.', 'danger');

        // Reset button
        submitBtn.disabled = false;
        submitBtn.innerHTML = originalText;
    });
}

function showToast(message, type = 'success') {
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

    toast.addEventListener('hidden.bs.toast', () => {
        toast.remove();
    });
}
</script>

<?php require_once '../includes/footer.php'; ?>