<?php
require_once __DIR__ . '/Model.php';

class Setting extends Model {
    private $table = 'school_settings';

    public function get($key) {
        $stmt = $this->db->prepare("SELECT value FROM {$this->table} WHERE key_name = :key");
        $stmt->execute(['key' => $key]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ? $row['value'] : null;
    }

    public function set($key, $value) {
        $stmt = $this->db->prepare("
            INSERT INTO {$this->table} (key_name, value)
            VALUES (:key, :val1)
            ON DUPLICATE KEY UPDATE value = :val2
        ");
        $stmt->execute(['key' => $key, 'val1' => $value, 'val2' => $value]);
    }
}
