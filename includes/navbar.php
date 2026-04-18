    <!-- NAVBAR -->
    <nav class="navbar navbar-expand-lg navbar-light bg-white sticky-top">
        <div class="container">
            <!-- Brand -->
            <a class="navbar-brand" href="home.php">
                <span>🍕</span> CraveCart
            </a>

            <!-- Toggler Button -->
            <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>

            <!-- Navbar Content -->
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto align-items-center">
                    <!-- Home Link -->
                    <li class="nav-item">
                        <a class="nav-link" href="home.php">
                            <i class="bi bi-house-door me-1"></i> Home
                        </a>
                    </li>

                    <!-- Restaurants Link -->
                    <li class="nav-item">
                        <a class="nav-link" href="home.php#restaurants">
                            <i class="bi bi-shop me-1"></i> Restaurants
                        </a>
                    </li>

                    <!-- About Link -->
                    <li class="nav-item">
                        <a class="nav-link" href="home.php#features">
                            <i class="bi bi-info-circle me-1"></i> About
                        </a>
                    </li>

                    <!-- Contact Link -->
                    <li class="nav-item">
                        <a class="nav-link" href="home.php#faq">
                            <i class="bi bi-question-circle me-1"></i> Help
                        </a>
                    </li>

                    <!-- User Menu (Logged In) -->
                    <?php if (isLoggedIn()):
                        $cart_count = isset($_SESSION['cart']) && is_array($_SESSION['cart']) ? count($_SESSION['cart']) : 0;
                    ?>

                    <!-- Cart Icon -->
                    <li class="nav-item ms-3">
                        <a class="nav-link" href="cart.php" title="Shopping Cart">
                            <div class="cart-icon">
                                <i class="bi bi-cart3"></i>
                                <?php if ($cart_count > 0): ?>
                                <span class="cart-badge"><?php echo $cart_count; ?></span>
                                <?php endif; ?>
                            </div>
                        </a>
                    </li>

                    <!-- User Dropdown Menu -->
                    <li class="nav-item dropdown ms-3">
                        <div class="user-avatar" id="userDropdown" role="button" data-bs-toggle="dropdown">
                            <?php echo strtoupper(substr(getUserName(), 0, 1)); ?>
                        </div>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li>
                                <span class="dropdown-header">
                                    <strong><?php echo htmlspecialchars(getUserName()); ?></strong>
                                </span>
                            </li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <a class="dropdown-item" href="dashboard.php">
                                    <i class="bi bi-speedometer2"></i> Dashboard
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="cart.php">
                                    <i class="bi bi-cart3"></i> My Cart
                                    <?php if ($cart_count > 0): ?>
                                    <span class="badge bg-danger ms-2"><?php echo $cart_count; ?></span>
                                    <?php endif; ?>
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="dashboard.php#orders">
                                    <i class="bi bi-bag-check"></i> My Orders
                                </a>
                            </li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <a class="dropdown-item" href="../auth/logout.php">
                                    <i class="bi bi-box-arrow-right text-danger"></i> Logout
                                </a>
                            </li>
                        </ul>
                    </li>

                    <!-- Guest User Menu -->
                    <?php else: ?>

                    <!-- Login Link -->
                    <li class="nav-item ms-3">
                        <a class="nav-link fw-bold" href="login.php">
                            <i class="bi bi-box-arrow-in-right me-1"></i> Login
                        </a>
                    </li>

                    <!-- Sign Up Button -->
                    <li class="nav-item">
                        <a class="btn-signup" href="register.php">
                            <i class="bi bi-person-plus me-1"></i> Sign Up
                        </a>
                    </li>

                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>

    <script>
        // Navbar scroll effect
        window.addEventListener('scroll', function() {
            const navbar = document.querySelector('.navbar');
            if (window.scrollY > 50) {
                navbar.classList.add('scrolled');
            } else {
                navbar.classList.remove('scrolled');
            }
        });

        // Update cart count on page load
        function updateCartDisplay() {
            const cartBadge = document.querySelector('.cart-badge');
            const cartCount = <?php echo $cart_count ?? 0; ?>;

            if (cartCount > 0 && !cartBadge) {
                const cartIcon = document.querySelector('.cart-icon');
                const badge = document.createElement('span');
                badge.className = 'cart-badge';
                badge.textContent = cartCount;
                cartIcon.appendChild(badge);
            }
        }

        // Call on load
        document.addEventListener('DOMContentLoaded', updateCartDisplay);
    </script>