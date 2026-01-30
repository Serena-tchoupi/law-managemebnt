<?php
// modules/notes/add_note.php
// Role: Add a timeline note for a case.

require_once __DIR__ . '/../../auth/check_session.php';
require_login();
require_once __DIR__ . '/../../config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $case_id = (int)($_POST['case_id'] ?? 0);
    $note = trim($_POST['note'] ?? '');
    if ($case_id <= 0 || $note === '') {
        $_SESSION['error'] = 'Case and note required.';
        header('Location: /view_cases.php');
        exit;
    }
    $conn = db_connect();
    $stmt = $conn->prepare("INSERT INTO notes (case_id, user_id, note) VALUES (?, ?, ?)");
    $stmt->bind_param('iis', $case_id, $_SESSION['user_id'], $note);
    $stmt->execute();
    header('Location: /view_cases.php');
    exit;
}
?>