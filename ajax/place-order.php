<?php
require_once '../config/config.php';

header('Content-Type: application/json');

if (!isLoggedIn()) {
    echo json_encode(['success' => false, 'message' => 'Please login first']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = getUserId();
    $address = sanitize($_POST['address']);
    $phone = sanitize($_POST['phone']);
    $payment_method = sanitize($_POST['payment_method']);

    $cart = $_SESSION['cart'] ?? [];

    if (empty($cart)) {
        echo json_encode(['success' => false, 'message' => 'Cart is empty']);
        exit;
    }

    // Calculate total
    $total = 0;
    foreach ($cart as $item) {
        $total += $item['price'] * $item['quantity'];
    }
    $total += 50; // Delivery fee

    // Start transaction
    $conn->begin_transaction();

    try {
        // Insert order
        $order_query = "INSERT INTO orders (user_id, total_amount, delivery_address, phone, payment_method, status)
                        VALUES (?, ?, ?, ?, ?, 'Pending')";
        $stmt = $conn->prepare($order_query);
        $stmt->bind_param("idsss", $user_id, $total, $address, $phone, $payment_method);
        $stmt->execute();
        $order_id = $stmt->insert_id;

        // Insert order items
        $item_query = "INSERT INTO order_items (order_id, menu_item_id, restaurant_id, quantity, price)
                       VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($item_query);

        foreach ($cart as $item) {
            $stmt->bind_param("iiiid", $order_id, $item['id'], $item['restaurant_id'], $item['quantity'], $item['price']);
            $stmt->execute();
        }

        // Commit transaction
        $conn->commit();

        // Clear cart
        unset($_SESSION['cart']);

        echo json_encode([
            'success' => true,
            'message' => 'Order placed successfully',
            'order_id' => $order_id
        ]);

    } catch (Exception $e) {
        $conn->rollback();
        echo json_encode(['success' => false, 'message' => 'Failed to place order']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request']);
}
?>