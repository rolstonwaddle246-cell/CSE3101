<?php
require_once __DIR__ . '/Controller.php';
require_once __DIR__ . '/../models/ClassModel.php';
require_once __DIR__ . '/../models/Grade.php';

class ClassesController extends Controller
{
    private $classModel;

    public function __construct()
    {
        $this->classModel = new ClassModel();
    }

    // Show all classes
    public function index()
    {
        $gradeModel = new Grade();
        $classes = $this->classModel->getAll();
        $grades = $gradeModel->getAll();

        require_once __DIR__ . '/../views/classes.php';
    }

    // Create class
    public function store()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['success' => false, 'error' => 'Invalid request']);
            return;
        }

        $grade_id     = $_POST['grade_id'] ?? null;
        $class_name   = trim($_POST['class_name'] ?? '');
        $num_students = isset($_POST['num_students']) ? (int)$_POST['num_students'] : 0;

        if (!$grade_id || $class_name === '') {
            echo json_encode([
                'success' => false,
                'error'   => 'Grade and Class name are required'
            ]);
            return;
        }

        // Allows 6 grades per class max
        $classCount = $this->classModel->countByGrade($grade_id);

        if ($classCount >= 6) {
            echo json_encode([
                'success' => false,
                'error'   => 'A grade cannot have more than 6 classes'
            ]);
            return;
        }

        try {
            $this->classModel->create($grade_id, $class_name, $num_students);
            echo json_encode(['success' => true]);
        } catch (PDOException $e) {
            $msg = ($e->getCode() == 23000)
                ? 'Duplicate class name in this grade'
                : $e->getMessage();

            echo json_encode(['success' => false, 'error' => $msg]);
        }
    }

    // Update class
    public function update()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['success' => false, 'error' => 'Invalid request']);
            return;
        }

        $id           = $_POST['id'] ?? null;
        $grade_id     = $_POST['grade_id'] ?? null;
        $class_name   = trim($_POST['class_name'] ?? '');
        $num_students = isset($_POST['num_students']) ? (int)$_POST['num_students'] : 0;

        if (!$id || !$grade_id || $class_name === '') {
            echo json_encode([
                'success' => false,
                'error'   => 'Missing required fields'
            ]);
            return;
        }

        //If class is being changed recheck limit
        $currentClass = $this->classModel->getById($id);

        if ($currentClass && $currentClass['grade_id'] != $grade_id) {
            $classCount = $this->classModel->countByGrade($grade_id);

            if ($classCount >= 6) {
                echo json_encode([
                    'success' => false,
                    'error'   => 'A grade cannot have more than 6 classes'
                ]);
                return;
            }
        }

        try {
            $this->classModel->update($id, $grade_id, $class_name, $num_students);
            echo json_encode(['success' => true]);
        } catch (PDOException $e) {
            $msg = ($e->getCode() == 23000)
                ? 'Duplicate class name in this grade'
                : $e->getMessage();

            echo json_encode(['success' => false, 'error' => $msg]);
        }
    }

    // Delete class
    public function delete()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['success' => false, 'error' => 'Invalid request']);
            return;
        }

        $id = $_POST['id'] ?? null;

        if (!$id) {
            echo json_encode([
                'success' => false,
                'error'   => 'Missing ID'
            ]);
            return;
        }

        try {
            $this->classModel->delete($id);
            echo json_encode(['success' => true]);
        } catch (Exception $e) {
            echo json_encode([
                'success' => false,
                'error'   => $e->getMessage()
            ]);
        }
    }

    // Get class by ID
    public function getById()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
            echo json_encode(['success' => false, 'error' => 'Invalid request']);
            return;
        }

        $id = $_GET['id'] ?? null;

        if (!$id) {
            echo json_encode([
                'success' => false,
                'error'   => 'Missing ID'
            ]);
            return;
        }

        $class = $this->classModel->getById($id);
        echo json_encode(['success' => true, 'data' => $class]);
    }
}
