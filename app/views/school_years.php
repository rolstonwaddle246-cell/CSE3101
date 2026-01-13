<!-- http://localhost/CSE3101/index.php?action=admin_dashboard -->

<!DOCTYPE html>
<html lang="en">

<head>
    <?php include 'includes/header.php'; ?>

<style>
.btn-icon-split {
    margin-right: 15px;
}
#dataTable tbody tr:hover {
    background-color: #e6f2ff; /* light blue */
    transition: background-color 0.3s;
}
.table thead th {
    background-color: #4e73df; /* dark blue */
    color: white;
}
</style>
</head>

<body id="page-top">
    <!-- Page Wrapper -->
    <div id="wrapper">

        <?php include 'includes/sidebar.php'; ?>
        <!-- End of Sidebar -->

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">

                <!-- Topbar -->
                <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">
                    <?php include 'includes/navbar.php'; ?>
                </nav>
                <!-- End of Topbar -->

                <!-- Begin Table Page Content -->
                <div class="container-fluid">

                    <!-- Page Heading -->
                    <h1 class="h3 mb-2 text-gray-800">Academic Periods</h1>
                    <p class="mb-4">See all school years at a glance.</p>

                    <?php
                    $title = 'School Years';
                    $columns = ['School Year', 'Status', 'Actions'];

                    $data = [];
                    foreach ($schoolYears as $year) {
                        $data[] = [
                            'id' => $year['id'], // keep id for edit/delete
                            'School Year' => $year['school_year'],
                            'Status' => $year['status'],
                        ];
                    }
                    ?>

                    <!-- Add School Year Button -->
                    <div class="mb-3 d-flex justify-content-end">
                        <a href="#" id="add-school-year" class="btn btn-primary btn-icon-split">
                            <span class="icon text-white-50"><i class="fas fa-plus"></i></span>
                            <span class="text">Add School Year</span>
                        </a>
                    </div>

                    <!-- Table -->
                    <?php require __DIR__ . '/includes/table.php'; ?>

                    <!-- Hidden Form for Adding New School Year -->
                    <!-- <form id="addSchoolYearForm" method="POST" action="index.php?action=add_school_year" style="display:none;">
                        <input type="hidden" name="school_year" id="newSchoolYear">
                        <input type="hidden" name="status" id="newStatus">
                    </form> -->

                </div>
                <!-- /.container-fluid -->

            </div>
            <!-- End of Main Content -->

            <!-- Footer -->
            <footer class="sticky-footer bg-white">
                <?php include 'includes/footer.php'; ?>
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
    <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Ready to Leave?</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">Select "Logout" below if you are ready to end your current session.</div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                    <a class="btn btn-primary" href="login.html">Logout</a>
                </div>
            </div>
        </div>
    </div>

    <?php include 'assets/js/scripts.php'; ?>

    <script>
    $(document).ready(function() {

        // Add new row
        $(document).on('click', '#add-school-year', function(e) {
            e.preventDefault();

            var newRow = `<tr>
                <td class="editable year"><input type="text" class="form-control form-control-sm year-input" placeholder="YYYY/YYYY"></td>
                <td class="editable status">
                    <select class="form-control form-control-sm status-input">
                        <option value="Active">Active</option>
                        <option value="Inactive">Inactive</option>
                    </select>
                </td>
                <td>
                    <button class="btn btn-success btn-sm save-new">Save</button>
                    <button class="btn btn-secondary btn-sm cancel-new">Cancel</button>
                </td>
            </tr>`;

            var tbody = $('#dataTable tbody');
            tbody.find('td.text-center').closest('tr').remove();
            tbody.prepend(newRow);
        });

        // Save new school year to DB
        $(document).on('click', '.save-new', function(e) {
            e.preventDefault();
            var row = $(this).closest('tr');
            var school_year = row.find('.year-input').val();
            var status = row.find('.status-input').val();

            if (!school_year) { alert('Enter a school year'); return; }

            $.post('index.php?action=store_school_year', {school_year, status}, function() {
                location.reload();
            });
        });

        // Cancel adding new row
        $(document).on('click', '.cancel-new', function() {
            $(this).closest('tr').remove();
            if ($('#dataTable tbody tr').length === 0) {
                $('#dataTable tbody').append('<tr><td colspan="3" class="text-center">No data available</td></tr>');
            }
        });

        // Delete school year
        $(document).on('click', '.delete-btn', function() {
            if (!confirm('Are you sure you want to delete this school year?')) return;
            var id = $(this).data('id');

            $.post('index.php?action=delete_school_year', {id}, function() {
                location.reload();
            });
        });

        // Edit school year
        $(document).on('click', '.edit-btn', function() {
            var row = $(this).closest('tr');
            row.find('.editable').each(function() {
                var value = $(this).text();
                $(this).data('original', value);
                if ($(this).hasClass('status')) {
                    $(this).html(`<select class="form-control form-control-sm edit-input">
                                    <option value="Active" ${value==='Active'?'selected':''}>Active</option>
                                    <option value="Inactive" ${value==='Inactive'?'selected':''}>Inactive</option>
                                  </select>`);
                } else {
                    $(this).html('<input type="text" class="form-control form-control-sm edit-input" value="'+value+'">');
                }
            });

            $(this).replaceWith(`<a href="#" class="btn btn-primary btn-icon-split btn-sm save-btn" data-id="${$(this).data('id')}">
                                    <span class="icon text-white-50"><i class="fas fa-check"></i></span>
                                    <span class="text">Save</span></a>`);
            row.find('.delete-btn').replaceWith(`<a href="#" class="btn btn-secondary btn-icon-split btn-sm cancel-btn">
                                                    <span class="icon text-white-50"><i class="fas fa-times"></i></span>
                                                    <span class="text">Cancel</span></a>`);
        });

        // Save edit
        $(document).on('click', '.save-btn', function() {
            var row = $(this).closest('tr');
            var id = $(this).data('id');
            var school_year = row.find('input').val();
            var status = row.find('select').val();

            $.post('index.php?action=update_school_year', {id, school_year, status}, function() {
                location.reload();
            });
        });

        // Cancel edit
        $(document).on('click', '.cancel-btn', function() {
            location.reload();
        });

    });
    </script>
</body>
</html>
