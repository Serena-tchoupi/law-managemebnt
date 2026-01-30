<?php
// includes/nav.php
// Role: Public-friendly navigation. DOES NOT force auth redirect on include.
// WHY: Allow header+nav on public pages (index) and show role-aware links when logged in.

require_once __DIR__ . '/../config.php';
if (session_status() === PHP_SESSION_NONE) session_start();

$user = null;
if (!empty($_SESSION['user_id'])) {
    $user = [
        'id' => $_SESSION['user_id'],
        'name' => $_SESSION['user_name'] ?? null,
        'role' => $_SESSION['user_role'] ?? null,
    ];
}
?>
<nav class='main-nav'>
  <?php if ($user): ?>
    <a href='dashboard.php'>Dashboard</a>
    <?php if (in_array($user['role'], ['admin','clerk'])): ?>
      <a href='view_clients.php'>Clients</a>
    <?php endif; ?>

    <a href='view_cases.php'>Cases</a>
    <a href='modules/appointments/view_appointments.php'>Appointments</a>
    <a href='modules/documents/view_documents.php'>Documents</a>

    <span style="margin-left:auto;color:#333;">Logged in as <?php echo e($user['name'] ?? 'User'); ?> (<?php echo e($user['role'] ?? ''); ?>) | <a href='auth/logout.php'>Logout</a></span>
  <?php else: ?>
    <a href='index.php'>Home</a>
    <a href='index.php' style='margin-left:auto' class='btn'>Login / Register</a>
  <?php endif; ?>
</nav>