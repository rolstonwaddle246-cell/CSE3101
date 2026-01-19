<?php
require_once __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../includes/sidebar.php';
?>

<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Score List</h1>
        <a href="index.php?action=score_entry" class="btn btn-primary">Back to Score Entry</a>
    </div>

    <?php if (isset($error) && $error): ?>
        <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
    <?php endif; ?>

    <div class="row">
        <div class="col-lg-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Student Scores</h6>
                </div>
                <div class="card-body">
                    <?php if (!empty($scores)): ?>
                        <div class="table-responsive">
                            <table class="table table-bordered" id="scoreTable">
                                <thead>
                                    <tr>
                                        <th>Student ID</th>
                                        <th>Student Name</th>
                                        <th>Score</th>
                                        <th>Percentage</th>
                                        <th>Grade</th>
                                        <th>Remarks</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($scores as $score): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($score['student_number']); ?></td>
                                        <td><?php echo htmlspecialchars($score['first_name'] . ' ' . $score['last_name']); ?></td>
                                        <td><?php echo number_format($score['score'], 2); ?>/<?php echo number_format($score['max_score'], 2); ?></td>
                                        <td>
                                            <?php
                                            $percentage = ($score['max_score'] > 0) ? ($score['score'] / $score['max_score']) * 100 : 0;
                                            $badgeClass = $percentage >= 85 ? 'badge-success' : ($percentage >= 75 ? 'badge-warning' : 'badge-danger');
                                            ?>
                                            <span class="badge <?php echo $badgeClass; ?>"><?php echo number_format($percentage, 1); ?>%</span>
                                        </td>
                                        <td>
                                            <?php
                                            if ($percentage >= 85) echo 'A';
                                            elseif ($percentage >= 75) echo 'B';
                                            elseif ($percentage >= 65) echo 'C';
                                            elseif ($percentage >= 50) echo 'D';
                                            else echo 'E';
                                            ?>
                                        </td>
                                        <td><?php echo htmlspecialchars($score['remarks'] ?? ''); ?></td>
                                        <td>
                                            <a href="index.php?action=edit_score&id=<?php echo $score['id']; ?>" class="btn btn-sm btn-primary">Edit</a>
                                            <button type="button" class="btn btn-sm btn-danger delete-score" data-id="<?php echo $score['id']; ?>">Delete</button>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>

                        <?php if ($classAverage): ?>
                        <div class="mt-3">
                            <div class="alert alert-info">
                                <strong>Class Average:</strong> <?php echo number_format($classAverage['average'], 2); ?> (<?php echo $classAverage['count']; ?> students)
                            </div>
                        </div>
                        <?php endif; ?>

                    <?php else: ?>
                        <div class="alert alert-info">No scores found for the selected criteria.</div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    // Handle delete score
    $('.delete-score').click(function() {
        var scoreId = $(this).data('id');
        if (confirm('Are you sure you want to delete this score?')) {
            $.post('index.php?action=delete_score', { id: scoreId }, function(response) {
                if (response.success) {
                    alert('Score deleted successfully!');
                    location.reload();
                } else {
                    alert('Error deleting score: ' + response.error);
                }
            }, 'json').fail(function() {
                alert('Network error while deleting score.');
            });
        }
    });
});
</script>

<?php
require_once __DIR__ . '/../includes/footer.php';
?>