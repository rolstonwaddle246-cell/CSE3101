<?php
class UserRecord {
    private $id;
    private $first_name;
    private $last_name;
    private $email;
    private $passcode;
    private $role;

    // Getters
    public function get_id() { return $this->id; }
    public function get_first_name() { return $this->first_name; }
    public function get_last_name() { return $this->last_name; }
    public function get_email() { return $this->email; }
    public function get_passcode() { return $this->passcode; }
    public function get_role() { return $this->role; }

    // Setters
    public function set_first_name($val) { $this->first_name = $val; }
    public function set_last_name($val) { $this->last_name = $val; }
    public function set_email($val) { $this->email = $val; }
    public function set_passcode($val) { $this->passcode = $val; }
    public function set_role($val) { $this->role = $val; }

    public function find_all() {
        $db = new Database();
        $db->connect();
        $conn = $db->get_connection();
        $stmt = $conn->query("SELECT * FROM account");
        $results = [];
        while ($row = $stmt->fetch()) {
            $user = new UserRecord();
            $user->id = $row['id'];
            $user->first_name = $row['first_name'];
            $user->last_name = $row['last_name'];
            $user->email = $row['email'];
            $user->role = $row['role'];
            $user->passcode = $row['passcode'];
            $results[] = $user;
        }
        return $results;
    }

    public function populate_by_id($id) {
        $db = new Database();
        $db->connect();
        $conn = $db->get_connection();
        $stmt = $conn->prepare("SELECT * FROM account WHERE id = ?");
        $stmt->execute([$id]);
        $row = $stmt->fetch();
        if ($row) {
            $this->id = $row['id'];
            $this->first_name = $row['first_name'];
            $this->last_name = $row['last_name'];
            $this->email = $row['email'];
            $this->role = $row['role'];
            $this->passcode = $row['passcode'];
            return true;
        }
        return false;
    }

    public function find_by_email($email) {
        $db = new Database();
        $db->connect();
        $conn = $db->get_connection();
        $stmt = $conn->prepare("SELECT * FROM account WHERE email = ?");
        $stmt->execute([$email]);
        $row = $stmt->fetch();
        if ($row) {
            $user = new UserRecord();
            $user->id = $row['id'];
            $user->email = $row['email'];
            $user->passcode = $row['passcode'];
            $user->role = $row['role'];
            return $user;
        }
        return null;
    }

    public function create() {
        $db = new Database();
        $db->connect();
        $conn = $db->get_connection();
        $stmt = $conn->prepare("INSERT INTO account (first_name, last_name, email, passcode, role) VALUES (?, ?, ?, ?, ?)");
        return $stmt->execute([$this->first_name, $this->last_name, $this->email, $this->passcode, $this->role]);
    }

    public function update() {
        $db = new Database();
        $db->connect();
        $conn = $db->get_connection();
        $stmt = $conn->prepare("UPDATE account SET passcode = ? WHERE id = ?");
        return $stmt->execute([$this->passcode, $this->id]);
    }

    public function delete() {
        $db = new Database();
        $db->connect();
        $conn = $db->get_connection();
        $stmt = $conn->prepare("DELETE FROM account WHERE id = ?");
        return $stmt->execute([$this->id]);
    }
}