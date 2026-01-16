$(document).ready(function() {

// Search as you type
//Debug: Open the browser console to see the returned JSON when typing in the search box.
$('#studentSearch').on('input', function() {
    var query = $(this).val();
    if(query.length < 2) return; // wait for at least 2 chars

    $.get('index.php?action=search_student', { q: query }, function(data) {
        // Populate a dropdown or suggestion box
        console.log(data); // debug: see results

        var dropdown = $('#studentDropdown');
        dropdown.empty().show();
        data.forEach(function(student) {
            console.log(student);
            dropdown.append(`
                <a href="#" class="list-group-item list-group-item-action student-item" data-id="${student.student_id}">
                    ${student.student_number} - ${student.first_name} ${student.last_name} (${student.grade_name})
                </a>
            `);
        });
    });
});

// clicking a student
$(document).on('click', '.student-item', function(e) {
    e.preventDefault();

    const studentId = $(this).data('id');
    const studentName = $(this).text().trim();

    $('#studentSearch').val(studentName);
    $('#studentDropdown').hide().empty();

    // store selected student ID for later (Generate button)
    $('#studentSearch').data('student-id', studentId);

    console.log('Selected student ID:', studentId); // debug
});

// hide search results when clicking outside
$(document).on('click', function(e) {
    if (!$(e.target).closest('#studentSearch, #studentDropdown').length) {
        $('#studentDropdown').hide();
    }
});

// select entire text when search bar is clicked
$('#studentSearch').on('focus', function() {
    $(this).select();
});

// school year dropdown
// debug: http://localhost/CSE3101/index.php?action=get_school_years
function loadSchoolYears() {
    $.get('index.php?action=get_school_years', function(data) {
        var dropdown = $('#schoolYearDropdown');
        dropdown.empty().append('<option value="">Select School Year</option>');
        data.forEach(function(year) {
            var optionText = year.school_year;
            if (year.status === 'Inactive') optionText += ' (Inactive)';
            dropdown.append('<option value="'+year.id+'">'+optionText+'</option>');
        });
    }, 'json');
}


    loadSchoolYears();
});

// TERMS DROPDOWN
// Load Terms when School Year changes
$('#schoolYearDropdown').on('change', function() {
    var school_year_id = $(this).val();
    var termDropdown = $('#termDropdown');

    termDropdown.empty().append('<option value="">Select Term</option>');

    if (!school_year_id) return;

    $.get('index.php?action=get_terms', { school_year_id: school_year_id }, function(data) {
        console.log('Terms:', data); // Debug: Check returned terms
        data.forEach(function(term) {
            var optionText = term.term_name;
            if (term.status === 'Inactive') optionText += ' (Inactive)';
            termDropdown.append('<option value="'+term.term_id+'">'+optionText+'</option>');
        });
    }, 'json');
});

