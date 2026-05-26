<?php
echo "<h1>Configuration Test</h1>";

// Check PHP version
echo "<p>PHP Version: " . PHP_VERSION . "</p>";

// Check required extensions
$extensions = ['pdo_mysql', 'mysqli', 'gd', 'fileinfo'];
foreach($extensions as $ext) {
    if(extension_loaded($ext)) {
        echo "<p style='color:green'>✓ $ext extension loaded</p>";
    } else {
        echo "<p style='color:red'>✗ $ext extension NOT loaded</p>";
    }
}

// Check directory permissions
$dirs = [
    '../public/assets/uploads/students',
    '../public/assets/uploads'
];

foreach($dirs as $dir) {
    if(is_dir($dir)) {
        echo "<p style='color:green'>✓ Directory exists: $dir</p>";
        if(is_writable($dir)) {
            echo "<p style='color:green'>✓ Directory is writable: $dir</p>";
        } else {
            echo "<p style='color:red'>✗ Directory NOT writable: $dir</p>";
        }
    } else {
        echo "<p style='color:red'>✗ Directory missing: $dir</p>";
    }
}

// Test database connection
try {
    require_once '../config/database.php';
    $database = new Database();
    $db = $database->getConnection();
    echo "<p style='color:green'>✓ Database connection successful</p>";
} catch(Exception $e) {
    echo "<p style='color:red'>✗ Database connection failed: " . $e->getMessage() . "</p>";
}

echo "<p><a href='index.php'>Go to Application</a></p>";
?>