<?php
if (!isset($id)) $id = 'subjectsTable';
if (!isset($title)) $title = 'Subjects';
if (!isset($subjects)) $subjects = [];
if (!isset($grades)) $grades = [];
?>


<div class="card shadow mb-4">
    <div class="card-header py-3 d-flex justify-content-between align-items-center">
        <h6 class="m-0 font-weight-bold text-primary"><?= htmlspecialchars($title) ?></h6>
        <a href="#" id="add-subject" class="btn btn-primary btn-sm">
            <i class="fas fa-plus"></i> Add Subject
        </a>
    </div>

    <div class="card-body">

        <!-- Filters -->
        <div class="row mb-3">
            <!-- Search -->
            <div class="col-md-4">
                <input type="text" id="searchInput" class="form-control" placeholder="Search subjects...">
            </div>

            <!-- Subject Filter -->
            <div class="col-md-4">
                <select id="subjectFilter" class="form-control">
                    <option value="">All Subjects</option>
                    <?php foreach ($subjects as $s): ?>
                        <option value="<?= htmlspecialchars($s['subject_name']) ?>">
                            <?= htmlspecialchars($s['subject_name']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <!-- Grade Filter -->
            <div class="col-md-4">
                <select id="gradeFilter" class="form-control">
                    <option value="">All Grades</option>
                    <?php foreach ($grades as $g): ?>
                        <option value="<?= htmlspecialchars($g['grade_name']) ?>">
                            <?= htmlspecialchars($g['grade_name']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>

        <!-- Table -->
        <div class="table-responsive">
            <table class="table table-bordered" id="<?= $id ?>">
                <thead class="bg-primary text-white">
                    <tr>
                        <th>ID</th>
                        <th>Subject</th>
                        <th>Grade</th>
                        <th>No. of Classes</th>
                        <th style="min-width:160px;">Actions</th>
                    </tr>
                </thead>

                <tbody id="subjectsTableBody">
                <?php foreach ($subjects as $row): ?>
                    <tr data-id="<?= $row['id'] ?>">
                        <td><?= $row['id'] ?></td>
                        <td class="subject"><?= htmlspecialchars($row['subject_name']) ?></td>
                        <td class="grade"><?= htmlspecialchars($row['grade_name']) ?></td>
                        <td class="class"><?= htmlspecialchars($row['number_of_class']) ?></td>
                        <td class="action-buttons">
                            <button class="btn btn-info btn-sm edit-btn">Edit</button>
                            <button class="btn btn-danger btn-sm delete-btn">Delete</button>
                        </td>
                    </tr>
                <?php endforeach; ?>

                <!-- NEW ROW TEMPLATE -->
                <tr id="newSubjectTemplate" class="d-none">
                    <td>New</td>

                    <td class="subject">
                        <input type="text" class="form-control subject-input" placeholder="Subject Name">
                    </td>

                    <td class="grade">
                        <select class="form-control grade-select">
                            <option value="">Select Grade</option>
                            <?php foreach ($grades as $g): ?>
                                <option value="<?= $g['id'] ?>">
                                    <?= htmlspecialchars($g['grade_name']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </td>

                    <td class="class">
                        <input type="number" min="1" class="form-control class-input" placeholder="Number of Classes">
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
