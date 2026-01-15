<?php
    require_once __DIR__ . '/Model.php';

class Term extends Model {
    protected $table = 'terms';

    // Fetch all terms (optional: for a specific school year)
    public function getAll($schoolYearId) {
        $stmt = $this->db->query("SELECT * FROM terms WHERE school_year_id = :school_year_id ORDER BY start_date ASC");
        $stmt->execute(['school_year_id' => $schoolYearId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getBySchoolYear($schoolYearId) {
        $stmt = $this->db->prepare(
            "SELECT t.*, s.school_year 
            FROM terms t
            JOIN school_years s ON t.school_year_id = s.id
            WHERE t.school_year_id = :school_year_id
            ORDER BY t.start_date ASC"
        );
        $stmt->execute(['school_year_id' => $schoolYearId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function create($term_name, $start_date, $end_date, $status, $school_year_id) {
        $stmt = $this->db->prepare("
            INSERT INTO terms (term_name, start_date, end_date, status, school_year_id) 
            VALUES (:term_name, :start_date, :end_date, :status, :school_year_id)
        ");
        $stmt->execute([
            'term_name' => $term_name,
            'start_date' => $start_date,
            'end_date' => $end_date,
            'status' => $status,
            'school_year_id' => $school_year_id
        ]);
        return $this->db->lastInsertId(); // return the new term ID
    }

    public function update($id, $term_name, $start_date, $end_date, $status) {
        $stmt = $this->db->prepare("
            UPDATE terms 
            SET term_name = :term_name, start_date = :start_date, end_date = :end_date, status = :status 
            WHERE id = :id
        ");
        return $stmt->execute([
            'term_name' => $term_name,
            'start_date' => $start_date,
            'end_date' => $end_date,
            'status' => $status,
            'id' => $id
        ]);
    }

    public function delete($id) {
        $stmt = $this->db->prepare("DELETE FROM terms WHERE id = :id");
        return $stmt->execute(['id' => $id]);
    }

    public function getById($id) {
        $stmt = $this->db->prepare("SELECT * FROM terms WHERE id = :id");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }


}
?>