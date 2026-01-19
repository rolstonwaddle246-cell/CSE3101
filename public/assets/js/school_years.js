// school_years.js
console.log('school_years.js loaded');

$(document).ready(function() {

    // Initialize DataTable
    var table = $('#schoolYearsTable').DataTable();

    // -------------------------------
    // Add new school year
    // -------------------------------
    $('#add-school-year').on('click', function(e) {
        e.preventDefault();
        e.stopPropagation();

        console.log('Add School Year button clicked!');

        // Prevent multiple "new-row" placeholders
        if ($('#schoolYearsTable tbody tr.new-row').length > 0) {
            return; // already a row being added, do nothing
        }

        var newRow = `
            <tr class="new-row">
                <td class="editable year">
                    <input type="text" class="form-control form-control-sm year-input" placeholder="YYYY/YYYY">
                </td>
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
            </tr>
        `;

        table.row.add($(newRow)).draw(false);
    });

    // -------------------------------
    // Save new school year
    // -------------------------------
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

        $.post('index.php?action=store_school_year', { school_year, status }, function(response) {
            console.log('New school year ID:', response.id);
            var newId = response.id || 'new';

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

    // -------------------------------
    // Cancel new row
    // -------------------------------
    $('#schoolYearsTable').on('click', '.cancel-new', function(e) {
        e.preventDefault();
        e.stopPropagation();

        var row = $(this).closest('tr');
        table.row(row).remove().draw(false);
        
    });

    // -------------------------------
    // Delete school year
    // -------------------------------
    $('#schoolYearsTable').on('click', '.delete-btn', function(e) {
        e.preventDefault();
        e.stopPropagation();

        if (!confirm('Are you sure you want to delete this school year?')) return;

        var row = $(this).closest('tr');
        var id = $(this).data('id');

        $.post('index.php?action=delete_school_year', { id }, function(response) {
            row.remove();
            if ($('#schoolYearsTable tbody tr').length === 0) {
                $('#schoolYearsTable tbody').append('<tr><td colspan="3" class="text-center">No data available</td></tr>');
            }
        }).fail(function() {
            alert('Error deleting school year.');
        });
    });

    // -------------------------------
    // Edit school year
    // -------------------------------
    $('#schoolYearsTable').on('click', '.edit-btn', function(e) {
        e.preventDefault();
        e.stopPropagation();

        var row = $(this).closest('tr');
        row.find('.editable').each(function() {
            var value = $(this).text();
            $(this).data('original', value);

            if ($(this).hasClass('status')) {
                $(this).html(`
                    <select class="form-control form-control-sm status-input">
                        <option value="Active" ${value==='Active'?'selected':''}>Active</option>
                        <option value="Inactive" ${value==='Inactive'?'selected':''}>Inactive</option>
                    </select>
                `);
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

    // -------------------------------
    // Save edit
    // -------------------------------
    $('#schoolYearsTable').on('click', '.save-btn', function(e) {
        e.preventDefault();
        e.stopPropagation();

        var row = $(this).closest('tr');
        var id = $(this).data('id');
        var school_year = row.find('input.year-input').val();
        var status = row.find('select.status-input').val();

        if (!school_year) {
            alert('Enter a school year');
            return;
        }

        console.log('Saving:', { school_year, status });

        $.post('index.php?action=update_school_year', { id, school_year, status }, function(response) {
            row.find('.year').text(school_year);
            row.find('.status').text(status);

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

    // -------------------------------
    // Cancel edit
    // -------------------------------
    $('#schoolYearsTable').on('click', '.cancel-btn', function(e) {
        e.preventDefault();
        e.stopPropagation();

        var row = $(this).closest('tr');
        row.find('.editable').each(function() {
            var original = $(this).data('original');
            $(this).text(original);
        });

        row.find('.save-btn').replaceWith(`<a href="#" class="btn btn-info btn-icon-split btn-sm edit-btn" data-id="${row.data('id')}">
                                            <span class="icon text-white-50"><i class="fas fa-edit"></i></span>
                                            <span class="text">Edit</span></a>`);

        $(this).replaceWith(`<a href="#" class="btn btn-danger btn-icon-split btn-sm delete-btn" data-id="${row.data('id')}">
                            <span class="icon text-white-50"><i class="fas fa-trash"></i></span>
                            <span class="text">Delete</span></a>`);
    });

});
