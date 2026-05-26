<?php
// Database configuration for XAMPP
$host = '127.0.0.1';
$dbname = 'student_management';
$username = 'root'; // XAMPP default
$password = ''; // XAMPP default
$charset = 'utf8mb4';
$port = 3306;

$dsn = "mysql:host=$host;port=$port;dbname=$dbname;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

$pdo = new PDO($dsn, $username, $password, $options);