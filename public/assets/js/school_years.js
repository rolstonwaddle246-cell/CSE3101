// SCHOOL YEARS 

$(document).ready(function() {
    $('#schoolYearsTable').DataTable();
});

    $(document).ready(function() {

        $('#schoolYearsTable').on('click', '.edit-btn, .delete-btn', function(e) {
        e.preventDefault();
        e.stopPropagation();
    });

        // Add new row
        $('#schoolYearsTable').on('click', '#add-school-year', function(e) {
            e.preventDefault();
            e.stopPropagation();
            var newRow = `<tr>
                <td class="editable year"><input type="text" class="form-control form-control-sm year-input" placeholder="YYYY/YYYY"></td>
                <td class="editable status">
                    <select class="form-control form-control-sm status-input">
                        <option value="Active">Active</option>
                        <option value="Inactive">Inactive</option>
                    </select>
                </td>
                <td>
                    <button class="btn btn-success btn-sm save-new">Save</button>
                    <button class="btn btn-secondary btn-sm cancel-new">Cancel</button>
                </td>
            </tr>`;

            var tbody = $('#schoolYearsTable tbody');
            tbody.find('td.text-center').closest('tr').remove();
            tbody.prepend(newRow);
        });

        // Save new school year to DB
    $('#schoolYearsTable').on('click', '.save-new', function(e) {
    e.preventDefault();
    e.stopPropagation();

    var row = $(this).closest('tr');
    var school_year = row.find('.year-input').val();
    var status = row.find('.status-input').val();

    if (!school_year) {
        alert('Enter a school year');
        return;
    }

    $.post('index.php?action=store_school_year', {school_year, status}, function(response) {
        // Assuming response contains the new ID from DB
        var newId = response.id || 'new'; 

        // Update row to normal display
        row.html(`
            <td class="editable year">${school_year}</td>
            <td class="editable status">${status}</td>
            <td>
                <a href="#" class="btn btn-info btn-icon-split btn-sm edit-btn" data-id="${newId}">
                    <span class="icon text-white-50"><i class="fas fa-edit"></i></span>
                    <span class="text">Edit</span>
                </a>
                <a href="#" class="btn btn-danger btn-icon-split btn-sm delete-btn" data-id="${newId}">
                    <span class="icon text-white-50"><i class="fas fa-trash"></i></span>
                    <span class="text">Delete</span>
                </a>
            </td>
        `);
    }).fail(function() {
        alert('Error adding school year.');
    });
});

        // Cancel adding new row
        $('#schoolYearsTable').on('click', '.cancel-new', function() {
            $(this).closest('tr').remove();
            if ($('#schoolYearsTable tbody tr').length === 0) {
                $('#schoolYearsTable tbody').append('<tr><td colspan="3" class="text-center">No data available</td></tr>');
            }
        });

        // Delete school year
    $('#schoolYearsTable').on('click', '.delete-btn', function(e) {
    e.preventDefault();
    e.stopPropagation();

    if (!confirm('Are you sure you want to delete this school year?')) return;

    var row = $(this).closest('tr');
    var id = $(this).data('id');

    $.post('index.php?action=delete_school_year', {id}, function(response) {
        // Remove row from DOM
        row.remove();

        // If table is empty, show "No data"
        if ($('#schoolYearsTable tbody tr').length === 0) {
            $('#schoolYearsTable tbody').append('<tr><td colspan="3" class="text-center">No data available</td></tr>');
        }
    }).fail(function() {
        alert('Error deleting school year.');
    });
});

        // Edit school year
        $('#schoolYearsTable').on('click', '.edit-btn', function(e) {
            e.preventDefault();
            e.stopPropagation();
            var row = $(this).closest('tr');
            row.find('.editable').each(function() {
                var value = $(this).text();
                $(this).data('original', value);
                if ($(this).hasClass('status')) {
                    $(this).html(`<select class="form-control form-control-sm status-input">
                                    <option value="Active" ${value==='Active'?'selected':''}>Active</option>
                                    <option value="Inactive" ${value==='Inactive'?'selected':''}>Inactive</option>
                                  </select>`);
                } else {
                    $(this).html('<input type="text" class="form-control form-control-sm year-input" value="'+value+'">');
                }
            });

            $(this).replaceWith(`<button class="btn btn-primary btn-icon-split btn-sm save-btn" data-id="${$(this).data('id')}">
                                    <span class="icon text-white-50"><i class="fas fa-check"></i></span>
                                    <span class="text">Save</span></button>`);
            row.find('.delete-btn').replaceWith(`<button class="btn btn-secondary btn-icon-split btn-sm cancel-btn">
                                                    <span class="icon text-white-50"><i class="fas fa-times"></i></span>
                                                    <span class="text">Cancel</span></button>`);
        });

        // Save edit
    $('#schoolYearsTable').on('click', '.save-btn', function(e) {
    e.preventDefault();
    e.stopPropagation();

    var row = $(this).closest('tr');
    var id = $(this).data('id');

    var school_year = row.find('input.year-input').val();
    var status = row.find('select.status-input').val();
    console.log('Values grabbed:', {school_year, status});

    if (!school_year) {
        alert('Enter a school year');
        return;
    }

    $.post('index.php?action=update_school_year', {id, school_year, status}, function(response) {
        // Update the table row visually
        row.find('.year').text(school_year);
        row.find('.status').text(status);

        // Restore Edit/Delete buttons
        row.find('.save-btn').replaceWith(`<a href="#" class="btn btn-info btn-icon-split btn-sm edit-btn" data-id="${id}">
            <span class="icon text-white-50"><i class="fas fa-edit"></i></span>
            <span class="text">Edit</span></a>`);

        row.find('.cancel-btn').replaceWith(`<a href="#" class="btn btn-danger btn-icon-split btn-sm delete-btn" data-id="${id}">
            <span class="icon text-white-50"><i class="fas fa-trash"></i></span>
            <span class="text">Delete</span></a>`);
    }).fail(function() {
        alert('Error updating school year.');
    });
});

        // Cancel edit
        $('#schoolYearsTable').on('click', '.cancel-btn', function(e) {
    e.preventDefault();
    e.stopPropagation();

    var row = $(this).closest('tr');
    row.find('.editable').each(function() {
        var original = $(this).data('original');
        $(this).text(original);
    });

    // Replace save and cancel buttons back to edit and delete
    row.find('.save-btn').replaceWith(`<button class="btn btn-info btn-icon-split btn-sm edit-btn" data-id="${row.data('id')}">
                                         <span class="icon text-white-50"><i class="fas fa-edit"></i></span>
                                         <span class="text">Edit</span></button>`);

    $(this).replaceWith(`<button class="btn btn-danger btn-icon-split btn-sm delete-btn" data-id="${row.data('id')}">
                            <span class="icon text-white-50"><i class="fas fa-trash"></i></span>
                            <span class="text">Delete</span></button>`);
});

    });