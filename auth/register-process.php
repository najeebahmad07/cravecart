<?php
require_once '../config/config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = sanitize($_POST['name']);
    $email = sanitize($_POST['email']);
    $phone = sanitize($_POST['phone']);
    $address = sanitize($_POST['address']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // Validate input
    if (empty($name) || empty($email) || empty($phone) || empty($address) || empty($password)) {
        setToast('Please fill in all fields', 'danger');
        header("Location: ../pages/register.php");
        exit();
    }

    // Validate email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        setToast('Invalid email address', 'danger');
        header("Location: ../pages/register.php");
        exit();
    }

    // Validate password
    if (strlen($password) < 6) {
        setToast('Password must be at least 6 characters', 'danger');
        header("Location: ../pages/register.php");
        exit();
    }

    if ($password !== $confirm_password) {
        setToast('Passwords do not match', 'danger');
        header("Location: ../pages/register.php");
        exit();
    }

    // Check if email already exists
    $check_query = "SELECT id FROM users WHERE email = ?";
    $stmt = $conn->prepare($check_query);
    $stmt->bind_param("s", $email);
    $stmt->execute();

    if ($stmt->get_result()->num_rows > 0) {
        setToast('Email already registered', 'danger');
        header("Location: ../pages/register.php");
        exit();
    }

    // Hash password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Insert user
    $insert_query = "INSERT INTO users (name, email, phone, address, password) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($insert_query);
    $stmt->bind_param("sssss", $name, $email, $phone, $address, $hashed_password);

    if ($stmt->execute()) {
        $user_id = $stmt->insert_id;

        // Set session
        $_SESSION['user_id'] = $user_id;
        $_SESSION['user_name'] = $name;
        $_SESSION['user_email'] = $email;

        setToast('Account created successfully! Welcome to CraveCart', 'success');
        header("Location: ../pages/dashboard.php");
        exit();
    } else {
        setToast('Registration failed. Please try again.', 'danger');
        header("Location: ../pages/register.php");
        exit();
    }
} else {
    header("Location: ../pages/register.php");
    exit();
}
?>