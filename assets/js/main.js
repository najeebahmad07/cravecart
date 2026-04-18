/**
 * CraveCart - Complete Main JavaScript
 * Developed by Neha Qazmi
 * Version: 2.0 (.php extension support)
 */

(function() {
    'use strict';

    // ========================================
    // CONFIGURATION
    // ========================================
    const CONFIG = {
        BASE_URL: '', // No base URL needed for .php files
        DEBUG: true,
        TOAST_DURATION: 4000,
        ANIMATION_DURATION: 300
    };

    // ========================================
    // UTILITY FUNCTIONS
    // ========================================
    function log(...args) {
        if (CONFIG.DEBUG) {
            console.log('[CraveCart]', ...args);
        }
    }

    function error(...args) {
        console.error('[CraveCart ERROR]', ...args);
    }

    // Get proper AJAX URL based on current page location
    function getAjaxUrl(endpoint) {
        const path = window.location.pathname;
        let prefix = '';

        if (path.includes('/pages/')) {
            prefix = '../ajax/';
        } else if (path.includes('/admin/')) {
            prefix = '../ajax/';
        } else {
            prefix = 'ajax/';
        }

        return prefix + endpoint;
    }

    // ========================================
    // TOAST NOTIFICATION SYSTEM
    // ========================================
    function showToast(message, type = 'info', duration = CONFIG.TOAST_DURATION) {
        log('Showing toast:', message, type);

        // Remove existing toasts
        document.querySelectorAll('.cravecart-toast').forEach(toast => {
            toast.remove();
        });

        const toastTypes = {
            success: 'bg-success',
            error: 'bg-danger',
            danger: 'bg-danger',
            warning: 'bg-warning',
            info: 'bg-info'
        };

        const bgClass = toastTypes[type] || 'bg-info';

        const toastHtml = `
            <div class="position-fixed bottom-0 end-0 p-3 cravecart-toast" style="z-index: 99999;">
                <div class="toast show align-items-center text-white ${bgClass} border-0" role="alert" style="min-width: 300px;">
                    <div class="d-flex align-items-center">
                        <div class="toast-body">
                            <i class="bi bi-${getToastIcon(type)} me-2"></i>
                            ${message}
                        </div>
                        <button type="button" class="btn-close btn-close-white me-2 ms-auto" onclick="this.closest('.cravecart-toast').remove()"></button>
                    </div>
                </div>
            </div>
        `;

        document.body.insertAdjacentHTML('beforeend', toastHtml);

        // Auto remove
        setTimeout(() => {
            const toastElement = document.querySelector('.cravecart-toast');
            if (toastElement) {
                toastElement.style.opacity = '0';
                setTimeout(() => toastElement.remove(), CONFIG.ANIMATION_DURATION);
            }
        }, duration);
    }

    function getToastIcon(type) {
        const icons = {
            success: 'check-circle-fill',
            error: 'x-circle-fill',
            danger: 'x-circle-fill',
            warning: 'exclamation-triangle-fill',
            info: 'info-circle-fill'
        };
        return icons[type] || 'info-circle-fill';
    }

    // ========================================
    // CART FUNCTIONALITY
    // ========================================
    function updateCartCount(count) {
        log('Updating cart count:', count);

        const cartBadges = document.querySelectorAll('.navbar .badge');
        cartBadges.forEach(badge => {
            if (count > 0) {
                badge.textContent = count;
                badge.style.display = 'inline-block';
            } else {
                badge.style.display = 'none';
            }
        });
    }

    // ========================================
    // ADD TO CART FUNCTIONALITY
    // ========================================
    function initAddToCart() {
        log('Initializing add to cart functionality...');

        const addToCartButtons = document.querySelectorAll('.btn-add-to-cart');
        log(`Found ${addToCartButtons.length} add to cart buttons`);

        addToCartButtons.forEach((button, index) => {
            log(`Setting up button ${index + 1}:`, button);

            // Remove any existing event listeners
            button.removeEventListener('click', handleAddToCart);
            button.addEventListener('click', handleAddToCart);
        });
    }

    function handleAddToCart(e) {
        e.preventDefault();

        const button = this;
        const itemId = button.dataset.id;
        const itemName = button.dataset.name;
        const itemPrice = button.dataset.price;
        const restaurantId = button.dataset.restaurant;

        log('Add to cart clicked:', {
            itemId,
            itemName,
            itemPrice,
            restaurantId,
            button
        });

        // Validation
        if (!itemId) {
            error('Missing item ID');
            showToast('Error: Missing item information', 'error');
            return;
        }

        // Save original state
        const originalText = button.innerHTML;
        const originalDisabled = button.disabled;

        // Update button state
        button.disabled = true;
        button.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span>Adding...';

        // Prepare form data
        const formData = new FormData();
        formData.append('item_id', itemId);
        formData.append('restaurant_id', restaurantId || '');

        // Make request
        fetch(getAjaxUrl('add-to-cart.php'), {
            method: 'POST',
            body: formData,
            credentials: 'same-origin'
        })
        .then(response => {
            log('Response status:', response.status);

            if (!response.ok) {
                throw new Error(`HTTP ${response.status}: ${response.statusText}`);
            }

            return response.text();
        })
        .then(responseText => {
            log('Raw response:', responseText);

            let data;
            try {
                data = JSON.parse(responseText);
            } catch (parseError) {
                error('JSON parse error:', parseError);
                error('Response text:', responseText);
                throw new Error('Invalid response format');
            }

            log('Parsed response:', data);

            if (data.success) {
                showToast(data.message || 'Item added to cart!', 'success');

                // Update cart count
                if (data.cart_count !== undefined) {
                    updateCartCount(data.cart_count);
                }

                // Success animation
                button.innerHTML = '<i class="bi bi-check-circle-fill me-1"></i>Added!';
                button.classList.add('btn-success');
                button.classList.remove('btn-primary');

                // Reset after delay
                setTimeout(() => {
                    button.innerHTML = originalText;
                    button.disabled = originalDisabled;
                    button.classList.remove('btn-success');
                    button.classList.add('btn-primary');
                }, 2000);

            } else {
                throw new Error(data.message || 'Failed to add item to cart');
            }
        })
        .catch(err => {
            error('Add to cart error:', err);
            showToast(err.message || 'Failed to add item to cart', 'error');

            // Reset button
            button.innerHTML = originalText;
            button.disabled = originalDisabled;
        });
    }

    // ========================================
    // CART QUANTITY UPDATES
    // ========================================
    function initCartQuantityControls() {
        log('Initializing cart quantity controls...');

        // Increase quantity
        document.querySelectorAll('.btn-quantity-increase').forEach(button => {
            button.addEventListener('click', function() {
                updateCartQuantity(this.dataset.id, 'increase');
            });
        });

        // Decrease quantity
        document.querySelectorAll('.btn-quantity-decrease').forEach(button => {
            button.addEventListener('click', function() {
                updateCartQuantity(this.dataset.id, 'decrease');
            });
        });
    }

    function updateCartQuantity(itemId, action) {
        log('Updating cart quantity:', itemId, action);

        const quantityDisplay = document.getElementById(`qty-${itemId}`);
        if (quantityDisplay) {
            quantityDisplay.textContent = '...';
        }

        fetch(getAjaxUrl('update-cart.php'), {
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
                // Reload page to update cart display
                location.reload();
            } else {
                showToast(data.message || 'Failed to update quantity', 'error');
                if (quantityDisplay) {
                    quantityDisplay.textContent = '1'; // Reset display
                }
            }
        })
        .catch(error => {
            error('Update quantity error:', error);
            showToast('Failed to update quantity', 'error');
            if (quantityDisplay) {
                quantityDisplay.textContent = '1'; // Reset display
            }
        });
    }

    // ========================================
    // REMOVE FROM CART
    // ========================================
    function initRemoveFromCart() {
        log('Initializing remove from cart...');

        document.querySelectorAll('.btn-remove-item').forEach(button => {
            button.addEventListener('click', function() {
                const itemId = this.dataset.id;
                const itemName = this.dataset.name || 'this item';

                if (confirm(`Remove ${itemName} from cart?`)) {
                    removeFromCart(itemId, this);
                }
            });
        });
    }

    function removeFromCart(itemId, button) {
        log('Removing from cart:', itemId);

        const originalText = button.innerHTML;
        button.innerHTML = '<span class="spinner-border spinner-border-sm"></span>';
        button.disabled = true;

        fetch(getAjaxUrl('remove-from-cart.php'), {
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
                showToast(data.message || 'Item removed from cart', 'success');

                // Remove the cart item row with animation
                const cartItem = button.closest('.cart-item');
                if (cartItem) {
                    cartItem.style.transition = 'opacity 0.3s ease';
                    cartItem.style.opacity = '0';
                    setTimeout(() => cartItem.remove(), 300);
                }

                // Reload page after animation
                setTimeout(() => location.reload(), 500);
            } else {
                showToast(data.message || 'Failed to remove item', 'error');
                button.innerHTML = originalText;
                button.disabled = false;
            }
        })
        .catch(error => {
            error('Remove from cart error:', error);
            showToast('Failed to remove item', 'error');
            button.innerHTML = originalText;
            button.disabled = false;
        });
    }

    // ========================================
    // CHECKOUT FORM HANDLING
    // ========================================
    function initCheckoutForm() {
        log('Initializing checkout form...');

        const checkoutForm = document.getElementById('checkoutForm');
        if (!checkoutForm) return;

        checkoutForm.addEventListener('submit', function(e) {
            e.preventDefault();

            const submitBtn = document.getElementById('placeOrderBtn');
            if (!submitBtn) return;

            // Disable button and show loading
            const originalText = submitBtn.innerHTML;
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Processing Order...';

            // Prepare form data
            const formData = new FormData(checkoutForm);

            // Submit order
            fetch(getAjaxUrl('place-order.php'), {
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
                    showToast('Order placed successfully!', 'success');
                    // Redirect to success page
                    setTimeout(() => {
                        window.location.href = `order-success.php?order=${data.order_id}`;
                    }, 1000);
                } else {
                    throw new Error(data.message || 'Failed to place order');
                }
            })
            .catch(error => {
                error('Checkout error:', error);
                showToast(error.message || 'Failed to place order', 'error');

                // Reset button
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalText;
            });
        });
    }

    // ========================================
    // ORDER DETAILS MODAL
    // ========================================
    function initOrderDetailsModal() {
        log('Initializing order details modal...');

        document.querySelectorAll('.view-order-details').forEach(button => {
            button.addEventListener('click', function() {
                const orderId = this.dataset.orderId;
                const modal = document.getElementById('orderDetailsModal');
                const content = document.getElementById('orderDetailsContent');

                if (!modal || !content) return;

                // Show loading
                content.innerHTML = `
                    <div class="text-center py-5">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                        <p class="mt-3 text-muted">Loading order details...</p>
                    </div>
                `;

                // Show modal
                const bsModal = new bootstrap.Modal(modal);
                bsModal.show();

                // Fetch order details
                fetch(getAjaxUrl(`get-order-details.php?order_id=${orderId}`), {
                    credentials: 'same-origin'
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error(`HTTP ${response.status}`);
                    }
                    return response.text();
                })
                .then(html => {
                    content.innerHTML = html;
                })
                .catch(error => {
                    error('Order details error:', error);
                    content.innerHTML = `
                        <div class="alert alert-danger">
                            <i class="bi bi-exclamation-triangle"></i>
                            Failed to load order details. Please try again.
                        </div>
                    `;
                });
            });
        });
    }

    // ========================================
    // SMOOTH SCROLLING
    // ========================================
    function initSmoothScroll() {
        log('Initializing smooth scroll...');

        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function(e) {
                const href = this.getAttribute('href');
                const target = document.querySelector(href);

                if (href !== '#' && target) {
                    e.preventDefault();
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });
    }

    // ========================================
    // FORM VALIDATION
    // ========================================
    function initFormValidation() {
        log('Initializing form validation...');

        // Password confirmation
        const registerForm = document.getElementById('registerForm');
        if (registerForm) {
            registerForm.addEventListener('submit', function(e) {
                const password = document.getElementById('password');
                const confirmPassword = document.getElementById('confirm_password');

                if (password && confirmPassword) {
                    if (password.value !== confirmPassword.value) {
                        e.preventDefault();
                        showToast('Passwords do not match!', 'error');
                        confirmPassword.focus();
                        return false;
                    }
                }
            });
        }
    }

    // ========================================
    // NAVBAR SCROLL EFFECT
    // ========================================
    function initNavbarScrollEffect() {
        log('Initializing navbar scroll effect...');

        const navbar = document.querySelector('.navbar');
        if (!navbar) return;

        let ticking = false;

        function updateNavbar() {
            if (window.scrollY > 50) {
                navbar.classList.add('scrolled');
                navbar.style.backgroundColor = 'rgba(255, 255, 255, 0.95)';
                navbar.style.backdropFilter = 'blur(10px)';
            } else {
                navbar.classList.remove('scrolled');
                navbar.style.backgroundColor = '';
                navbar.style.backdropFilter = '';
            }
            ticking = false;
        }

        function requestNavbarUpdate() {
            if (!ticking) {
                requestAnimationFrame(updateNavbar);
                ticking = true;
            }
        }

        window.addEventListener('scroll', requestNavbarUpdate);
    }

    // ========================================
    // INITIALIZATION
    // ========================================
    function init() {
        log('Initializing CraveCart JavaScript...');

        // Wait for DOM to be fully loaded
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', init);
            return;
        }

        // Initialize all modules
        try {
            initAddToCart();
            initCartQuantityControls();
            initRemoveFromCart();
            initSmoothScroll();
            initFormValidation();
            initNavbarScrollEffect();
            initCheckoutForm();
            initOrderDetailsModal();

            log('CraveCart JavaScript initialized successfully!');
        } catch (err) {
            error('Initialization error:', err);
        }
    }

    // ========================================
    // BOOTSTRAP INITIALIZATION
    // ========================================
    function initBootstrap() {
        log('Initializing Bootstrap components...');

        // Initialize tooltips
        if (typeof bootstrap !== 'undefined') {
            const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            tooltipTriggerList.map(function(tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });

            // Initialize popovers
            const popoverTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="popover"]'));
            popoverTriggerList.map(function(popoverTriggerEl) {
                return new bootstrap.Popover(popoverTriggerEl);
            });
        }
    }

    // ========================================
    // GLOBAL FUNCTIONS (for debugging)
    // ========================================
    window.CraveCart = {
        showToast,
        updateCartCount,
        log,
        error,
        CONFIG,
        getAjaxUrl
    };

    // ========================================
    // START APPLICATION
    // ========================================
    init();

    // Initialize Bootstrap after DOM is ready
    document.addEventListener('DOMContentLoaded', initBootstrap);

})();