<?php
require_once __DIR__ . '/Model.php';

class AveragePerformance extends Model {

    // Get all school years
    public function getAllSchoolYears() {
        $stmt = $this->db->query("SELECT id, school_year FROM school_years ORDER BY school_year DESC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Get all terms for a school year
    public function getTermsBySchoolYear($schoolYearId) {
        $stmt = $this->db->prepare("SELECT term_id, term_name FROM terms WHERE school_year_id = ? ORDER BY start_date");
        $stmt->execute([$schoolYearId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Get all grades
    public function getAllGrades() {
        $stmt = $this->db->query("SELECT grade_id, grade_name FROM grades ORDER BY grade_name");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Get all subjects
    public function getAllSubjects() {
        $stmt = $this->db->query("SELECT subject_id, subject_name FROM subjects ORDER BY subject_name");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Fetch report data for all 6 cases
    public function fetchResults($filters) {
        $schoolYear = $filters['school_year'] ?? null;
        $term = $filters['term'] ?? null;
        $grade = $filters['grade'] ?? null;
        $subject = $filters['subject'] ?? null;

        // Default: case 1 (school year only)
        $case = 1;

        if ($schoolYear && !$grade && !$subject && !$term) $case = 1;
        elseif ($schoolYear && $grade && !$subject && !$term) $case = 2;
        elseif ($schoolYear && !$grade && $subject && !$term) $case = 3;
        elseif ($schoolYear && $grade && $subject && !$term) $case = 4;
        elseif ($schoolYear && $term) $case = 5;
        elseif ($schoolYear && $term && !$grade && !$subject) $case = 6;

        $results = [];

        switch ($case) {
            case 1: // School Year only
                $stmt = $this->db->prepare("
                    SELECT 
                    t.term_id,
                    t.term_name,
                    ROUND(AVG(d.marks_obtained), 2) AS avg_score,
                    COUNT(DISTINCT r.student_id) AS num_students
                    FROM report_cards r
                    JOIN report_card_details d ON r.report_id = d.report_id
                    JOIN terms t ON r.term_id = t.term_id
                    WHERE r.school_year_id = ?
                    GROUP BY t.term_id, t.term_name
                    ORDER BY t.start_date ASC
                ");
                $stmt->execute([$schoolYear]);
                $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
                break;

            case 2: // School Year + Grade
                $stmt = $this->db->prepare("
                    SELECT 
                        g.grade_name,
                        ROUND(AVG(d.marks_obtained), 2) AS avg_score,
                        COUNT(DISTINCT r.student_id) AS num_students
                    FROM report_cards r
                    JOIN report_card_details d ON r.report_id = d.report_id
                    JOIN grades g ON r.grade_id = g.grade_id
                    WHERE r.school_year_id = :school_year
                    AND g.grade_id = :grade_id
                    GROUP BY g.grade_id, g.grade_name
                ");
                $stmt->execute([$schoolYear, $grade]);
                $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
                break;

            case 3: // School Year + Subject
                $stmt = $this->db->prepare("
                    SELECT 
                    t.term_name,
                    ROUND(AVG(d.marks_obtained), 2) AS avg_score,
                    COUNT(DISTINCT r.student_id) AS num_students
                    FROM report_cards r
                    JOIN report_card_details d ON r.report_id = d.report_id
                    JOIN subjects sub ON d.subject_id = sub.subject_id
                    JOIN terms t ON r.term_id = t.term_id
                    WHERE r.school_year_id = :school_year
                    AND sub.subject_id = :subject_id
                    GROUP BY t.term_id, t.term_name
                    ORDER BY t.start_date ASC");
                $stmt->execute([$schoolYear, $subject]);
                $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
                break;

            case 4: // School Year + Grade + Subject
                $stmt = $this->db->prepare("
                    SELECT 
                    t.term_name,
                    ROUND(AVG(d.marks_obtained), 2) AS avg_score,
                    COUNT(DISTINCT r.student_id) AS num_students
                    FROM report_cards r
                    JOIN report_card_details d ON r.report_id = d.report_id
                    JOIN students s ON r.student_id = s.student_id
                    JOIN subjects sub ON d.subject_id = sub.subject_id
                    JOIN terms t ON r.term_id = t.term_id
                    WHERE r.school_year_id = :school_year
                    AND s.grade_id = :grade_id
                    AND sub.subject_id = :subject_id
                    GROUP BY t.term_id, t.term_name
                    ORDER BY t.start_date ASC
                ");
                $stmt->execute([$schoolYear, $grade, $subject]);
                $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
                break;

            case 5: // School Year + Term
                $stmt = $this->db->prepare("
                    SELECT 
                        g.grade_name,
                        sub.subject_name,
                        ROUND(AVG(d.marks_obtained), 2) AS avg_score,
                        COUNT(DISTINCT r.student_id) AS num_students
                    FROM report_cards r
                    JOIN report_card_details d ON r.report_id = d.report_id
                    JOIN students s ON r.student_id = s.student_id
                    JOIN grades g ON s.grade_id = g.grade_id
                    JOIN subjects sub ON d.subject_id = sub.subject_id
                    WHERE r.term_id = :term_id
                    GROUP BY g.grade_id, sub.subject_id
                    ORDER BY g.grade_name ASC, sub.subject_name ASC
                ");
                $stmt->execute([$term]);
                $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
                break;

            case 6: // School Year + Term + All students
                $stmt = $this->db->prepare("
                    SELECT 
                        s.student_name,
                        ROUND(AVG(d.marks_obtained), 2) AS avg_score,
                        COUNT(d.subject_id) AS num_subjects
                    FROM report_cards r
                    JOIN report_card_details d ON r.report_id = d.report_id
                    JOIN students s ON r.student_id = s.student_id
                    WHERE r.term_id = :term_id
                    GROUP BY s.student_id
                    ORDER BY s.student_name ASC
                ");
                $stmt->execute([$term]);
                $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
                break;
        }

        return ['case' => $case, 'results' => $results];
    }
}
