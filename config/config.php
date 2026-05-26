<?php
session_start();
define('BASE_URL', 'http://localhost/student-management-system/');
define('BASE_PATH', dirname(__DIR__));
define('UPLOAD_PATH', BASE_PATH . '/public/assets/uploads/students/');

// Error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);
?>