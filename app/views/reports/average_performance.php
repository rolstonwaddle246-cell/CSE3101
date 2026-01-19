<!DOCTYPE html>
<html lang="en">

<head>
    <?php $pageTitle = "Average Performance Report"; ?>
    <?php include __DIR__ . '/../includes/header.php'; ?>

<style>
/* Dropdown and search styling */
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

.card-body {
    padding: 1.5rem;
}

#apResults {
    margin-top: 1rem;
}
</style>

</head>

<body id="page-top">
    <!-- Page Wrapper -->
    <div id="wrapper">
        <?php include __DIR__ . '/../includes/sidebar.php'; ?>

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">

                <!-- Topbar -->
                <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">
                    <?php include __DIR__ . '/../includes/navbar-w/o-searchbar.php'; ?>
                </nav>

                <!-- Begin Page Content -->
                <div id="averagePerformancePage" class="container-fluid">
                    <h1 class="h3 text-gray-800 mb-3 ml-3">Average Performance Report</h1>
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <p class="mb-4 ml-3">View average student performance across various filters.</p>
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

                    <!-- Filters Form -->
                    <form method="GET" class="d-flex align-items-end mb-3 flex-wrap mr-5">

                        <!-- School Year -->
                        <div class="mr-2" style="min-width: 150px;">
                            <select id="apSchoolYearDropdown" name="school_year" class="form-control">
                                <option value="">Select School Year</option>
                                <?php foreach ($data['school_years'] as $year): ?>
                                    <option value="<?= $year['id'] ?>"
                                        <?= (isset($data['filters']['school_year']) && $data['filters']['school_year'] == $year['id']) ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($year['school_year']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <!-- Term -->
                        <div class="mr-2" style="min-width: 150px;">
                            <select id="apTermDropdown" name="term" class="form-control">
                                <option value="">All Terms</option>
                                <?php foreach ($data['terms'] as $term): ?>
                                    <option value="<?= $term['term_id'] ?>"
                                        <?= (isset($data['filters']['term']) && $data['filters']['term'] == $term['term_id']) ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($term['term_name']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <!-- Grade -->
                        <div class="mr-2" style="min-width: 150px;">
                            <select name="grade" id="apGradeDropdown" class="form-control">
                                <option value="">All Grades</option>
                                <?php foreach ($data['grades'] as $grade): ?>
                                    <option value="<?= $grade['grade_id'] ?>"
                                        <?= (isset($data['filters']['grade']) && $data['filters']['grade'] == $grade['grade_id']) ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($grade['grade_name']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <!-- Subject -->
                        <div class="mr-2" style="min-width: 150px;">
                            <select name="subject" id="apSubjectDropdown" class="form-control">
                                <option value="">All Subjects</option>
                                <?php foreach ($data['subjects'] as $subject): ?>
                                    <option value="<?= $subject['subject_id'] ?>"
                                        <?= (isset($data['filters']['subject']) && $data['filters']['subject'] == $subject['subject_id']) ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($subject['subject_name']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <!-- Generate Button -->
                        <div style="min-width: 120px;">
                            <button type="button" id="apGenerateBtn" class="btn btn-success w-100">Generate</button>
                        </div>

                    </form>

                    <hr>

                    <!-- Results -->
                    <div id="apResults">
                        <?php if (!empty($data['results'])): ?>
                            <div class="table-responsive">
                                <table class="table table-bordered table-hover" id="apDataTable" width="100%" cellspacing="0">
                                    <thead class="thead-light">
                                        <tr>
                                            <th>
                                                <?php
                                                switch ($data['case']) {
                                                    case 1: echo "Term"; break; // School Year
                                                    case 2: echo "Term"; break; // School Year + Grade
                                                    case 3: echo "Term"; break; // School Year + Subject
                                                    case 4: echo "Term"; break; // School Year + Grade + Subject
                                                    case 5: echo "Grade / Subject"; break; // School Year + Term
                                                    case 6: echo "Student"; break; // School Year + Student
                                                    default: echo "Term";
                                                }
                                                ?>
                                            </th>
                                            <th>Average Score</th>
                                            <th>No. of Students</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($data['results'] as $row): ?>
                                            <tr>
                                                <td>
                                                    <?php
                                                    switch ($data['case']) {
                                                        case 5:
                                                            echo htmlspecialchars($row['grade_name'] ?? $row['subject_name']);
                                                            break;
                                                        case 6:
                                                            echo htmlspecialchars($row['student_name']);
                                                            break;
                                                        default:
                                                            echo htmlspecialchars($row['term_name']);
                                                    }
                                                    ?>
                                                </td>
                                                <td><?= htmlspecialchars($row['avg_score']) ?></td>
                                                <td><?= htmlspecialchars($row['num_students']) ?></td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>

                            <!-- Charts -->
                            <div class="row mt-4">
                                <div class="col-md-6">
                                    <div class="card shadow mb-4">
                                        <div class="card-header py-3">
                                            <h6 class="m-0 font-weight-bold text-primary">
                                                <?php echo ($data['case'] === 5) ? "Grade vs Average Score" : "Line Chart"; ?>
                                            </h6>
                                        </div>
                                        <div class="card-body">
                                            <canvas id="chart1"
                                                    data-labels='<?= json_encode(array_column($data['results'], $data['case'] === 5 ? "grade_name" : "term_name")) ?>'
                                                    data-scores='<?= json_encode(array_column($data['results'], "avg_score")) ?>'
                                                    width="400" height="250">
                                            </canvas>
                                        </div>
                                    </div>
                                </div>

                                <?php if ($data['case'] === 5): ?>
                                <div class="col-md-6">
                                    <div class="card shadow mb-4">
                                        <div class="card-header py-3">
                                            <h6 class="m-0 font-weight-bold text-primary">Subject vs Average Score</h6>
                                        </div>
                                        <div class="card-body">
                                            <canvas id="chart2"
                                                    data-labels='<?= json_encode(array_column($data['results'], "subject_name")) ?>'
                                                    data-scores='<?= json_encode(array_column($data['results'], "avg_score")) ?>'
                                                    width="400" height="250">
                                            </canvas>
                                        </div>
                                    </div>
                                </div>
                                <?php endif; ?>

                            </div>
                        <?php else: ?>
                            <p class="text-center text-muted mb-0">No data available for selected filters.</p>
                        <?php endif; ?>
                    </div>
                </div>
                <!-- /.container-fluid -->

            </div>
            <!-- End of Main Content -->

            <!-- Footer -->
            <footer class="sticky-footer bg-white">
                <?php include __DIR__ . '/../includes/footer.php'; ?>
            </footer>
        </div>
        <!-- End of Content Wrapper -->
    </div>
    <!-- End of Page Wrapper -->

    <!-- Scroll to Top -->
    <a class="scroll-to-top rounded" href="#page-top"><i class="fas fa-angle-up"></i></a>

    <!-- Logout Modal -->
    <?php include __DIR__ . '/../includes/logout_modal.php'; ?>

    <!-- JS -->
    <?php include __DIR__ . '/../../../public/assets/js/scripts.php'; ?>

</body>
</html>
