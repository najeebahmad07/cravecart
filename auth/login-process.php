<?php
require_once '../config/config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = sanitize($_POST['email']);
    $password = $_POST['password'];

    // Validate input
    if (empty($email) || empty($password)) {
        setToast('Please fill in all fields', 'danger');
        header("Location: ../pages/login.php");
        exit();
    }

    // Check user credentials
    $query = "SELECT * FROM users WHERE email = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();

        // Verify password
        if (password_verify($password, $user['password'])) {
            // Set session
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['name'];
            $_SESSION['user_email'] = $user['email'];

            setToast('Welcome back, ' . $user['name'] . '!', 'success');
            header("Location: ../pages/dashboard.php");
            exit();
        } else {
            setToast('Invalid email or password', 'danger');
            header("Location: ../pages/login.php");
            exit();
        }
    } else {
        setToast('Invalid email or password', 'danger');
        header("Location: ../pages/login.php");
        exit();
    }
} else {
    header("Location: ../pages/login.php");
    exit();
}
?>