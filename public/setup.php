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
    // Connect without selecting a DB first so we can create it
    $pdo = new PDO("mysql:host=$host;charset=utf8mb4", $user, $pass,
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);

    $pdo->exec("CREATE DATABASE IF NOT EXISTS `$dbname` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
    $pdo->exec("USE `$dbname`");

    // Run schema — skip duplicate-key errors so re-running is safe
    $schema     = file_get_contents(__DIR__ . '/../database/schema.sql');
    $statements = array_filter(array_map('trim', explode(';', $schema)));
    $skipped    = 0;

    foreach ($statements as $stmt) {
        if (empty($stmt)) continue;
        try {
            $pdo->exec($stmt);
        } catch (PDOException $e) {
            // 23000 = integrity constraint (duplicate key) — safe to skip on re-run
            if ($e->getCode() === '23000') {
                $skipped++;
            } else {
                throw $e; // re-throw anything unexpected
            }
        }
    }

    // Always ensure the admin password is set correctly
    $hash = password_hash('admin123', PASSWORD_BCRYPT);
    $pdo->prepare("UPDATE admins SET password = ? WHERE username = 'admin'")->execute([$hash]);

    // Verify the admin row exists (in case it was somehow missing)
    $row = $pdo->query("SELECT id FROM admins WHERE username = 'admin'")->fetch();
    if (!$row) {
        $pdo->prepare("INSERT INTO admins (username, password, full_name, email) VALUES ('admin', ?, 'System Administrator', 'admin@school.com')")
            ->execute([$hash]);
    }

    echo '<div style="font-family:monospace;padding:30px;background:#1e2130;color:#4ade80;min-height:100vh">';
    echo '<h2>✅ Setup Complete!</h2>';
    echo '<p>Database <strong>' . htmlspecialchars($dbname) . '</strong> is ready.</p>';
    if ($skipped > 0) {
        echo '<p style="color:#fbbf24">ℹ️ ' . $skipped . ' statement(s) skipped (already existed — this is normal on re-run).</p>';
    }
    echo '<p>Admin credentials: <code>admin / admin123</code></p>';
    echo '<p><strong>⚠️ Delete this file after logging in!</strong></p>';
    echo '<a href="/student-management/public/index.php?page=login" style="color:#818cf8;font-size:1.1rem">→ Go to Login</a>';
    echo '</div>';

} catch (PDOException $e) {
    echo '<div style="font-family:monospace;padding:30px;background:#1e2130;color:#f87171;min-height:100vh">';
    echo '<h2>❌ Setup Failed</h2>';
    echo '<pre>' . htmlspecialchars($e->getMessage()) . '</pre>';
    echo '<p>Check your database credentials at the top of this file.</p>';
    echo '</div>';
}
