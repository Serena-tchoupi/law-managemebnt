<?php
// delete_client.php
// Role: Delete a client safely using prepared statements

require_once __DIR__ . '/auth/check_session.php';
require_login();
require_once __DIR__ . '/config.php';

$id = (int)($_GET['id'] ?? 0);
if ($id <= 0) {
    header('Location: view_clients.php');
    exit;
}

$conn = db_connect();
$stmt = $conn->prepare("DELETE FROM clients WHERE id = ?");
$stmt->bind_param('i', $id);
$stmt->execute();

header('Location: view_clients.php');
exit();
?>
