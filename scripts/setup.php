<?php
declare(strict_types=1);

// Database setup must only be run from a trusted command-line session.
if (PHP_SAPI !== 'cli') {
    http_response_code(404);
    exit("Not found\n");
}

$host = getenv('DB_HOST') ?: '127.0.0.1';
$port = getenv('DB_PORT') ?: '3306';
$dbname = getenv('DB_NAME') ?: 'student_management';
$username = getenv('DB_USERNAME') ?: 'root';
$password = getenv('DB_PASSWORD') ?: '';

if (!preg_match('/^[A-Za-z0-9_]+$/', $dbname)) {
    fwrite(STDERR, "DB_NAME may contain only letters, numbers, and underscores.\n");
    exit(1);
}

$options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES => false,
];

try {
    $server = new PDO("mysql:host={$host};port={$port};charset=utf8mb4", $username, $password, $options);
    $server->exec("CREATE DATABASE IF NOT EXISTS `{$dbname}` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");

    $pdo = new PDO("mysql:host={$host};port={$port};dbname={$dbname};charset=utf8mb4", $username, $password, $options);
    $schemaPath = __DIR__ . '/../database/schema.sql';
    $schema = file_get_contents($schemaPath);

    if ($schema === false) {
        throw new RuntimeException('Unable to read database/schema.sql.');
    }

    $statements = array_filter(array_map('trim', explode(';', $schema)));
    foreach ($statements as $statement) {
        try {
            $pdo->exec($statement);
        } catch (PDOException $error) {
            if ((string) $error->getCode() !== '23000') {
                throw $error;
            }
        }
    }

    $adminCount = (int) $pdo->query('SELECT COUNT(*) FROM admins')->fetchColumn();
    if ($adminCount > 0) {
        echo "Database schema checked. An administrator already exists; no account or password was changed.\n";
        exit(0);
    }

    $adminUsername = trim((string) getenv('INITIAL_ADMIN_USERNAME'));
    $adminPassword = (string) getenv('INITIAL_ADMIN_PASSWORD');
    $adminFullName = trim((string) getenv('INITIAL_ADMIN_FULL_NAME'));
    $adminEmail = trim((string) getenv('INITIAL_ADMIN_EMAIL'));

    if ($adminUsername === '' || $adminFullName === '' || strlen($adminPassword) < 12) {
        fwrite(STDERR, "Set INITIAL_ADMIN_USERNAME, INITIAL_ADMIN_FULL_NAME, and a unique INITIAL_ADMIN_PASSWORD of at least 12 characters, then run setup again.\n");
        exit(1);
    }

    if (strlen($adminUsername) > 100 || strlen($adminFullName) > 200) {
        fwrite(STDERR, "The administrator username or full name is too long.\n");
        exit(1);
    }

    if ($adminEmail !== '' && filter_var($adminEmail, FILTER_VALIDATE_EMAIL) === false) {
        fwrite(STDERR, "INITIAL_ADMIN_EMAIL must be a valid email address when provided.\n");
        exit(1);
    }

    $insert = $pdo->prepare(
        'INSERT INTO admins (username, password, full_name, email) VALUES (?, ?, ?, ?)'
    );
    $insert->execute([
        $adminUsername,
        password_hash($adminPassword, PASSWORD_DEFAULT),
        $adminFullName,
        $adminEmail !== '' ? $adminEmail : null,
    ]);

    echo "Database initialized and the first administrator account was created.\n";
    echo "Remove INITIAL_ADMIN_PASSWORD from the environment after setup.\n";
} catch (Throwable $error) {
    fwrite(STDERR, "Setup failed: " . $error->getMessage() . "\n");
    exit(1);
}
