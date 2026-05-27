<?php
define('ROOT',    dirname(__DIR__));
define('APP',     ROOT . '/app');
define('UPLOADS', ROOT . '/public/assets/uploads/photos');

session_start();

// Autoload config and models
require_once APP . '/config/database.php';
require_once APP . '/models/Model.php';
require_once APP . '/models/StudentModel.php';
require_once APP . '/models/ClassModel.php';
require_once APP . '/models/AcademicYearModel.php';
require_once APP . '/models/HistoryModel.php';
require_once APP . '/models/ActivityLogModel.php';

// Autoload controllers
require_once APP . '/controllers/AuthController.php';
require_once APP . '/controllers/DashboardController.php';
require_once APP . '/controllers/StudentController.php';
require_once APP . '/controllers/ClassController.php';
require_once APP . '/controllers/AcademicYearController.php';
require_once APP . '/controllers/HistoryController.php';

// Helpers
function redirect(string $url): void {
    header("Location: $url");
    exit;
}

function jsonResponse(array $data): void {
    header('Content-Type: application/json');
    echo json_encode($data);
    exit;
}

function isLoggedIn(): bool {
    return isset($_SESSION['admin_id']);
}

function requireAuth(): void {
    if (!isLoggedIn()) redirect('?page=login');
}

function asset(string $path): string {
    return '/student-management/public/assets/' . ltrim($path, '/');
}

function uploadUrl(string $file): string {
    return '/student-management/public/assets/uploads/photos/' . $file;
}

function timeAgo(string $datetime): string {
    $diff = time() - strtotime($datetime);
    if ($diff < 60)     return 'just now';
    if ($diff < 3600)   return floor($diff/60) . 'm ago';
    if ($diff < 86400)  return floor($diff/3600) . 'h ago';
    if ($diff < 604800) return floor($diff/86400) . 'd ago';
    return date('M d, Y', strtotime($datetime));
}

// ── Router ──────────────────────────────────────────────
$page   = $_GET['page']   ?? 'login';
$action = $_GET['action'] ?? 'index';
$method = $_SERVER['REQUEST_METHOD'];

// Public routes
if ($page === 'login') {
    $ctrl = new AuthController();
    if ($method === 'POST') $ctrl->login();
    else                    $ctrl->showLogin();
    exit;
}

if ($page === 'logout') {
    (new AuthController())->logout();
    exit;
}

// Protected routes
requireAuth();

switch ($page) {
    // ── Dashboard ────────────────────────────────────────
    case 'dashboard':
        (new DashboardController())->index();
        break;

    // ── Students ─────────────────────────────────────────
    case 'students':
        $ctrl = new StudentController();
        match($action) {
            'create'  => $ctrl->create(),
            'store'   => $ctrl->store(),
            'edit'    => $ctrl->edit(),
            'update'  => $ctrl->update(),
            'view'    => $ctrl->view(),
            'delete'  => $ctrl->delete(),
            'restore' => $ctrl->restore(),
            'trash'   => $ctrl->trash(),
            default   => $ctrl->index(),
        };
        break;

    // ── Classes ──────────────────────────────────────────
    case 'classes':
        $ctrl = new ClassController();
        match($action) {
            'store'  => $ctrl->store(),
            'update' => $ctrl->update(),
            'delete' => $ctrl->delete(),
            'all'    => $ctrl->getAll(),
            default  => $ctrl->index(),
        };
        break;

    // ── Academic Years ───────────────────────────────────
    case 'academic_years':
        $ctrl = new AcademicYearController();
        match($action) {
            'store'      => $ctrl->store(),
            'update'     => $ctrl->update(),
            'set_active' => $ctrl->setActive(),
            'delete'     => $ctrl->delete(),
            'all'        => $ctrl->getAll(),
            default      => $ctrl->index(),
        };
        break;

    // ── History ──────────────────────────────────────────
    case 'history':
        $ctrl = new HistoryController();
        match($action) {
            'promote'            => $ctrl->promote(),
            'transfer'           => $ctrl->transfer(),
            'repeat'             => $ctrl->repeat(),
            'get_student_history'=> $ctrl->getStudentHistory(),
            default              => $ctrl->index(),
        };
        break;

    default:
        redirect('?page=dashboard');
}
