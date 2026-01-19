<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../app/controllers/Controller.php';
require_once __DIR__ . '/../app/controllers/AuthController.php';
require_once __DIR__ . '/../app/controllers/AnnouncementController.php';
require_once __DIR__ . '/../app/controllers/SchoolYearController.php';
require_once __DIR__ . '/../app/controllers/TermController.php';
require_once __DIR__ . '/../app/controllers/ReportCardController.php';

// echo "SMS database connected successfully.";

$action = $_GET['action'] ?? 'login';

$authController = new AuthController();
$announcementController = new AnnouncementController();
$schoolYearController = new SchoolYearController();
$termController = new TermController();
$reportCardController = new ReportCardController();

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
    $schoolYearController->index();
    break;
case 'store_school_year':
    $schoolYearController->store();
    break;
case 'update_school_year':
    $schoolYearController->update();
    break;
case 'delete_school_year':
    $schoolYearController->delete();
    break;

    // TERMS
case 'store_term':
    $termController->store();
    break;
case 'update_term':
    $termController->update();
    break;

case 'delete_term':
    $termController->delete();
    break;


// REPORTS

// student report card page
case 'student_report_card':
        $reportCardController->index();
        break;
case 'search_student':
    $reportCardController->searchStudent();
    break;
case 'get_school_years':
    $reportCardController->getSchoolYears();
    break;
case 'get_terms':
    $reportCardController->getTerms();
    break;
case 'generate_report':
    $reportCardController->generate();
    break;
case 'get_grading_system':
    $reportCardController->getGradingSystem();
    break;
case 'debug':
    $reportCardController->debug();
    break;

default:
        echo "Page not found";
        break;
}

?>
