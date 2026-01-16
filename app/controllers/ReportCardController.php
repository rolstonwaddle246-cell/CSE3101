<?php
require_once __DIR__ . '/Controller.php';
require_once __DIR__ . '/../models/Student.php';
require_once __DIR__ . '/../models/ReportCard.php';
require_once __DIR__ . '/../models/ReportCardDetail.php';
require_once __DIR__ . '/../models/SchoolYear.php';
require_once __DIR__ . '/../models/Term.php';
require_once __DIR__ . '/../models/GradingSystem.php';

class ReportCardController extends Controller {
    private $studentModel;
    private $reportCardModel;
    private $reportCardDetailModel;
    private $schoolYearModel;
    private $termModel;
    private $gradingModel;

    public function __construct() {
        // Debug: check if Student class exists
        if (!class_exists('Student')) die('Student class not found');
        if (!class_exists('ReportCard')) die('ReportCard class not found');
        if (!class_exists('ReportCardDetail')) die('ReportCardDetail class not found');

        $this->studentModel = new Student();
        $this->reportCardModel = new ReportCard();
        $this->reportCardDetailModel = new ReportCardDetail();
        $this->schoolYearModel = new SchoolYear();
        $this->termModel = new Term();
        $this->gradingModel = new GradingSystem();
    }

    // Show the student report card page
    public function index() {
        // Load the view (empty for now, will populate filters via AJAX)
        $this->view('reports/student_report_card');
    }

    // Search students by keyword (AJAX endpoint)
    public function searchStudent() {
        $keyword = $_GET['q'] ?? '';
        if (empty($keyword)) {
            echo json_encode([]);
            return;
        }
        $results = $this->studentModel->searchStudents($keyword);

        header('Content-Type: application/json');
        echo json_encode($results);
        exit;
        // debug: http://localhost/CSE3101/index.php?action=search_student&q=John
    }

    // REPORT
    public function getSchoolYears() {
        $schoolYears = $this->schoolYearModel->getAllSchoolYears();

        header('Content-Type: application/json');
        echo json_encode($schoolYears);
        exit;
}

    public function getTerms() {
    $school_year_id = $_GET['school_year_id'] ?? null;

    if (!$school_year_id) {
        echo json_encode([]);
        exit;
    }

    $terms = $this->termModel->getTermsBySchoolYear($school_year_id);

    header('Content-Type: application/json');
    echo json_encode($terms);
    exit;
}

    // Generate report card for selected student, year, term
    public function generate() {
        error_log('Generate called');

        $student_id = $_POST['student_id'] ?? null;
        $school_year_id = $_POST['school_year_id'] ?? null;
        $term_id = $_POST['term_id'] ?? null;

        // Debug: print input
        // echo "Received: student_id=$student_id, school_year_id=$school_year_id, term_id=$term_id";

        if (!$student_id || !$school_year_id || !$term_id) {
            http_response_code(400);
            echo json_encode(['error' => 'Missing parameters']);
            exit;
        }

        $student = $this->studentModel->getStudentById($student_id);
        
        $reportCard = $this->reportCardModel->getReportCard($student_id, $school_year_id, $term_id);
        if (!$reportCard) {
            http_response_code(404);
            echo json_encode(['error' => 'Report card not found']);
            exit;
        }

        // Get total pupils in class
        $totalPupils = $this->studentModel->countByClassId($reportCard['class_id'] ?? 0);

        // Get student rank in class
        $rank = $this->reportCardModel->getStudentRank(
            $student_id,
            $student['class_id'],   // use student's class
            $term_id,
            $school_year_id
        );
        // If only one student, rank is 1
        if ($totalPupils == 1) {
            $rank = 1;
        }
        $reportCard['rank'] = $rank;
        error_log('Rank: ' . $rank);

        $reportDetails = $this->reportCardDetailModel->getDetailsByReportId($reportCard['report_id'] ?? 0);
        
        $totals = $this->reportCardDetailModel->getTotalsByReportId($reportCard['report_id']);
        // ================= OVERALL PERCENTAGE =================
        $totalMarks = $totals['total_marks'] ?? 0;
        $totalObtained = $totals['total_marks_obtained'] ?? 0;
        $overallPercentage = null;
        if ($totalMarks > 0) {
            $overallPercentage = round(
                ($totalObtained / $totalMarks) * 100,
                2
            );
        }
        
        
        $gradingSystem = $this->gradingModel->getAll();

        // ================= OVERALL GRADE =================
        $overallGrade = null;

        if ($overallPercentage !== null) {
            foreach ($gradingSystem as $grade) {
                if (
                    $overallPercentage >= $grade['min_score'] &&
                    $overallPercentage <= $grade['max_score']
                ) {
                    $overallGrade = $grade['grade'];
                    break;
                }
            }
        }

        $reportCard = array_merge(
        $reportCard,
        $totals,
        [
            'total_pupils' => $totalPupils,
            'overall_percentage' => $overallPercentage,
            'overall_grade' => $overallGrade
        ]
    );

        header('Content-Type: application/json');
        echo json_encode([
            'student' => $student,
            'reportCard' => $reportCard,
            'details' => $reportDetails,
            'grading_system' => $gradingSystem
        ]);
        exit;

    }

    public function getGradingSystem() {
        $gradingSystem = $this->gradingModel->getAll();

        header('Content-Type: application/json');
        echo json_encode($gradingSystem);
        exit;
}

    public function debug() {
        $this->searchStudent();
    }
}
?>