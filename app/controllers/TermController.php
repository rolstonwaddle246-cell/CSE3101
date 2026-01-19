<?php

require_once __DIR__ . '/../models/Term.php';
require_once __DIR__ . '/Controller.php';



class TermController extends Controller {

    private $model;

    public function __construct() {
        $this->model = new Term();
    }

    // List terms for a specific school year (optional)
    public function index($schoolYearId = null) {
        $terms = [];

        if ($schoolYearId) {
            $terms = $this->model->getBySchoolYear($schoolYearId);
        } else {
            $terms = $this->model->getAll($schoolYearId);
        }

        $this->view('terms', [
            'terms' => $terms,
            'schoolYearId' => $schoolYearId
        ]);
    }

    // Store new term
    public function store() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $term_name = $_POST['term_name'] ?? '';
            $start_date = $_POST['start_date'] ?? '';
            $end_date = $_POST['end_date'] ?? '';
            $status = $_POST['status'] ?? '';
            $school_year_id = $_POST['school_year_id'] ?? '';

            if (!$term_name || !$start_date || !$end_date || !$school_year_id) {
                echo json_encode(['success' => false, 'error' => 'Missing required fields']);
                return;
            }

            $newId = $this->model->create($term_name, $start_date, $end_date, $status, $school_year_id);

            echo json_encode(['success' => true, 'id' => $newId]);
        }
    }

    // Update existing term
    public function update() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'] ?? 'null';
            $term_name = $_POST['term_name'] ?? '';
            $start_date = $_POST['start_date'] ?? '';
            $end_date = $_POST['end_date'] ?? '';
            $status = $_POST['status'] ?? '';

            if (!$id || !$term_name || !$start_date || !$end_date) {
                echo json_encode(['success' => false, 'error' => 'Missing required fields']);
                return;
            }

            $this->model->update($id, $term_name, $start_date, $end_date, $status);
            echo json_encode(['success' => true]);
        }
    }

    // Delete term
    public function delete() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'] ?? '';

            if (!$id) {
                echo json_encode(['success' => false, 'error' => 'ID is required']);
                return;
            }

            $this->model->delete($id);
            echo json_encode(['success' => true]);
        }
    }


}

?>
