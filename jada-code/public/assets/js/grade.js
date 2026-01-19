document.addEventListener('DOMContentLoaded', function () {

    console.log('grades.js loaded');

    const tableBody = document.getElementById('gradesTableBody');
    const addBtn = document.getElementById('add-grade');
    const searchInput = document.getElementById('searchInput');
    const gradeFilter = document.getElementById('gradeFilter');

    if (!tableBody || !addBtn) return;

    // --- ADD NEW ROW ---
    addBtn.addEventListener('click', function (e) {
        e.preventDefault();

        if (tableBody.querySelector('.new-grade-row')) return;

        tableBody.insertAdjacentHTML('afterbegin', `
            <tr class="new-grade-row">
                <td>New</td>
                <td>
                    <input type="text" class="form-control grade-name" placeholder="Grade name">
                </td>
                <td>
                    <input type="number" class="form-control class-count" value="0" min="0">
                </td>
                <td>
                    <button class="btn btn-success btn-sm save-grade">Save</button>
                    <button class="btn btn-secondary btn-sm cancel-grade">Cancel</button>
                </td>
            </tr>
        `);
    });

    // --- EVENT DELEGATION FOR SAVE / CANCEL ---
    tableBody.addEventListener('click', function (e) {
        const row = e.target.closest('tr');

        // SAVE
        if (e.target.classList.contains('save-grade')) {
            const gradeName = row.querySelector('.grade-name').value.trim();
            const classCount = row.querySelector('.class-count').value;

            if (!gradeName) {
                alert('Grade name is required');
                return;
            }

            fetch('index.php?action=store_grade', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                body: `grade_name=${encodeURIComponent(gradeName)}&number_of_classes=${classCount}`
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    location.reload();
                } else {
                    alert(data.error || 'Failed to save grade');
                }
            })
            .catch(err => {
                console.error(err);
                alert('Server error');
            });
        }

        // CANCEL
        if (e.target.classList.contains('cancel-grade')) {
            row.remove();
        }
    });

    // --- FILTER & SEARCH ---
    function filterTable() {
        const searchValue = searchInput.value.toLowerCase();
        const filterValue = gradeFilter.value.toLowerCase();

        const rows = tableBody.querySelectorAll('tr'); // dynamic rows included

        rows.forEach(row => {
            const gradeNameCell = row.children[1]; // grade name column
            if (!gradeNameCell) return; // skip if row is empty or new row

            const gradeName = gradeNameCell.textContent.toLowerCase();
            let match = true;

            // Search input
            if (searchValue && !gradeName.includes(searchValue)) match = false;

            // Filter select
            if (filterValue && gradeName !== filterValue) match = false;

            if (match) {
                row.style.display = '';
                // Only highlight if search or filter is active
                if (searchValue || filterValue) {
                    row.classList.add('highlight-row');
                } else {
                    row.classList.remove('highlight-row');
                }
            } else {
                row.style.display = 'none';
                row.classList.remove('highlight-row');
            }
        });
    }

    searchInput.addEventListener('input', filterTable);
    gradeFilter.addEventListener('change', filterTable);

});

