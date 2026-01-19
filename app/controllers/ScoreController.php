<?php
require_once __DIR__ . '/../models/Score.php';
require_once __DIR__ . '/../models/Student.php';
require_once __DIR__ . '/../models/Subject.php';
require_once __DIR__ . '/../models/Term.php';
require_once __DIR__ . '/Controller.php';

class ScoreController extends Controller {
    private $scoreModel;
    private $studentModel;
    private $subjectModel;
    private $termModel;

    public function __construct() {
        $this->scoreModel = new Score();
        $this->studentModel = new Student();
        $this->subjectModel = new Subject();
        $this->termModel = new Term();

        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    /**
     * Display score entry form for teachers
     */
    public function entryForm() {
        if (!isset($_SESSION['user_id']) || $_SESSION['role_name'] !== 'teacher') {
            header("Location: index.php?action=login");
            exit();
        }

        $teacherId = $_SESSION['user_id'];
        $error = '';
        $success = '';

        // Get teacher's assigned classes
        $stmt = $this->scoreModel->db->prepare("
            SELECT DISTINCT c.class_id, c.class_name, g.grade_name, g.grade_id
            FROM classes c
            JOIN grades g ON c.grade_id = g.grade_id
            WHERE c.teacher_id = :teacher_id
            ORDER BY g.level_order, c.class_name
        ");
        $stmt->execute(['teacher_id' => $teacherId]);
        $assignedClasses = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $subjects = [];
        $students = [];
        $terms = [];
        $selectedClassId = $_GET['class_id'] ?? null;
        $selectedSubjectId = $_GET['subject_id'] ?? null;
        $selectedTermId = $_GET['term_id'] ?? null;

        // Get active terms
        $stmt = $this->scoreModel->db->prepare("
            SELECT t.term_id, t.term_name, sy.school_year
            FROM terms t
            JOIN school_years sy ON t.school_year_id = sy.id
            WHERE t.status = 'Active'
            ORDER BY sy.school_year DESC, t.start_date DESC
        ");
        $stmt->execute();
        $terms = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // If class is selected, get subjects for that grade
        if ($selectedClassId) {
            $stmt = $this->scoreModel->db->prepare("
                SELECT s.subject_id, s.subject_name
                FROM subjects s
                JOIN classes c ON c.grade_id = (
                    SELECT grade_id FROM classes WHERE class_id = :class_id
                )
                ORDER BY s.subject_name
            ");
            $stmt->execute(['class_id' => $selectedClassId]);
            $subjects = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // Get students for the class
            $stmt = $this->scoreModel->db->prepare("
                SELECT student_id, student_number, first_name, last_name
                FROM students
                WHERE class_id = :class_id
                ORDER BY last_name, first_name
            ");
            $stmt->execute(['class_id' => $selectedClassId]);
            $students = $stmt->fetchAll(PDO::FETCH_ASSOC);
        }

        $this->view('scores/entry_form', [
            'assignedClasses' => $assignedClasses,
            'subjects' => $subjects,
            'students' => $students,
            'terms' => $terms,
            'selectedClassId' => $selectedClassId,
            'selectedSubjectId' => $selectedSubjectId,
            'selectedTermId' => $selectedTermId,
            'error' => $error,
            'success' => $success
        ]);
    }

    /**
     * Store a single score
     */
    public function store() {
        if (!isset($_SESSION['user_id']) || $_SESSION['role_name'] !== 'teacher') {
            echo json_encode(['success' => false, 'error' => 'Unauthorized']);
            exit();
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['success' => false, 'error' => 'Invalid request method']);
            exit();
        }

        $studentId = $_POST['student_id'] ?? null;
        $subjectId = $_POST['subject_id'] ?? null;
        $termId = $_POST['term_id'] ?? null;
        $score = $_POST['score'] ?? null;
        $maxScore = $_POST['max_score'] ?? 100;
        $remarks = $_POST['remarks'] ?? null;

        // Validation
        if (!$studentId || !$subjectId || !$termId || $score === null) {
            echo json_encode(['success' => false, 'error' => 'Missing required fields']);
            exit();
        }

        if (!is_numeric($score) || $score < 0 || $score > $maxScore) {
            echo json_encode(['success' => false, 'error' => 'Invalid score value']);
            exit();
        }

        try {
            // Check if score already exists
            if ($this->scoreModel->scoreExists($studentId, $subjectId, $termId)) {
                // Update existing score
                $stmt = $this->scoreModel->db->prepare("
                    UPDATE scores
                    SET score = :score, max_score = :max_score, remarks = :remarks, updated_at = CURRENT_TIMESTAMP
                    WHERE student_id = :student_id AND subject_id = :subject_id AND term_id = :term_id
                ");
                $stmt->execute([
                    'student_id' => $studentId,
                    'subject_id' => $subjectId,
                    'term_id' => $termId,
                    'score' => $score,
                    'max_score' => $maxScore,
                    'remarks' => $remarks
                ]);
                echo json_encode(['success' => true, 'message' => 'Score updated successfully']);
            } else {
                // Insert new score
                $stmt = $this->scoreModel->db->prepare("
                    INSERT INTO scores (student_id, subject_id, term_id, score, max_score, remarks)
                    VALUES (:student_id, :subject_id, :term_id, :score, :max_score, :remarks)
                ");
                $stmt->execute([
                    'student_id' => $studentId,
                    'subject_id' => $subjectId,
                    'term_id' => $termId,
                    'score' => $score,
                    'max_score' => $maxScore,
                    'remarks' => $remarks
                ]);
                echo json_encode(['success' => true, 'message' => 'Score saved successfully']);
            }
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'error' => 'Database error: ' . $e->getMessage()]);
        }
        exit();
    }

    /**
     * View scores for a class, subject, and term
     */
    public function viewScores() {
        if (!isset($_SESSION['user_id']) || $_SESSION['role_name'] !== 'teacher') {
            header("Location: index.php?action=login");
            exit();
        }

        $classId = $_GET['class_id'] ?? null;
        $subjectId = $_GET['subject_id'] ?? null;
        $termId = $_GET['term_id'] ?? null;

        if (!$classId || !$subjectId || !$termId) {
            $this->view('scores/score_list', ['scores' => [], 'error' => 'Missing required parameters']);
            return;
        }

        $scores = $this->scoreModel->getClassSubjectTermScores($classId, $subjectId, $termId);
        $classAverage = $this->scoreModel->getClassSubjectTermAverage($classId, $subjectId, $termId);

        $this->view('scores/score_list', [
            'scores' => $scores,
            'classAverage' => $classAverage,
            'classId' => $classId,
            'subjectId' => $subjectId,
            'termId' => $termId
        ]);
    }

    /**
     * Edit a specific score
     */
    public function edit() {
        if (!isset($_SESSION['user_id']) || $_SESSION['role_name'] !== 'teacher') {
            header("Location: index.php?action=login");
            exit();
        }

        $scoreId = $_GET['id'] ?? null;
        if (!$scoreId) {
            header("Location: index.php?action=score_entry");
            exit();
        }

        $score = $this->scoreModel->getById($scoreId);
        if (!$score) {
            header("Location: index.php?action=score_entry");
            exit();
        }

        $this->view('scores/edit_score', ['score' => $score]);
    }

    /**
     * Update a score
     */
    public function update() {
        if (!isset($_SESSION['user_id']) || $_SESSION['role_name'] !== 'teacher') {
            header("Location: index.php?action=login");
            exit();
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: index.php?action=score_entry");
            exit();
        }

        $scoreId = $_POST['score_id'] ?? null;
        $score = $_POST['score'] ?? null;
        $maxScore = $_POST['max_score'] ?? 100;
        $remarks = $_POST['remarks'] ?? null;

        if (!$scoreId || $score === null) {
            $_SESSION['error'] = 'Missing required fields';
            header("Location: index.php?action=edit_score&id=" . $scoreId);
            exit();
        }

        if (!is_numeric($score) || $score < 0 || $score > $maxScore) {
            $_SESSION['error'] = 'Invalid score value';
            header("Location: index.php?action=edit_score&id=" . $scoreId);
            exit();
        }

        try {
            $stmt = $this->scoreModel->db->prepare("
                UPDATE scores
                SET score = :score, max_score = :max_score, remarks = :remarks, updated_at = CURRENT_TIMESTAMP
                WHERE id = :id
            ");
            $stmt->execute([
                'id' => $scoreId,
                'score' => $score,
                'max_score' => $maxScore,
                'remarks' => $remarks
            ]);

            $_SESSION['success'] = 'Score updated successfully';
        } catch (Exception $e) {
            $_SESSION['error'] = 'Database error: ' . $e->getMessage();
        }

        header("Location: index.php?action=score_entry");
        exit();
    }

    /**
     * Delete a score
     */
    public function delete() {
        if (!isset($_SESSION['user_id']) || $_SESSION['role_name'] !== 'teacher') {
            echo json_encode(['success' => false, 'error' => 'Unauthorized']);
            exit();
        }

        $scoreId = $_POST['id'] ?? null;
        if (!$scoreId) {
            echo json_encode(['success' => false, 'error' => 'Missing score ID']);
            exit();
        }

        try {
            $stmt = $this->scoreModel->db->prepare("DELETE FROM scores WHERE id = :id");
            $stmt->execute(['id' => $scoreId]);
            echo json_encode(['success' => true, 'message' => 'Score deleted successfully']);
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'error' => 'Database error: ' . $e->getMessage()]);
        }
        exit();
    }

    /**
     * Get student scores (API endpoint)
     */
    public function getStudentScores() {
        $studentId = $_GET['student_id'] ?? null;
        $termId = $_GET['term_id'] ?? null;

        if (!$studentId) {
            echo json_encode(['success' => false, 'error' => 'Missing student ID']);
            exit();
        }

        try {
            if ($termId) {
                $scores = $this->scoreModel->getStudentTermScores($studentId, $termId);
            } else {
                $scores = $this->scoreModel->getStudentScores($studentId);
            }
            echo json_encode(['success' => true, 'data' => $scores]);
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'error' => $e->getMessage()]);
        }
        exit();
    }

    /**
     * Get class averages (API endpoint)
     */
    public function getClassAverages() {
        $classId = $_GET['class_id'] ?? null;
        $termId = $_GET['term_id'] ?? null;

        if (!$classId || !$termId) {
            echo json_encode(['success' => false, 'error' => 'Missing class ID or term ID']);
            exit();
        }

        try {
            $averages = $this->scoreModel->getClassTermAverages($classId, $termId);
            echo json_encode(['success' => true, 'data' => $averages]);
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'error' => $e->getMessage()]);
        }
        exit();
    }
}
