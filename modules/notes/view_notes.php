<?php
// modules/notes/view_notes.php
// Role: View notes for a given case (case_id via GET)

require_once __DIR__ . '/../../auth/check_session.php';
require_login();
require_once __DIR__ . '/../../config.php';

$case_id = (int)($_GET['case_id'] ?? 0);
if ($case_id <= 0) {
    echo "Invalid case id.";
    exit;
}

$conn = db_connect();
$stmt = $conn->prepare("SELECT n.*, u.full_name as author FROM notes n LEFT JOIN users u ON n.user_id = u.user_id WHERE n.case_id = ? ORDER BY n.created_at DESC");
$stmt->bind_param('i', $case_id);
$stmt->execute();
$notes = $stmt->get_result();
?>
<?php include __DIR__ . '/../../includes/header.php'; ?>
<?php include __DIR__ . '/../../includes/nav.php'; ?>
<main class="container">
    <h2>Notes for Case #<?php echo e($case_id); ?></h2>
    <a href="/view_cases.php">← Back to Cases</a>
    <ul>
        <?php while ($n = $notes->fetch_assoc()): ?>
            <li><strong><?php echo e($n['author']); ?></strong> (<?php echo e($n['created_at']); ?>): <?php echo e($n['note']); ?></li>
        <?php endwhile; ?>
    </ul>
</main>
<?php include __DIR__ . '/../../includes/footer.php'; ?>