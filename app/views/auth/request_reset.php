<!-- after admin aproves -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="assets/css/styles.css">
    <link href='https://cdn.boxicons.com/3.0.6/fonts/basic/boxicons.min.css' rel='stylesheet'>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
</head>
<body>
    <style>
    .back-btn {
        position: absolute;
        top: 20px;
        left: 20px;
        cursor: pointer;
        transition: all 0.2s ease; 
        color: #b8b6b6ff; 
    }

    .back-btn:hover {
        color: #758FE1; 
        transform: scale(1.2); 
    }
</style>

    <div class="login-wrapper">
        <i class='bx  bx-arrow-left-stroke back-btn fs-2 position-absolute' onclick="window.history.back();"></i> 
        
        <form action="index.php?action=request_reset" method="POST">
            <h1>CHANGE YOUR PASSWORD</h1>

            
            <div class="input-box">
                <input type="password" id="password" name="new_password" placeholder="New password" required>
                <i class='bx  bx-eye-slash toggle-password' style="cursor:pointer;"></i> 
            </div>

            <div class="input-box">
                <input type="password" id="password" name="confirm_password" placeholder="Re-enter new password" required>
                <i class='bx  bx-eye-slash toggle-password' style="cursor:pointer;"></i> 
            </div>

            <button type="submit" class="btn">Update Password</button>
            <?php if (!empty($message)) echo "<p class='text-success'>$message</p>"; ?>
            <?php if (!empty($error)) echo "<p class='text-danger'>$error</p>"; ?>
        </form>
    </div>

    <script src="assets/js/login.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.min.js" integrity="sha384-G/EV+4j2dNv+tEPo3++6LCgdCROaejBqfUeNjuKAiuXbjrxilcCdDz6ZAVfHWe1Y" crossorigin="anonymous"></script>
</body>
</html>