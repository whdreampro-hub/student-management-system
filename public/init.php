<?php
// Start session only if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Check if user is logged in, otherwise redirect to login
function requireLogin() {
    if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
        header('Location: /student-management/public/index.php?controller=login&action=index');
        exit;
    }
}

// Check if user is admin (for simplicity, we only have one admin)
function requireAdmin() {
    requireLogin(); // First check if logged in
    // In a real system, we might check roles, but we only have admin
}

// Flash messages
function setFlash($key, $value) {
    $_SESSION['flash'][$key] = $value;
}

function getFlash($key) {
    if (isset($_SESSION['flash'][$key])) {
        $value = $_SESSION['flash'][$key];
        unset($_SESSION['flash'][$key]);
        return $value;
    }
    return null;
}

// Redirect function
function redirect($url) {
    header('Location: ' . $url);
    exit;
}

// Database connection
require_once __DIR__ . '/../app/config/database.php';
// After requiring database.php, the $pdo variable should be available in global scope