# Student Management System

A PHP and MySQL application for administrators to manage student records, academic history, class promotions, and transfers.

## Features

- Single-user administrator authentication
- Student create, read, update, and delete operations
- Student photo upload
- Academic year and class management
- Student promotions and transfers with historical records preserved
- Academic timeline tracking
- Student search and filtering
- PDF and Excel reports
- Dashboard charts and statistics
- Responsive Bootstrap 5 interface
- AJAX search, SweetAlert2 notifications, and activity logging

## Requirements

- PHP 8.0 or later with PDO MySQL enabled
- MySQL 5.7 or later (MySQL 8.0 recommended)
- Apache with `mod_rewrite`, or an equivalent Nginx configuration
- A web browser with JavaScript enabled

## Installation

1. Clone the repository:

   ```bash
   git clone https://github.com/whdreampro-hub/student-management-system.git
   cd student-management-system
   ```

2. Create a database user with permission to create and modify the application database. Set the following environment variables for both the PHP CLI and the web-server process:

   - `DB_HOST` (default: `127.0.0.1`)
   - `DB_PORT` (default: `3306`)
   - `DB_NAME` (default: `student_management`)
   - `DB_USERNAME`
   - `DB_PASSWORD`

   See `.env.example` for the variable names. It is a template only; PHP does not load it automatically. Keep actual values outside the repository and configure them through your server or process manager.

3. For a new database, set these additional variables for the initial administrator:

   - `INITIAL_ADMIN_USERNAME`
   - `INITIAL_ADMIN_PASSWORD` (use a unique password with at least 12 characters)
   - `INITIAL_ADMIN_FULL_NAME`
   - `INITIAL_ADMIN_EMAIL` (optional)

   Run the setup from the repository root:

   ```bash
   php scripts/setup.php
   ```

   The setup script is CLI-only. It creates the schema and the first administrator account, does not seed a default password, and never resets an existing administrator's password. Remove `INITIAL_ADMIN_PASSWORD` from the environment after setup.

4. Configure the web server document root to the `public/` directory. Do not expose the repository root, `app/`, `database/`, or `scripts/` as public web content.

5. Open the application through your local web server and sign in with the administrator account created during setup.

## Security and data handling

- Do not commit database credentials, `.env` files, SQL database dumps, or uploaded student photos.
- Change and protect administrator credentials; do not reuse passwords from other services.
- Restrict access to student records and uploaded photos.
- Use HTTPS and maintain encrypted, access-controlled database backups in production.
- The setup script does not replace a production backup or migration process.

## Development

The project uses PHP, MySQL, Bootstrap 5, and browser-side JavaScript. Review database migrations and access controls before deploying it with real student data.
