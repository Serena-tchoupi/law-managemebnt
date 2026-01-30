<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Database settings - change as needed for local/dev
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'law_management');

define('APP_ENV', 'development'); // change to 'production' in deployment

/**
 * db_connect
 * Returns a mysqli connection (singleton)
 * Uses a static variable so the connection is reused on multiple calls.
 */
function db_connect() {
    static $conn = null;
    if ($conn === null) {
        $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
        if ($conn->connect_error) {
            if (APP_ENV === 'development') {
                die('DB Connect Error: ' . $conn->connect_error);
            } else {
                // In production, avoid exposing raw errors
                error_log('DB Connect Error: ' . $conn->connect_error);
                die('Database connection error.');
            }
        }
        // Ensure UTF-8 for proper encoding
        $conn->set_charset('utf8mb4');
    }
    return $conn;
}

/**
 * e
 * Simple escape helper for output to prevent XSS
 */
function e($str) {
    return htmlspecialchars($str, ENT_QUOTES, 'UTF-8');
}

/**
 * flash_message
 * Simple session-based flash messages for UI feedback
 */
function flash_set($key, $message) {
    $_SESSION['flash'][$key] = $message;
}
function flash_get($key) {
    if (!empty($_SESSION['flash'][$key])) {
        $msg = $_SESSION['flash'][$key];
        unset($_SESSION['flash'][$key]);
        return $msg;
    }
    return null;
}

?>