<?php
require_once __DIR__ . '/Model.php';

class ReportCardDetail extends Model {

    // Fetch subjects and marks for a report card
    public function getDetailsByReportId($report_id) {
        $stmt = $this->db->prepare("
            SELECT rcd.*, s.subject_name
            FROM report_card_details rcd
            LEFT JOIN subjects s ON rcd.subject_id = s.subject_id
            WHERE rcd.report_id = ?
        ");
        $stmt->execute([$report_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getTotalsByReportId($report_id) {
    $stmt = $this->db->prepare("
        SELECT 
            SUM(total_marks) AS total_marks,
            SUM(marks_obtained) AS total_marks_obtained
        FROM report_card_details
        WHERE report_id = :report_id
    ");

    $stmt->execute(['report_id' => $report_id]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}
}