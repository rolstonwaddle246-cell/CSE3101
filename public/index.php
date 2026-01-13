<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../app/controllers/Controller.php';
require_once __DIR__ . '/../app/controllers/AuthController.php';
require_once __DIR__ . '/../app/controllers/AnnouncementController.php';
require_once __DIR__ . '/../app/controllers/SchoolYearController.php';
// echo "SMS database connected successfully.";

    $controller = new SchoolYearController();

    $action = $_GET['action'] ?? 'login';

    $authController = new AuthController();
    
    $announcementController = new AnnouncementController();

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
        // require_once __DIR__ . '/../app/views/admin_dashboard.php';
        // break;


        // ANNOUNCEMENT
            if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['post_announcement'])) {
                $announcementController->create();
            }
            $announcementController->index();
            break;

        case 'create_announcement':
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $announcementController->create();
            }
            break;

        case 'delete_announcement':
            if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
                $announcementController->delete($_POST['id']);
            }
            break;


        case 'teacher_dashboard':
            if (!isset($_SESSION['user_id'])) {
                header("Location: index.php?action=login");
                exit();
            }
            require_once __DIR__ . '/../app/views/teacher_dashboard.php';
            break;

        
        // SCHOOL YEARS
        case 'school_years':
        $controller->index();
        break;
    case 'store_school_year':
        $controller->store();
        break;
    case 'update_school_year':
        $controller->update();
        break;
    case 'delete_school_year':
        $controller->delete();
        break;


// DEFAULT CASE
        default:
            echo "Page not found";
            break;
    }


?>
