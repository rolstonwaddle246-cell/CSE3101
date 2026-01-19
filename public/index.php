<?php
// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);


if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../app/controllers/Controller.php';
require_once __DIR__ . '/../app/controllers/AuthController.php';
require_once __DIR__ . '/../app/controllers/AnnouncementController.php';
require_once __DIR__ . '/../app/controllers/SchoolYearController.php';
require_once __DIR__ . '/../app/controllers/TermController.php';
require_once __DIR__ . '/../app/controllers/ReportCardController.php';
require_once __DIR__ . '/../app/controllers/AveragePerformanceController.php';
require_once __DIR__ . '/../app/controllers/SettingController.php';
require_once __DIR__ . '/../app/controllers/SyllabusProgressController.php';
require_once __DIR__ . '/../app/controllers/UserController.php';
require_once __DIR__ . '/../app/controllers/GradesController.php';
require_once __DIR__ . '/../app/controllers/ClassesController.php';
require_once __DIR__ . '/../app/controllers/SubjectController.php';
require_once __DIR__ . '/../app/controllers/AssignTeachersController.php';
require_once __DIR__ . '/../app/controllers/ScoreController.php';

// Handle AJAX inline edit first
if (isset($_GET['action']) && $_GET['action'] === 'update_setting') {
    $data = json_decode(file_get_contents('php://input'), true);
    if (isset($data['key'], $data['value'])) {
        $settingController = new SettingController();
        $settingController->update($data['key'], $data['value']); // echoes JSON & exits
    } else {
        echo json_encode(['success' => false]);
    }
    exit;
}

// Handle AJAX for slider updates
if (isset($_GET['action']) && $_GET['action'] === 'update_syllabus') {
    $data = json_decode(file_get_contents('php://input'), true);
    if (isset($data['subject'], $data['value'])) {
        $controller = new SyllabusProgressController();
        $controller->update($data['subject'], $data['value']);
    } else {
        echo json_encode(['success' => false]);
    }
    exit;
}

// echo "SMS database connected successfully.";

    $controller = new SchoolYearController();

    $action = $_GET['action'] ?? 'login';

$authController = new AuthController();
$announcementController = new AnnouncementController();
$schoolYearController = new SchoolYearController();
$termController = new TermController();
$reportCardController = new ReportCardController();
$averagePerformanceController = new AveragePerformanceController();
$userController = new UserController();
$gradesController = new GradesController();
$classesController = new ClassesController();
$subjectController = new SubjectController();
$assignTeachersController = new AssignTeachersController();
$scoreController = new ScoreController();

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

// HANDLE EDITABLE CARDS
// case 'update_setting':
//     $data = json_decode(file_get_contents('php://input'), true);
//     if (isset($data['key'], $data['value'])) {
//         $settingController = new SettingController();
//         $settingController->update($data['key'], $data['value']); // echoes JSON & exits
//     } else {
//         echo json_encode(['success' => false]);
//     }
//     exit;


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


    // USERS / MANAGE USERS
case 'manage_users':  // list all users
    if (!isset($_SESSION['user_id'])) {
        header("Location: index.php?action=login");
        exit();
    }
    $userController->listUsers();
    break;
case 'create_user':
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $userController->createUser();
    }
    break;
case 'get_user_json':
    if (!isset($_GET['user_id'])) {
        echo json_encode(['error' => 'User ID missing']);
        exit;
    }
    $userId = (int)$_GET['user_id'];
    $user = $userController->fetchUserById($userId);
    echo json_encode($user);
    exit;
case 'update_user':
    $userController->updateUser();
    break;
case 'delete_user':  // handle deletion
    if (!isset($_SESSION['user_id'])) {
        header("Location: index.php?action=login");
        exit();
    }
    $userController->deleteUser();
    break;
case 'reset_password':  
    if (!isset($_SESSION['user_id'])) {
        header("Location: index.php?action=login");
        exit();
    }
    $userController->resetPassword();
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
case 'fetch_terms':
    $termModel = new Term();
    $school_year_id = $_GET['school_year'] ?? 0;
    $terms = $termModel->getTermsBySchoolYear($school_year_id);
    echo json_encode($terms);
    exit();


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


// average performance page
case 'average_performance':
    $averagePerformanceController->index();
    break;
case 'average_performance_data':
    $averagePerformanceController->fetchData();
    break;

// GRADES MANAGEMENT
case 'grades':
    $gradesController->index();
    break;
case 'store_grade':
    $gradesController->store();
    break;
case 'update_grade':
    $gradesController->update();
    break;
case 'delete_grade':
    $gradesController->delete();
    break;

// CLASSES MANAGEMENT
case 'classes':
    $classesController->index();
    break;
case 'store_class':
    $classesController->store();
    break;
case 'update_class':
    $classesController->update();
    break;
case 'delete_class':
    $classesController->delete();
    break;

// SUBJECTS MANAGEMENT
case 'subjects':
    $subjectController->index();
    break;
case 'store_subject':
    $subjectController->store();
    break;
case 'update_subject':
    $subjectController->update();
    break;
case 'delete_subject':
    $subjectController->delete();
    break;

// TEACHER ASSIGNMENT
case 'assign_teachers':
    $assignTeachersController->index();
    break;
case 'store_assign_teacher':
    $assignTeachersController->store();
    break;
case 'update_assign_teacher':
    $assignTeachersController->update();
    break;
case 'delete_assign_teacher':
    $assignTeachersController->delete();
    break;

// SCORE MANAGEMENT
case 'score_entry':
    $scoreController->entryForm();
    break;
case 'store_score':
    $scoreController->store();
    break;
case 'view_scores':
    $scoreController->viewScores();
    break;
case 'edit_score':
    $scoreController->edit();
    break;
case 'update_score':
    $scoreController->update();
    break;
case 'delete_score':
    $scoreController->delete();
    break;
case 'get_student_scores':
    $scoreController->getStudentScores();
    break;
case 'get_class_averages':
    $scoreController->getClassAverages();
    break;

default:
    if (!isset($_SESSION['user_id'])) {
        header("Location: index.php?action=login");
        exit();
    }

    http_response_code(404);
    require_once __DIR__ . '/../app/views/errors/404.php';
    exit();
}

?>
