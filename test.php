<?php
// Test script to verify basic functionality
require_once __DIR__ . '/public/init.php';

// Test database connection
try {
    global $pdo;
    $stmt = $pdo->query('SELECT 1');
    echo "Database connection: OK\n";
} catch (Exception $e) {
    echo "Database connection: FAILED - " . $e->getMessage() . "\n";
}

// Test if we can fetch data
try {
    global $pdo;
    $stmt = $pdo->query('SELECT COUNT(*) as total FROM admin_users');
    $result = $stmt->fetch();
    echo "Admin users count: " . $result['total'] . "\n";
} catch (Exception $e) {
    echo "Query failed: " . $e->getMessage() . "\n";
}

// Test session
session_start();
if (session_status() == PHP_SESSION_ACTIVE) {
    echo "Session: OK\n";
} else {
    echo "Session: FAILED\n";
}

echo "Basic system test completed.\n";