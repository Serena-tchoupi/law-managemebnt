<?php
// index.php
session_start();
if (isset($_SESSION['user_id'])) {
    header('Location: dashboard.php');
    exit();
}
?>
<?php include __DIR__ . '/includes/header.php'; ?>
<main class="container">
    <section style="max-width:720px; margin:20px auto;">
        <div class="card" style="padding:20px;">
            <h2 style="margin-top:0">Law Management System</h2>
            <p style="color:#555;">Sign in to access your dashboard or register to create an account. Keep it simple and secure.</p>

            <?php if (!empty($_SESSION['error'])): ?>
                <div class="error" role="alert"><?php echo e($_SESSION['error']); unset($_SESSION['error']); ?></div>
            <?php endif; ?>

            <?php if (!empty($_SESSION['success'])): ?>
                <div class="success" role="status"><?php echo e($_SESSION['success']); unset($_SESSION['success']); ?></div>
            <?php endif; ?>

            <div style="display:flex; gap:12px; margin-top:12px; flex-wrap:wrap;">
                <button class="btn tab-button active" onclick="switchTab(event, 'login')" aria-controls="login">Login</button>
                <button class="btn tab-button" onclick="switchTab(event, 'register')" aria-controls="register">Register</button>
            </div>

            <div id="login" class="tab-content active" style="margin-top:16px;">
                <form method="post" action="auth/login.php" aria-labelledby="login">
                    <label for="login-email">Email</label>
                    <input id="login-email" type="email" name="email" placeholder="you@example.com" required>

                    <label for="login-password">Password</label>
                    <input id="login-password" type="password" name="password" placeholder="Your password" required>

                    <button type="submit" class="btn">Login</button>
                </form>
            </div>

            <div id="register" class="tab-content" style="margin-top:16px; display:none;">
                <form method="post" action="auth/register.php" aria-labelledby="register">
                    <label for="reg-name">Full Name</label>
                    <input id="reg-name" type="text" name="name" placeholder="Full name" required>

                    <label for="reg-email">Email</label>
                    <input id="reg-email" type="email" name="email" placeholder="you@example.com" required>

                    <label for="reg-role">Role</label>
                    <select id="reg-role" name="role" required>
                        <option value="lawyer">Lawyer</option>
                        <option value="clerk">Clerk</option>
                    </select>

                    <label for="reg-password">Password</label>
                    <input id="reg-password" type="password" name="password" placeholder="Create a password" required>

                    <small style="color:#666;display:block;margin-top:6px;">Password should be at least 6 characters.</small>

                    <button type="submit" class="btn">Register</button>
                </form>
            </div>
        </div>
    </section>
</main>
<?php include __DIR__ . '/includes/footer.php'; ?>

<script>
function switchTab(evt, tab) {
    var tabs = document.querySelectorAll('.tab-content');
    tabs.forEach(function(t) { t.style.display = 'none'; t.classList.remove('active'); });

    var buttons = document.querySelectorAll('.tab-button');
    buttons.forEach(function(b) { b.classList.remove('active'); });

    var el = document.getElementById(tab);
    if (el) { el.style.display = 'block'; el.classList.add('active'); }
    if (evt && evt.target) evt.target.classList.add('active');
}
</script>