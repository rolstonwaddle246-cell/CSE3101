<?php
require_once __DIR__ . '/Model.php'; 

class Grade extends Model
{
    protected $table = 'grades';

    
    public function getAll()
    {
        $stmt = $this->db->prepare("SELECT * FROM grades ORDER BY grade_name ASC");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Fetch a single grade by ID
    public function getById($id)
    {
        $stmt = $this->db->prepare("SELECT * FROM grades WHERE id = :id");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Count all grades
    public function countAll()
    {
        return $this->db->query("SELECT COUNT(*) FROM grades")->fetchColumn();
    }

    // Insert a new grade (MAX 6 GRADES)
    public function create($grade_name, $number_of_classes = 0)
    {
        // Enforce limit of 6 grades
        if ($this->countAll() >= 6) {
            return false; // stop insert
        }

        $stmt = $this->db->prepare("
            INSERT INTO grades (grade_name, number_of_classes) 
            VALUES (:grade_name, :number_of_classes)
        ");

        $stmt->execute([
            'grade_name' => $grade_name,
            'number_of_classes' => $number_of_classes
        ]);

        return $this->db->lastInsertId();
    }

    // Update an existing grade
    public function update($id, $grade_name, $number_of_classes)
    {
        $stmt = $this->db->prepare("
            UPDATE grades
            SET grade_name = :grade_name, number_of_classes = :number_of_classes
            WHERE id = :id
        ");

        return $stmt->execute([
            'grade_name' => $grade_name,
            'number_of_classes' => $number_of_classes,
            'id' => $id
        ]);
    }

    // Delete a grade
    public function delete($id)
    {
        $stmt = $this->db->prepare("DELETE FROM grades WHERE id = :id");
        return $stmt->execute(['id' => $id]);
    }
}

