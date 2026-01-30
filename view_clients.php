<?php
// view_clients.php
// Role: List clients with basic actions; secure and consistent layout

require_once __DIR__ . '/auth/check_session.php';
require_login();
require_once __DIR__ . '/config.php';

$conn = db_connect();
$stmt = $conn->prepare("SELECT client_id AS id, full_name AS name, phone, email, address, created_at FROM clients ORDER BY created_at DESC");
$stmt->execute();
$clients = $stmt->get_result();
?>
<?php include __DIR__ . '/includes/header.php'; ?>
<?php include __DIR__ . '/includes/nav.php'; ?>
<main class="container">
    <a href="dashboard.php">← Back to Dashboard</a>

    <h2>All Clients <a href="add_client.php" class="btn action-btn" style="float:right">Add Client</a></h2>

    <div class="table-wrap">
    <table>
        <tr>
            <th>Name</th>
            <th>Email</th>
            <th>Phone</th>
            <th>Address</th>
            <th>Created Date</th>
            <th>Actions</th>
        </tr>
        <?php while($client = $clients->fetch_assoc()): ?>
        <tr>
            <td><?php echo e($client['name']); ?></td>
            <td><?php echo e($client['email']); ?></td>
            <td><?php echo e($client['phone']); ?></td>
            <td><?php echo e($client['address']); ?></td>
            <td><?php echo e(date('Y-m-d', strtotime($client['created_at']))); ?></td>
            <td>
                <a href="edit_client.php?id=<?php echo $client['id']; ?>">Edit</a> |
                <a href="delete_client.php?id=<?php echo $client['id']; ?>" onclick="return confirm('Delete?')">Delete</a>
            </td>
        </tr>
        <?php endwhile; ?>
    </table>
    </div>
</main>
<?php include __DIR__ . '/includes/footer.php'; ?>
