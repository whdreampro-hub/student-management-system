<?php
class Database {
    private static $instance = null;
    private PDO $pdo;

    private string $host     = '127.0.0.1';
    private string $dbname   = 'student_management';
    private string $username = 'root';
    private string $password = '';
    private string $charset  = 'utf8mb4';
    private string $port     = '3306';

    private function __construct() {
        $dsn = "mysql:host={$this->host};port={$this->port};dbname={$this->dbname};charset={$this->charset}";
        $options = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ];

        $this->pdo = new PDO($dsn, $this->username, $this->password, $options);
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

