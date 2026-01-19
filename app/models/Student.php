<?php
require_once __DIR__ . '/Model.php';

class Student extends Model {
        // Fetch students by search (name or student_number)
    public function searchStudents($keyword) {
        $keyword = "%$keyword%";
        $sql = "
            SELECT 
            s.student_id,
            s.student_number,
            s.first_name,
            s.last_name,
            g.grade_name
            FROM students s
            LEFT JOIN grades g ON s.grade_id = g.grade_id
            WHERE s.first_name LIKE ?
            OR s.last_name LIKE ?
            OR s.student_number LIKE ?
        ";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$keyword, $keyword, $keyword]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Fetch single student by ID
    public function getStudentById($id) {
        $sql = "
            SELECT s.student_id, s.first_name, s.last_name, s.grade_id, s.class_id,
                -- join the classes table to get class_name
                REPLACE(cl.class_name, 'Grade ', '') AS class_short,
                cl.class_name AS class_full
                FROM students s
                LEFT JOIN classes cl ON s.class_id = cl.class_id
                WHERE s.student_id = ?
        ";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function countByClassId($class_id) {
        $stmt = $this->db->prepare("SELECT COUNT(*) AS total_pupils FROM students WHERE class_id = ?");
        $stmt->execute([$class_id]);
        return $stmt->fetch(PDO::FETCH_ASSOC)['total_pupils'] ?? 0;
    }
}
?>