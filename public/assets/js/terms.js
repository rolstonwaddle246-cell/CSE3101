// TERMS TABLE
    //scroll to terms table on school year row click
    $(document).on("click", ".school-year-row", function (e) {
        console.log('terms.js loaded');
        if (
            $(e.target).closest(
                ".edit-btn, .delete-btn, button, input, select, a"
            ).length
        ) {
            return;
        }

        var yearId = $(this).data("id");
        var yearText = $(this).find('td.year').text();
        window.selectedYearId = yearId;
        window.selectedYearText = yearText;
        
        window.location.href = "index.php?action=school_years&year_id=" + yearId;

        console.log('Selected year:', yearText, 'ID:', yearId);
    });


$(document).ready(function() {
    $('#termsTable').DataTable();
});

$(document).ready(function() {

    $(document).on('click', '.edit-btn, .delete-btn', function(e) {
        e.preventDefault();
        e.stopPropagation();
    });

    // Add new term row
    $(document).on('click', '#add-term', function(e) {
        e.preventDefault();
        e.stopPropagation();

        var newRow = `<tr>
            <td class="year">${window.selectedYearText}</td>
            <td class="editable term"><input type="text" class="form-control form-control-sm term-input" placeholder="Term Name"></td>
            <td class="editable start"><input type="date" class="form-control form-control-sm start-input w-100"></td>
            <td class="editable end"><input type="date" class="form-control form-control-sm end-input w-100"></td>
            <td class="editable status">
                <select class="form-control form-control-sm status-input">
                    <option value="Active">Active</option>
                    <option value="Inactive" selected>Inactive</option>
                </select>
            </td>
            <td>
                <button class="btn btn-success btn-sm save-new-term">Save</button>
                <button class="btn btn-secondary btn-sm cancel-new-term">Cancel</button>
            </td>
        </tr>`;

        var tbody = $('#termsTable tbody');
        tbody.find('td.text-center').closest('tr').remove(); // remove "No data available"
        tbody.prepend(newRow);
    });

    // Save new term
    $(document).on('click', '.save-new-term', function(e) {
        e.preventDefault();
        e.stopPropagation();

        var row = $(this).closest('tr');
        var term_name = row.find('.term-input').val();
        var start_date = row.find('.start-input').val();
        var end_date = row.find('.end-input').val();
        var status = row.find('.status-input').val();
        var school_year_id = window.selectedYearId; 

        if (!term_name || !start_date || !end_date) {
            alert('All fields are required');
            return;
        }

        $.post('index.php?action=store_term', { term_name, start_date, end_date, status, school_year_id }, function(response) {
            var newId = response.id || 'new';

            // Update row to normal display
            row.empty(); // clear the row completely
            row.append(`<td class="year">${school_year_id}</td>`);
            row.append(`<td class="editable term">${term_name}</td>`);
            row.append(`<td class="editable start">${start_date}</td>`);
            row.append(`<td class="editable end">${end_date}</td>`);
            row.append(`<td class="editable status">${status}</td>`);
            row.append(`
                <td>
                    <button class="btn btn-info btn-icon-split btn-sm edit-btn" data-id="${newId}">
                        <span class="icon text-white-50"><i class="fas fa-edit"></i></span>
                        <span class="text">Edit</span>
                    </button>
                    <button class="btn btn-danger btn-icon-split btn-sm delete-btn" data-id="${newId}">
                        <span class="icon text-white-50"><i class="fas fa-trash"></i></span>
                        <span class="text">Delete</span>
                    </button>
                </td>
            `);
        }).fail(function() {
            alert('Error adding term.');
        });
    });

    // Cancel new term
    $(document).on('click', '.cancel-new-term', function() {
        $(this).closest('tr').remove();
        if ($('#termsTable tbody tr').length === 0) {
            $('#termsTable tbody').append('<tr><td colspan="5" class="text-center">No data available</td></tr>');
        }
    });

    // Delete term
    $(document).on('click', '.delete-btn', function(e) {
        e.preventDefault();
        e.stopPropagation();

        if (!confirm('Are you sure you want to delete this term?')) return;

        var row = $(this).closest('tr');
        var id = $(this).data('id');

        $.post('index.php?action=delete_term', { id }, function() {
            row.remove();
            if ($('#termsTable tbody tr').length === 0) {
                $('#termsTable tbody').append('<tr><td colspan="5" class="text-center">No data available</td></tr>');
            }
        }).fail(function() {
            alert('Error deleting term.');
        });
    });

    // Edit term
    $(document).on('click', '.edit-btn', function(e) {
        e.preventDefault();
        e.stopPropagation();

        var row = $(this).closest('tr');
        row.find('.editable').not('.year').each(function() {
            var value = $(this).text();
            $(this).data('original', value);

            if ($(this).hasClass('status')) {
                $(this).html(`
                    <select class="form-control form-control-sm edit-status">
                        <option value="Active" ${value==='Active'?'selected':''}>Active</option>
                        <option value="Inactive" ${value==='Inactive'?'selected':''}>Inactive</option>
                    </select>
                `);
            } else if ($(this).hasClass('term')) {
                $(this).html('<input type="text" class="form-control form-control-sm edit-term" value="'+value+'">');
            } else if ($(this).hasClass('start')) {
                $(this).html('<input type="date" class="form-control form-control-sm edit-start w-100" value="'+value+'">');
            } else if ($(this).hasClass('end')) {
                $(this).html('<input type="date" class="form-control form-control-sm edit-end w-100" value="'+value+'">');
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
    $(document).on('click', '.save-btn', function(e) {
        e.preventDefault();
        e.stopPropagation();

        var row = $(this).closest('tr');
        var id = $(this).data('id');

        console.log('Row HTML at save:', row.html());
console.log('Term input exists?', row.find('input.edit-term').length);
console.log('Start input exists?', row.find('input.edit-start').length);
console.log('End input exists?', row.find('input.edit-end').length);
console.log('Status select exists?', row.find('select.edit-status').length);

        var term_name = row.find('td.term input, td.term select').val();
        var start_date = row.find('td.start input, td.start select').val();
        var end_date = row.find('td.end input, td.end select').val();
        var status = row.find('td.status input, td.status select').val();

        console.log('Values grabbed:', { term_name, start_date, end_date, status });
        console.log('Inputs found:', {
    term: row.find('input.edit-term').length,
    start: row.find('input.edit-start').length,
    end: row.find('input.edit-end').length,
    status: row.find('select.edit-status').length
});

        $.post('index.php?action=update_term', { id, term_name, start_date, end_date, status }, function(response) {
            row.empty();
            row.append(`<td class="year">${window.selectedYearText}</td>`);
            row.append(`<td class="editable term">${term_name}</td>`);
            row.append(`<td class="editable start">${start_date}</td>`);
            row.append(`<td class="editable end">${end_date}</td>`);
            row.append(`<td class="editable status">${status}</td>`);
            row.append(`
                <td>
                    <button class="btn btn-info btn-icon-split btn-sm edit-btn" data-id="${id}">
                        <span class="icon text-white-50"><i class="fas fa-edit"></i></span>
                        <span class="text">Edit</span>
                    </button>
                    <button class="btn btn-danger btn-icon-split btn-sm delete-btn" data-id="${id}">
                        <span class="icon text-white-50"><i class="fas fa-trash"></i></span>
                        <span class="text">Delete</span>
                    </button>
                </td>
            `);
        }
    ).fail(function() {
        alert('Error updating term.');
    });
});

    // Cancel edit
    $(document).on('click', '.cancel-btn', function(e) {
        e.preventDefault();
        e.stopPropagation();

        var row = $(this).closest('tr');
        row.find('.editable').each(function() {
            $(this).text($(this).data('original'));
        });

        // Restore buttons
        row.find('.save-btn').replaceWith(`<button class="btn btn-info btn-icon-split btn-sm edit-btn" data-id="${row.find('.edit-btn, .save-btn, .delete-btn').data('id')}">
                                             <span class="icon text-white-50"><i class="fas fa-edit"></i></span>
                                             <span class="text">Edit</span></button>`);

        $(this).replaceWith(`<button class="btn btn-danger btn-icon-split btn-sm delete-btn" data-id="${row.find('.edit-btn, .save-btn, .delete-btn').data('id')}">
                                <span class="icon text-white-50"><i class="fas fa-trash"></i></span>
                                <span class="text">Delete</span></button>`);
    });



});

