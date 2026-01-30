# Law Management System (Student Project)

Minimal PHP + MySQL law management system scaffold.

## Structure
- `index.php` - Landing page (login/register)
- `config.php` - Central DB connection and helpers
- `dashboard.php` - Role-aware dashboard
- `auth/` - Authentication (`login.php`, `register.php`, `logout.php`, `check_session.php`)
- `includes/` - Shared views (`header.php`, `nav.php`, `footer.php`)
- `modules/` - Feature modules: `notes`, `documents`, `appointments`
- `assets/css/style.css` - Basic styling
- `uploads/` - Uploaded files
- `sql/schema.sql` - Database schema
- `sql/create_admin.php` - Utility script to create an admin user

## Quick setup
1. Create database `law_management` in MySQL.
2. Run `sql/schema.sql` to create tables (use phpMyAdmin or CLI).
3. (Optional) Run `php sql/create_admin.php` to create a default admin user (`admin@example.com` / `admin123`).
4. Point your web server to the project root (e.g., using XAMPP put in `htdocs`).

## Notes
- All DB interactions use prepared statements where user input is involved.
- Passwords use `password_hash()` / `password_verify()`.
- Keep `uploads/` protected on production (e.g., serve via script or restrict by server config).

## Next improvements
- Add edit pages, soft-deletes, pagination.
- Add role management (admin panel) and unit tests.
- Add user-friendly flash messaging UI.

