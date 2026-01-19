<?php
if (session_status() === PHP_SESSION_NONE) session_start();

// Example
if (empty($_SESSION['user_id'])) {
    header("Location: index.php?action=login");
    exit();
}

// Access user info safely
$role = strtolower($_SESSION['role_name'] ?? '');
$username = $_SESSION['username'] ?? '';

// Determine if the current user is admin
$isAdmin = $role === 'admin';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <?php 
    $pageTitle = "Manage Users"; 
    require __DIR__ . '/../includes/header.php'; 


    require_once __DIR__ . '/../../models/Role.php';
    $roleModel = new Role();
    $roles = $roleModel->getAllRoles();


    // echo "<pre>";
    // print_r($_SESSION['user_id'] ?? 'No user in session');
    // echo "</pre>";
    ?>

<style>
.btn-icon-split {
    margin-right: 15px;
}
#usersTable tbody tr:hover {
    background-color: #e6f2ff; /* light blue */
    transition: background-color 0.3s;
    cursor: pointer
}
.table thead th {
    background-color: #4e73df; /* dark blue */
    color: white;
}

/* edit panel */
.edit-panel {
    position: fixed;
    top: 0;
    right: -400px; /* hidden by default */
    width: 400px;
    height: 100%;
    background: #fff;
    box-shadow: -2px 0 8px rgba(0,0,0,0.15);
    padding: 20px;
    transition: right 0.3s ease;
    z-index: 1050;
    overflow-y: auto;
}
.edit-panel.open { right: 0; }
.edit-panel .close {
    position: absolute;
    top: 10px;
    right: 15px;
    font-size: 25px;
    background: none;
    border: none;
}
#overlay {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0,0,0,0.5); /* semi-transparent black */
    z-index: 1040; /* behind the panel but above content */
    display: none;
    transition: opacity 0.3s ease;
}
#overlay.active {
    display: block;
}

/* Mobile edit panel full width */
    @media (max-width: 768px) {
        .edit-panel {
            width: 100%;
            right: -100%;
        }
        .edit-panel.open {
            right: 0;
        }
    }
/* Responsive Modal Form Fields */
    .modal .row > div {
        margin-bottom: 15px;
    }

    /* Hide columns on small screens */
    .d-none.d-md-table-cell { display: none !important; }
    @media (min-width: 768px) {
        .d-none.d-md-table-cell { display: table-cell !important; }
    }
</style>
</head>

