<?php
require_once __DIR__ . '/Model.php';

class Role extends Model {
    protected $table = 'roles';

    public function __construct() {
        parent::__construct();
    }

    // Fetch all roles
    public function getAllRoles() {
        $stmt = $this->db->query("SELECT role_id, CONCAT(UPPER(LEFT(role_name,1)),LOWER(SUBSTRING(role_name,2))) AS role_name
    FROM roles
    ORDER BY role_name ASC");
        return $stmt->fetchAll();
    }
}
?>
