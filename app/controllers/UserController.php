<?php
require_once __DIR__ . '/../models/UserManager.php';
require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/Controller.php';

class UserController extends Controller {
    private $manager;

    public function __construct() {
        $this->manager = new UserManager();
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    // Admin dashboard: list all users
    public function listUsers() {
        $users = $this->manager->getAllUsers();
        $this->view('manage_users/users_table', ['users' => $users]);
    }

    // Create user form submission
    public function createUser() {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {

        try {
            // Prepare data for insertion
            $data = [
                'username' => $_POST['username'] ?? null,
                'email' => $_POST['email'] ?? null,
                'password' => $_POST['password'] ?? null,
                'first_name' => $_POST['first_name'] ?? null,
                'last_name' => $_POST['last_name'] ?? null,
                'role_id' => $_POST['role_id'] ?? 2,
                'must_reset_password' => 1
            ];

            // Call the manager to insert user
            $this->manager->createStandardUser($data);

            $_SESSION['success_message'] = "User created successfully.";

        } catch (Exception $e) {
            $_SESSION['errors'][] = $e->getMessage();
        }

        // Redirect back to manage users page
        header("Location: index.php?action=manage_users");
        exit;
    }
}

public function updateUser() {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $user_id = $_POST['user_id'] ?? null;
        if (!$user_id) {
            $_SESSION['errors'][] = "User ID missing.";
            header("Location: index.php?action=manage_users");
            exit;
        }

        $data = [
            'username' => $_POST['username'] ?? '',
            'first_name' => $_POST['first_name'] ?? '',
            'last_name' => $_POST['last_name'] ?? '',
            'email' => $_POST['email'] ?? '',
            'role_id' => $_POST['role_id'] ?? 2,
            'must_reset_password' => isset($_POST['must_reset_password']) ? 1 : 0,
            'status' => isset($_POST['status']) && $_POST['status'] === 'active' ? 'active' : 'inactive'
        ];

        try {
            $this->manager->updateUser((int)$user_id, $data); // now works
            $_SESSION['success_message'] = "User updated successfully.";
        } catch (Exception $e) {
            $_SESSION['errors'][] = $e->getMessage();
        }

        header("Location: index.php?action=manage_users");
        exit;
    }
}

    // Delete a user
    public function deleteUser() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                $target_id = (int)$_POST['user_id'];
                $current_user = $this->manager->getUserById($_SESSION['user_id']);

                $this->manager->deleteUser($target_id, $current_user);
                $_SESSION['success_message'] = "User deleted successfully.";
            } catch (Exception $e) {
                $_SESSION['errors'][] = $e->getMessage();
            }
            header("Location: index.php?action=manage_users");
            exit;
        }
    }

    // Admin or user changes password
    public function resetPassword() {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        try {
            $target_id = (int)$_POST['user_id'];
            $current_user = $this->manager->getUserById($_SESSION['user_id']);

            // Make sure current user has permission
            $this->manager->adminResetPassword($target_id, 'admin123', $current_user);

            $_SESSION['success_message'] = "Password reset to 'admin123' successfully.";
        } catch (Exception $e) {
            $_SESSION['errors'][] = $e->getMessage();
        }
        header("Location: index.php?action=manage_users");
        exit;
    }
}

public function fetchUserById($user_id) {
        return $this->manager->getUserById($user_id);
    }

public function getUserJson() {
    $id = $_GET['user_id'] ?? null;

    if (!$id) {
        echo json_encode(['error' => 'User ID missing']);
        exit;
    }

    $user = $this->manager->getUserById($id);

    if (!$user) {
        echo json_encode(['error' => 'User not found']);
        exit;
    }

    echo json_encode($user);
    exit;
}

}
?>
