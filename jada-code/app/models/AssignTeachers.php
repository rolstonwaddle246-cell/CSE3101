<?php
require_once __DIR__ . '/Model.php';

class AssignTeachers extends Model {
    protected $table = 'assign_teachers';

    // Fetch all assignments
    public function getAll() {
        $stmt = $this->db->prepare("
            SELECT at.id,
                   CONCAT(u.first_name, ' ', u.last_name) AS Name,
                   c.class_name AS Class,
                   g.grade_name AS Grade,
                   at.created_at
            FROM assign_teachers at
            INNER JOIN users u ON at.user_id = u.user_id
            INNER JOIN classes c ON at.class_id = c.id
            INNER JOIN grades g ON at.grade_id = g.id
            ORDER BY at.id ASC
        ");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Insert a new assignment
    public function create($user_id, $class_id, $grade_id) {
        $stmt = $this->db->prepare("
            INSERT INTO assign_teachers (user_id, class_id, grade_id) 
            VALUES (:user_id, :class_id, :grade_id)
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
            UPDATE assign_teachers
            SET user_id = :user_id, class_id = :class_id, grade_id = :grade_id
            WHERE id = :id
        ");
        return $stmt->execute([
            ':user_id' => $user_id,
            ':class_id' => $class_id,
            ':grade_id' => $grade_id,
            ':id' => $id
        ]);
    }

    // Delete an assignment
    public function delete($id) {
        $stmt = $this->db->prepare("DELETE FROM assign_teachers WHERE id = :id");
        return $stmt->execute([':id' => $id]);
    }

    // Get a single assignment by ID
    public function getById($id) {
        $stmt = $this->db->prepare("SELECT * FROM assign_teachers WHERE id = :id");
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
?>
