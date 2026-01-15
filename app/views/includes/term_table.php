<?php
if (!isset($id)) {
    $id = '';
}

if (!isset($title)) {
    $title = 'Terms Table';
}

if (!isset($columns)) {
    $columns = [];
}

if (!isset($data)) {
    $data = [];
}
?>

<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary"><?= htmlspecialchars($title) ?></h6>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered" id="<?= htmlspecialchars($id) ?>" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <?php foreach($columns as $col): ?>
                            <th><?= htmlspecialchars($col) ?></th>
                        <?php endforeach; ?>
                    </tr>
                </thead>
                <tfoot>
                    <tr>
                        <?php foreach($columns as $col): ?>
                            <th><?= htmlspecialchars($col) ?></th>
                        <?php endforeach; ?>
                    </tr>
                </tfoot>

                <tbody>
                    <?php if (!empty($data)): ?>
                        <?php foreach($data as $row): ?>
                            <tr class="term-row" data-id="<?= $row['id'] ?>">
                                <td class="year"><?= htmlspecialchars($row['Year'] ?? '') ?></td>
                                <td class="editable term"><?= htmlspecialchars($row['Term']) ?></td>
                                <td class="editable start"><?= htmlspecialchars($row['Start Date']) ?></td>
                                <td class="editable end"><?= htmlspecialchars($row['End Date']) ?></td>
                                <td class="editable status"><?= htmlspecialchars($row['Status']) ?></td>
                                <td>
                                    <button class="btn btn-info btn-icon-split btn-sm edit-btn" data-id="<?= $row['id'] ?>">
                                        <span class="icon text-white-50"><i class="fas fa-edit"></i></span>
                                        <span class="text">Edit</span>
                                    </button>
                                    <button class="btn btn-danger btn-icon-split btn-sm delete-btn" data-id="<?= $row['id'] ?>">
                                        <span class="icon text-white-50"><i class="fas fa-trash"></i></span>
                                        <span class="text">Delete</span>
                                    </button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="<?= count($columns) ?>" class="text-center">No data available</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
