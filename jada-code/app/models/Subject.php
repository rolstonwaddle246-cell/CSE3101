<?php
require_once __DIR__ . '/Model.php';

class Subject extends Model
{
    public function getAll()
    {
        $sql = "
            SELECT 
                subjects.id,
                subjects.subject_name,
                subjects.number_of_class,
                grades.grade_name,
                subjects.grade_id
            FROM subjects
            INNER JOIN grades ON subjects.grade_id = grades.id
            ORDER BY subjects.id DESC
        ";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Create a Subject 
    public function create($data)
    {
        $sql = "INSERT INTO subjects (subject_name, grade_id, number_of_class)
                VALUES (:subject_name, :grade_id, :number_of_class)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':subject_name' => $data['subject_name'],
            ':grade_id' => $data['grade_id'],
            ':number_of_class' => $data['number_of_class']
        ]);
    }

    // Update a Subject
    public function update($data)
    {
        $sql = "UPDATE subjects SET subject_name=:subject_name, grade_id=:grade_id, number_of_class=:number_of_class
                WHERE id=:id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':subject_name' => $data['subject_name'],
            ':grade_id' => $data['grade_id'],
            ':number_of_class' => $data['number_of_class'],
            ':id' => $data['id']
        ]);
    }

    // Delete a Subject
    public function delete($id)
    {
        $stmt = $this->db->prepare("DELETE FROM subjects WHERE id=?");
        return $stmt->execute([$id]);
    }
}
