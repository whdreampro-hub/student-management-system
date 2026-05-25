<?php
/**
 * Front Controller
 * Handles all incoming requests and routes to appropriate controllers
 */

// Initialize (session_start is handled inside init.php)
require_once __DIR__ . '/init.php';

// Autoload Models
require_once __DIR__ . '/../app/models/BaseModel.php';
require_once __DIR__ . '/../app/models/StudentModel.php';

// Get controller and action from URL
$controller = isset($_GET['controller']) ? $_GET['controller'] : 'dashboard';
$action     = isset($_GET['action'])     ? $_GET['action']     : 'index';
$id         = isset($_GET['id'])         ? (int)$_GET['id']   : null;

// Sanitize: strip tags and special chars (FILTER_SANITIZE_STRING removed in PHP 8.1)
$controller = htmlspecialchars(strip_tags(trim($controller)));
$action     = htmlspecialchars(strip_tags(trim($action)));

// Map controller names to class names
$controller_map = [
    'dashboard'    => 'DashboardController',
    'student'      => 'StudentController',
    'academic_year'=> 'AcademicYearController',
    'class'        => 'ClassController',
    'login'        => 'LoginController',
    'logout'       => 'LoginController', // logout action handled in LoginController
];

// Default to dashboard if controller not in map
if (!isset($controller_map[$controller])) {
    $controller = 'dashboard';
}

// Load the controller file
$controller_file = __DIR__ . '/../app/controllers/' . $controller_map[$controller] . '.php';
if (file_exists($controller_file)) {
    require_once $controller_file;
} else {
    die('Controller not found: ' . htmlspecialchars($controller));
}

// Instantiate controller
$controller_obj = new $controller_map[$controller]($pdo);

// Dispatch: pass $id as argument when present, so methods like view($id) work
if (method_exists($controller_obj, $action)) {
    if ($id !== null) {
        $controller_obj->$action($id);
    } else {
        $controller_obj->$action();
    }
} else {
    // Fall back to index
    if (method_exists($controller_obj, 'index')) {
        $controller_obj->index();
    } else {
        die('Action not found: ' . htmlspecialchars($action));
    }
}