<?php
require_once '../config/config.php';

header('Content-Type: application/json');

if (!isLoggedIn()) {
    echo json_encode(['success' => false, 'message' => 'Please login first']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $item_id = (int)$_POST['item_id'];
    $action = $_POST['action']; // 'increase' or 'decrease'

    if (!isset($_SESSION['cart'][$item_id])) {
        echo json_encode(['success' => false, 'message' => 'Item not in cart']);
        exit;
    }

    if ($action === 'increase') {
        $_SESSION['cart'][$item_id]['quantity']++;
    } elseif ($action === 'decrease') {
        if ($_SESSION['cart'][$item_id]['quantity'] > 1) {
            $_SESSION['cart'][$item_id]['quantity']--;
        } else {
            unset($_SESSION['cart'][$item_id]);
        }
    }

    echo json_encode(['success' => true, 'message' => 'Cart updated']);
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request']);
}
?>