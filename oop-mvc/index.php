<?php
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }
    
    include_once __DIR__."/inc/model/Database.php";
    include_once __DIR__."/inc/controller/UserController.php";
    
    // Initialize database and create tables if needed
    $db = new Database();
    $db->init();

    $user_controller = new UserController();
    
    
    $request_uri = parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH);
    $path = str_replace("/oop-mvc", "", $request_uri); 
    
    $is_logged_in = $_SESSION['logged_in'] ?? false;
?>
<!DOCTYPE html>
<html>
    <head>
        <title>CSE 3101 - Database Connectivity</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" 
              integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
        <link rel="stylesheet" href="css/main.css">
    </head>
    <body>
        <main class="container mt-4">
            
            <?php if (!$is_logged_in): ?>
                <a href="/oop-mvc/register" class="btn btn-outline-primary mr-2">Register</a>
                <a href="/oop-mvc/login" class="btn btn-success">Login</a>
            <?php else: ?>
                <span class="mr-3">Logged in as: <b><?= htmlspecialchars($_SESSION['user_email'] ?? 'User') ?></b></span>
                <a href="/oop-mvc/" class="btn btn-outline-secondary">View Users</a>
                <a href="/oop-mvc/logout" class="btn btn-danger">Logout</a>
            <?php endif; ?>

            <hr>

            <?php 
                switch ($path) {
                    case '/':
                        $user_controller->find_users();
                        break;
                    case '/register':
                        $user_controller->create_user();
                        break;
                    case '/login':
                        $user_controller->login();
                        break;
                    case '/logout':
                        $user_controller->logout();
                        break;
                    case '/delete-user':
                        $user_controller->delete_user();
                        break;
                    case '/change-password':
                        $user_controller->change_password();
                        break;
                    default:
                        echo "<p class='mt-3 text-danger'>404 - Page Not Found</p>";
                        break;
                }
            ?>
        </main>

        <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" 
                integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" 
                integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" 
                integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
    </body>
</html>
