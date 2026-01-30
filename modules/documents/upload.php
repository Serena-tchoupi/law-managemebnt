<?php
// modules/documents/upload.php
// Role: Upload file and link to case/client.

require_once __DIR__ . '/../../auth/check_session.php';
require_login();
require_once __DIR__ . '/../../config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_FILES['file']['name'])) {
    $case_id = (int)($_POST['case_id'] ?? 0) ?: null;
    $client_id = (int)($_POST['client_id'] ?? 0) ?: null;

    $file = $_FILES['file'];
    if ($file['error'] !== UPLOAD_ERR_OK) {
        $_SESSION['error'] = 'Upload failed.';
    } else {
        $uploadsDir = __DIR__ . '/../../uploads/';
        if (!is_dir($uploadsDir)) mkdir($uploadsDir, 0755, true);
        $filename = time() . '_' . basename($file['name']);
        $target = $uploadsDir . $filename;
        if (move_uploaded_file($file['tmp_name'], $target)) {
            $conn = db_connect();
            $stmt = $conn->prepare("INSERT INTO documents (case_id, client_id, filename, filepath, uploaded_by) VALUES (?, ?, ?, ?, ?)");
            $path = 'uploads/' . $filename;
            $stmt->bind_param('iissi', $case_id, $client_id, $file['name'], $path, $_SESSION['user_id']);
            $stmt->execute();
            $_SESSION['success'] = 'File uploaded.';
            header('Location: /modules/documents/view_documents.php');
            exit;
        } else {
            $_SESSION['error'] = 'Could not move uploaded file.';
        }
    }
}

// Simple upload form
$conn = db_connect();
$clients = $conn->query("SELECT client_id AS id, full_name AS name FROM clients ORDER BY full_name");
$cases = $conn->query("SELECT id, title FROM cases ORDER BY created_at DESC");
?>
<?php include __DIR__ . '/../../includes/header.php'; ?>
<?php include __DIR__ . '/../../includes/nav.php'; ?>
<main class="container">
    <h2>Upload Document</h2>
    <?php if (!empty($_SESSION['error'])): ?><div class="error"><?php echo e($_SESSION['error']); unset($_SESSION['error']); ?></div><?php endif; ?>
    <?php if (!empty($_SESSION['success'])): ?><div class="success"><?php echo e($_SESSION['success']); unset($_SESSION['success']); ?></div><?php endif; ?>
    <form method="post" enctype="multipart/form-data">
        <label>Client (optional)</label>
        <select name="client_id">
            <option value="">--Select--</option>
            <?php while ($c = $clients->fetch_assoc()): ?>
                <option value="<?php echo $c['id']; ?>"><?php echo e($c['name']); ?></option>
            <?php endwhile; ?>
        </select>

        <label>Case (optional)</label>
        <select name="case_id">
            <option value="">--Select--</option>
            <?php while ($c = $cases->fetch_assoc()): ?>
                <option value="<?php echo $c['id']; ?>"><?php echo e($c['title']); ?></option>
            <?php endwhile; ?>
        </select>

        <label>File</label>
        <input type="file" name="file" required>
        <button type="submit">Upload</button>
    </form>
</main>
<?php include __DIR__ . '/../../includes/footer.php'; ?>