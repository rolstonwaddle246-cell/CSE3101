<?php
require_once __DIR__ . '/Model.php';

class Subject extends Model
{
    public function getAll()
    {
        $sql = "
            SELECT 
                subjects.subject_id,
                subjects.subject_name,
                subjects.total_marks,
                grades.grade_name,
                subjects.grade_id
            FROM subjects
            INNER JOIN grades ON subjects.grade_id = grades.grade_id
            ORDER BY subjects.subject_id DESC
        ";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Create a Subject 
    public function create($data)
    {
        $sql = "INSERT INTO subjects (subject_name, grade_id, total_marks)
                VALUES (:subject_name, :grade_id, :total_marks)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':subject_name' => $data['subject_name'],
            ':grade_id' => $data['grade_id'],
            ':total_marks' => $data['total_marks'] ?? 50
        ]);
    }

    // Update a Subject
    public function update($data)
    {
        $sql = "UPDATE subjects SET subject_name=:subject_name, grade_id=:grade_id, total_marks=:total_marks
                WHERE subject_id=:id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':subject_name' => $data['subject_name'],
            ':grade_id' => $data['grade_id'],
            ':total_marks' => $data['total_marks'] ?? 50,
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