<body id="page-top">
    <div id="overlay" style="display:none;"></div>
    <!-- Page Wrapper -->
    <div id="wrapper">

        <?php include __DIR__ . '/../includes/sidebar.php'; ?>
        <!-- End of Sidebar -->

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">

                <!-- Topbar -->
                <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">
                    <?php include __DIR__ . '/../includes/navbar-wo-searchbar.php'; ?>
                </nav>
                <!-- End of Topbar -->

                <!-- Begin Table Page Content -->
                <div class="container-fluid">
                
                    <!-- Page Heading -->
                    <h1 class="h3 mb-2 text-gray-800">Users</h1>
                    <p class="mb-4">View, edit, and control user accounts in the system.</p>

                    <?php if (isset($_SESSION['success_message'])): ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <?= htmlspecialchars($_SESSION['success_message']) ?>
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <?php unset($_SESSION['success_message']); // remove it so it doesn't show again ?>
                <?php endif; ?>

                <?php if (isset($_SESSION['errors']) && count($_SESSION['errors']) > 0): ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <?php foreach ($_SESSION['errors'] as $error): ?>
                            <div><?= htmlspecialchars($error) ?></div>
                        <?php endforeach; ?>
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <?php unset($_SESSION['errors']); ?>
                <?php endif; ?>


                    <!-- Filter & Add User Row -->
                    <div class="mb-4 d-flex justify-content-between align-items-center">

                        <!-- Add User Button -->
                    <div class="ml-auto">
                        <?php if($isAdmin): ?>
                        <button type="button" id="add-user" class="btn btn-primary btn-icon-split" data-toggle="modal" data-target="#addUserModal">
                            <span class="icon text-white-50"><i class="fas fa-plus"></i></span>
                            <span class="text">Add User</span>
                        </button>
                        <?php endif; ?>
                    </div>
                        <!-- Add User Modal -->
                        <div class="modal fade" id="addUserModal" tabindex="-1" aria-labelledby="addUserModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-lg modal-dialog-centered"> <!-- large modal -->
                            <div class="modal-content">

                            <div class="modal-header">
                                <h5 class="modal-title" id="addUserModalLabel">Add New User</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>

                            <form action="index.php?action=create_user" method="POST">
                                <div class="modal-body">
                                    <div class="row">
                                        <!-- Username & Role -->
                                        <div class="col-md-6">
                                            <input type="text" class="form-control mb-3" id="username" name="username" placeholder="Username" required>
                                        </div>
                                        <div class="col-md-6">
                                            <select class="form-control" aria-placeholder="Role" id="role" name="role_id" required>
                                                <option value="">Select Role</option>
                                                <?php foreach($roles as $role): ?>
                                                    <option value="<?= $role['role_id'] ?>"><?= htmlspecialchars($role['role_name']) ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>

                                        <!-- Password & Re-enter Password -->
                                        <div class="col-md-6">
                                            <input type="password" class="form-control mb-3" id="password" name="password" placeholder="Password" required>
                                        </div>
                                        <div class="col-md-6">
                                            <input type="password" placeholder="Re-enter Password" class="form-control" id="confirm_password" name="confirm_password" required>
                                        </div>

                                        <!-- First Name & Last Name -->
                                        <div class="col-md-6">
                                            <input type="text" class="form-control mb-3" id="first_name" name="first_name" placeholder="First Name" required>
                                        </div>
                                        <div class="col-md-6">
                                            <input type="text" class="form-control" id="last_name" name="last_name" placeholder="Last Name" required>
                                        </div>

                                        <!-- Email (full width) -->
                                        <div class="col-12">
                                            <input type="email" class="form-control" id="email" name="email" placeholder="Email">
                                        </div>
                                    </div>
                                </div>

                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                    <button type="submit" class="btn btn-primary">Add User</button>
                                </div>
                            </form>
                            </div>
                        </div>
                        </div>

                    </div>

                <!-- user list table -->
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Registered Users</h6>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered" id="usersTable" width="100%" cellspacing="0">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th class="d-none d-md-table-cell">ID</th>
                                        <th>Full Name</th>
                                        <th>Email</th>
                                        <th>Username</th>
                                        <th>Status</th>
                                        <th>Role</th>
                                        <th class="d-none d-lg-table-cell">Joined Date</th>
                                        <?php if($isAdmin): ?>
                                        <th>Actions</th>
                                        <?php endif; ?>
                                    </tr>
                                </thead>
                                <tfoot>
                                    <tr>
                                        <th>#</th>
                                        <th class="d-none d-md-table-cell">ID</th>
                                        <th>Full Name</th>
                                        <th>Email</th>
                                        <th>Username</th>
                                        <th>Status</th>
                                        <th>Role</th>
                                        <th class="d-none d-lg-table-cell">Joined Date</th>
                                        <?php if($isAdmin): ?>
                                        <th>Actions</th>
                                        <?php endif; ?>
                                    </tr>
                                </tfoot>
                                <tbody>
                                    <?php if (!empty($users)): ?>
                                        <?php $count = 1; ?>
                                        <?php foreach ($users as $user): ?>
                                            <tr data-id="<?= $user['user_id'] ?>">
                                                <td><?= $count++ ?></td>
                                                <td class="d-none d-md-table-cell"><?= htmlspecialchars($user['user_id']) ?></td>
                                                <td><?= htmlspecialchars($user['first_name'] . ' ' . $user['last_name']) ?></td>
                                                <td><?= htmlspecialchars($user['email'] ?? '') ?></td>
                                                <td><?= htmlspecialchars($user['username']) ?></td>
                                                <td>
                                                    <span class="badge <?= $user['status'] === 'active' ? 'badge-success' : 'badge-secondary' ?>">
                                                    <?= ucfirst($user['status']) ?>
                                                    </span>
                                                </td>
                                                <td><?= htmlspecialchars($user['role_name']) ?></td>
                                                <td class="d-none d-lg-table-cell"><?= date('d M Y', strtotime($user['created_at'])) ?></td>
                                                <?php if($isAdmin): ?>
                                                <td>

                                                    <button class="btn btn-info btn-sm edit-user-btn"
                                                        data-id="<?= $user['user_id'] ?>"
                                                        data-status="<?= $user['status'] ?>">
                                                        <i class="fas fa-edit"></i> Edit
                                                    </button>
                                                </td>
                                                <?php endif; ?>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="10" class="text-center">No users found.</td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>


