<div class="container-fluid" style="position: absolute; top: 15%">
    <div class="row">
        <div class="col-4 mr-auto ml-auto">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">
                        Register Your Account
                    </h5>

                    <?php 
                        $errors = $_SESSION['errors'] ?? [];
                        $old_input = $_SESSION['old_input'] ?? [];
                    ?>

                    <?php if (!empty($errors)): ?>
                        <div class="alert alert-danger" role="alert">
                            <p>Registration failed:</p>
                            <ul>
                                <?php foreach ($errors as $error): ?>
                                    <li><?= htmlspecialchars($error) ?></li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    <?php endif; ?>

                    <div class="card-text">
                        <form method="post" action="" novalidate>
                            <div class="mb-3">
                                <label for="fName" class="form-label">First Name</label>
                                <input type="text" class="form-control" name="fName" id="fName" required
                                    value="<?= htmlspecialchars($old_input['fName'] ?? '') ?>">
                                <div class="invalid-feedback">Please enter your first name.</div>
                            </div>
                            <div class="mb-3">
                                <label for="lName" class="form-label">Last Name</label>
                                <input type="text" class="form-control" name="lName" id="lName" required
                                    value="<?= htmlspecialchars($old_input['lName'] ?? '') ?>">
                                <div class="invalid-feedback">Please enter your last name.</div>
                            </div>
                            <div class="mb-3">
                                <label for="email" class="form-label">Email address</label>
                                <input type="email" class="form-control" name="email" id="email" required
                                    value="<?= htmlspecialchars($old_input['email'] ?? '') ?>">
                                <div class="invalid-feedback">Please enter a valid email.</div>
                            </div>
                            <div class="mb-3">
                                <label for="passcode" class="form-label">Password</label>
                                <input type="password" class="form-control" name="passcode" id="passcode" required minlength="8">
                                <div class="invalid-feedback">Password must be at least 8 characters.</div>
                            </div>
                            <button type="submit" class="btn btn-primary">Submit</button>
                        </form>
                        <script>
                            (function () {
                                'use strict';
                                const forms = document.querySelectorAll('form');
                                Array.from(forms).forEach(form => {
                                    form.addEventListener('submit', function (event) {
                                        if (!form.checkValidity()) {
                                            event.preventDefault();
                                            event.stopPropagation();
                                        }
                                        form.classList.add('was-validated');
                                    }, false);
                                });
                            })();
                        </script>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>