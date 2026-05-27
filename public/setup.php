<?php
/**
 * Setup Script — Run ONCE to initialize the database.
 * Access: http://localhost/student-management/public/setup.php
 * DELETE this file after running it!
 */

$host   = 'localhost';
$dbname = 'student_management';
$user   = 'root';
$pass   = '';

try {
    // Create DB if not exists
    $pdo = new PDO("mysql:host=$host;charset=utf8mb4", $user, $pass,
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
    $pdo->exec("CREATE DATABASE IF NOT EXISTS `$dbname` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
    $pdo->exec("USE `$dbname`");

    // Run schema
    $schema = file_get_contents(__DIR__ . '/../database/schema.sql');
    // Split and run each statement
    $statements = array_filter(array_map('trim', explode(';', $schema)));
    foreach ($statements as $stmt) {
        if (!empty($stmt)) $pdo->exec($stmt);
    }

    // Fix admin password to admin123
    $hash = password_hash('admin123', PASSWORD_BCRYPT);
    $pdo->prepare("UPDATE admins SET password = ? WHERE username = 'admin'")->execute([$hash]);

    echo '<div style="font-family:monospace;padding:30px;background:#1e2130;color:#4ade80;min-height:100vh">';
    echo '<h2>✅ Setup Complete!</h2>';
    echo '<p>Database <strong>' . $dbname . '</strong> created and configured.</p>';
    echo '<p>Admin credentials: <code>admin / admin123</code></p>';
    echo '<p><strong>⚠️ Delete this file now!</strong></p>';
    echo '<a href="/student-management/public/?page=login" style="color:#818cf8">→ Go to Login</a>';
    echo '</div>';

} catch (PDOException $e) {
    echo '<div style="font-family:monospace;padding:30px;background:#1e2130;color:#f87171;min-height:100vh">';
    echo '<h2>❌ Setup Failed</h2>';
    echo '<pre>' . htmlspecialchars($e->getMessage()) . '</pre>';
    echo '<p>Check your database credentials in this file.</p>';
    echo '</div>';
}
