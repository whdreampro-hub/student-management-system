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
        $usernameInput = $_POST['username'] ?? '';
        $password = $_POST['password'] ?? '';
        $username = is_string($usernameInput) ? trim($usernameInput) : '';

        // Preserve the exact password bytes; leading/trailing spaces may be intentional.
        if ($username === '' || !is_string($password) || $password === '') {
            $_SESSION['error'] = 'Please enter username and password.';
            redirect('?page=login');
            return;
        }

        $stmt = $this->db->prepare("SELECT * FROM admins WHERE username = ? LIMIT 1");
        $stmt->execute([$username]);
        $admin = $stmt->fetch();

        if ($admin && password_verify($password, $admin['password'])) {
            // Prevent an attacker from reusing the pre-authentication session ID.
            session_regenerate_id(true);

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
        // Remove all session data and expire the browser's session cookie.
        $_SESSION = [];
        if (ini_get('session.use_cookies')) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', [
                'expires'  => time() - 42000,
                'path'     => $params['path'],
                'domain'   => $params['domain'],
                'secure'   => $params['secure'],
                'httponly' => $params['httponly'],
                'samesite' => $params['samesite'] ?? 'Lax',
            ]);
        }
        session_destroy();
        redirect('?page=login');
    }
}
