<!-- http://localhost/CSE3101/index.php?action=admin_dashboard -->

<!DOCTYPE html>
<html lang="en">

<head>
    <?php include 'includes/header.php'; ?>

<style>
.btn-icon-split {
    margin-right: 15px;
}
#schoolYearsTable tbody tr:hover, #termsTable tbody tr:hover {
    background-color: #e6f2ff; /* light blue */
    transition: background-color 0.3s;
    cursor: pointer
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
                    $id = 'schoolYearsTable'; 
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
                        <button type="button" id="add-school-year" class="btn btn-primary btn-icon-split">
                            <span class="icon text-white-50"><i class="fas fa-plus"></i></span>
                            <span class="text">Add School Year</span>
                        </button>
                    </div>

                    <!-- School year Table -->
                    <?php require __DIR__ . '/includes/table.php'; ?>


                    <!-- TERMS Table -->
                    <?php if (!empty($selectedYear)): ?>
                    <hr class="mt-5">
                    <h1 class="h4 text-gray-800 terms-heading">
                        Terms for School Year: 
                            <span class="text-info">
                                <?= isset($selectedYear['school_year']) ? htmlspecialchars($selectedYear['school_year']) : '' ?>
                            </span>
                    </h1>
                    <?php endif; ?>

<?php
$id = 'termsTable'; 
$title = 'Terms';
$columns = ['Year', 'Term', 'Start Date', 'End Date', 'Status', 'Actions'];

$data = [];
foreach ($terms as $term) {
    $data[] = [
        'id' => $term['term_id'], // keep id for edit/delete
        'Year' =>  isset($term['school_year']) ? $term['school_year'] : 'Unknown',
        'Term' => $term['term_name'],
        'Start Date' => $term['start_date'],
        'End Date' => $term['end_date'],
        'Status' => $term['status'],
    ];
}
?>

<!-- Add Term Button -->
<div class="mb-3 d-flex justify-content-end">
    <a href="#" id="add-term" class="btn btn-primary btn-icon-split">
        <span class="icon text-white-50"><i class="fas fa-plus"></i></span>
        <span class="text">Add Term</span>
    </a>
</div>

<!-- Terms Table -->
<?php require __DIR__ . '/includes/term_table.php'; ?>


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

<script>
    window.selectedYearId = <?= $selectedYearId ?? 'null' ?>;
    window.selectedYearText = "<?= $selectedYear['school_year'] ?? '' ?>";

</script>

    <!-- JS -->
    <?php include 'assets/js/scripts.php'; ?>

</body>
</html>
