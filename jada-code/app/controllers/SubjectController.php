<?php

require_once __DIR__ . '/Controller.php';
require_once __DIR__ . '/../models/Subject.php';
require_once __DIR__ . '/../models/Grade.php';

class SubjectController extends Controller
{
    private $subjectModel;
    private $gradeModel;

    public function __construct()
    {
        $this->subjectModel = new Subject();
        $this->gradeModel   = new Grade();
    }

    
    public function index()
    {
        $subjects = $this->subjectModel->getAll();
        $grades   = $this->gradeModel->getAll();

        require_once __DIR__ . '/../views/subject.php';
    }

    // Store subject
    public function store()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['success' => false, 'error' => 'Invalid request']);
            return;
        }

        $subjectName   = trim($_POST['subject_name'] ?? '');
        $gradeId       = $_POST['grade_id'] ?? '';
        $numberOfClass = $_POST['number_of_class'] ?? '';

        // Validation
        if (
            $subjectName === '' ||
            $gradeId === '' ||
            $numberOfClass === ''
        ) {
            echo json_encode([
                'success' => false,
                'error'   => 'All fields are required'
            ]);
            return;
        }

        if (!is_numeric($numberOfClass) || $numberOfClass < 1 || $numberOfClass > 6) {
            echo json_encode([
                'success' => false,
                'error'   => 'Number of classes must be between 1 and 6'
            ]);
            return;
        }


        $saved = $this->subjectModel->create([
            'subject_name'    => $subjectName,
            'grade_id'        => $gradeId,
            'number_of_class' => (int)$numberOfClass
        ]);

        echo json_encode(['success' => $saved]);
    }

    // Update subject
    public function update()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['success' => false, 'error' => 'Invalid request']);
            return;
        }

        $id             = $_POST['id'] ?? '';
        $subjectName    = trim($_POST['subject_name'] ?? '');
        $gradeId        = $_POST['grade_id'] ?? '';
        $numberOfClass  = $_POST['number_of_class'] ?? '';

        if (
            $id === '' ||
            $subjectName === '' ||
            $gradeId === '' ||
            $numberOfClass === ''
        ) {
            echo json_encode([
                'success' => false,
                'error'   => 'All fields are required'
            ]);
            return;
        }

        if (!is_numeric($numberOfClass) || $numberOfClass < 1 || $numberOfClass > 6) {
            echo json_encode([
                'success' => false,
                'error'   => 'Number of classes must be between 1 and 6'
            ]);
            return;
        }

        $updated = $this->subjectModel->update([
            'id'               => $id,
            'subject_name'     => $subjectName,
            'grade_id'         => $gradeId,
            'number_of_class'  => (int)$numberOfClass
        ]);

        echo json_encode(['success' => $updated]);
    }

    // Delete Subject
    public function delete()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['success' => false, 'error' => 'Invalid request']);
            return;
        }

        $id = $_POST['id'] ?? '';

        if ($id === '') {
            echo json_encode([
                'success' => false,
                'error'   => 'ID is required'
            ]);
            return;
        }

        $deleted = $this->subjectModel->delete($id);

        echo json_encode(['success' => $deleted]);
    }
}
