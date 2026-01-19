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
            INSERT INTO users 
                (username, email, password, first_name, last_name, role_id, status, must_reset_password, created_at, updated_at)
            VALUES 
                (:username, :email, :password, :first_name, :last_name, :role_id, 'active', :must_reset_password, NOW(), NOW());
        ");
        return $stmt->execute($data);
    }

    // Update a user
    public function updateUser($user_id, $data) {
        $data['user_id'] = $user_id;
        $stmt = $this->db->prepare("
            UPDATE users SET 
            username = :username, 
            first_name = :first_name, 
            last_name = :last_name, 
            email = :email,
            role_id = :role_id,
            status = :status
        WHERE user_id = :user_id
        ");
        return $stmt->execute([
        'user_id' => $data['user_id'],
        'username' => $data['username'],
        'first_name' => $data['first_name'],
        'last_name' => $data['last_name'],
        'email' => $data['email'] ?? null,
        'role_id' => $data['role_id'],
        'status' => $data['status']
    ]);
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

    // Admin resets someone else's password
public function resetPasswordByAdmin($user_id, $hashedPassword) {
    $stmt = $this->db->prepare("
        UPDATE users
        SET password = :password,
            must_reset_password = 1
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
        SELECT 
        u.user_id,
        u.username,
        u.first_name,
        u.last_name,
        u.email,
        u.status,
        u.created_at,
        r.role_name
        FROM users u
        JOIN roles r ON u.role_id = r.role_id
        ORDER BY u.user_id ASC;
        ");
        return $stmt->fetchAll();
    }

    // FROM OMAR MANAGE USERS
    // Fetch a single user by ID
    public function getById($user_id) {
        $stmt = $this->db->prepare("
            SELECT u.*, r.role_name 
            FROM users u
            JOIN roles r ON u.role_id = r.role_id
            WHERE u.user_id = :user_id
        ");
        $stmt->execute(['user_id' => $user_id]);
        return $stmt->fetch();
    }

    // Fetch a single user by email
    public function getByEmail($email) {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE email = :email");
        $stmt->execute(['email' => $email]);
        return $stmt->fetch();
    }

    
}
?>