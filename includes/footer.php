    <style>
        /* FOOTER STYLES */
        .footer {
            background: linear-gradient(135deg, #1a1a2e 0%, #16213e 100%);
            color: white;
            padding: 4rem 0 2rem;
            margin-top: 5rem;
            position: relative;
            overflow: hidden;
        }

        .footer::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><circle cx="50" cy="50" r="1" fill="white" opacity="0.05"/></svg>');
            background-size: 50px 50px;
            pointer-events: none;
        }

        .footer-content {
            position: relative;
            z-index: 2;
        }

        .footer-column {
            margin-bottom: 2rem;
        }

        .footer-title {
            font-size: 1.3rem;
            font-weight: 700;
            margin-bottom: 1.5rem;
            color: white;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .footer-title span {
            font-size: 1.8rem;
        }

        .footer-desc {
            color: rgba(255,255,255,0.7);
            font-size: 0.95rem;
            line-height: 1.6;
            margin-bottom: 1.5rem;
        }

        .footer-links {
            list-style: none;
            padding: 0;
        }

        .footer-links li {
            margin-bottom: 0.8rem;
        }

        .footer-links a {
            color: rgba(255,255,255,0.7);
            text-decoration: none;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .footer-links a:hover {
            color: white;
            transform: translateX(5px);
        }

        .footer-links i {
            width: 18px;
            color: #3858e9;
        }

        .social-links {
            display: flex;
            gap: 1rem;
            margin-top: 1.5rem;
        }

        .social-links a {
            width: 45px;
            height: 45px;
            border-radius: 50%;
            background: rgba(56, 88, 233, 0.2);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.2rem;
            transition: all 0.3s ease;
            border: 1px solid rgba(56, 88, 233, 0.3);
        }

        .social-links a:hover {
            background: #3858e9;
            transform: translateY(-5px);
            border-color: #3858e9;
        }

        .contact-info {
            color: rgba(255,255,255,0.7);
            font-size: 0.95rem;
            margin-bottom: 1rem;
            display: flex;
            align-items: flex-start;
            gap: 0.8rem;
        }

        .contact-info i {
            color: #3858e9;
            margin-top: 0.2rem;
            width: 20px;
        }

        .newsletter-form {
            display: flex;
            gap: 0.5rem;
            margin-top: 1rem;
        }

        .newsletter-form input {
            flex: 1;
            padding: 0.8rem 1rem;
            border: 1px solid rgba(255,255,255,0.2);
            border-radius: 8px;
            background: rgba(255,255,255,0.1);
            color: white;
            outline: none;
            transition: all 0.3s ease;
        }

        .newsletter-form input::placeholder {
            color: rgba(255,255,255,0.5);
        }

        .newsletter-form input:focus {
            border-color: #3858e9;
            background: rgba(255,255,255,0.15);
        }

        .newsletter-form button {
            padding: 0.8rem 1.5rem;
            background: #3858e9;
            color: white;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .newsletter-form button:hover {
            background: #2642c7;
            transform: translateY(-2px);
        }

        .footer-divider {
            background: rgba(255,255,255,0.1);
            height: 1px;
            margin: 3rem 0;
        }

        .footer-bottom {
            display: grid;
            grid-template-columns: 1fr auto 1fr;
            gap: 2rem;
            align-items: center;
            padding-top: 2rem;
            border-top: 1px solid rgba(255,255,255,0.1);
        }

        .footer-credit {
            color: rgba(255,255,255,0.6);
            font-size: 0.9rem;
        }

        .footer-credit strong {
            color: #3858e9;
        }

        .payment-methods {
            display: flex;
            gap: 1rem;
            flex-wrap: wrap;
            justify-content: center;
        }

        .payment-icon {
            width: 45px;
            height: 30px;
            background: rgba(255,255,255,0.1);
            border-radius: 6px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.2rem;
            border: 1px solid rgba(255,255,255,0.2);
        }

        /* RESPONSIVE */
        @media (max-width: 768px) {
            .footer {
                padding: 2rem 0 1rem;
            }

            .footer-bottom {
                grid-template-columns: 1fr;
                gap: 1rem;
            }

            .payment-methods {
                justify-content: flex-start;
            }

            .footer-title {
                font-size: 1.1rem;
            }

            .newsletter-form {
                flex-direction: column;
            }
        }
    </style>

    <!-- FOOTER -->
    <footer class="footer">
        <div class="container footer-content">
            <!-- Footer Content Row -->
            <div class="row">
                <!-- About Column -->
                <div class="col-lg-3 col-md-6 footer-column">
                    <div class="footer-title">
                        <span>🍕</span> CraveCart
                    </div>
                    <p class="footer-desc">
                        Your trusted platform for delicious food delivery. Order from top restaurants and enjoy meals delivered to your doorstep in minutes.
                    </p>

                    <!-- Social Links -->
                    <div class="social-links">
                        <a href="https://facebook.com/cravecart" target="_blank" title="Facebook">
                            <i class="bi bi-facebook"></i>
                        </a>
                        <a href="https://instagram.com/cravecart" target="_blank" title="Instagram">
                            <i class="bi bi-instagram"></i>
                        </a>
                        <a href="https://twitter.com/cravecart" target="_blank" title="Twitter">
                            <i class="bi bi-twitter"></i>
                        </a>
                        <a href="https://wa.me/923001234567" target="_blank" title="WhatsApp">
                            <i class="bi bi-whatsapp"></i>
                        </a>
                    </div>
                </div>

                <!-- Quick Links Column -->
                <div class="col-lg-3 col-md-6 footer-column">
                    <div class="footer-title">
                        <i class="bi bi-link-45deg"></i> Quick Links
                    </div>
                    <ul class="footer-links">
                        <li><a href="home.php">Home</a></li>
                        <li><a href="home.php#restaurants">Browse Restaurants</a></li>
                        <li><a href="home.php#features">About Us</a></li>
                        <li><a href="home.php#faq">Help & FAQ</a></li>
                        <li><a href="#">Careers</a></li>
                    </ul>
                </div>

                <!-- Support Column -->
                <div class="col-lg-3 col-md-6 footer-column">
                    <div class="footer-title">
                        <i class="bi bi-headset"></i> Support
                    </div>
                    <ul class="footer-links">
                        <li><a href="#">Contact Us</a></li>
                        <li><a href="#">Terms of Service</a></li>
                        <li><a href="#">Privacy Policy</a></li>
                        <li><a href="#">Refund Policy</a></li>
                        <li><a href="#">Report Issue</a></li>
                    </ul>
                </div>

                <!-- Contact Column -->
                <div class="col-lg-3 col-md-6 footer-column">
                    <div class="footer-title">
                        <i class="bi bi-telephone"></i> Contact
                    </div>
                    <div class="contact-info">
                        <i class="bi bi-telephone"></i>
                        <div>
                            <div>+91 88820 82994</div>
                            <div>+91 88820 82995</div>
                        </div>
                    </div>
                    <div class="contact-info">
                        <i class="bi bi-envelope"></i>
                        <div>
                            <a href="mailto:info@cravecart.com" style="color: inherit;">info@cravecart.com</a>
                        </div>
                    </div>
                    <div class="contact-info">
                        <i class="bi bi-geo-alt"></i>
                        <div>
                            <div>Sonipat Haryana</div>
                            <div>24/7 Open</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Newsletter Section -->
            <div class="footer-divider"></div>
            <div class="row mt-4">
                <div class="col-lg-6">
                    <h6 style="color: white; font-weight: 600; margin-bottom: 1rem;">
                        <i class="bi bi-bell me-2"></i>Subscribe to Our Newsletter
                    </h6>
                    <p class="text-muted" style="font-size: 0.9rem; margin-bottom: 1rem;">
                        Get exclusive deals, new restaurant alerts, and special offers directly to your inbox.
                    </p>
                    <form class="newsletter-form" id="newsletterForm">
                        <input type="email" placeholder="Enter your email" required>
                        <button type="submit">Subscribe</button>
                    </form>
                </div>
                <div class="col-lg-6">
                    <h6 style="color: white; font-weight: 600; margin-bottom: 1rem;">
                        <i class="bi bi-credit-card me-2"></i>We Accept
                    </h6>
                    <div class="payment-methods">
                        <div class="payment-icon" title="Cash on Delivery">💵</div>
                        <div class="payment-icon" title="Visa/Mastercard">💳</div>
                        <div class="payment-icon" title="JazzCash">📱</div>
                        <div class="payment-icon" title="Easypaisa">📱</div>
                        <div class="payment-icon" title="HBL">🏦</div>
                    </div>
                </div>
            </div>

            <!-- Footer Bottom -->
            <div class="footer-bottom">
                <div class="footer-credit">
                    &copy; 2026 <strong>CraveCart</strong> - All Rights Reserved
                </div>
                <div style="text-align: center;">
                    <span style="color: rgba(255,255,255,0.6);">Made with ❤️ by</span><br>
                    <strong style="color: #3858e9;">Neha Qazmi</strong>
                </div>
                <div class="text-end text-muted" style="font-size: 0.85rem;">
                    Version 1.0.0<br>
                    <span id="currentYear"></span>
                </div>
            </div>
        </div>
    </footer>

    <!-- SCRIPTS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="<?php echo isset($base_path) ? $base_path : ''; ?>/assets/js/main.js"></script>

    <script>
        // Current Year
        document.getElementById('currentYear').textContent = new Date().getFullYear();

        // Newsletter Form
        document.getElementById('newsletterForm').addEventListener('submit', function(e) {
            e.preventDefault();
            const email = this.querySelector('input[type="email"]').value;

            // Show success message
            alert(`Thank you for subscribing with ${email}! Check your inbox for exclusive offers. 🎉`);
            this.reset();
        });

        // Update cart display on cart change
        function updateNavbarCart() {
            const cartLink = document.querySelector('.cart-icon');
            if (cartLink) {
                // This will be updated via AJAX
                const badge = cartLink.querySelector('.cart-badge');
                if (badge && parseInt(badge.textContent) === 0) {
                    badge.remove();
                }
            }
        }

        // Listen for cart updates
        window.addEventListener('cartUpdated', updateNavbarCart);
    </script>

</body>
</html>