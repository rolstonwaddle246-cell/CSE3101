<?php
require_once __DIR__ . '/Model.php';

class ClassModel extends Model
{
    protected $table = 'classes';

    // Get all classes with grade names
    public function getAll()
    {
        $stmt = $this->db->prepare("
            SELECT c.*, g.grade_name
            FROM classes c
            JOIN grades g ON c.grade_id = g.id
            ORDER BY c.id ASC
        ");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Get class by ID (with grade name)
    public function getById($id)
    {
        $stmt = $this->db->prepare("
            SELECT c.*, g.grade_name
            FROM classes c
            JOIN grades g ON c.grade_id = g.id
            WHERE c.id = :id
        ");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Create a new class
    public function create($grade_id, $class_name, $num_students = 0)
    {
        $stmt = $this->db->prepare("
            INSERT INTO classes (grade_id, class_name, num_students) 
            VALUES (:grade_id, :class_name, :num_students)
        ");
        return $stmt->execute([
            'grade_id' => $grade_id,
            'class_name' => $class_name,
            'num_students' => $num_students
        ]);
    }

    // Update class 
    public function update($id, $grade_id, $class_name, $num_students)
{
    $stmt = $this->db->prepare("
        UPDATE classes 
        SET grade_id = :grade_id,
            class_name = :class_name,
            num_students = :num_students
        WHERE id = :id
    ");
    return $stmt->execute([
        'grade_id' => $grade_id,
        'class_name' => $class_name,
        'num_students' => $num_students,
        'id' => $id
    ]);
}


    // Delete class
    public function delete($id)
    {
        $stmt = $this->db->prepare("DELETE FROM classes WHERE id = :id");
        return $stmt->execute(['id' => $id]);
    }

    //Count by Grade
      public function countByGrade($grade_id)
    {
        $stmt = $this->db->prepare("
            SELECT COUNT(*) as total 
            FROM {$this->table} 
            WHERE grade_id = ?
        ");
        $stmt->execute([$grade_id]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return (int) ($result['total'] ?? 0);
    }
}