<!-- Edit User Panel -->
<div class="edit-panel" id="editUserPanel">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h5 class="text-dark" style="font-weight: 700;" id="editUserHeader">Edit User</h5>
        <button type="button" class="close" onclick="closePanel()">&times;</button>
    </div>

    <form id="resetPasswordForm" method="POST" action="index.php?action=reset_password" style="display:none;">
        <input type="hidden" name="user_id" id="reset_user_id">
    </form>

    <form id="deleteUserForm" method="POST" action="index.php?action=delete_user" style="display:none;">
        <input type="hidden" name="user_id" id="delete_user_id">
    </form>


    <form id="editUserForm" method="POST" action="index.php?action=update_user">
        <input type="hidden" name="user_id" id="edit_user_id">
        <input type="hidden" name="status" id="edit_user_status">

        <!-- Status Toggle -->
        <div class="form-group d-flex align-items-center">
            <div class="custom-control custom-switch">
                <input type="checkbox" class="custom-control-input" id="edit_user_status_toggle">
                <label class="custom-control-label" for="edit_user_status_toggle" id="edit_user_status_text">Active</label>
            </div>
        </div>

        <hr>

        <!-- Account Header -->
        <h6 class="text-dark" style="font-weight: 700;">Account</h6>
        <div class="form-group">
            <label>Username</label>
            <input type="text" name="username" id="edit_user_username" class="form-control">
        </div>

        <!-- Change Password -->
        <h6 class="text-dark mt-4" style="font-weight: 700;">Change Password</h6>
        <button type="button" class="btn btn-warning btn-sm" id="resetPasswordBtn">Reset Password to admin123</button>

        <!-- Details -->
        <h6 class="text-dark mt-4" style="font-weight: 700;">Details</h6>
        <div class="form-group">
            <label>Role</label>
            <select name="role_id" id="edit_user_role" class="form-control">
                <?php foreach($roles as $role): ?>
                    <option value="<?= $role['role_id'] ?>"><?= htmlspecialchars($role['role_name']) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="form-group">
            <label>First Name</label>
            <input type="text" name="first_name" id="edit_user_first_name" class="form-control">
        </div>
        <div class="form-group">
            <label>Last Name</label>
            <input type="text" name="last_name" id="edit_user_last_name" class="form-control">
        </div>
        <div class="form-group mb-4">
            <label>Email</label>
            <input type="email" name="email" id="edit_user_email" class="form-control">
        </div>

        <hr>

    <div class="d-flex justify-content-between">
        <!-- Delete button -->
        <div>
            <h6 class="text-dark mt-2" style="font-weight: 700;">Delete User</h6>
            <button type="button" class="btn btn-danger btn-sm" id="deleteUserBtn">Delete User</button>
        </div>

        <!-- Save button -->
        <div style="margin-top: 100px;">
            <button type="submit" class="btn btn-primary">Save</button>
        </div>
    </div>
    </form>
