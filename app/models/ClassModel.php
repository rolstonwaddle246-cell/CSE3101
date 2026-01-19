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
            JOIN grades g ON c.grade_id = g.grade_id
            ORDER BY c.class_id ASC
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
            JOIN grades g ON c.grade_id = g.grade_id
            WHERE c.class_id = :id
        ");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Create a new class
    public function create($grade_id, $class_name)
    {
        $stmt = $this->db->prepare("
            INSERT INTO classes (grade_id, class_name) 
            VALUES (:grade_id, :class_name)
        ");
        return $stmt->execute([
            'grade_id' => $grade_id,
            'class_name' => $class_name
        ]);
    }

    // Update class 
    public function update($id, $grade_id, $class_name)
{
    $stmt = $this->db->prepare("
        UPDATE classes 
        SET grade_id = :grade_id,
            class_name = :class_name
        WHERE class_id = :id
    ");
    return $stmt->execute([
        'grade_id' => $grade_id,
        'class_name' => $class_name,
        'id' => $id
    ]);
}


    // Delete class
    public function delete($id)
    {
        $stmt = $this->db->prepare("DELETE FROM classes WHERE class_id = :id");
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

