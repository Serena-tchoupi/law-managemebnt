<?php
// modules/appointments/add_appointment.php
// Role: Schedule an appointment linked to a case.

require_once __DIR__ . '/../../auth/check_session.php';
require_login();
require_once __DIR__ . '/../../config.php';

$conn = db_connect();
$cases = $conn->query("SELECT id, title FROM cases ORDER BY created_at DESC");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title'] ?? '');
    $case_id = (int)($_POST['case_id'] ?: 0) ?: null;
    $date = $_POST['appointment_date'] ?? '';

    if ($title === '' || $date === '') {
        $_SESSION['error'] = 'Title and date required.';
    } else {
        $stmt = $conn->prepare("INSERT INTO appointments (case_id, title, appointment_date, created_by) VALUES (?, ?, ?, ?)");
        $stmt->bind_param('issi', $case_id, $title, $date, $_SESSION['user_id']);
        $stmt->execute();
        $_SESSION['success'] = 'Appointment scheduled.';
        header('Location: /modules/appointments/view_appointments.php');
        exit;
    }
}
?>
<?php include __DIR__ . '/../../includes/header.php'; ?>
<?php include __DIR__ . '/../../includes/nav.php'; ?>
<main class="container">
    <h2>Schedule Appointment</h2>
    <?php if (!empty($_SESSION['error'])): ?><div class="error"><?php echo e($_SESSION['error']); unset($_SESSION['error']); ?></div><?php endif; ?>
    <form method="post">
        <label>Title</label>
        <input type="text" name="title" required>

        <label>Case (optional)</label>
        <select name="case_id">
            <option value="">--Select--</option>
            <?php while ($c = $cases->fetch_assoc()): ?>
                <option value="<?php echo $c['id']; ?>"><?php echo e($c['title']); ?></option>
            <?php endwhile; ?>
        </select>

        <label>Date & Time</label>
        <input type="datetime-local" name="appointment_date" required>

        <button type="submit">Schedule</button>
    </form>
</main>
<?php include __DIR__ . '/../../includes/footer.php'; ?>