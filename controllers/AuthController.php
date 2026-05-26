<?php
require_once '../models/User.php';

class AuthController {
    private $db;
    private $user;

    public function __construct($db) {
        $this->db = $db;
        $this->user = new User($db);
    }

    public function login() {
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            $username = $_POST['username'];
            $password = $_POST['password'];
            
            $user = $this->user->login($username, $password);
            
            if($user) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                $this->user->logActivity($user['id'], 'Login', 'User logged in successfully');
                header("Location: index.php?controller=dashboard&action=index");
                exit();
            } else {
                $error = "Invalid username or password!";
            }
        }
        
        require_once '../views/auth/login.php';
    }

    public function logout() {
        if(isset($_SESSION['user_id'])) {
            $this->user->logActivity($_SESSION['user_id'], 'Logout', 'User logged out');
        }
        session_destroy();
        header("Location: index.php?controller=auth&action=login");
        exit();
    }
}
?>