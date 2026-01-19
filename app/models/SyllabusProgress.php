<?php
require_once __DIR__ . '/Model.php';

class SyllabusProgress extends Model {
    private $table = 'syllabus_progress';

    public function get($subject) {
        $stmt = $this->db->prepare("SELECT value FROM {$this->table} WHERE subject = :subject");
        $stmt->execute(['subject' => $subject]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ? $row['value'] : null;
    }

    public function set($subject, $value) {
        $stmt = $this->db->prepare("
            INSERT INTO {$this->table} (subject, value)
            VALUES (:subject, :val1)
            ON DUPLICATE KEY UPDATE value = :val2
        ");
        $stmt->execute([
            'subject' => $subject,
            'val1' => $value,
            'val2' => $value
        ]);
    }
}
