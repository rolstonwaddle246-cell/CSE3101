<div class="container-fluid" style="position: absolute; top: 15%">
    <div class="row">
        <div class="col-4 mr-auto ml-auto">
            <div class="card">
                <div class="card-body">
                    <?php 
                        $target_id = isset($_GET['id']) ? (int)$_GET['id'] : (isset($_POST['id']) ? (int)$_POST['id'] : 0);
                        if ($target_id == 0) {
                            echo "<div class='alert alert-danger'>Invalid user ID specified.</div>";
                            return;
                        }
                        
                        $errors = $_SESSION['errors'] ?? [];
                    ?>
                    <h5 class="card-title">
                        Change Password for User ID: <?= htmlspecialchars($target_id) ?>
                    </h5>
                    
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
                        <form method="post" action="/oop-mvc/change-password?id=<?= $target_id ?>">
                            <input type="hidden" name="id" value="<?= $target_id ?>">

                            <div class="mb-3">
                                <label for="new_passcode" class="form-label">New Password (min 8 chars)</label>
                                <input type="password" class="form-control" name="new_passcode" id="new_passcode" required minlength="8">
                            </div>
                            <div class="mb-3">
                                <label for="confirm_passcode" class="form-label">Confirm New Password</label>
                                <input type="password" class="form-control" name="confirm_passcode" id="confirm_passcode" required minlength="8">
                            </div>
                            <button type="submit" class="btn btn-primary">Update Password</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>