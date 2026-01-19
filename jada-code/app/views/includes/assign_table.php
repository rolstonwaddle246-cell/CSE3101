<?php
if (!isset($id)) $id = 'assignteachersTable';
if (!isset($title)) $title = 'Assign Teachers';
if (!isset($columns)) $columns = ['ID', 'Name', 'Class', 'Grade', 'Actions'];
if (!isset($assignments)) $assignments = [];
?>

<div class="card shadow mb-4">
    <div class="card-header py-3 d-flex justify-content-between align-items-center">
        <h6 class="m-0 font-weight-bold text-primary"><?= htmlspecialchars($title) ?></h6>
        <a href="#" id="add-assignment" class="btn btn-primary btn-icon-split btn-sm">
            <span class="icon text-white-50"><i class="fas fa-plus"></i></span>
            <span class="text">Assign Teacher</span>
        </a>
    </div>
    <div class="card-body">

        <!-- Filters and Search -->
        <div class="row mb-3">
            <div class="col-md-3">
                <input type="text" id="searchInput" class="form-control" placeholder="Search teacher...">
            </div>
            <div class="col-md-3">
                <select id="teacherFilter" class="form-control">
                    <option value="">All Teachers</option>
                    <?php foreach ($teachers as $teacher): ?>
                        <option value="<?= htmlspecialchars($teacher['first_name'] . ' ' . $teacher['last_name']) ?>"><?= htmlspecialchars($teacher['first_name'] . ' ' . $teacher['last_name']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-3">
                <select id="gradeFilter" class="form-control">
                    <option value="">All Grades</option>
                    <?php foreach ($grades as $grade): ?>
                        <option value="<?= htmlspecialchars($grade['grade_name']) ?>"><?= htmlspecialchars($grade['grade_name']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-3">
                <select id="classFilter" class="form-control">
                    <option value="">All Classes</option>
                    <?php foreach ($classes as $class): ?>
                        <option value="<?= htmlspecialchars($class['class_name']) ?>"><?= htmlspecialchars($class['class_name']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>

        <!-- Table -->
        <div class="table-responsive">
            <table class="table table-bordered" id="<?= htmlspecialchars($id) ?>" width="100%" cellspacing="0">
                <thead class="bg-primary text-white">
                    <tr>
                        <?php foreach ($columns as $col): ?>
                            <th><?= htmlspecialchars($col) ?></th>
                        <?php endforeach; ?>
                    </tr>
                </thead>
                <tbody id="assignTeachersBody">
                    <?php if (!empty($assignments)): ?>
                        <?php foreach ($assignments as $row): ?>
                            <tr data-id="<?= $row['id'] ?>">
                                <td><?= htmlspecialchars($row['id']) ?></td>
                                <td class="teacher"><?= htmlspecialchars($row['Name']) ?></td>
                                <td class="class"><?= htmlspecialchars($row['Class']) ?></td>
                                <td class="grade"><?= htmlspecialchars($row['Grade']) ?></td>
                                <td class="action-buttons">
                                    <button class="btn btn-info btn-sm edit-btn">Edit</button>
                                    <button class="btn btn-danger btn-sm delete-btn">Delete</button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="<?= count($columns) ?>" class="text-center">No assignments found</td>
                        </tr>
                    <?php endif; ?>

                    <!-- Hidden template for JS -->
                    <tr id="newAssignmentTemplate" class="d-none">
                        <td>New</td>
                        <td class="teacher">
                            <select class="form-control teacher-select">
                                <option value="">Select Teacher</option>
                                <?php foreach ($teachers as $t): ?>
                                    <option value="<?= $t['user_id'] ?>"><?= htmlspecialchars($t['first_name'] . ' ' . $t['last_name']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </td>
                        <td class="class">
                            <select class="form-control class-select">
                                <option value="">Select Class</option>
                                <?php foreach ($classes as $c): ?>
                                    <option value="<?= $c['id'] ?>"><?= htmlspecialchars($c['class_name']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </td>
                        <td class="grade">
                            <select class="form-control grade-select">
                                <option value="">Select Grade</option>
                                <?php foreach ($grades as $g): ?>
                                    <option value="<?= $g['id'] ?>"><?= htmlspecialchars($g['grade_name']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </td>
                        <td class="action-buttons">
                            <button class="btn btn-success btn-sm save-btn">Save</button>
                            <button class="btn btn-secondary btn-sm cancel-btn">Cancel</button>
                        </td>
                    </tr>

                </tbody>
            </table>
        </div>
    </div>
</div>
<style>
.highlight-row {
    background-color: #fff3cd !important;
    transition: background-color 0.3s ease;
}
</style>
