<?php
session_start();

// Check admin authentication
if (!isset($_SESSION['admin_id'])) {
    header("Location: index.php");
    exit;
}

require_once '../config/database.php';

define('SITE_URL', 'http://localhost/cravecart');
define('BASE_PATH', dirname(dirname(__DIR__)));
define('UPLOAD_PATH', BASE_PATH . '/uploads/');
define('RESTAURANT_UPLOAD_PATH', UPLOAD_PATH . 'restaurants/');
define('MENU_UPLOAD_PATH', UPLOAD_PATH . 'menu/');

$db = new Database();
$conn = $db->getConnection();

// Helper function for image upload
function uploadImage($file, $path) {
    $allowed = ['jpg', 'jpeg', 'png', 'webp', 'avif'];
    $filename = $file['name'];
    $fileTmp = $file['tmp_name'];
    $fileSize = $file['size'];
    $fileError = $file['error'];

    $fileExt = strtolower(pathinfo($filename, PATHINFO_EXTENSION));

    if (!in_array($fileExt, $allowed)) {
        return ['success' => false, 'message' => 'Invalid file type'];
    }

    if ($fileError !== 0) {
        return ['success' => false, 'message' => 'Error uploading file'];
    }

    if ($fileSize > 5000000) {
        return ['success' => false, 'message' => 'File too large'];
    }

    $newFilename = uniqid('', true) . '.' . $fileExt;
    $destination = $path . $newFilename;

    if (move_uploaded_file($fileTmp, $destination)) {
        return ['success' => true, 'filename' => $newFilename];
    }

    return ['success' => false, 'message' => 'Failed to upload file'];
}

// Get current page
$current_page = basename($_SERVER['PHP_SELF']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - CraveCart</title>

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Admin CSS -->
    <link rel="stylesheet" href="<?php echo SITE_URL; ?>/assets/css/admin.css">
</head>
<body>

<!-- Sidebar -->
<div class="admin-sidebar">
    <div class="brand">
        <h4>🍕 CraveCart</h4>
        <p class="text-white-50 small mb-0">Admin Panel</p>
    </div>

    <ul class="sidebar-menu">
        <li>
            <a href="dashboard.php" class="<?php echo $current_page === 'dashboard.php' ? 'active' : ''; ?>">
                <i class="bi bi-speedometer2"></i>
                <span>Dashboard</span>
            </a>
        </li>
        <li>
            <a href="restaurants.php" class="<?php echo $current_page === 'restaurants.php' ? 'active' : ''; ?>">
                <i class="bi bi-shop"></i>
                <span>Restaurants</span>
            </a>
        </li>
        <li>
            <a href="menu-items.php" class="<?php echo $current_page === 'menu-items.php' ? 'active' : ''; ?>">
                <i class="bi bi-basket"></i>
                <span>Menu Items</span>
            </a>
        </li>
        <li>
            <a href="orders.php" class="<?php echo $current_page === 'orders.php' ? 'active' : ''; ?>">
                <i class="bi bi-bag-check"></i>
                <span>Orders</span>
            </a>
        </li>
        <li>
            <a href="users.php" class="<?php echo $current_page === 'users.php' ? 'active' : ''; ?>">
                <i class="bi bi-people"></i>
                <span>Users</span>
            </a>
        </li>
        <li>
            <a href="<?php echo SITE_URL; ?>/home" target="_blank">
                <i class="bi bi-globe"></i>
                <span>View Website</span>
            </a>
        </li>
        <li>
            <a href="logout.php">
                <i class="bi bi-box-arrow-right"></i>
                <span>Logout</span>
            </a>
        </li>
    </ul>
</div>

<!-- Main Content -->
<div class="admin-main">
    <!-- Topbar -->
    <div class="admin-topbar">
        <h5 class="mb-0 fw-bold">Welcome, <?php echo htmlspecialchars($_SESSION['admin_name']); ?></h5>
        <div>
            <span class="text-muted me-3"><?php echo date('l, F j, Y'); ?></span>
            <a href="logout.php" class="btn btn-sm btn-outline-danger">
                <i class="bi bi-box-arrow-right"></i> Logout
            </a>
        </div>
    </div>