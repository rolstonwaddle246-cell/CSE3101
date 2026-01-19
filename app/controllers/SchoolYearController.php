<?php
    require_once __DIR__ . '/../models/SchoolYear.php';
    require_once __DIR__ . '/../models/Term.php';
    require_once __DIR__ . '/Controller.php';

    class SchoolYearController extends Controller {

    private $model;

    public function __construct() {
        $this->model = new SchoolYear();
    }

    public function index() {
        $schoolYears = $this->model->getAll();

        //select year
        $selectedYearId = isset($_GET['year_id']) ? (int)$_GET['year_id'] : null;
        $selectedYear = null;

        $terms = [];
        if ($selectedYearId) {
            $selectedYear = $this->model->getById($selectedYearId);

            $termModel = new Term();
            $terms = $termModel->getBySchoolYear($selectedYearId);
        }

        // require __DIR__ . '/../views/school_years.php';
        $this->view('school_years', [
            'schoolYears' => $schoolYears,
            'selectedYearId' => $selectedYearId,
            'selectedYear' => $selectedYear,
            'terms' => $terms
        ]);

    }

    public function store() {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $school_year = $_POST['school_year'] ?? null;
        $status = $_POST['status'] ?? null;

        $newId = $this->model->create($school_year, $status); 
        header('Content-Type: application/json');
        echo json_encode(['id' => $newId]);
    }
}

    public function update() {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $id = $_POST['id'] ?? null;
        $school_year = $_POST['school_year'] ?? null;
        $status = $_POST['status'] ?? null;

        if (!$id || !$school_year || !$status) {
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'error' => 'Missing required fields']);
            return;
        }

        $updated = $this->model->update($id, $school_year, $status);
        if ($updated) {
            echo json_encode([
                'success' => true,
                'id' => $id,
                'school_year' => $school_year,
                'status' => $status
            ]);
        } else {
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'error' => 'Update failed']);
        }
    }
}

    public function delete() {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $id = $_POST['id'] ?? null;

        if (!$id) {
            echo 'error';
            return;
        }

        $this->model->delete($id);
        echo 'success';
        }
    }
}
?>