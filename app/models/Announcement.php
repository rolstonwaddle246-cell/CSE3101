<?php
require_once __DIR__ . '/Model.php';

class Announcement extends Model {

    private $table = "announcements";

    public function getAll() {
        $stmt = $this->db->query("SELECT * FROM {$this->table} ORDER BY created_at DESC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function create($text) {
        $stmt = $this->db->prepare("INSERT INTO {$this->table} (text) VALUES (:text)");
        $stmt->execute(['text' => $text]);
    }

    public function delete($id) {
        $stmt = $this->db->prepare("DELETE FROM {$this->table} WHERE id = :id");
        $stmt->execute(['id' => $id]);
    }

    public function getById($id) {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE id = :id");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch();
    }

    public function update($id, $text) {
        $stmt = $this->db->prepare("UPDATE {$this->table} SET text = :text WHERE id = :id");
        $stmt->execute(['text' => $text, 'id' => $id]);
    }
}
?>