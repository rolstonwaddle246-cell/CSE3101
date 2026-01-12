<?php
    require_once __DIR__ . '/Model.php';

    class User extends Model {
        protected $table = 'users';

        public function __construct() {
            parent::__construct(); // connect to DB
        }

        // Fetch user by username (for login)
        public function getByUsername($username) {
            $stmt = $this->db->prepare("SELECT u.*, r.role_name FROM users u 
                                        JOIN roles r ON u.role_id = r.role_id
                                        WHERE u.username = :username");
            $stmt->execute(['username' => $username]);
            return $stmt->fetch();
        }

        // Create a new user
        public function create($data) {
            $stmt = $this->db->prepare("
                INSERT INTO users (username, password, first_name, last_name, role_id, must_reset_password) 
                VALUES (:username, :password, :first_name, :last_name, :role_id, :must_reset_password)
            ");
            return $stmt->execute($data);
        }

        // Update a user
        public function update($user_id, $data) {
            $data['user_id'] = $user_id;
            $stmt = $this->db->prepare("
                UPDATE users SET 
                    username = :username, 
                    first_name = :first_name, 
                    last_name = :last_name, 
                    role_id = :role_id, 
                    must_reset_password = :must_reset_password
                WHERE user_id = :user_id
            ");
            return $stmt->execute($data);
        }

        //update password
        public function updatePassword($user_id, $hashedPassword) {
            $stmt = $this->db->prepare("
                UPDATE users 
                SET password = :password, must_reset_password = 0
                WHERE user_id = :user_id
            ");
            return $stmt->execute([
                'password' => $hashedPassword,
                'user_id' => $user_id
            ]);
        }

        // Delete a user
        public function delete($user_id) {
            $stmt = $this->db->prepare("DELETE FROM users WHERE user_id = :user_id");
            return $stmt->execute(['user_id' => $user_id]);
        }

        // Fetch all users (with role)
        public function getAll() {
            $stmt = $this->db->query("
                SELECT u.user_id, u.username, u.first_name, u.last_name, r.role_name 
                FROM users u
                JOIN roles r ON u.role_id = r.role_id
                ORDER BY u.user_id ASC
            ");
            return $stmt->fetchAll();
        }
    }
?>