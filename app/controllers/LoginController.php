<?php
class LoginController {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function index() {
        // If already logged in, redirect to dashboard
        if (isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true) {
            header('Location: /student-management/public/index.php?controller=dashboard&action=index');
            exit;
        }

        // Display login form
        require_once __DIR__ . '/../views/login.php';
    }

    public function login() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = $_POST['username'] ?? '';
            $password = $_POST['password'] ?? '';

            // Prepare statement to prevent SQL injection
            $stmt = $this->pdo->prepare("SELECT * FROM admin_users WHERE username = :username");
            $stmt->execute(['username' => $username]);
            $admin = $stmt->fetch();

            // In a real application, use password_verify. For simplicity, we compare directly.
            // NOTE: This is for demonstration only. In production, use password_hash and password_verify.
            if ($admin && $password === $admin['password']) {
                $_SESSION['admin_logged_in'] = true;
                $_SESSION['admin_id'] = $admin['id'];
                setFlash('success', 'Login successful!');
                header('Location: /student-management/public/index.php?controller=dashboard&action=index');
                exit;
            } else {
                setFlash('error', 'Invalid username or password!');
                header('Location: /student-management/public/index.php?controller=login&action=index');
                exit;
            }
        } else {
            // If not POST, redirect to login form
            header('Location: /student-management/public/index.php?controller=login&action=index');
            exit;
        }
    }

    public function logout() {
        $_SESSION = [];
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000,
                $params["path"], $params["domain"],
                $params["secure"], $params["httponly"]
            );
        }
        session_destroy();
        setFlash('success', 'You have been logged out.');
        header('Location: /student-management/public/index.php?controller=login&action=index');
        exit;
    }
}