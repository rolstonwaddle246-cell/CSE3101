<?php
    require_once __DIR__ . '/../../config/Database.php';

    abstract class Model {
        protected $db;

        public function __construct() {
            $this->db = Database::getInstance()->getConnection();
        }
    }
?>