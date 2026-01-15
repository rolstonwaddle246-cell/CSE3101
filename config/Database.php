<?php
    class Database {
        private static $instance = null;
        private $conn;

        private $db_server = "localhost";
        private $db_root = "root";      //user or username
        private $db_pass = "";
        private $db_name = "sms";
        private $charset = "utf8mb4";

        private function __construct() {
            $dsn = "mysql:host={$this->db_server};dbname={$this->db_name};charset={$this->charset}";

            $options = [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
            ];

            try {
                $this->conn = new PDO($dsn, $this->db_root, $this->db_pass, $options);

            } catch (PDOException $e) {
                die("Database connection failed: " . $e->getMessage());
            }
        }

        public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new Database();
            }
        return self::$instance;
        }

        public function getConnection() {
        return $this->conn;
        }
    }
?>