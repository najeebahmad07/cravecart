<?php
$pageTitle = "Login - CraveCart";
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
            <div class="col-md-5">
                <div class="auth-card">
                    <h2 class="fw-bold">Welcome Back!</h2>
                    <p class="text-muted">Login to continue ordering delicious food</p>

                    <form id="loginForm" method="POST" action="../auth/login-process.php">
                        <div class="mb-3">
                            <label for="email" class="form-label">Email Address</label>
                            <input type="email" class="form-control" id="email" name="email" required>
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label">Password</label>
                            <input type="password" class="form-control" id="password" name="password" required>
                        </div>

                        <button type="submit" class="btn btn-primary w-100 mb-3">
                            Login
                        </button>

                        <p class="text-center mb-0">
                            Don't have an account?
                            <a href="register.php" class="text-primary fw-bold">Sign Up</a>
                        </p>
                    </form>

                    <div class="mt-4 p-3 bg-light rounded">
                        <p class="mb-1 small fw-bold">Demo Credentials:</p>
                        <p class="mb-0 small">Email: neha@example.com</p>
                        <p class="mb-0 small">Password: password</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once '../includes/footer.php'; ?>