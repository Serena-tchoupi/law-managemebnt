<?php
// add_case.php
// Role: Add a new case and link to client and assigned lawyer.

require_once __DIR__ . '/auth/check_session.php';
require_login();
require_once __DIR__ . '/config.php';

$conn = db_connect();
$clients = $conn->query("SELECT client_id AS id, full_name AS name FROM clients ORDER BY full_name");
$lawyers = $conn->query("SELECT user_id AS id, full_name AS name FROM users WHERE role = 'lawyer' ORDER BY full_name");

$success = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $client_id = (int)($_POST['client_id'] ?? 0);
    $title = trim($_POST['title'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $status = $_POST['status'] ?? 'open';
    $assigned_to = (int)($_POST['assigned_to'] ?: 0) ?: null;

    if ($client_id <= 0 || $title === '') {
        $error = 'Client and title are required.';
    } else {
        $stmt = $conn->prepare("INSERT INTO cases (client_id, title, description, status, assigned_to) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param('isssi', $client_id, $title, $description, $status, $assigned_to);
        if ($stmt->execute()) {
            $success = 'Case added successfully!';
        } else {
            $error = 'Error: ' . $conn->error;
        }
    }
}
?>
<?php include __DIR__ . '/includes/header.php'; ?>
<?php include __DIR__ . '/includes/nav.php'; ?>
<main class="container">
    <a href="dashboard.php">← Back to Dashboard</a>

    <h2>Add New Case</h2>

    <?php if ($success): ?>
        <div class="success"><?php echo e($success); ?></div>
    <?php endif; ?>
    <?php if ($error): ?>
        <div class="error"><?php echo e($error); ?></div>
    <?php endif; ?>

    <form method="post">
        <label>Client</label>
        <select name="client_id" required>
            <option value="">Select Client</option>
            <?php while($client = $clients->fetch_assoc()): ?>
                <option value="<?php echo $client['id']; ?>"><?php echo e($client['name']); ?></option>
            <?php endwhile; ?>
        </select>

        <label>Title</label>
        <input type="text" name="title" placeholder="Case Title" required>

        <label>Description</label>
        <textarea name="description" placeholder="Case Description"></textarea>

        <label>Status</label>
        <select name="status">
            <option value="open">Open</option>
            <option value="pending">Pending</option>
            <option value="closed">Closed</option>
        </select>

        <label>Assign to (Lawyer)</label>
        <select name="assigned_to">
            <option value="">Unassigned</option>
            <?php while($lawyer = $lawyers->fetch_assoc()): ?>
                <option value="<?php echo $lawyer['id']; ?>"><?php echo e($lawyer['name']); ?></option>
            <?php endwhile; ?>
        </select>

        <button type="submit" class="btn">Add Case</button>
    </form>
</main>
<?php include __DIR__ . '/includes/footer.php'; ?>