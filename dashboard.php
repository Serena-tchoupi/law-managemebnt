<?php
// dashboard.php
// Role: Role-aware main dashboard; includes header, nav and footer for consistent layout.
// WHY: Provide quick overview per user role and use centralized DB helper.

require_once __DIR__ . '/auth/check_session.php';
require_once __DIR__ . '/config.php';
require_login();
$user_name = $_SESSION['user_name'] ?? 'User';

$conn = db_connect();
// Stats (wrapped to avoid fatal errors if DB schema is not yet applied)
$clients_count = 0;
$cases_count = 0;
$active_cases = 0;

try {
    $result = $conn->query("SELECT COUNT(*) as count FROM clients");
    $clients_count = $result->fetch_assoc()['count'] ?? 0;
} catch (mysqli_sql_exception $e) {
    // Schema might not exist yet; keep 0 and log error for debugging
    error_log('DB error (clients count): ' . $e->getMessage());
}

try {
    $result = $conn->query("SELECT COUNT(*) as count FROM cases");
    $cases_count = $result->fetch_assoc()['count'] ?? 0;
} catch (mysqli_sql_exception $e) {
    error_log('DB error (cases count): ' . $e->getMessage());
}

// Active cases -> treat 'open' as active
try {
    $result = $conn->query("SELECT COUNT(*) as count FROM cases WHERE status='open'");
    $active_cases = $result->fetch_assoc()['count'] ?? 0;
} catch (mysqli_sql_exception $e) {
    error_log('DB error (active cases): ' . $e->getMessage());
}
?>
<?php include __DIR__ . '/includes/header.php'; ?>
<?php include __DIR__ . '/includes/nav.php'; ?>
<main class="container">
    <h1>Dashboard</h1>

    <section class="cards">
        <div class="card">
            <h3>Total Clients</h3>
            <p><?php echo e($clients_count); ?></p>
        </div>
        <div class="card">
            <h3>Total Cases</h3>
            <p><?php echo e($cases_count); ?></p>
        </div>
        <div class="card">
            <h3>Active Cases</h3>
            <p><?php echo e($active_cases); ?></p>
        </div>
    </section>

    <section>
        <h2>Quick Actions</h2>
        <a href="add_client.php" class="action-btn">Add New Client</a>
        <a href="add_case.php" class="action-btn">Add New Case</a>
    </section>
</main>
<?php include __DIR__ . '/includes/footer.php'; ?>
