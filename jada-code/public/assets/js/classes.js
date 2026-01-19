document.addEventListener('DOMContentLoaded', function () {
    const tableBody = document.getElementById('classesTableBody');
    const addBtn = document.getElementById('add-class');
    const searchInput = document.getElementById('searchInput');
    const gradeFilter = document.getElementById('gradeFilter');
    const classFilter = document.getElementById('classNameFilter');
    const numFilter = document.getElementById('numStudentsFilter');

    if (!tableBody || !addBtn) return;

    // --- FILTER FUNCTION ---
    function filterTable() {
        const search = searchInput.value.toLowerCase().trim();
        const gradeVal = gradeFilter.value.trim();
        const classVal = classFilter.value.toLowerCase().trim();
        const numVal = numFilter.value.trim();

        Array.from(tableBody.rows).forEach(row => {
            if (!row.dataset.id) return;

            const gradeCell = row.children[1];
            const classCell = row.children[2];
            const numCell = row.children[3];

            const gradeId = gradeCell.dataset.gradeId || '';
            const gradeName = gradeCell.textContent.toLowerCase().trim();
            const className = classCell.textContent.toLowerCase().trim();
            const numStudents = numCell.textContent.trim();

            const matchesSearch = !search || gradeName.includes(search) || className.includes(search);
            const matchesGrade = !gradeVal || gradeId === gradeVal;
            const matchesClass = !classVal || className.includes(classVal);
            const matchesNum = !numVal || numStudents === numVal;

            if (matchesSearch && matchesGrade && matchesClass && matchesNum) {
                row.style.display = '';
                if (search || gradeVal || classVal || numVal) row.classList.add('highlight-row');
                else row.classList.remove('highlight-row');
            } else {
                row.style.display = 'none';
                row.classList.remove('highlight-row');
            }
        });
    }

    searchInput?.addEventListener('input', filterTable);
    gradeFilter?.addEventListener('change', filterTable);
    classFilter?.addEventListener('change', filterTable);
    numFilter?.addEventListener('change', filterTable);

    // --- ADD NEW ROW ---
    addBtn?.addEventListener('click', function (e) {
        e.preventDefault();
        if (tableBody.querySelector('.new-class-row')) return;

        const template = document.getElementById('newClassTemplate');
        const newRow = template.cloneNode(true);
        newRow.id = '';
        newRow.classList.remove('d-none');
        newRow.classList.add('new-class-row');
        newRow.dataset.id = 'new-' + Date.now();

        tableBody.prepend(newRow);
    });

    // --- EVENT DELEGATION ---
    tableBody.addEventListener('click', function (e) {
        const row = e.target.closest('tr');
        if (!row) return;
        const id = row.dataset.id;

        // --- EDIT BUTTON ---
        if (e.target.classList.contains('edit-class')) {
            const gradeCell = row.children[1];
            const classCell = row.children[2];
            const numCell = row.children[3];
            const actionCell = row.children[4];

            const currentGradeId = gradeCell.dataset.gradeId;
            const currentClassName = classCell.textContent.trim();
            const currentNum = numCell.textContent.trim();

            // Store originals
            row.dataset.origGradeId = currentGradeId;
            row.dataset.origGradeName = gradeCell.textContent.trim();
            row.dataset.origClassName = currentClassName;
            row.dataset.origNum = currentNum;

            // Generate grade options
            const gradeOptions = GRADES.map(g =>
                `<option value="${g.id}" ${g.id == currentGradeId ? 'selected' : ''}>${g.grade_name}</option>`
            ).join('');

            gradeCell.innerHTML = `<select class="form-control grade-name">${gradeOptions}</select>`;
            classCell.innerHTML = `<input type="text" class="form-control class-name" value="${currentClassName}">`;
            numCell.innerHTML = `<input type="number" class="form-control num-students" value="${currentNum}" min="0">`;

            actionCell.innerHTML = `
                <button class="btn btn-success btn-sm save-class">Save</button>
                <button class="btn btn-secondary btn-sm cancel-edit">Cancel</button>
            `;
        }

        // --- SAVE EDIT BUTTON ---
        if (e.target.classList.contains('save-class')) {
            const gradeSelect = row.querySelector('.grade-name');
            const classInput = row.querySelector('.class-name');
            const numInput = row.querySelector('.num-students');

            const gradeId = gradeSelect.value;
            const className = classInput.value.trim();
            const numStudents = numInput.value;

            if (!gradeId || !className) {
                alert('Grade and Class name are required');
                return;
            }

            const action = row.classList.contains('new-class-row') ? 'store_class' : 'update_class';
            let bodyData = `grade_id=${gradeId}&class_name=${encodeURIComponent(className)}&num_students=${numStudents}`;
            if (!row.classList.contains('new-class-row')) bodyData += `&id=${id}`;

            fetch(`index.php?action=${action}`, {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: bodyData
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) location.reload();
                else alert(data.error || 'Failed to save');
            })
            .catch(err => {
                console.error(err);
                alert('Server error');
            });
        }

        // --- CANCEL EDIT BUTTON ---
        if (e.target.classList.contains('cancel-edit')) {
            const gradeCell = row.children[1];
            const classCell = row.children[2];
            const numCell = row.children[3];
            const actionCell = row.children[4];

            gradeCell.textContent = row.dataset.origGradeName;
            gradeCell.dataset.gradeId = row.dataset.origGradeId;
            classCell.textContent = row.dataset.origClassName;
            numCell.textContent = row.dataset.origNum;

            actionCell.innerHTML = `
                <button class="btn btn-info btn-sm edit-class" data-id="${id}">Edit</button>
                <button class="btn btn-danger btn-sm delete-class" data-id="${id}">Delete</button>
            `;
        }

        // --- CANCEL NEW ROW ---
        if (e.target.classList.contains('cancel-class')) row.remove();

        // --- DELETE BUTTON ---
        if (e.target.classList.contains('delete-class')) {
            if (!confirm('Are you sure you want to delete this class?')) return;

            fetch('index.php?action=delete_class', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: `id=${id}`
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) row.remove();
                else alert(data.error || 'Failed to delete');
            })
            .catch(err => {
                console.error(err);
                alert('Server error');
            });
        }
    });
});
