<?php



if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../app/controllers/Controller.php';
require_once __DIR__ . '/../app/controllers/AuthController.php';
// echo "SMS database connected successfully.";

    $action = $_GET['action'] ?? 'login';
    $authController = new AuthController();

    switch ($action) {
        case 'login':
            $authController->login();
            break;

        case 'reset':
            $authController->resetPassword();
            break;

        case 'logout':
            $authController->logout();
            break;

        case 'admin_dashboard':
        if (!isset($_SESSION['user_id'])) {
            header("Location: index.php?action=login");
            exit();
        }
        require_once __DIR__ . '/../app/views/admin_dashboard.php';
        break;

        case 'teacher_dashboard':
            if (!isset($_SESSION['user_id'])) {
                header("Location: index.php?action=login");
                exit();
            }
            require_once __DIR__ . '/../app/views/teacher_dashboard.php';
            break;

        default:
            echo "Page not found";
            break;
    }
?>