</div>

                </div>
                <!-- /.container-fluid -->

            </div>
            <!-- End of Main Content -->

            <!-- Footer -->
            <footer class="sticky-footer bg-white">
                <?php include __DIR__ . '/../includes/footer.php'; ?>
            </footer>
            <!-- End of Footer -->

        </div>
        <!-- End of Content Wrapper -->

    </div>
    <!-- End of Page Wrapper -->

    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

    <!-- Logout Modal-->
    <?php include __DIR__ . '/../includes/logout_modal.php'; ?>

    <!-- JS -->
    <?php include 'assets/js/scripts.php'; ?>

<script>
$(document).ready(function() {
    $('#usersTable').DataTable();
});

// Grab overlay element
const overlay = document.getElementById('overlay');

// Open edit panel and populate fields
document.querySelectorAll('.edit-user-btn').forEach(btn => {
    btn.addEventListener('click', (event) => {
        event.stopPropagation(); // <-- ADD THIS
        const userId = btn.dataset.id;
        console.log("Clicked user ID:", userId);

        fetch(`index.php?action=get_user_json&user_id=${userId}`)
            .then(res => res.json())
            .then(user => {
                if (user.error) {
                    alert(user.error);
                    return;
                }

                document.getElementById('edit_user_id').value = user.user_id;
                document.getElementById('edit_user_username').value = user.username;
                document.getElementById('edit_user_first_name').value = user.first_name;
                document.getElementById('edit_user_last_name').value = user.last_name;
                document.getElementById('edit_user_email').value = user.email;
                document.getElementById('edit_user_role').value = user.role_id;

                const statusToggle = document.getElementById('edit_user_status_toggle');
                const statusText = document.getElementById('edit_user_status_text');
                const statusHidden = document.getElementById('edit_user_status'); 
                statusToggle.checked = user.status === 'active';
                statusText.innerText = user.status.charAt(0).toUpperCase() + user.status.slice(1);
                statusHidden.value = user.status; 
                statusToggle.onchange = () => {
                    statusText.innerText = statusToggle.checked ? 'Active' : 'Inactive';
                    statusHidden.value = statusToggle.checked ? 'active' : 'inactive'; 
                };

                document.getElementById('editUserHeader').innerText = `Edit User: ${user.first_name} ${user.last_name}`;

                document.getElementById('editUserPanel').classList.add('open');
                overlay.style.display = 'block';
            })
            .catch(err => console.error(err));
    });
});

function closePanel() {
    document.getElementById('editUserPanel').classList.remove('open');
    overlay.style.display = 'none'; // hide overlay when panel closes
}
// Close when clicking outside
document.addEventListener('click', function(event) {
    const panel = document.getElementById('editUserPanel');
    if (!panel.contains(event.target) && !event.target.classList.contains('edit-user-btn')) {
        closePanel();
    }
});
// Close when clicking overlay
overlay.addEventListener('click', closePanel);

document.getElementById('resetPasswordBtn').addEventListener('click', () => {
    const userId = document.getElementById('edit_user_id').value;
    if (!userId) return alert("User ID missing.");

    if (confirm("Are you sure you want to reset this user's password to 'admin123'?")) {
        document.getElementById('reset_user_id').value = userId;
        document.getElementById('resetPasswordForm').submit();
    }
});

document.addEventListener('DOMContentLoaded', function() {
    const deleteBtn = document.getElementById('deleteUserBtn');
    if (deleteBtn) {
        deleteBtn.addEventListener('click', () => {
            const userId = document.getElementById('edit_user_id').value;
            if (!userId) return alert("User ID missing.");

            if (confirm("Are you sure you want to delete this user?")) {
                document.getElementById('delete_user_id').value = userId;
                document.getElementById('deleteUserForm').submit();
            }
        });
    }
});

</script>

    


</body>
</html>
