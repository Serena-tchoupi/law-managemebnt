<?php

require_once __DIR__ . '/../../auth/check_session.php';
require_login();
require_once __DIR__ . '/../../config.php';

$conn = db_connect();
$res = $conn->query("SELECT a.*, ca.title as case_title, u.full_name as creator FROM appointments a LEFT JOIN cases ca ON a.case_id = ca.id LEFT JOIN users u ON a.created_by = u.user_id ORDER BY a.appointment_date DESC");
?>
<?php include __DIR__ . '/../../includes/header.php'; ?>
<?php include __DIR__ . '/../../includes/nav.php'; ?>
<main class="container">
    <h2>Appointments</h2>
    <a href="/modules/appointments/add_appointment.php">Schedule new</a>
    <table>
        <tr><th>Title</th><th>Case</th><th>Date</th><th>Created by</th></tr>
        <?php while ($r = $res->fetch_assoc()): ?>
            <tr>
                <td><?php echo e($r['title']); ?></td>
                <td><?php echo e($r['case_title']); ?></td>
                <td><?php echo e($r['appointment_date']); ?></td>
                <td><?php echo e($r['creator']); ?></td>
            </tr>
        <?php endwhile; ?>
    </table>
</main>
<?php include __DIR__ . '/../../includes/footer.php'; ?>