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

- PHP 7.4 or later
- MySQL 5.7 or later
- Apache or Nginx with PHP support
- A web browser with JavaScript enabled

## Installation

1. Clone or download this repository:

   ```bash
   git clone https://github.com/whdreampro-hub/student-management-system.git
   cd student-management-system
   ```

2. Create a MySQL database for the application.

3. Locate the database configuration file in the project and set the database host, database name, username, and password for your local environment. Do not commit production credentials.

4. If the repository includes a SQL schema or database dump, import it into the database created in step 2. Review the schema before importing it into any database containing existing data.

5. Configure the web server's document root to the project directory (or the application's documented public directory, if one exists).

6. Open the application through your local web server and sign in with the administrator account configured for your installation.

## Security notes

- Change any default administrator credentials before using the application.
- Use a strong, unique password and do not share administrator access.
- Keep database credentials outside public source control.
- Restrict access to uploaded student photos and personal information.
- Use HTTPS and maintain regular, access-controlled database backups in production.

## Development

The project uses PHP, MySQL, Bootstrap 5, and browser-side JavaScript. Review the application configuration and database schema before deploying it to a live school environment.
