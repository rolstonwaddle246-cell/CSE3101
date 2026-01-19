<?php
require_once __DIR__ . '/Model.php';

class AssignTeachers extends Model {
    protected $table = 'assign_teachers';

    // Fetch all assignments
    public function getAll() {
        $stmt = $this->db->prepare("
            SELECT c.class_id as id,
                   CONCAT(u.first_name, ' ', u.last_name) AS Name,
                   c.class_name AS Class,
                   g.grade_name AS Grade,
                   c.created_at
            FROM classes c
            LEFT JOIN users u ON c.teacher_id = u.user_id
            INNER JOIN grades g ON c.grade_id = g.grade_id
            WHERE c.teacher_id IS NOT NULL
            ORDER BY c.class_id ASC
        ");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Insert a new assignment
    public function create($user_id, $class_id, $grade_id) {
        $stmt = $this->db->prepare("
            UPDATE classes SET teacher_id = :user_id
            WHERE class_id = :class_id AND grade_id = :grade_id
        ");
        return $stmt->execute([
            ':user_id' => $user_id,
            ':class_id' => $class_id,
            ':grade_id' => $grade_id
        ]);
    }

    // Update an existing assignment
    public function update($id, $user_id, $class_id, $grade_id) {
        $stmt = $this->db->prepare("
            UPDATE classes SET teacher_id = :user_id
            WHERE class_id = :id
        ");
        return $stmt->execute([
            ':user_id' => $user_id,
            ':id' => $id
        ]);
    }

    // Delete an assignment
    public function delete($id) {
        $stmt = $this->db->prepare("UPDATE classes SET teacher_id = NULL WHERE class_id = :id");
        return $stmt->execute([':id' => $id]);
    }

    // Get a single assignment by ID
    public function getById($id) {
        $stmt = $this->db->prepare("
            SELECT c.class_id as id, c.teacher_id as user_id, c.class_id, c.grade_id,
                   CONCAT(u.first_name, ' ', u.last_name) AS teacher_name
            FROM classes c
            LEFT JOIN users u ON c.teacher_id = u.user_id
            WHERE c.class_id = :id
        ");
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
?>
