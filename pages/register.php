<?php
$pageTitle = "Register - CraveCart";
require_once '../config/config.php';
require_once '../includes/header.php';
require_once '../includes/navbar.php';

// Redirect if already logged in
if (isLoggedIn()) {
    redirect('dashboard.php');
}
?>

<div class="auth-container">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="auth-card">
                    <h2 class="fw-bold">Create Account</h2>
                    <p class="text-muted">Join CraveCart and start ordering today</p>

                    <form id="registerForm" method="POST" action="../auth/register-process.php">
                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <label for="name" class="form-label">Full Name</label>
                                <input type="text" class="form-control" id="name" name="name" required>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="email" class="form-label">Email Address</label>
                                <input type="email" class="form-control" id="email" name="email" required>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="phone" class="form-label">Phone Number</label>
                                <input type="tel" class="form-control" id="phone" name="phone" required>
                            </div>

                            <div class="col-md-12 mb-3">
                                <label for="address" class="form-label">Delivery Address</label>
                                <textarea class="form-control" id="address" name="address" rows="2" required></textarea>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="password" class="form-label">Password</label>
                                <input type="password" class="form-control" id="password" name="password" required minlength="6">
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="confirm_password" class="form-label">Confirm Password</label>
                                <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary w-100 mb-3">
                            Create Account
                        </button>

                        <p class="text-center mb-0">
                            Already have an account?
                            <a href="login.php" class="text-primary fw-bold">Login</a>
                        </p>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once '../includes/footer.php'; ?>