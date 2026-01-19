<?php
if (!isset($id)) $id = 'gradesTable';
if (!isset($title)) $title = 'Grades';
if (!isset($grades)) $grades = [];
?>

<div class="container-fluid">

    <!-- Page Heading -->
    <h1 class="h3 mb-2 text-gray-800"><?= htmlspecialchars($title) ?></h1>
    <p class="mb-4">View and manage grades.</p>

    <div class="card shadow mb-4">

        <!-- Card Header -->
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">Grades</h6>
            <a href="#" id="add-grade" class="btn btn-primary btn-icon-split btn-sm">
                <span class="icon text-white-50"><i class="fas fa-plus"></i></span>
                <span class="text">Add Grade</span>
            </a>
        </div>

        <!-- Card Body -->
        <div class="card-body">

            <!-- Search & Filter -->
            <div class="row mb-3">
                <div class="col-md-6">
                    <input type="text" id="searchInput" class="form-control" placeholder="Search grade...">
                </div>
                <div class="col-md-6">
                    <select id="gradeFilter" class="form-control">
                        <option value="">All Grades</option>
                        <?php foreach ($grades as $grade): ?>
                            <option value="<?= htmlspecialchars($grade['grade_name']) ?>">
                                <?= htmlspecialchars($grade['grade_name']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <!-- Table -->
            <div class="table-responsive">
                <table class="table table-bordered" id="<?= htmlspecialchars($id) ?>" width="100%" cellspacing="0">
                    <thead class="bg-primary text-white">
                        <tr>
                            <th>ID</th>
                            <th>Grade Name</th>
                            <th>Number of Classes</th>
                        </tr>
                    </thead>
                    <tbody id="gradesTableBody">

                        <?php if (!empty($grades)): ?>
                            <?php foreach ($grades as $grade): ?>
                                <tr data-id="<?= $grade['id'] ?>">
                                    <td><?= htmlspecialchars($grade['id']) ?></td>
                                    <td><?= htmlspecialchars($grade['grade_name']) ?></td>
                                    <td><?= htmlspecialchars($grade['number_of_classes']) ?></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="3" class="text-center">No grades found</td>
                            </tr>
                        <?php endif; ?>

                    </tbody>
                </table>
            </div>

        </div>
    </div>
</div>

<!-- Highlight CSS -->
<style>
.highlight-row {
    background-color: #fff3cd !important; 
    transition: background-color 0.3s ease;
}
</style>
