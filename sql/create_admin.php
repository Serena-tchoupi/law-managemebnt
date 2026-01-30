<?php
// sql/create_admin.php
// Utility: create a default admin user for development/testing.
// USAGE: Run via CLI: php create_admin.php or access via browser (not recommended in production).
require_once __DIR__ . '/../config.php';

$name = 'Administrator';
$email = 'admin@example.com';
$password = 'admin123'; // change immediately
$role = 'admin';

$conn = db_connect();
$hash = password_hash($password, PASSWORD_DEFAULT);
$stmt = $conn->prepare("INSERT INTO users (full_name, email, password, role) VALUES (?, ?, ?, ?)");
$stmt->bind_param('ssss', $name, $email, $hash, $role);
if ($stmt->execute()) {
    echo "Admin user created: {$email} / {$password}\n";
} else {
    echo "Failed to create admin: " . $conn->error . "\n";
}

?>