<!-- http://localhost/CSE3101/login -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - School Management System</title>

    <link rel="stylesheet" href="assets/css/styles.css">

    <link href='https://cdn.boxicons.com/3.0.6/fonts/basic/boxicons.min.css' rel='stylesheet'>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    
</head>
<body>
    <div class="login-wrapper">
        <form action="index.php?action=login" method="POST">
            <h1>LOGIN</h1>

            <?php if (!empty($error)): ?>
                <p class="text-danger text-center fs-5"><?= $error ?></p>
            <?php endif; ?>

            <div class="input-box">
                <input type="text" name="username" placeholder="Username" required>
                <i class='bx  bx-user'></i>
            </div>

            <div class="input-box">
                <input type="password" id="password" name="password" placeholder="Password" required>
                <i class='bx  bx-eye-slash toggle-password' style="cursor:pointer;"></i> 
            </div>

            <button type="submit" class="btn">Login</button>
            <a href="#" class="reset-link d-block text-center mt-3 text-white">Request password reset<i class='bx bx-info-circle ms-2' data-bs-toggle="tooltip"
            data-bs-placement="top" title="Your request will be sent to an administrator."></i></a>
        </form>
    </div>

    <script src="assets/js/login.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.min.js" integrity="sha384-G/EV+4j2dNv+tEPo3++6LCgdCROaejBqfUeNjuKAiuXbjrxilcCdDz6ZAVfHWe1Y" crossorigin="anonymous"></script>
</body>
</html>