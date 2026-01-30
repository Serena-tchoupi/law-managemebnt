<?php
// add_client.php
// Role: Add a new client to the system (secure, prepared statements, centralized helpers)

require_once __DIR__ . '/auth/check_session.php';
require_login();
require_once __DIR__ . '/config.php';

$success = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $address = trim($_POST['address'] ?? '');

    if ($name === '') {
        $error = 'Name is required.';
    } else {
        $conn = db_connect();
        $stmt = $conn->prepare("INSERT INTO clients (full_name, phone, email, address) VALUES (?, ?, ?, ?)");
        $stmt->bind_param('ssss', $name, $phone, $email, $address);
        if ($stmt->execute()) {
            $success = 'Client added successfully!';
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

    <h2>Add New Client</h2>

    <?php if ($success): ?>
        <div class="success"><?php echo e($success); ?></div>
    <?php endif; ?>
    <?php if ($error): ?>
        <div class="error"><?php echo e($error); ?></div>
    <?php endif; ?>

    <form method="post">
        <label>Full Name</label>
        <input type="text" name="name" placeholder="Client Full Name" required>

        <label>Email</label>
        <input type="email" name="email" placeholder="Email">

        <label>Phone Number</label>
        <input type="text" name="phone" placeholder="Phone Number">

        <label>Address</label>
        <textarea name="address" placeholder="Address"></textarea>

        <button type="submit" class="btn">Add Client</button>
    </form>
</main>
<?php include __DIR__ . '/includes/footer.php'; ?>