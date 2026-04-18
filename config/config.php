<?php
session_start();

// Site Configuration
define('SITE_NAME', 'CraveCart');
define('SITE_URL', 'http://localhost/cravecart');
define('ADMIN_EMAIL', 'admin@cravecart.com');

// Path Configuration
define('BASE_PATH', dirname(__DIR__));
define('UPLOAD_PATH', BASE_PATH . '/uploads/');
define('RESTAURANT_UPLOAD_PATH', UPLOAD_PATH . 'restaurants/');
define('MENU_UPLOAD_PATH', UPLOAD_PATH . 'menu/');

// Database
require_once BASE_PATH . '/config/database.php';
$db = new Database();
$conn = $db->getConnection();

// Helper Functions
function redirect($url) {
    // Handle different URL formats
    if (strpos($url, 'http') === 0) {
        // Full URL
        header("Location: " . $url);
    } elseif (strpos($url, '.php') !== false) {
        // Direct PHP file
        header("Location: " . $url);
    } else {
        // Convert old format to new format
        $url = ltrim($url, '/');
        if ($url === 'login') {
            header("Location: ../pages/login.php");
        } elseif ($url === 'register') {
            header("Location: ../pages/register.php");
        } elseif ($url === 'dashboard') {
            header("Location: ../pages/dashboard.php");
        } elseif ($url === 'cart') {
            header("Location: ../pages/cart.php");
        } elseif ($url === 'home') {
            header("Location: ../pages/home.php");
        } else {
            header("Location: ../pages/" . $url . ".php");
        }
    }
    exit();
}

function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

function isAdmin() {
    return isset($_SESSION['admin_id']);
}

function getUserId() {
    return $_SESSION['user_id'] ?? null;
}

function getUserName() {
    return $_SESSION['user_name'] ?? 'Guest';
}

function formatPrice($price) {
    return 'Rs. ' . number_format($price, 2);
}

function timeAgo($datetime) {
    $timestamp = strtotime($datetime);
    $difference = time() - $timestamp;

    if ($difference < 60) {
        return 'Just now';
    } elseif ($difference < 3600) {
        return floor($difference / 60) . ' min ago';
    } elseif ($difference < 86400) {
        return floor($difference / 3600) . ' hours ago';
    } elseif ($difference < 604800) {
        return floor($difference / 86400) . ' days ago';
    } else {
        return date('M d, Y', $timestamp);
    }
}

function sanitize($data) {
    return htmlspecialchars(strip_tags(trim($data)));
}

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

    if ($fileSize > 5000000) { // 5MB
        return ['success' => false, 'message' => 'File too large'];
    }

    $newFilename = uniqid('', true) . '.' . $fileExt;
    $destination = $path . $newFilename;

    if (move_uploaded_file($fileTmp, $destination)) {
        return ['success' => true, 'filename' => $newFilename];
    }

    return ['success' => false, 'message' => 'Failed to upload file'];
}

// Toast Notification Function
function setToast($message, $type = 'success') {
    $_SESSION['toast'] = [
        'message' => $message,
        'type' => $type
    ];
}

function getToast() {
    if (isset($_SESSION['toast'])) {
        $toast = $_SESSION['toast'];
        unset($_SESSION['toast']);
        return $toast;
    }
    return null;
}
?>