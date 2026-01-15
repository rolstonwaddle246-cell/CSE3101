<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mt-4 mb-3">
        <h2>Registered Users</h2>
        <span class="badge badge-primary p-2">Total Users: <?= count($users) ?></span>
    </div>

    <table class="table table-striped">
        <thead>
            <tr>
                <th>#</th>
                <th>ID</th>
                <th>Name</th>
                <th>Email</th>
                <th>Role</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php $sn = 1; foreach($users as $user): ?>
            <tr>
                <td><?= $sn++ ?></td>
                <td><?= $user->get_id() ?></td>
                <td><?= htmlspecialchars($user->get_first_name() . " " . $user->get_last_name()) ?></td>
                <td><?= htmlspecialchars($user->get_email()) ?></td>
                <td><?= htmlspecialchars($user->get_role()) ?></td>
                <td>
                    <?php if ($_SESSION['user_id'] == $user->get_id()): ?>
                        <a href="/oop-mvc/change-password?id=<?= $user->get_id() ?>" class="btn btn-sm btn-warning">Password</a>
                    <?php endif; ?>

                    <?php if (($_SESSION['user_role'] ?? '') === 'superuser' && $_SESSION['user_id'] != $user->get_id()): ?>
                        <form method="POST" action="/oop-mvc/delete-user" style="display:inline;">
                            <input type="hidden" name="user_id" value="<?= $user->get_id() ?>">
                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Delete?')">Delete</button>
                        </form>
                    <?php endif; ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>