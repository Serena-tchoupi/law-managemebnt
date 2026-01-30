<?php
// view_cases.php
// Role: List cases using standardized fields and consistent layout

require_once __DIR__ . '/auth/check_session.php';
require_login();
require_once __DIR__ . '/config.php';

$conn = db_connect();
$sql = "SELECT c.id, c.title, c.status, c.created_at, cl.name AS client_name, u.full_name AS lawyer
        FROM cases c
        LEFT JOIN clients cl ON c.client_id = cl.id
        LEFT JOIN users u ON c.assigned_to = u.user_id
        ORDER BY c.created_at DESC";
$res = $conn->query($sql);
?>
<?php include __DIR__ . '/includes/header.php'; ?>
<?php include __DIR__ . '/includes/nav.php'; ?>
<main class="container">
    <a href="dashboard.php">← Back to Dashboard</a>

    <h2>All Cases <a href="add_case.php" class="btn action-btn" style="float:right">Add Case</a></h2>

    <div class="table-wrap">
    <table>
        <tr>
            <th>Title</th>
            <th>Client</th>
            <th>Assigned</th>
            <th>Status</th>
            <th>Created</th>
            <th>Actions</th>
        </tr>
        <?php while($r = $res->fetch_assoc()): ?>
        <tr>
            <td><?php echo e($r['title']); ?></td>
            <td><?php echo e($r['client_name']); ?></td>
            <td><?php echo e($r['lawyer']); ?></td>
            <td><?php echo e($r['status']); ?></td>
            <td><?php echo e(date('Y-m-d', strtotime($r['created_at']))); ?></td>
            <td>
                <a href="edit_case.php?id=<?php echo $r['id']; ?>">Edit</a> |
                <a href="delete_case.php?id=<?php echo $r['id']; ?>" onclick="return confirm('Delete?')">Delete</a>
            </td>
        </tr>
        <?php endwhile; ?>
    </table>
    </div>
</main>
<?php include __DIR__ . '/includes/footer.php'; ?>