<?php
    require_once __DIR__ . '/../models/User.php';

    class AuthController {
        private $userModel;

        public function __construct() {
            $this->userModel = new User();
            if(session_status() === PHP_SESSION_NONE){
                session_start();
            }
        }

        // Login page
public function login() {
    // Start session if not already started
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    // If already logged in, redirect based on role
    if (!empty($_SESSION['user_id'])) {
        $role = strtolower($_SESSION['role_name'] ?? '');
        if ($role === 'admin') {
            header("Location: index.php?action=admin_dashboard");
            exit();
        } elseif ($role === 'teacher') {
            header("Location: index.php?action=teacher_dashboard");
            exit();
        } else {
            // Unknown role, clear session and go back to login
            session_unset();
            session_destroy();
            header("Location: index.php?action=login");
            exit();
        }
    }

    $error = '';

    // Handle login POST request
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $username = trim($_POST['username'] ?? '');
        $password = $_POST['password'] ?? '';

        if ($username === '' || $password === '') {
            $error = "Please enter username and password.";
        } else {
            $user = $this->userModel->getByUsername($username);

            if ($user && password_verify($password, $user['password'])) {
                // Regenerate session ID for security
                session_regenerate_id(true);

                // Store user info in session
                $_SESSION['user_id']   = $user['user_id'];
                $_SESSION['username']  = $user['username'];
                $_SESSION['role_name'] = $user['role_name'];
                $_SESSION['first_name'] = $user['first_name'] ?? '';
                $_SESSION['last_name']  = $user['last_name'] ?? '';

                // Force password reset if required
                if ((int)$user['must_reset_password'] === 1) {
                    header("Location: index.php?action=reset");
                    exit();
                }

                // Redirect based on role
                $role = strtolower($user['role_name']);
                if ($role === 'admin') {
                    header("Location: index.php?action=admin_dashboard");
                    exit();
                } elseif ($role === 'teacher') {
                    header("Location: index.php?action=teacher_dashboard");
                    exit();
                } else {
                    // Unknown role fallback
                    session_unset();
                    session_destroy();
                    $error = "Your account role is invalid. Contact admin.";
                }
            } else {
                $error = "Invalid username or password.";
            }
        }
    }

    // Load login view
    require_once __DIR__ . '/../views/auth/login.php';
}

        // Reset password page 
        public function resetPassword() {
    if (!isset($_SESSION['user_id'])) {
        header("Location: index.php?action=login");
        exit();
    }

    $error = '';

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $newPassword = $_POST['new_password'];
        $confirmPassword = $_POST['confirm_password'];

        if ($newPassword === $confirmPassword) {
            $hashed = password_hash($newPassword, PASSWORD_BCRYPT);

            $this->userModel->updatePassword($_SESSION['user_id'], $hashed);

            // Force re-login
            session_unset();
            session_destroy();

            header("Location: index.php?action=login&reset=success");
            exit();
        } else {
            $error = "Passwords do not match";
        }
    }

    require_once __DIR__ . '/../views/auth/reset_password.php';
}

        // Logout
        public function logout() {
            session_unset();
            session_destroy();

            header("Location: index.php?action=login");
            exit();
        }
    }
?>