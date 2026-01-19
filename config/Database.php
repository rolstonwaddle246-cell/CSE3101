<?php
class Database {
    private static $instance = null;
    private $conn;

    private $db_server = "localhost";
    private $db_root = "root";      // user or username
    private $db_pass = "";
    private $db_name = "sms";
    private $charset = "utf8mb4";

    private function __construct() {
        try {
            // Connect to MySQL without specifying DB first
            $dsn = "mysql:host={$this->db_server};charset={$this->charset}";
            $this->conn = new PDO($dsn, $this->db_root, $this->db_pass, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
            ]);

            // Create DB if it doesn't exist
            $this->conn->exec("CREATE DATABASE IF NOT EXISTS `{$this->db_name}` CHARACTER SET {$this->charset}");
            $this->conn->exec("USE `{$this->db_name}`");

            // Initialize tables and default superuser
            $this->init();



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

    private function init() {
        // Create users table if it doesn't exist
        $createUsersTable = "
            CREATE TABLE IF NOT EXISTS users (
                user_id INT AUTO_INCREMENT PRIMARY KEY,
                username VARCHAR(50) NOT NULL UNIQUE,
                email VARCHAR(100) UNIQUE,
                password VARCHAR(255) NOT NULL,
                first_name VARCHAR(50) NOT NULL,
                last_name VARCHAR(50) NOT NULL,
                role_id INT NOT NULL,
                must_reset_password TINYINT(1) DEFAULT 1,
                status ENUM('active','inactive') NOT NULL DEFAULT 'active',
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                updated_at timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
            ) ENGINE=InnoDB;
        ";
        $this->conn->exec($createUsersTable);

        // Check if a superuser exists
        $stmt = $this->conn->query("
            SELECT COUNT(*) as count FROM users WHERE role_id = 2
        ");
        $result = $stmt->fetch();

        if ($result['count'] == 0) {
        // Insert default superuser
        $username = 'sjuman';
        $email = 'sjuman@example.com'; // add an email for superuser
        $password = password_hash('admin123', PASSWORD_BCRYPT);
        $first_name = 'Sameera';
        $last_name = 'Juman';
        $role_id = 2; // OfficeAdmin
        $must_reset_password = 1;
        $status = 'active'; // new status column

        $sql = "
            INSERT INTO users (username, email, password, first_name, last_name, role_id, must_reset_password, status) 
            VALUES (:username, :email, :password, :first_name, :last_name, :role_id, :must_reset_password, :status)
        ";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute(compact(
            'username',
            'email',
            'password',
            'first_name',
            'last_name',
            'role_id',
            'must_reset_password',
            'status'
        ));

        echo "Default superuser created: {$username} / admin123\n";
            }
    }
}
?>