<!-- http://localhost/CSE3101/index.php?action=student_report_card -->

<!DOCTYPE html>
<html lang="en">

<head>
    <?php $pageTitle = "Student Report Card"; ?>
    <?php include __DIR__ . '/../includes/header.php'; ?>

<style>
#studentDropdown a:hover {
background-color: #e6f2ff; 
}
#studentDropdown a.selected {
padding-left: 12px;
padding-right: 12px;
}
#studentDropdown {
    top: 100%;
    z-index: 1000;
    display: none;
    max-height: 220px;
    overflow-y: auto;
    background: #fff;
    transition: background-color 0.2s, color 0.2s;
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
        <?php include __DIR__ . '/../includes/sidebar.php'; ?>
        <!-- End of Sidebar -->

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">

                <!-- Topbar -->
                <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">
                    <?php include __DIR__ . '/../includes/navbar-w/o-searchbar.php'; ?>
                </nav>
                <!-- End of Topbar -->

                <!-- Begin Table Page Content -->
                <div class="container-fluid">
                    <!-- Page Heading -->
                    <h1 class="h3 text-gray-800 mb-3 ml-3">Student Report Card</h1>
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <p class="mb-4 ml-3">Some phrase here.</p>
                        <div class="dropdown ml-auto mr-5">
                            <button class="btn btn-dark dropdown-toggle" type="button" id="exportDropdownBtn" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                Export
                            </button>
                            <div class="dropdown-menu" aria-labelledby="exportDropdownBtn">
                                <a class="dropdown-item" href="#" id="exportPDF">Print to PDF</a>
                                <a class="dropdown-item" href="#" id="exportWord">Print to Word</a>
                                <a class="dropdown-item" href="#" id="exportExcel">Print to Excel</a>
                            </div>
                        </div>
                    </div>

                    <!-- search bar -->
                <div class="d-flex align-items-end mb-3 flex-wrap">
                    <form class="d-none d-sm-inline-block form-inline mr-auto ml-md-3 my-2 my-md-0 mw-100 navbar-search">
                        <div class="mr-2">
                            <div class="input-group position-relative">
                                <input type="text" class="form-control bg-white border-0" style="width: 350px;" placeholder="Search student by name or ID" id="studentSearch" aria-label="Search" aria-describedby="basic-addon2">
                                <div id="studentDropdown" class="list-group position-absolute w-100"></div>

                                <div class="input-group-append">
                                    <button class="btn btn-primary" type="button">
                                        <i class="fas fa-search fa-sm"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                </form>
                
                <!-- School Year -->
                <div class="mr-2" style="min-width: 150px;">
                    <select id="schoolYearDropdown" class="form-control">
                        <option value="">Select School Year</option>
                    </select>
                </div>

                <!-- Term -->
                <div class="mr-2" style="min-width: 150px;">
                    <select id="termDropdown" class="form-control">
                        <option value="">Select Term</option>
                    </select>
                </div>

                <!-- Generate Button -->
                <div class="mr-5" style="min-width: 120px;">
                    <button class="btn btn-success w-100" id="generateBtn">Generate Report</button>
                </div>

            </div>

                <!-- Container to display report -->
                <div id="reportContainer" class="mt-5"></div>


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


</script>

    <!-- JS -->
    <?php include __DIR__ . '/../../../public/assets/js/scripts.php'; ?>

</body>
</html>
