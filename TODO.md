# TODO

## ✅ Completed

All merge conflicts have been resolved. The app is fully functional.

### What was fixed
- Resolved all 13 git merge conflict markers across PHP files, CSS, JS, and .htaccess
- Kept the HEAD (Branch A) implementation consistently throughout
- Fixed `public/.htaccess` merge conflict — Apache was returning 500 before PHP ran
- Fixed root `.htaccess` wrong path (`student-management-system` → `student-management`)
- Removed dead Branch B code: `controllers/`, `models/`, `views/`, `config/`, `helpers/` (root-level)
- Removed security-risk diagnostic files: `public/test-config.php`, `public/test.php`, `test.php`
- Removed duplicate schema: `database_schema.sql` (Branch B's incompatible schema)
- Removed unused `app/controllers/LoginController.php` (Branch B, plaintext password comparison)
- Removed unused `index.html` placeholder

### Architecture (Branch A — active)
- Entry point: `public/index.php` (front controller + router)
- URL scheme: `?page=X&action=Y`
- Auth: session key `admin_id`, table `admins`, bcrypt passwords
- DB: `student_management` (see `database/schema.sql`)
- Setup: run `public/setup.php` once, then delete it

## Remaining / Future Work
- [ ] Delete `public/setup.php` after running it (it's a one-time setup script)
- [ ] Reports feature — add `app/controllers/ReportController.php` with CSV/PDF export
- [ ] Consider moving DB credentials to a `.env` file outside webroot
