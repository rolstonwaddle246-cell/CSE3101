<?php
require_once __DIR__ . '/Model.php';

class Score extends Model {
    protected $table = 'scores';

    /**
     * Get all scores for a specific student
     */
    public function getStudentScores($studentId) {
        $stmt = $this->db->prepare("
            SELECT s.*, sub.subject_name, t.term_name, sy.school_year
            FROM {$this->table} s
            JOIN subjects sub ON s.subject_id = sub.subject_id
            JOIN terms t ON s.term_id = t.term_id
            JOIN school_years sy ON t.school_year_id = sy.id
            WHERE s.student_id = :student_id
            ORDER BY sy.school_year DESC, t.start_date DESC, sub.subject_name
        ");
        $stmt->execute(['student_id' => $studentId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Get scores for a specific term
     */
    public function getStudentTermScores($studentId, $termId) {
        $stmt = $this->db->prepare("
            SELECT s.*, sub.subject_name
            FROM {$this->table} s
            JOIN subjects sub ON s.subject_id = sub.subject_id
            WHERE s.student_id = :student_id AND s.term_id = :term_id
            ORDER BY sub.subject_name
        ");
        $stmt->execute(['student_id' => $studentId, 'term_id' => $termId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Get all scores for a class, subject, and term
     */
    public function getClassSubjectTermScores($classId, $subjectId, $termId) {
        $stmt = $this->db->prepare("
            SELECT s.*, st.first_name, st.last_name, st.student_number
            FROM {$this->table} s
            JOIN students st ON s.student_id = st.student_id
            WHERE st.class_id = :class_id AND s.subject_id = :subject_id AND s.term_id = :term_id
            ORDER BY st.last_name, st.first_name
        ");
        $stmt->execute([
            'class_id' => $classId,
            'subject_id' => $subjectId,
            'term_id' => $termId
        ]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Get average score for a class, subject, and term
     */
    public function getClassSubjectTermAverage($classId, $subjectId, $termId) {
        $stmt = $this->db->prepare("
            SELECT AVG(s.score) as average, COUNT(*) as count
            FROM {$this->table} s
            JOIN students st ON s.student_id = st.student_id
            WHERE st.class_id = :class_id AND s.subject_id = :subject_id AND s.term_id = :term_id
        ");
        $stmt->execute([
            'class_id' => $classId,
            'subject_id' => $subjectId,
            'term_id' => $termId
        ]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Check if a score already exists
     */
    public function scoreExists($studentId, $subjectId, $termId) {
        $stmt = $this->db->prepare("
            SELECT id FROM {$this->table}
            WHERE student_id = :student_id AND subject_id = :subject_id AND term_id = :term_id
        ");
        $stmt->execute([
            'student_id' => $studentId,
            'subject_id' => $subjectId,
            'term_id' => $termId
        ]);
        return $stmt->fetch(PDO::FETCH_ASSOC) !== false;
    }

    /**
     * Get class averages for all subjects in a term
     */
    public function getClassTermAverages($classId, $termId) {
        $stmt = $this->db->prepare("
            SELECT sub.subject_name, AVG(s.score) as average, COUNT(*) as student_count
            FROM {$this->table} s
            JOIN students st ON s.student_id = st.student_id
            JOIN subjects sub ON s.subject_id = sub.subject_id
            WHERE st.class_id = :class_id AND s.term_id = :term_id
            GROUP BY s.subject_id, sub.subject_name
            ORDER BY sub.subject_name
        ");
        $stmt->execute(['class_id' => $classId, 'term_id' => $termId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Bulk insert scores
     */
    public function bulkInsert($scores) {
        $this->db->beginTransaction();
        try {
            $stmt = $this->db->prepare("
                INSERT INTO {$this->table} (student_id, subject_id, term_id, score, max_score, remarks)
                VALUES (:student_id, :subject_id, :term_id, :score, :max_score, :remarks)
                ON DUPLICATE KEY UPDATE
                score = VALUES(score), max_score = VALUES(max_score), remarks = VALUES(remarks), updated_at = CURRENT_TIMESTAMP
            ");

            foreach ($scores as $score) {
                $stmt->execute($score);
            }

            $this->db->commit();
            return true;
        } catch (Exception $e) {
            $this->db->rollBack();
            return false;
        }
    }

    /**
     * Get grade averages for a subject and term
     */
    public function getGradeSubjectTermAverage($gradeId, $subjectId, $termId) {
        $stmt = $this->db->prepare("
            SELECT AVG(s.score) as average, COUNT(*) as count
            FROM {$this->table} s
            JOIN students st ON s.student_id = st.student_id
            WHERE st.grade_id = :grade_id AND s.subject_id = :subject_id AND s.term_id = :term_id
        ");
        $stmt->execute([
            'grade_id' => $gradeId,
            'subject_id' => $subjectId,
            'term_id' => $termId
        ]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}