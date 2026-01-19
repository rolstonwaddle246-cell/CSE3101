<?php
require_once __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../includes/sidebar.php';
?>

<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Score Management</h1>
    </div>

    <?php if (isset($error) && $error): ?>
        <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
    <?php endif; ?>

    <?php if (isset($success) && $success): ?>
        <div class="alert alert-success"><?php echo htmlspecialchars($success); ?></div>
    <?php endif; ?>

    <div class="row">
        <!-- Selection Form -->
        <div class="col-lg-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Select Class, Subject & Term</h6>
                </div>
                <div class="card-body">
                    <form id="selectionForm" method="GET" action="index.php">
                        <input type="hidden" name="action" value="score_entry">
                        <div class="row">
                            <div class="col-md-4">
                                <label for="class_id">Class:</label>
                                <select name="class_id" id="class_id" class="form-control" required>
                                    <option value="">Select Class</option>
                                    <?php foreach ($assignedClasses as $class): ?>
                                        <option value="<?php echo $class['class_id']; ?>" <?php echo ($selectedClassId == $class['class_id']) ? 'selected' : ''; ?>>
                                            <?php echo htmlspecialchars($class['grade_name'] . ' - ' . $class['class_name']); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label for="subject_id">Subject:</label>
                                <select name="subject_id" id="subject_id" class="form-control" <?php echo $selectedClassId ? '' : 'disabled'; ?>>
                                    <option value="">Select Subject</option>
                                    <?php foreach ($subjects as $subject): ?>
                                        <option value="<?php echo $subject['subject_id']; ?>" <?php echo ($selectedSubjectId == $subject['subject_id']) ? 'selected' : ''; ?>>
                                            <?php echo htmlspecialchars($subject['subject_name']); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label for="term_id">Term:</label>
                                <select name="term_id" id="term_id" class="form-control" required>
                                    <option value="">Select Term</option>
                                    <?php foreach ($terms as $term): ?>
                                        <option value="<?php echo $term['term_id']; ?>" <?php echo ($selectedTermId == $term['term_id']) ? 'selected' : ''; ?>>
                                            <?php echo htmlspecialchars($term['term_name'] . ' (' . $term['school_year'] . ')'); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label>&nbsp;</label><br>
                                <button type="submit" class="btn btn-primary">Load Students</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <?php if ($selectedClassId && $selectedSubjectId && $selectedTermId && !empty($students)): ?>
    <!-- Score Entry Form -->
    <div class="row">
        <div class="col-lg-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Enter Scores</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered" id="scoreTable">
                            <thead>
                                <tr>
                                    <th>Student ID</th>
                                    <th>Student Name</th>
                                    <th>Score</th>
                                    <th>Max Score</th>
                                    <th>Remarks</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($students as $student): ?>
                                <tr data-student-id="<?php echo $student['student_id']; ?>">
                                    <td><?php echo htmlspecialchars($student['student_number']); ?></td>
                                    <td><?php echo htmlspecialchars($student['first_name'] . ' ' . $student['last_name']); ?></td>
                                    <td>
                                        <input type="number" class="form-control score-input" step="0.01" min="0"
                                               data-student-id="<?php echo $student['student_id']; ?>" placeholder="0.00">
                                    </td>
                                    <td>
                                        <input type="number" class="form-control max-score-input" step="0.01" min="0" value="100"
                                               data-student-id="<?php echo $student['student_id']; ?>">
                                    </td>
                                    <td>
                                        <input type="text" class="form-control remarks-input"
                                               data-student-id="<?php echo $student['student_id']; ?>" placeholder="Optional remarks">
                                    </td>
                                    <td>
                                        <button type="button" class="btn btn-sm btn-success save-individual"
                                                data-student-id="<?php echo $student['student_id']; ?>">Save</button>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-3">
                        <button type="button" class="btn btn-primary" id="saveAllBtn">Save All Scores</button>
                        <a href="index.php?action=view_scores&class_id=<?php echo $selectedClassId; ?>&subject_id=<?php echo $selectedSubjectId; ?>&term_id=<?php echo $selectedTermId; ?>" class="btn btn-info">View Scores</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>
</div>

<script>
$(document).ready(function() {
    // Handle class selection change
    $('#class_id').change(function() {
        var classId = $(this).val();
        if (classId) {
            // Load subjects for selected class
            $.get('index.php?action=get_subjects&class_id=' + classId, function(data) {
                $('#subject_id').html('<option value="">Select Subject</option>');
                if (data.success) {
                    data.subjects.forEach(function(subject) {
                        $('#subject_id').append('<option value="' + subject.subject_id + '">' + subject.subject_name + '</option>');
                    });
                }
                $('#subject_id').prop('disabled', false);
            });
        } else {
            $('#subject_id').html('<option value="">Select Subject</option>').prop('disabled', true);
        }
    });

    // Handle individual save
    $('.save-individual').click(function() {
        var studentId = $(this).data('student-id');
        saveScore(studentId);
    });

    // Handle save all
    $('#saveAllBtn').click(function() {
        var promises = [];
        $('.score-input').each(function() {
            var studentId = $(this).data('student-id');
            var score = $(this).val();
            if (score !== '') {
                promises.push(saveScore(studentId));
            }
        });

        if (promises.length > 0) {
            Promise.all(promises).then(function() {
                alert('All scores saved successfully!');
                location.reload();
            });
        } else {
            alert('No scores to save.');
        }
    });

    function saveScore(studentId) {
        return new Promise(function(resolve, reject) {
            var score = $('.score-input[data-student-id="' + studentId + '"]').val();
            var maxScore = $('.max-score-input[data-student-id="' + studentId + '"]').val();
            var remarks = $('.remarks-input[data-student-id="' + studentId + '"]').val();

            if (score === '') {
                resolve();
                return;
            }

            $.post('index.php?action=store_score', {
                student_id: studentId,
                subject_id: '<?php echo $selectedSubjectId; ?>',
                term_id: '<?php echo $selectedTermId; ?>',
                score: score,
                max_score: maxScore,
                remarks: remarks
            }, function(response) {
                if (response.success) {
                    $('.save-individual[data-student-id="' + studentId + '"]').removeClass('btn-success').addClass('btn-secondary').text('Saved');
                } else {
                    alert('Error saving score for student ' + studentId + ': ' + response.error);
                }
                resolve();
            }, 'json').fail(function() {
                alert('Network error while saving score.');
                reject();
            });
        });
    }
});
</script>

<?php
require_once __DIR__ . '/../includes/footer.php';
?>