<?php
class AuthController {
    private PDO $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    public function showLogin(): void {
        if (isset($_SESSION['admin_id'])) {
            redirect('?page=dashboard');
        }
        require_once APP . '/views/auth/login.php';
    }

    public function login(): void {
        $username = trim($_POST['username'] ?? '');
        $password = trim($_POST['password'] ?? '');

        if (empty($username) || empty($password)) {
            $_SESSION['error'] = 'Please enter username and password.';
            redirect('?page=login');
            return;
        }

        $stmt = $this->db->prepare("SELECT * FROM admins WHERE username = ? LIMIT 1");
        $stmt->execute([$username]);
        $admin = $stmt->fetch();

        if ($admin && password_verify($password, $admin['password'])) {
            $_SESSION['admin_id']   = $admin['id'];
            $_SESSION['admin_name'] = $admin['full_name'];
            $_SESSION['admin_user'] = $admin['username'];

            $log = new ActivityLogModel();
            $log->log($admin['id'], 'LOGIN', 'Admin logged in', 'admin', $admin['id']);

            redirect('?page=dashboard');
        } else {
            $_SESSION['error'] = 'Invalid username or password.';
            redirect('?page=login');
        }
    }

    public function logout(): void {
        if (isset($_SESSION['admin_id'])) {
            $log = new ActivityLogModel();
            $log->log($_SESSION['admin_id'], 'LOGOUT', 'Admin logged out', 'admin', $_SESSION['admin_id']);
        }
        session_destroy();
        redirect('?page=login');
    }
}
