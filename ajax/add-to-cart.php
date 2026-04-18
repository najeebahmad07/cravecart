<?php
// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Start output buffering to catch any errors
ob_start();

try {
    require_once '../config/config.php';

    header('Content-Type: application/json');

    // Debug info
    error_log('Add to cart request: ' . print_r($_POST, true));

    if (!isLoggedIn()) {
        echo json_encode([
            'success' => false,
            'message' => 'Please login first',
            'debug' => 'User not logged in'
        ]);
        exit;
    }

    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        echo json_encode([
            'success' => false,
            'message' => 'Invalid request method',
            'debug' => 'Method: ' . $_SERVER['REQUEST_METHOD']
        ]);
        exit;
    }

    if (empty($_POST['item_id'])) {
        echo json_encode([
            'success' => false,
            'message' => 'Missing item ID',
            'debug' => 'POST data: ' . print_r($_POST, true)
        ]);
        exit;
    }

    $item_id = (int)$_POST['item_id'];
    $restaurant_id = (int)$_POST['restaurant_id'];

    // Fetch item details
    $query = "SELECT * FROM menu_items WHERE id = ? AND is_available = 1";
    $stmt = $conn->prepare($query);

    if (!$stmt) {
        echo json_encode([
            'success' => false,
            'message' => 'Database error',
            'debug' => 'Prepare failed: ' . $conn->error
        ]);
        exit;
    }

    $stmt->bind_param("i", $item_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $item = $result->fetch_assoc();

    if (!$item) {
        echo json_encode([
            'success' => false,
            'message' => 'Item not found',
            'debug' => 'Item ID: ' . $item_id
        ]);
        exit;
    }

    // Initialize cart if not exists
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }

    // Check if item already in cart
    if (isset($_SESSION['cart'][$item_id])) {
        $_SESSION['cart'][$item_id]['quantity']++;
    } else {
        $_SESSION['cart'][$item_id] = [
            'id' => $item_id,
            'name' => $item['name'],
            'price' => $item['price'],
            'image' => $item['image'],
            'restaurant_id' => $restaurant_id,
            'quantity' => 1
        ];
    }

    // Clean any output that might have been generated
    ob_clean();

    echo json_encode([
        'success' => true,
        'message' => 'Item added to cart successfully!',
        'cart_count' => count($_SESSION['cart']),
        'debug' => 'Cart updated for item: ' . $item['name']
    ]);

} catch (Exception $e) {
    // Clean any output
    ob_clean();

    echo json_encode([
        'success' => false,
        'message' => 'Server error occurred',
        'debug' => $e->getMessage()
    ]);
}

ob_end_flush();
?>