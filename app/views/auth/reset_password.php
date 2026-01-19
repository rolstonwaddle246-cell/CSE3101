<?php
if (session_status() === PHP_SESSION_NONE) session_start();

// Must be logged in
if (empty($_SESSION['user_id'])) {
    header("Location: index.php?action=login");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
    <link rel="stylesheet" href="assets/css/styles.css">
    <link href='https://cdn.boxicons.com/3.0.6/fonts/basic/boxicons.min.css' rel='stylesheet'>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
</head>
<body>
    <div class="login-wrapper">
        <form action="index.php?action=reset" method="POST">
            <h1>CHANGE YOUR PASSWORD</h1>
            <p class="reset-msg">Welcome! Please choose a new password to continue.</p>

            <?php if (!empty($error)): ?>
                <p class="text-danger"><?= $error ?></p>
            <?php endif; ?>

            <div class="input-box">
                <input type="password" id="new_password" name="new_password" placeholder="New password" required>
                <i class='bx  bx-eye-slash toggle-password' style="cursor:pointer;"></i> 
            </div>

            <div class="input-box">
                <input type="password" id="confirm_password" name="confirm_password" placeholder="Re-enter new password" required>
                <i class='bx  bx-eye-slash toggle-password' style="cursor:pointer;"></i> 
            </div>

            <button type="submit" class="btn">Update Password</button>
        </form>
    </div>

    <script src="assets/js/login.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.min.js" integrity="sha384-G/EV+4j2dNv+tEPo3++6LCgdCROaejBqfUeNjuKAiuXbjrxilcCdDz6ZAVfHWe1Y" crossorigin="anonymous"></script>
</body>
</html>