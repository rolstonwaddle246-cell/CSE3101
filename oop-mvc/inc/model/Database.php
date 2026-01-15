<?php
class Database {
    protected $connection; 

    private $create_statements = [
        "CREATE TABLE IF NOT EXISTS account( 
            id INT AUTO_INCREMENT PRIMARY KEY,
            first_name VARCHAR(30) NOT NULL,
            last_name VARCHAR(30) NOT NULL,
            email VARCHAR(50) NOT NULL UNIQUE,
            passcode VARCHAR(255) NOT NULL,
            role VARCHAR(20) DEFAULT 'user'
        );"
    ];

    public function connect() {
        $host = 'localhost';
        $db = 'phonebook';
        $user = 'root';
        $password = '';
        $dsn = "mysql:host=$host;charset=UTF8";

        try {
            $this->connection = new PDO($dsn, $user, $password, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
            ]);
            $this->connection->exec("CREATE DATABASE IF NOT EXISTS `$db`");
            $this->connection->exec("USE `$db`");
        } catch (PDOException $e) {
            die("Database connection failed: " . $e->getMessage());
        }
    }

    public function init() {
        $this->connect();
        try {
            foreach($this->create_statements as $statement) {
                $this->connection->exec($statement);
            }
            $this->seed();
        } catch (PDOException $e) {
            error_log($e->getMessage());
        }
    }

    private function seed() {
        // Check if a superuser already exists
        $result = $this->connection->query("SELECT count(id) as count FROM account WHERE role = 'superuser'");
        $data = $result->fetch();
        
        // If the table already has a superuser
        if($data["count"] > 0) return;

        // Create a default superuser
        $plain_password = "admin123"; 
        $hashed_passcode = password_hash($plain_password, PASSWORD_DEFAULT);
        
        $superuser = [
            "fName"=> "Admin",
            "lName"=> "System",
            "email"=> "cse3101@gmail.com",
            "passcode"=> $hashed_passcode
        ];

        $sql = "INSERT INTO account(first_name, last_name, email, passcode, role) 
                VALUES (:fName,:lName,:email,:passcode, 'superuser')";
        $statement = $this->connection->prepare($sql);
        $statement->execute($superuser);
    }

    public function get_connection() { return $this->connection; }
}