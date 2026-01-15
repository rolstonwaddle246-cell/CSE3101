<?php

// Fix: Ensure the class is loaded for type-hinting
include_once __DIR__ . "/records/UserRecord.php";

class UserManager {

    public function create_standard_user($user) {
        if (!$user instanceof UserRecord) {
            throw new Exception("Invalid user object.");
        }
        $user->set_role("user"); 
        $user->set_passcode(password_hash($user->get_passcode(), PASSWORD_DEFAULT));
        return $user->create();
    }

    public function authenticate_user($email, $passcode) {
        $user_record = new UserRecord();
        $user = $user_record->find_by_email($email);
        if ($user && password_verify($passcode, $user->get_passcode())) {
            return $user;
        }
        return null;
    }

    // Line 18/26 Fix: UserRecord is now loaded
    public function delete_user($user_id, UserRecord $current_user) {
        if ($current_user->get_role() !== "superuser") {
            throw new Exception("Access denied. Only super users can delete users.");
        }
        $user = new UserRecord();
        if ($user->populate_by_id($user_id)) {
            return $user->delete();
        }
        throw new Exception("User not found.");
    }

    public function change_user_password($user_id, $new_password, UserRecord $current_user) {
        if ((int)$current_user->get_id() !== (int)$user_id) {
            throw new Exception("Access denied. You can only change your own password.");
        }
        $user = new UserRecord();
        $user->populate_by_id($user_id);
        $user->set_passcode(password_hash($new_password, PASSWORD_DEFAULT));
        return $user->update();
    }
}