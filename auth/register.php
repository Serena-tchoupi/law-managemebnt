<?php
// auth/register.php
// Role: Simple user registration (uses prepared statements and password hashing)
// NOTE: For real systems, restrict who can create certain roles (e.g., admin only).

require_once __DIR__ . '/../config.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../index.php');
    exit;
}

$name = trim($_POST['name'] ?? '');
$email = trim($_POST['email'] ?? '');
$password_raw = $_POST['password'] ?? '';
$requested_role = trim($_POST['role'] ?? 'lawyer');

// Allowed self-register roles
$open_roles = ['lawyer', 'clerk'];

// If an admin is creating a user (already logged in with admin role), allow admin creation
$allowed_roles = $open_roles;
if (!empty($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin') {
    $allowed_roles[] = 'admin';
}

// Ensure requested role is allowed
if (!in_array($requested_role, $allowed_roles, true)) {
    $requested_role = 'lawyer';
}

if ($name === '' || $email === '' || $password_raw === '') {
    $_SESSION['error'] = 'All fields are required.';
    header('Location: ../index.php');
    exit;
}

$conn = db_connect();
// Check existing
$stmt = $conn->prepare("SELECT user_id FROM users WHERE email = ? LIMIT 1");
$stmt->bind_param('s', $email);
$stmt->execute();
if ($stmt->get_result()->num_rows > 0) {
    $_SESSION['error'] = 'Email already exists.';
    header('Location: ../index.php');
    exit;
}

$pass_hash = password_hash($password_raw, PASSWORD_DEFAULT);
$stmt = $conn->prepare("INSERT INTO users (full_name, email, password, role) VALUES (?, ?, ?, ?)");
$stmt->bind_param('ssss', $name, $email, $pass_hash, $requested_role);
if ($stmt->execute()) {
    $_SESSION['success'] = 'Registration successful. Please login.';
    header('Location: ../index.php');
    exit;
} 

$_SESSION['error'] = 'Registration failed.';
header('Location: ../index.php');
exit;
?>
