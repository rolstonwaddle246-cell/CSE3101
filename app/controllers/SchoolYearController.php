<?php
    require_once __DIR__ . '/../models/SchoolYear.php';
    require_once __DIR__ . '/Controller.php';

    class SchoolYearController extends Controller {

    private $model;

    public function __construct() {
        $this->model = new SchoolYear();
    }

    public function index() {
        $schoolYears = $this->model->getAll();
        // require __DIR__ . '/../views/school_years.php';
        $this->view('school_years', ['schoolYears' => $schoolYears]);
    }

    public function store() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $school_year = $_POST['school_year'];
            $status = $_POST['status'];

            $this->model->create($school_year, $status);
        }
    }

    public function update() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'];
            $school_year = $_POST['school_year'];
            $status = $_POST['status'];
            $this->model->update($id, $school_year, $status);
        }
    }

    public function delete() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'];
            $this->model->delete($id);
        }
    }
}
?>