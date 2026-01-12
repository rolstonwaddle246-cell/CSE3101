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
            $error = '';
            if ($_SERVER['REQUEST_METHOD'] === 'POST' ) {
                $username = trim($_POST['username']);
                $password = $_POST['password'];

                $user = $this->userModel->getByUsername($username);

                if ($user && password_verify($password, $user['password'])) {
                    $_SESSION['user_id'] = $user['user_id'];
                    $_SESSION['username'] = $user['username'];
                    $_SESSION['role_name'] = $user['role_name'];

                    if ($user['must_reset_password'] == 1) {
                        header("Location: index.php?action=reset");
                        exit();

                    } else {
                        switch($user['role_name']) {
                            case 'admin':
                                header("Location: index.php?action=admin_dashboard");
                                exit();
                            case 'teacher':
                                header("Location: index.php?action=teacher_dashboard");
                                exit();
                            default:
                                header("Location: index.php?action=login");
                                exit();
                    }
                    }
                } else {
                    $error = "Invalid username or password";
                }
            }

            // Load view
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

                    $roleName = $_SESSION['role_name'];
                    switch($roleName) {
                        case 'admin':
                            header("Location: index.php?action=admin_dashboard");
                            exit();
                        case 'teacher':
                            header("Location: index.php?action=teacher_dashboard");
                            exit();
                        default:
                            header("Location: index.php?action=login");
                            exit();
                    }
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