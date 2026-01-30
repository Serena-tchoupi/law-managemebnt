<?php
// auth/login.php
// Role: Authenticate user using email and password (secure, prepared statements)
require_once __DIR__ . '/../config.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../index.php');
    exit;
}

$email = trim($_POST['email'] ?? '');
$password = $_POST['password'] ?? '';

if ($email === '' || $password === '') {
    $_SESSION['error'] = 'Please provide email and password.';
    header('Location: ../index.php');
    exit;
}

$conn = db_connect();
$stmt = $conn->prepare("SELECT user_id AS id, full_name AS name, email, password, role FROM users WHERE email = ? LIMIT 1");
$stmt->bind_param('s', $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result && $user = $result->fetch_assoc()) {
    if (password_verify($password, $user['password'])) {
        // set session variables
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_name'] = $user['name'];
        $_SESSION['user_email'] = $user['email'];
        $_SESSION['user_role'] = $user['role'];
        header('Location: ../dashboard.php');
        exit;
    }
}

// Authentication failed
$_SESSION['error'] = 'Invalid credentials.';
header('Location: ../index.php');
exit;
?>
