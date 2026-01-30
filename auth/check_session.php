<?php
// auth/check_session.php
// Role: Ensure user is logged in and provide role-based helpers.
// WHY: Centralize access control and keep consistent session handling.

require_once __DIR__ . '/../config.php';

function require_login() {
    if (empty($_SESSION['user_id'])) {
        header('Location: /index.php');
        exit;
    }
}

function current_user() {
    if (empty($_SESSION['user_id'])) return null;
    return [
        'id' => $_SESSION['user_id'],
        'name' => $_SESSION['user_name'] ?? null,
        'email' => $_SESSION['user_email'] ?? null,
        'role' => $_SESSION['user_role'] ?? null,
    ];
}

function require_role($roles = []) {
    $user = current_user();
    if (!$user || !in_array($user['role'], (array)$roles)) {
        http_response_code(403);
        echo 'Access denied.';
        exit;
    }
}

// Backwards compatible: redirect if not logged in
if (basename($_SERVER['PHP_SELF']) !== 'login.php' && basename($_SERVER['PHP_SELF']) !== 'register.php') {
    if (empty($_SESSION['user_id'])) {
        header('Location: /index.php');
        exit;
    }
}
?>
