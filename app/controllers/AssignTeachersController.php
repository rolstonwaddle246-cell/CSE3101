<?php
require_once __DIR__ . '/../models/AssignTeachers.php';
require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../models/Grade.php';
require_once __DIR__ . '/../models/ClassModel.php';
require_once __DIR__ . '/Controller.php';

class AssignTeachersController extends Controller {

    private $assignTeachersModel;
    private $userModel;
    private $gradeModel;
    private $classModel;

    public function __construct() {
        $this->assignTeachersModel = new AssignTeachers();
        $this->userModel = new User();
        $this->gradeModel = new Grade();
        $this->classModel = new ClassModel(); 
    }

    // Display all assigned teachers
    public function index() {
        $assignments = $this->assignTeachersModel->getAll();
        $teachers = $this->userModel->getAllTeachers(); 
        $grades = $this->gradeModel->getAll();
        $classes = $this->classModel->getAll();

        $columns = ['ID', 'Name', 'Class', 'Grade', 'Actions'];

        $this->view('assign_teachers', [
            'assignments' => $assignments,
            'teachers' => $teachers,
            'grades' => $grades,
            'classes' => $classes,
            'columns' => $columns,
            'title' => 'Assign Teachers'
        ]);
    }

    // Create a new assignment
    public function store() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $teacher_id = $_POST['teacher_id'] ?? null;
            $class_id = $_POST['class_id'] ?? null;
            $grade_id = $_POST['grade_id'] ?? null;

            if (!$teacher_id || !$class_id || !$grade_id) {
                echo json_encode(['success' => false, 'error' => 'Missing required fields']);
                return;
            }

            $this->assignTeachersModel->create($teacher_id, $class_id, $grade_id);
            echo json_encode(['success' => true]);
        }
    }

    // Update an assignment
    public function update() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'] ?? null;
            $teacher_id = $_POST['teacher_id'] ?? null;
            $class_id = $_POST['class_id'] ?? null;
            $grade_id = $_POST['grade_id'] ?? null;

            if (!$id || !$teacher_id || !$class_id || !$grade_id) {
                echo json_encode(['success' => false, 'error' => 'Missing required fields']);
                return;
            }

            $this->assignTeachersModel->update($id, $teacher_id, $class_id, $grade_id);
            echo json_encode(['success' => true]);
        }
    }

    // Delete an assignment
    public function delete() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'] ?? null;

            if (!$id) {
                echo json_encode(['success' => false, 'error' => 'Missing ID']);
                return;
            }

            $this->assignTeachersModel->delete($id);
            echo json_encode(['success' => true]);
        }
    }

    // fetch a single assignment by ID
    public function getById() {
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            $id = $_GET['id'] ?? null;
            if (!$id) {
                echo json_encode(['success' => false, 'error' => 'Missing ID']);
                return;
            }

            $assignment = $this->assignTeachersModel->getById($id);
            echo json_encode(['success' => true, 'data' => $assignment]);
        }
    }
}
?>
