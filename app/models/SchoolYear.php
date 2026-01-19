<?php
    require_once __DIR__ . '/Model.php';

class SchoolYear extends Model {
    protected $table = 'school_years';

    // Fetch all school years
    public function getAll() {
        $stmt = $this->db->query("
            SELECT * FROM school_years
            ORDER BY school_year DESC
        ");
        return $stmt->fetchAll();
    }

    public function create($school_year, $status) {
        $stmt = $this->db->prepare("INSERT INTO school_years (school_year, status) VALUES (:school_year, :status)");
        $stmt->execute(['school_year' => $school_year, 'status' => $status]);
        return $this->db->lastInsertId(); // <-- returns new ID
    }

    public function update($id, $school_year, $status) {
        $stmt = $this->db->prepare("UPDATE school_years SET school_year = :school_year, status = :status WHERE id = :id");
        return $stmt->execute(['school_year' => $school_year, 'status' => $status, 'id' => $id]);
    }

    public function delete($id) {
        $stmt = $this->db->prepare("DELETE FROM school_years WHERE id = :id");
        return $stmt->execute(['id' => $id]);
    }

    public function getById($id) {
        $stmt = $this->db->prepare("SELECT * FROM school_years WHERE id = :id");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch();
    }

    // REPORTS
    // Fetch active school years from the database
    public function getAllSchoolYears() {
        $stmt = $this->db->prepare("
            SELECT id, school_year, status
            FROM school_years
            ORDER BY 
            CASE WHEN status='Active' THEN 0 ELSE 1 END, 
            school_year DESC");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>
