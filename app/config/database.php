<?php
class Database {
    private static $instance = null;
    private PDO $pdo;

    private function __construct() {
        $host = getenv('DB_HOST') ?: '127.0.0.1';
        $port = getenv('DB_PORT') ?: '3306';
        $dbname = getenv('DB_NAME') ?: 'student_management';
        $username = getenv('DB_USERNAME') ?: 'root';
        $password = getenv('DB_PASSWORD') ?: '';
        $charset = 'utf8mb4';

        $dsn = "mysql:host={$host};port={$port};dbname={$dbname};charset={$charset}";
        $options = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ];

        $this->pdo = new PDO($dsn, $username, $password, $options);
    }

    public static function getInstance(): self {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function getConnection(): PDO {
        return $this->pdo;
    }
}
