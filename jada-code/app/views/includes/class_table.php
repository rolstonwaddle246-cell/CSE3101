<?php
if (!isset($id)) $id = 'classesTable';
if (!isset($title)) $title = 'Classes';
if (!isset($classes)) $classes = [];
if (!isset($grades)) $grades = []; 
?>

<div class="container-fluid">

    <h1 class="h3 mb-2 text-gray-800"><?= htmlspecialchars($title) ?></h1>
    <p class="mb-4">View and manage classes.</p>

    <div class="card shadow mb-4">

        <!-- Card Header -->
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">Classes</h6>
            <a href="#" id="add-class" class="btn btn-primary btn-icon-split btn-sm">
                <span class="icon text-white-50"><i class="fas fa-plus"></i></span>
                <span class="text">Add Class</span>
            </a>
        </div>

        <div class="card-body">

            <!-- Search & Filters -->
            <div class="row mb-3">
                <div class="col-md-3">
                    <input type="text" id="searchInput" class="form-control" placeholder="Search...">
                </div>
                <div class="col-md-3">
                    <select id="gradeFilter" class="form-control">
                        <option value="">All Grades</option>
                        <?php foreach ($grades as $g): ?>
                            <option value="<?= $g['id'] ?>"><?= htmlspecialchars($g['grade_name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-3">
                    <select id="classNameFilter" class="form-control">
                        <option value="">All Classes</option>
                        <?php foreach ($classes as $class): ?>
                            <option value="<?= htmlspecialchars($class['class_name']) ?>"><?= htmlspecialchars($class['class_name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-3">
                    <select id="numStudentsFilter" class="form-control">
                        <option value="">All Students</option>
                        <?php foreach ($classes as $class): ?>
                            <option value="<?= htmlspecialchars($class['num_students']) ?>"><?= htmlspecialchars($class['num_students']) ?></option>
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
                            <th>Grade</th>
                            <th>Class Name</th>
                            <th>Number of Students</th>
                            <th style="min-width: 160px;">Actions</th>
                        </tr>
                    </thead>
                    <tbody id="classesTableBody">

                        <?php if (!empty($classes)): ?>
                            <?php foreach ($classes as $class): ?>
                                <tr data-id="<?= $class['id'] ?>">
                                    <td><?= htmlspecialchars($class['id']) ?></td>
                                    <!-- Add data-grade-id here -->
                                    <td data-grade-id="<?= $class['grade_id'] ?>"><?= htmlspecialchars($class['grade_name']) ?></td>
                                    <td><?= htmlspecialchars($class['class_name']) ?></td>
                                    <td><?= htmlspecialchars($class['num_students']) ?></td>
                                    <td class="action-buttons">
                                        <div class="btn-group" role="group" style="white-space: nowrap;">
                                            <button class="btn btn-info btn-sm edit-class" data-id="<?= $class['id'] ?>">Edit</button>
                                            <button class="btn btn-danger btn-sm delete-class" data-id="<?= $class['id'] ?>">Delete</button>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="5" class="text-center">No classes found</td>
                            </tr>
                        <?php endif; ?>

                        <!-- Hidden template for JS add row -->
                        <tr id="newClassTemplate" class="d-none">
                            <td>New</td>
                            <td>
                                <select class="form-control grade-name">
                                    <option value="">Select Grade</option>
                                    <?php foreach ($grades as $g): ?>
                                        <option value="<?= $g['id'] ?>"><?= htmlspecialchars($g['grade_name']) ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </td>
                            <td><input type="text" class="form-control class-name" placeholder="Class name"></td>
                            <td><input type="number" class="form-control num-students" value="0" min="0"></td>
                            <td class="action-buttons">
                                <button class="btn btn-success btn-sm save-class">Save</button>
                                <button class="btn btn-secondary btn-sm cancel-class">Cancel</button>
                            </td>
                        </tr>

                    </tbody>
                </table>
            </div>

        </div>
    </div>
</div>

<!-- Pass grades to JS -->
<script>
const GRADES = <?= json_encode($grades) ?>;
</script>

<style>
.highlight-row {
    background-color: #fff3cd !important;
    transition: background-color 0.3s ease;
}
.action-buttons {
    min-width: 160px;
}
.action-buttons .btn {
    min-width: 70px;
    margin-right: 5px;
    white-space: nowrap;
}
</style>

