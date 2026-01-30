<?php
// modules/documents/view_documents.php
// Role: List uploaded documents

require_once __DIR__ . '/../../auth/check_session.php';
require_login();
require_once __DIR__ . '/../../config.php';

$conn = db_connect();
$res = $conn->query("SELECT d.*, c.name as client_name, ca.title as case_title, u.full_name as uploader FROM documents d LEFT JOIN clients c ON d.client_id = c.id LEFT JOIN cases ca ON d.case_id = ca.id LEFT JOIN users u ON d.uploaded_by = u.user_id ORDER BY d.uploaded_at DESC");
?>
<?php include __DIR__ . '/../../includes/header.php'; ?>
<?php include __DIR__ . '/../../includes/nav.php'; ?>
<main class="container">
    <h2>Documents</h2>
    <a href="/modules/documents/upload.php">Upload new</a>
    <table>
        <tr><th>File</th><th>Case</th><th>Client</th><th>Uploaded by</th><th>Date</th><th>Action</th></tr>
        <?php while ($r = $res->fetch_assoc()): ?>
            <tr>
                <td><?php echo e($r['filename']); ?></td>
                <td><?php echo e($r['case_title']); ?></td>
                <td><?php echo e($r['client_name']); ?></td>
                <td><?php echo e($r['uploader']); ?></td>
                <td><?php echo e($r['uploaded_at']); ?></td>
                <td><a href="/<?php echo e($r['filepath']); ?>" download>Download</a></td>
            </tr>
        <?php endwhile; ?>
    </table>
</main>
<?php include __DIR__ . '/../../includes/footer.php'; ?>