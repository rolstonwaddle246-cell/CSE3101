<?php
require_once __DIR__ . '/Model.php';

class ReportCard extends Model {
    // Fetch report card by student, school_year, term
    public function getReportCard($student_id, $school_year_id, $term_id) {
        $sql = "
            SELECT 
            rc.report_id,
            rc.created_at,
            g.grade_name,
            s.class_id,  -- get class_id from students table
            CONCAT(u.first_name, ' ', u.last_name) AS teacher_name,
            sy.school_year,
            t.term_name,
            rc.comments
            FROM report_cards rc
            LEFT JOIN students s ON rc.student_id = s.student_id
            LEFT JOIN grades g ON rc.grade_id = g.grade_id
            LEFT JOIN school_years sy ON rc.school_year_id = sy.id
            LEFT JOIN terms t ON rc.term_id = t.term_id
            LEFT JOIN users u ON rc.teacher_id = u.user_id
            WHERE rc.student_id = ? AND rc.school_year_id = ? AND rc.term_id = ?
        ";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$student_id, $school_year_id, $term_id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Fetch all report cards for a student (debug/testing)
    public function getAllReportCardsByStudent($student_id) {
        $sql = "
            SELECT 
            rc.report_id,
            g.grade_name,
            sy.school_year,
            t.term_name
            FROM report_cards rc
            LEFT JOIN grades g ON rc.grade_id = g.grade_id
            LEFT JOIN school_years sy ON rc.school_year_id = sy.id
            LEFT JOIN terms t ON rc.term_id = t.term_id
            WHERE rc.student_id = ?
        ";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$student_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getStudentRank($studentId, $classId, $termId, $schoolYearId)
{
    $sql = "
        SELECT 
            rc.student_id,
            SUM(d.marks_obtained) AS total_marks
        FROM report_card_details d
        JOIN report_cards rc ON rc.report_id = d.report_id
        JOIN students s ON s.student_id = rc.student_id
        WHERE 
            s.class_id = :class_id
            AND rc.term_id = :term_id
            AND rc.school_year_id = :school_year_id
        GROUP BY rc.student_id
        ORDER BY total_marks DESC
    ";

    $stmt = $this->db->prepare($sql);
    $stmt->execute([
        ':class_id' => $classId,
        ':term_id' => $termId,
        ':school_year_id' => $schoolYearId
    ]);

    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $rank = null;
    $prevMarks = null;
    $currentRank = 0;

    foreach ($results as $index => $row) {
        if ($row['total_marks'] !== $prevMarks) {
            $currentRank = $index + 1;
        }

        if ($row['student_id'] == $studentId) {
            $rank = $currentRank;
            break;
        }

        $prevMarks = $row['total_marks'];
    }

    return $rank;
}
}
?>