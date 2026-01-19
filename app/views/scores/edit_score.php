<?php
require_once __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../includes/sidebar.php';
?>

<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Edit Score</h1>
        <a href="index.php?action=score_entry" class="btn btn-secondary">Back to Score Entry</a>
    </div>

    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-danger"><?php echo htmlspecialchars($_SESSION['error']); unset($_SESSION['error']); ?></div>
    <?php endif; ?>

    <?php if (isset($_SESSION['success'])): ?>
        <div class="alert alert-success"><?php echo htmlspecialchars($_SESSION['success']); unset($_SESSION['success']); ?></div>
    <?php endif; ?>

    <div class="row">
        <div class="col-lg-6">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Edit Score Details</h6>
                </div>
                <div class="card-body">
                    <form method="POST" action="index.php?action=update_score">
                        <input type="hidden" name="score_id" value="<?php echo $score['id']; ?>">

                        <div class="form-group">
                            <label for="student">Student:</label>
                            <input type="text" class="form-control" value="<?php echo htmlspecialchars($score['first_name'] . ' ' . $score['last_name'] . ' (' . $score['student_number'] . ')'); ?>" readonly>
                        </div>

                        <div class="form-group">
                            <label for="subject">Subject:</label>
                            <input type="text" class="form-control" value="<?php echo htmlspecialchars($score['subject_name']); ?>" readonly>
                        </div>

                        <div class="form-group">
                            <label for="term">Term:</label>
                            <input type="text" class="form-control" value="<?php echo htmlspecialchars($score['term_name']); ?>" readonly>
                        </div>

                        <div class="form-group">
                            <label for="score">Score:</label>
                            <input type="number" name="score" id="score" class="form-control" step="0.01" min="0"
                                   value="<?php echo $score['score']; ?>" required>
                        </div>

                        <div class="form-group">
                            <label for="max_score">Max Score:</label>
                            <input type="number" name="max_score" id="max_score" class="form-control" step="0.01" min="0"
                                   value="<?php echo $score['max_score']; ?>" required>
                        </div>

                        <div class="form-group">
                            <label for="remarks">Remarks:</label>
                            <textarea name="remarks" id="remarks" class="form-control" rows="3"><?php echo htmlspecialchars($score['remarks'] ?? ''); ?></textarea>
                        </div>

                        <div class="form-group">
                            <label>Percentage:</label>
                            <span id="percentage" class="badge badge-primary">0.0%</span>
                        </div>

                        <button type="submit" class="btn btn-primary">Update Score</button>
                        <a href="index.php?action=score_entry" class="btn btn-secondary">Cancel</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    function updatePercentage() {
        var score = parseFloat($('#score').val()) || 0;
        var maxScore = parseFloat($('#max_score').val()) || 100;
        var percentage = maxScore > 0 ? (score / maxScore) * 100 : 0;

        $('#percentage').text(percentage.toFixed(1) + '%');

        // Update badge color
        $('#percentage').removeClass('badge-primary badge-success badge-warning badge-danger');
        if (percentage >= 85) {
            $('#percentage').addClass('badge-success');
        } else if (percentage >= 75) {
            $('#percentage').addClass('badge-warning');
        } else {
            $('#percentage').addClass('badge-danger');
        }
    }

    $('#score, #max_score').on('input', updatePercentage);
    updatePercentage(); // Initial calculation
});
</script>

<?php
require_once __DIR__ . '/../includes/footer.php';
?>