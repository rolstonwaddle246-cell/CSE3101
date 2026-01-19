<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if (session_status() === PHP_SESSION_NONE) session_start();

// Controllers
require_once __DIR__ . '/../app/controllers/Controller.php';
require_once __DIR__ . '/../app/controllers/AssignTeachersController.php';
require_once __DIR__ . '/../app/controllers/GradesController.php';
require_once __DIR__ . '/../app/controllers/ClassesController.php';
require_once __DIR__ . '/../app/controllers/SubjectController.php';

// Instantiate controllers
$assignteachersController = new AssignTeachersController();
$gradesController         = new GradesController();
$classesController        = new ClassesController();
$subjectController        = new SubjectController();

// GET Action - default to 'classes' if none provided
$action = $_GET['action'] ?? 'classes';

// Router
switch ($action) {

    // ASSIGN TEACHERS
    case 'assign_teachers':
        $assignteachersController->index();
        break;

    case 'store_assign_teachers':
        $assignteachersController->store();
        break;

    case 'update_assign_teachers':
        $assignteachersController->update();
        break;

    case 'delete_assign_teachers':
        $assignteachersController->delete();
        break;

    // GRADES
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

    // CLASSES
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

    // SUBJECTS
    case 'subject':
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

    default:
        http_response_code(404);
        echo "<h1>Page not found</h1>";
         echo "<p>The page you requested does not exist.</p>";
        exit;
}