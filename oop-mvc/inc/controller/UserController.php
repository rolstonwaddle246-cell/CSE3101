<?php
include_once __DIR__."/../model/UserManager.php";
include_once __DIR__."/../model/records/UserRecord.php";

class UserController { 
    private $user_model;

    public function __construct() {
        $this->user_model = new UserManager();
    }

    // MATCHES index.php case '/register'
    public function create_user() {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            try {
                $user = new UserRecord();
                $user->set_first_name($_POST['fName']);
                $user->set_last_name($_POST['lName']);
                $user->set_email($_POST['email']);
                $user->set_passcode($_POST['passcode']);
                
                $this->user_model->create_standard_user($user);
                $_SESSION['success_message'] = "Registration successful!";
                header("Location: /oop-mvc/login"); exit;
            } catch (Exception $e) {
                $_SESSION['errors'][] = $e->getMessage();
            }
        }
        include_once __DIR__."/../view/user_registration.php";
    }

    public function delete_user() {
        // Only allow deletion via POST for security
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            try {
                $target_id = isset($_POST["user_id"]) ? (int)$_POST["user_id"] : 0;

                // 1. Create a record of the CURRENT logged-in user to check their role
                $current_user = new UserRecord();
                if (!$current_user->populate_by_id($_SESSION['user_id'])) {
                    throw new Exception("Session error: Current user not found.");
                }

                // 2. Prevent the Admin from deleting themselves
                if ($target_id === (int)$_SESSION['user_id']) {
                    throw new Exception("Security Error: You cannot delete your own admin account.");
                }

                // 3. Call the manager (Passing the target ID and the Current User object)
                $this->user_model->delete_user($target_id, $current_user);

                $_SESSION['success_message'] = "User ID $target_id has been deleted. Counter updated.";
            } catch (Exception $e) {
                $_SESSION['errors'][] = $e->getMessage();
            }
        }
        
        // Redirect back to the user list
        header("Location: /oop-mvc/");
        exit;
    }
    public function change_password() {
        $target_id = (int)$_REQUEST["id"];
        if ($_SESSION['user_id'] != $target_id) {
            header("Location: /oop-mvc/"); exit;
        }

        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            try {
                $current_user = new UserRecord();
                $current_user->populate_by_id($_SESSION['user_id']);
                
                // FIX: Pass all 3 arguments required by UserManager
                $this->user_model->change_user_password($target_id, $_POST['new_passcode'], $current_user);
                
                $_SESSION['success_message'] = "Password updated.";
                header("Location: /oop-mvc/"); exit;
            } catch (Exception $e) {
                $_SESSION['errors'][] = $e->getMessage();
            }
        }
        include_once __DIR__."/../view/change_password_form.php";
    }

    public function find_users() {
        $user_record = new UserRecord();
        $users = $user_record->find_all(); 
        include_once __DIR__."/../view/users_table.php";
    }

    public function login() {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $user = $this->user_model->authenticate_user($_POST['email'], $_POST['passcode']);
            if ($user) {
                $_SESSION['logged_in'] = true;
                $_SESSION['user_id'] = $user->get_id();
                $_SESSION['user_role'] = $user->get_role();
                $_SESSION['user_email'] = $user->get_email();
                header("Location: /oop-mvc/"); exit;
            }
            $_SESSION['errors'][] = "Login failed.";
        }
        include_once __DIR__."/../view/login_form.php";
    }

    public function logout() {
        session_destroy();
        header("Location: /oop-mvc/login"); exit;
    }
}