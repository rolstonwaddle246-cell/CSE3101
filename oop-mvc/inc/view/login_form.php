<div class="container-fluid" style="position: absolute; top: 15%">
    <div class="row">
        <div class="col-4 mr-auto ml-auto">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">
                        User Login
                    </h5>
                    <?php 
                        $errors = $_SESSION['errors'] ?? [];
                        $success = $_SESSION['success_message'] ?? '';
                    ?>

                    <?php if (!empty($success)): ?>
                        <div class="alert alert-success" role="alert">
                            <?= htmlspecialchars($success) ?>
                        </div>
                    <?php endif; ?>

                    <?php if (!empty($errors)): ?>
                        <div class="alert alert-danger" role="alert">
                            <ul>
                                <?php foreach ($errors as $error): ?>
                                    <li><?= htmlspecialchars($error) ?></li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    <?php endif; ?>

                    <div class="card-text">
                        <form method="post" action="">
                            <div class="mb-3">
                                <label for="email" class="form-label">Email address</label>
                                <input type="email" class="form-control" name="email" id="email" required>
                            </div>
                            <div class="mb-3">
                                <label for="passcode" class="form-label">Password</label>
                                <input type="password" class="form-control" name="passcode" id="passcode" required>
                            </div>
                            <button type="submit" class="btn btn-primary">Login</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>