// AJAX call for the Generate btn
$('#generateBtn').on('click', function() {
    var student_id = $('#studentSearch').data('student-id'); 
    var school_year_id = $('#schoolYearDropdown').val();
    var term_id = $('#termDropdown').val();

    console.log('Generate clicked', { student_id, school_year_id, term_id });

    if (!student_id || !school_year_id || !term_id) {
        alert('Please select a student, school year, and term.');
        return;
    }

    $.post('index.php?action=generate_report', {
        student_id: student_id,
        school_year_id: school_year_id,
        term_id: term_id
    }, function(response) {
        console.log(response); // debug
        console.log('Report response:', response);
        console.log('Grading system:', response.grading_system);

        // TODO: populate the report card HTML
        $('#reportContainer').html(renderReport(response));

        // store for export
        $('#reportContainer').data('report', response);
        console.log('Report data stored for export:', response);
    }, 'json').fail(function(xhr, status, error) {
        console.error('AJAX error:', status, error, xhr.responseText);;
});

// generating report
function renderReport(data) {
    if(!data) return "<p>No report card found for this student.</p>";

    function friendlyValue(value) {
        if (value === null || value === undefined || value === '') return 'N/A';
        return value;
    }


    let student = data.student;
    let report = data.reportCard;
    let details = data.details;

    let html = `
        <div class="card shadow mb-4">
            <div class="card-body">
            
            <!-- Heading -->
            <div class="text-center report-title rounded py-3 mb-4">
                <h2 class="text-center text-dark font-weight-bold mb-2">
                    ${friendlyValue(report.term_name)} Report Card
                </h2>
                <h5 class="text-center text-dark mb-3">${friendlyValue('Sunshine Primary School')}</h5>
            </div>

                <!-- Student Info -->
                <div class="row pb-5 justify-content-center text-center text-nowrap">
                    <div class="col-md-3"><strong>Student:</strong><span class="value-underline"> ${friendlyValue(student.first_name)} ${friendlyValue(student.last_name)}</span></div>
                    <div class="col-md-2"><strong>Grade:</strong><span class="value-underline"> ${friendlyValue(student.class_short)}</span></div>
                    <div class="col-md-3"><strong>Teacher:</strong><span class="value-underline"> ${friendlyValue(report.teacher_name)}</span></div>
                    <div class="col-md-4"><strong>Academic Year:</strong><span class="value-underline"> ${friendlyValue(report.school_year)}</span></div>
                </div>

                <!-- Report Table -->
                <table class="table table-bordered report-table">
                    <thead class="thead-dark">
                        <tr>
                            <th>#</th>
                            <th class="subject-col">Subjects</th>
                            <th>Total Marks</th>
                            <th>Marks Obtained</th>
                            <th>Subject Grade</th>
                            <th>Remarks</th>
                        </tr>
                    </thead>
                    <tbody>
    `;

    details.forEach((d, i) => {
        html += `
            <tr>
                <td>${i+1}</td>
                <td>${d.subject_name}</td>
                <td class="text-center">${friendlyValue(d.total_marks)}</td>
                <td class="text-center">${friendlyValue(d.marks_obtained)}</td>
                <td class="text-center">${friendlyValue(d.subject_grade)}</td>
                <td class="text-center">${friendlyValue(d.remarks)}</td>
            </tr>
        `;
    });

    html += `
                        <tr class="font-weight-bold bg-light">
                            <td colspan="2" class="text-right">Total</td>
                            <td class="text-center">${friendlyValue(report.total_marks)}</td>
                            <td class="text-center">${friendlyValue(report.total_marks_obtained)}</td>
                            <td class="text-center">—</td>
                            <td class="text-center">—</td>
                        </tr>
                    </tbody>
                </table>

                <!-- Summary -->
                <div class="row pt-4 pb-5 justify-content-center text-center">
                    <div class="col-3"><strong>Number of Pupils:</strong><span class="value-underline"> ${friendlyValue(report.total_pupils)}</span></div>

                    <div class="col-3"><strong>Rank:</strong><span class="value-underline"> ${ordinalSuffix(friendlyValue((report.rank)))}</span></div>

                    <div class="col-3"><strong>Overall Percentage:</strong><span class="value-underline"> ${friendlyValue(report.overall_percentage)}%</span></div>

                    <div class="col-3"><strong>Grade:</strong><span class="value-underline"> ${friendlyValue(report.overall_grade)}</span></div>
                </div>

                <!-- Grading System & Comments -->
                <div class="row mt-3">
                    <div class="col-md-6" id="gradingSystemContainer">
                        ${renderGradingSystem(data.grading_system)}
                    </div>

                    <div class="col-md-6 mb-3 d-flex justify-content-end">
                        <div class="p-3 border rounded" style="width: 60%;">
                            <h6 class="font-weight-bold mb-3">Grade Teacher's Comments:</h6>
                            <p class="mb-0">${friendlyValue(report.comments)}</p>
                        </div>
    </div>
                </div>
            </div>
        </div>
    `;

    return html;
}

function ordinalSuffix(i) {
    let j = i % 10,
        k = i % 100;
    if (j == 1 && k != 11) return i + "st";
    if (j == 2 && k != 12) return i + "nd";
    if (j == 3 && k != 13) return i + "rd";
    return i + "th";
}

function renderGradingSystem(system) {
    if(!system || system.length === 0) return "<p>No grading system found.</p>";

    // Optional: fallback remarks if not provided
    const defaultRemarks = ["Excellent", "Very Good", "Good", "Fair", "Unsatisfactory"];

    let html = `
        <table class="table table-sm table-bordered text-center">
            <thead class="thead-dark">
                <tr class="bg-light">
                    <th colspan="${system.length}">Grading System</th>
                </tr>
            </thead>
            <tbody>
                <!-- Score Range Row -->
                <tr>
                    ${system.map(g => `<td>${g.min_score} - ${g.max_score}</td>`).join('')}
                </tr>
                
                <!-- Grade Row -->
                <tr>
                    ${system.map(g => `<td>${g.grade}</td>`).join('')}
                </tr>

                <!-- Remarks Row -->
                <tr>
                    ${system.map((g,i) => `<td>${g.remarks || defaultRemarks[i] || ''}</td>`).join('')}
                </tr>
            </tbody>
        </table>
    `;

    return html;
}

// export btn
$('#exportBtn').on('click', function() {
    console.log('Export clicked');

    // Get current report data from your container
    const reportData = $('#reportContainer').data('report');
    console.log('Report data fetched from container:', reportData);

    if (!reportData) {
        alert('Please generate a report first.');
        return;
    }
    if (!reportData.details || reportData.details.length === 0) {
        console.warn('No details to export:', reportData.details);
        alert('No details available to export.');
        return;
    }

    // Simple CSV export
    let csv = '';
    
    // Headers
    csv += 'Subject,Total Marks,Marks Obtained,Grade,Remarks\n';

    // Details
    reportData.details.forEach(d => {
        console.log('Adding row to CSV:', d);
        csv += `"${d.subject_name}",${d.total_marks},${d.marks_obtained},"${d.subject_grade}","${d.remarks}"\n`;
    });

    // Summary
    csv += `\nStudent,${reportData.student.first_name} ${reportData.student.last_name}\n`;
    csv += `Grade,${reportData.student.grade}\n`;
    csv += `Overall Percentage,${reportData.reportCard.overall_percentage}%\n`;
    csv += `Grade,${reportData.reportCard.overall_grade}\n`;
    csv += `Rank,${reportData.reportCard.rank}\n`;
    csv += `Number of Pupils,${reportData.reportCard.total_pupils}\n`;
    csv += `Comments,${reportData.reportCard.comments || 'N/A'}\n`;

    console.log('CSV generated:\n', csv);

    // Download CSV
    const blob = new Blob([csv], { type: 'text/csv;charset=utf-8;' });
    const link = document.createElement('a');
    link.href = URL.createObjectURL(blob);
    link.download = `${reportData.student.first_name}_${reportData.student.last_name}_Report.csv`;
    link.click();

    console.log('CSV download triggered');
});

// export pdf
$('#exportPDF').on('click', function(e) {
    e.preventDefault();
    const reportData = $('#reportContainer').data('report');
    if (!reportData) { alert('Generate a report first.'); return; }

    // Print only the report container
    let printContents = document.getElementById('reportContainer').innerHTML;
    let originalContents = document.body.innerHTML;

    document.body.innerHTML = printContents;
    window.print();
    document.body.innerHTML = originalContents;
});

// Export Word
$('#exportWord').on('click', function(e) {
    e.preventDefault();
    const reportData = $('#reportContainer').data('report');
    if (!reportData) { alert('Generate a report first.'); return; }

    let htmlContent = document.getElementById('reportContainer').innerHTML;
    let blob = new Blob(['\ufeff', htmlContent], { type: 'application/msword' });
    let url = URL.createObjectURL(blob);
    let link = document.createElement('a');
    link.href = url;
    link.download = `${reportData.student.first_name}_${reportData.student.last_name}_Report.doc`;
    link.click();
});

// Export Excel
$('#exportExcel').on('click', function(e) {
    e.preventDefault();
    const reportData = $('#reportContainer').data('report');
    if (!reportData) { alert('Generate a report first.'); return; }

    let htmlContent = document.getElementById('reportContainer').innerHTML;

    // Wrap in table tag for Excel
    let excelFile = `<table>${htmlContent}</table>`;

    let blob = new Blob([excelFile], { type: 'application/vnd.ms-excel' });
    let url = URL.createObjectURL(blob);
    let link = document.createElement('a');
    link.href = url;
    link.download = `${reportData.student.first_name}_${reportData.student.last_name}_Report.xls`;
    link.click();
});
});