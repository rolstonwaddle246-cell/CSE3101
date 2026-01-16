<?php
require_once __DIR__ . '/Model.php';

class GradingSystem extends Model {
    public function getAll() {
        $stmt = $this->db->prepare("SELECT grade, min_score, max_score, remarks FROM grading_system ORDER BY max_score DESC");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>