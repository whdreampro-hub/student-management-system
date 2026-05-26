<?php
require_once '../config/config.php';
require_once '../config/database.php';
require_once '../helpers/functions.php';

// Initialize database connection
$database = new Database();
$db = $database->getConnection();

// Simple routing
$controller = isset($_GET['controller']) ? $_GET['controller'] : 'auth';
$action = isset($_GET['action']) ? $_GET['action'] : 'login';
$id = isset($_GET['id']) ? $_GET['id'] : null;

// Check if user is logged in
if($controller != 'auth' && !isset($_SESSION['user_id'])) {
    header("Location: index.php?controller=auth&action=login");
    exit();
}

// Load controllers
switch($controller) {
    case 'auth':
        require_once '../controllers/AuthController.php';
        $auth = new AuthController($db);
        if($action == 'login') $auth->login();
        elseif($action == 'logout') $auth->logout();
        break;
        
    case 'dashboard':
        require_once '../controllers/DashboardController.php';
        $dashboard = new DashboardController($db);
        $dashboard->index();
        break;
        
    case 'student':
        require_once '../controllers/StudentController.php';
        $student = new StudentController($db);
        if($action == 'index') $student->index();
        elseif($action == 'create') $student->create();
        elseif($action == 'edit') $student->edit($id);
        elseif($action == 'delete') $student->delete($id);
        elseif($action == 'profile') $student->profile($id);
        elseif($action == 'promote') $student->promote($id);
        elseif($action == 'transfer') $student->transfer($id);
        elseif($action == 'search') $student->search();
        break;
        
    case 'class':
        require_once '../controllers/ClassController.php';
        $class = new ClassController($db);
        if($action == 'manage') $class->manage();
        break;
        
    case 'academic_year':
        require_once '../controllers/AcademicYearController.php';
        $academicYear = new AcademicYearController($db);
        if($action == 'manage') $academicYear->manage();
        break;
        
    case 'report':
        require_once '../controllers/ReportController.php';
        $report = new ReportController($db);
        if($action == 'index') $report->index();
        elseif($action == 'export') $report->export();
        break;
        
    default:
        header("Location: index.php?controller=dashboard&action=index");
}
?>