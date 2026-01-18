<?php
require_once __DIR__ . '/../models/AveragePerformance.php';
require_once __DIR__ . '/Controller.php';

class AveragePerformanceController extends Controller {
    private $model;

    public function __construct() {
        $this->model = new AveragePerformance();
    }

    // Main page
    public function index() {
        $school_years = $this->model->getAllSchoolYears();
        $grades = $this->model->getAllGrades();
        $subjects = $this->model->getAllSubjects();
        $terms = [];

        $filters = [
            'school_year' => $_GET['school_year'] ?? '',
            'term' => $_GET['term'] ?? '',
            'grade' => $_GET['grade'] ?? '',
            'subject' => $_GET['subject'] ?? ''
        ];

        if ($filters['school_year']) {
            $terms = $this->model->getTermsBySchoolYear($filters['school_year']);
        }

        $this->view('reports/average_performance', [
            'school_years' => $school_years,
            'grades' => $grades,
            'subjects' => $subjects,
            'terms' => $terms,
            'filters' => $filters,
            'results' => [],
            'case' => 1
        ]);
    }

    // AJAX fetch data
    public function fetchData() {
        $filters = [
            'school_year' => $_GET['school_year'] ?? null,
            'term' => $_GET['term'] ?? null,
            'grade' => $_GET['grade'] ?? null,
            'subject' => $_GET['subject'] ?? null
        ];
        $data = $this->model->fetchResults($filters);
        echo json_encode($data);
    }
}
