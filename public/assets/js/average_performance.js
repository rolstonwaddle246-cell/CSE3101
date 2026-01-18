$(document).ready(function () {

    $('#apSchoolYearDropdown').change(function () {
        const schoolYearId = $(this).val();
        if (!schoolYearId) {
            $('#apTermDropdown').html('<option value="">All Terms</option>');
            return;
        }

        $.get('index.php?action=fetch_terms&school_year=' + schoolYearId, function (data) {
            let terms = JSON.parse(data);
            let options = '<option value="">All Terms</option>';
            terms.forEach(term => {
                options += `<option value="${term.term_id}">${term.term_name}</option>`;
            });
            $('#apTermDropdown').html(options);
        });
    });

    $('#apGenerateBtn').click(function () {
        const filters = {
            school_year: $('#apSchoolYearDropdown').val(),
            term: $('#apTermDropdown').val(),
            grade: $('#apGradeDropdown').val(),
            subject: $('#apSubjectDropdown').val()
        };

        $.get('index.php?action=average_performance_data', filters, function (response) {
            const data = JSON.parse(response);
            renderTable(data);
            renderCharts(data);
        });
    });

    function renderTable(data) {
        let html = '';
        if (data.results.length > 0) {
            html += `<div class="table-responsive">
                        <table class="table table-bordered table-hover" id="apDataTable" width="100%" cellspacing="0">
                            <thead class="thead-light">
                                <tr>
                                    <th>${getColumnHeading(data.case)}</th>
                                    <th>Average Score</th>
                                    <th>No. of Students</th>
                                </tr>
                            </thead>
                            <tbody>`;
            data.results.forEach(row => {
                html += `<tr>
                            <td>${getColumnValue(data.case, row)}</td>
                            <td>${row.avg_score}</td>
                            <td>${row.num_students}</td>
                         </tr>`;
            });
            html += `</tbody></table></div>`;
        } else {
            html = `<p class="text-center text-muted mb-0">No data available for selected filters.</p>`;
        }
        $('#apResults').html(html);
    }

    function getColumnHeading(caseNum) {
        switch (caseNum) {
            case 1: case 2: case 3: case 4: return "Term";
            case 5: return "Grade / Subject";
            case 6: return "Student";
            default: return "Term";
        }
    }

    function getColumnValue(caseNum, row) {
        switch (caseNum) {
            case 5: return row.grade_name || row.subject_name || '';
            case 6: return row.student_name || '';
            default: return row.term_name || '';
        }
    }

    function renderCharts(data) {
        $('#apResults canvas').remove();
        if (data.results.length === 0) return;

        const labels = data.results.map(r => {
            switch (data.case) {
                case 5: return r.grade_name;
                case 6: return r.student_name;
                default: return r.term_name;
            }
        });

        const scores = data.results.map(r => r.avg_score);

        // Chart 1
        $('#apResults').append('<canvas id="chart1" width="400" height="250"></canvas>');
        new Chart(document.getElementById('chart1').getContext('2d'), {
            type: data.case === 5 ? 'bar' : 'line',
            data: { labels: labels, datasets: [{ label: 'Average Score', data: scores, backgroundColor: 'rgba(78, 115, 223, 0.6)', borderColor: 'rgba(78, 115, 223, 1)', borderWidth: 1 }] },
            options: { responsive: true, plugins: { legend: { display: false } }, scales: { y: { beginAtZero: true, max: 100 } } }
        });

        // Chart 2 for case 5
        if (data.case === 5) {
            const subjectLabels = data.results.map(r => r.subject_name);
            const subjectScores = data.results.map(r => r.avg_score);
            $('#apResults').append('<canvas id="chart2" width="400" height="250"></canvas>');
            new Chart(document.getElementById('chart2').getContext('2d'), {
                type: 'bar',
                data: { labels: subjectLabels, datasets: [{ label: 'Average Score', data: subjectScores, backgroundColor: 'rgba(28, 200, 138, 0.6)', borderColor: 'rgba(28, 200, 138, 1)', borderWidth: 1 }] },
                options: { responsive: true, plugins: { legend: { display: false } }, scales: { y: { beginAtZero: true, max: 100 } } }
            });
        }
    }

});
