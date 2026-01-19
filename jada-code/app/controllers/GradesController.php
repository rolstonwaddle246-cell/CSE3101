<?php
require_once __DIR__ . '/../models/Grade.php';
require_once __DIR__ . '/Controller.php';

class GradesController extends Controller
{
    private $gradeModel;

    public function __construct()
    {
        $this->gradeModel = new Grade();
    }

    // Show all grades
    public function index()
    {
        $grades = $this->gradeModel->getAll();

        $this->view('grade', [
            'grades' => $grades
        ]);
    }

    // Create a new grade (MAX 6)
    public function store()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') return;

        header('Content-Type: application/json');

        $grade_name = trim($_POST['grade_name'] ?? '');
        $number_of_classes = (int)($_POST['number_of_classes'] ?? 0);

        if ($grade_name === '') {
            echo json_encode([
                'success' => false,
                'error' => 'Grade name is required'
            ]);
            exit;
        }

        try {
            $result = $this->gradeModel->create($grade_name, $number_of_classes);

            if ($result === false) {
                echo json_encode([
                    'success' => false,
                    'error' => 'Only 6 grades are allowed'
                ]);
                exit;
            }

            echo json_encode(['success' => true]);
            exit;

        } catch (PDOException $e) {
            echo json_encode([
                'success' => false,
                'error' => 'Database error'
            ]);
            exit;
        }
    }

    // Update an existing grade
    public function update()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') return;

        header('Content-Type: application/json');

        $id = (int)($_POST['id'] ?? 0);
        $grade_name = trim($_POST['grade_name'] ?? '');
        $number_of_classes = (int)($_POST['number_of_classes'] ?? 0);

        if ($id <= 0 || $grade_name === '') {
            echo json_encode([
                'success' => false,
                'error' => 'Missing required fields'
            ]);
            exit;
        }

        try {
            $this->gradeModel->update($id, $grade_name, $number_of_classes);
            echo json_encode(['success' => true]);
            exit;

        } catch (PDOException $e) {
            echo json_encode([
                'success' => false,
                'error' => 'Database error'
            ]);
            exit;
        }
    }

    // Delete a grade
    public function delete()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') return;

        header('Content-Type: application/json');

        $id = (int)($_POST['id'] ?? 0);

        if ($id <= 0) {
            echo json_encode([
                'success' => false,
                'error' => 'Missing ID'
            ]);
            exit;
        }

        try {
            $this->gradeModel->delete($id);
            echo json_encode(['success' => true]);
            exit;

        } catch (PDOException $e) {
            echo json_encode([
                'success' => false,
                'error' => 'Database error'
            ]);
            exit;
        }
    }

    // Fetch a single grade
    public function getById()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'GET') return;

        header('Content-Type: application/json');

        $id = (int)($_GET['id'] ?? 0);

        if ($id <= 0) {
            echo json_encode([
                'success' => false,
                'error' => 'Missing ID'
            ]);
            exit;
        }

        $grade = $this->gradeModel->getById($id);

        echo json_encode([
            'success' => true,
            'data' => $grade
        ]);
        exit;
    }
}
