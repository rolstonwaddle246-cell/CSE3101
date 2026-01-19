<?php
require_once __DIR__ . '/User.php';

class UserManager {
    private $userModel;

    public function __construct() {
        $this->userModel = new User();
    }

    // Create a standard user (with automatic password hashing & role)
    public function createStandardUser($data) {
    // Validate required fields
    if (empty($data['username']) || empty($data['first_name']) || empty($data['last_name']) || empty($data['password'])) {
        throw new Exception("Username, First Name, Last Name, and Password are required.");
    }

    // Hash the password
    $data['password'] = password_hash($data['password'], PASSWORD_BCRYPT);

    // Set defaults
    $data['role_id'] = $data['role_id'] ?? 1; // default role
    $data['must_reset_password'] = 1;
    $data['status'] = 'active';
    $data['created_at'] = date('Y-m-d H:i:s');
    $data['updated_at'] = date('Y-m-d H:i:s');
    $data['email'] = $data['email'] ?? null;

    // Map data in **table column order**
    $insertData = [
        'username' => $data['username'],
        'email' => $data['email'] ?? null,
        'password' => $data['password'],
        'first_name' => $data['first_name'],
        'last_name' => $data['last_name'],
        'role_id' => isset($data['role_id']) ? (int)$data['role_id'] : 1,
        'must_reset_password' => 1,
    ];

    // Call the User model to insert
    return $this->userModel->create($insertData);
}

    // Authenticate user by email & password
    public function authenticateUser($email, $passcode) {
        $user = $this->userModel->getByEmail($email);
        if ($user && password_verify($passcode, $user['passcode'])) {
            return $user;
        }
        return null;
    }

    // Delete user (only if current user is admin)
    public function deleteUser($user_id, $current_user) {
        if ($current_user['role_name'] !== 'admin') {
            throw new Exception("Access denied. Only admins can delete users.");
        }

        // Prevent admin from deleting themselves
        if ($current_user['user_id'] == $user_id) {
            throw new Exception("You cannot delete your own account.");
        }

        return $this->userModel->delete($user_id);
    }

    // Change password (only self or admin)
    public function resetPassword() {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $userId = $_POST['user_id'] ?? null;
        if (!$userId) {
            $_SESSION['errors'][] = "User ID missing.";
            header("Location: index.php?action=manage_users");
            exit;
        }

        $newPassword = "admin123";
        $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);

        if ($this->userModel->updatePassword($userId, $hashedPassword)) {
            $_SESSION['success_message'] = "Password reset to admin123 successfully.";
        } else {
            $_SESSION['errors'][] = "Failed to reset password.";
        }

        header("Location: index.php?action=manage_users");
        exit;
    }
}

public function adminResetPassword($userId, $plainPassword, $currentUser) {

    if ($currentUser['role_name'] !== 'admin') {
        throw new Exception("Only admins can reset passwords.");
    }

    $newPassword = 'admin123';
    $hashed = password_hash($newPassword, PASSWORD_BCRYPT);

    return $this->userModel->resetPasswordByAdmin($userId, $hashed);
}

    // Fetch all users (for admin dashboard)
    public function getAllUsers() {
        return $this->userModel->getAll();
    }

    // Fetch a single user by ID
    public function getUserById($user_id) {
        return $this->userModel->getById($user_id);
    }

    public function updateUser($user_id, $data) {
        $user = new User();
        return $user->updateUser($user_id, $data);
}
}
?>
