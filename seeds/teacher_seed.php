<?php
    require_once __DIR__ . '/../config/Database.php';    
    require_once __DIR__ . '/../app/models/User.php';  

    // run 

    $userModel = new User();

$username = 'teacher1';

// Check if user already exists
$existingUser = $userModel->getByUsername($username);

if ($existingUser) {
    echo "User '$username' already exists. Seeder skipped.";
} else {
    $data = [
        'username' => $username,
        'password' => password_hash('admin123', PASSWORD_BCRYPT),
        'first_name' => 'Test',
        'last_name' => 'Teacher',
        'role_id' => 1, // 1 = teacher
        'must_reset_password' => 1,
    ];

    if ($userModel->create($data)) {
        echo "Teacher user created successfully!";
    } else {
        echo "Failed to create teacher user.";
    }
}
?>