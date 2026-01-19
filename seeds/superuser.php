<!-- Used at the start of the development of this application -->
<?php
require_once __DIR__ . '/../config/Database.php';    
require_once __DIR__ . '/../app/models/User.php';    

$userModel = new User();

$username = 'sjuman';

// Check if superuser already exists
$existingUser = $userModel->getByUsername($username);

if ($existingUser) {
    echo "Superuser '$username' already exists. Seeder skipped.";
} else {
    $superuserData = [
        'username' => $username,
        'password' => password_hash('admin123', PASSWORD_BCRYPT), // default password
        'first_name' => 'Sameera',
        'last_name' => 'Juman',
        'role_id' => 2,       // OfficeAdmin
        'must_reset_password' => 1  // password reset on first login
    ];

    // Insert superuser
    if ($userModel->create($superuserData)) {
        echo "Superuser inserted successfully!";
    } else {
        echo "Failed to insert superuser.";
    }
}
?>