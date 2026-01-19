document.addEventListener('DOMContentLoaded', function () {
    const tableBody = document.getElementById('assignTeachersBody');
    const addBtn = document.getElementById('add-assignment');
    const searchInput = document.getElementById('searchInput');
    const teacherFilter = document.getElementById('teacherFilter');
    const gradeFilter = document.getElementById('gradeFilter');
    const classFilter = document.getElementById('classFilter');
    const template = document.getElementById('newAssignmentTemplate');

    if (!tableBody || !addBtn) return;

    /* ================= FILTER FUNCTION ================= */
    function filterTable() {
        const search = searchInput.value.toLowerCase().trim();
        const teacherVal = teacherFilter.value.toLowerCase();
        const gradeVal = gradeFilter.value;
        const classVal = classFilter.value;

        Array.from(tableBody.rows).forEach(row => {
            if (!row.dataset.id) return;

            const tName = row.querySelector('.teacher')?.textContent.toLowerCase() || '';
            const tClass = row.querySelector('.class')?.textContent || '';
            const tGrade = row.querySelector('.grade')?.textContent || '';

            const visible =
                (!search || tName.includes(search)) &&
                (!teacherVal || tName === teacherVal) &&
                (!gradeVal || tGrade === gradeVal) &&
                (!classVal || tClass === classVal);

            row.style.display = visible ? '' : 'none';

            // Highlight matching rows
            if (visible && (search || teacherVal || gradeVal || classVal)) {
                row.classList.add('highlight-row');
            } else {
                row.classList.remove('highlight-row');
            }
        });
    }

    searchInput?.addEventListener('input', filterTable);
    teacherFilter?.addEventListener('change', filterTable);
    gradeFilter?.addEventListener('change', filterTable);
    classFilter?.addEventListener('change', filterTable);

    /* ================= ADD NEW ROW ================= */
    addBtn.addEventListener('click', function (e) {
        e.preventDefault();
        if (tableBody.querySelector('.new-row')) return;

        const newRow = template.cloneNode(true);
        newRow.classList.remove('d-none');
        newRow.classList.add('new-row');
        newRow.removeAttribute('id');
        newRow.dataset.id = 'new-' + Date.now();

        tableBody.prepend(newRow);
    });

    /* ================= TABLE ACTIONS ================= */
    tableBody.addEventListener('click', function (e) {
        const row = e.target.closest('tr');
        if (!row) return;
        const id = row.dataset.id;

        // -------- EDIT --------
        if (e.target.classList.contains('edit-btn')) {
            const teacherCell = row.querySelector('.teacher');
            const classCell = row.querySelector('.class');
            const gradeCell = row.querySelector('.grade');

            // Save original values
            row.dataset.origTeacher = teacherCell.textContent.trim();
            row.dataset.origClass = classCell.textContent.trim();
            row.dataset.origGrade = gradeCell.textContent.trim();

            // Clone selects from template
            const temp = template.cloneNode(true);
            temp.classList.remove('d-none');

            teacherCell.innerHTML = temp.querySelector('.teacher-select').outerHTML;
            classCell.innerHTML = temp.querySelector('.class-select').outerHTML;
            gradeCell.innerHTML = temp.querySelector('.grade-select').outerHTML;

            // Pre-select original values
            teacherCell.querySelectorAll('option').forEach(opt => {
                if (opt.textContent.trim() === row.dataset.origTeacher) opt.selected = true;
            });
            classCell.querySelectorAll('option').forEach(opt => {
                if (opt.textContent.trim() === row.dataset.origClass) opt.selected = true;
            });
            gradeCell.querySelectorAll('option').forEach(opt => {
                if (opt.textContent.trim() === row.dataset.origGrade) opt.selected = true;
            });

            // Replace buttons
            row.querySelector('.action-buttons').innerHTML = `
                <button class="btn btn-success btn-sm save-edit">Save</button>
                <button class="btn btn-secondary btn-sm cancel-edit">Cancel</button>
            `;
        }

        // -------- SAVE EDIT --------
        if (e.target.classList.contains('save-edit')) {
            const teacherId = row.querySelector('.teacher-select').value;
            const classId = row.querySelector('.class-select').value;
            const gradeId = row.querySelector('.grade-select').value;

            if (!teacherId || !classId || !gradeId) return alert('All fields are required');

            fetch('index.php?action=update_assign_teachers', {
                method: 'POST',
                body: new URLSearchParams({ id, teacher_id: teacherId, class_id: classId, grade_id: gradeId })
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) location.reload();
                else alert(data.error || 'Update failed');
            });
        }

        // -------- CANCEL EDIT --------
        if (e.target.classList.contains('cancel-edit')) {
            row.querySelector('.teacher').textContent = row.dataset.origTeacher;
            row.querySelector('.class').textContent = row.dataset.origClass;
            row.querySelector('.grade').textContent = row.dataset.origGrade;

            row.querySelector('.action-buttons').innerHTML = `
                <button class="btn btn-info btn-sm edit-btn">Edit</button>
                <button class="btn btn-danger btn-sm delete-btn">Delete</button>
            `;
        }

        // -------- SAVE NEW --------
        if (e.target.classList.contains('save-btn')) {
            const teacherId = row.querySelector('.teacher-select').value;
            const classId = row.querySelector('.class-select').value;
            const gradeId = row.querySelector('.grade-select').value;

            if (!teacherId || !classId || !gradeId) return alert('All fields are required');

            fetch('index.php?action=store_assign_teachers', {
                method: 'POST',
                body: new URLSearchParams({ teacher_id: teacherId, class_id: classId, grade_id: gradeId })
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) location.reload();
                else alert(data.error || 'Failed to save');
            });
        }

        // -------- CANCEL NEW --------
        if (e.target.classList.contains('cancel-btn')) row.remove();

        // -------- DELETE --------
        if (e.target.classList.contains('delete-btn')) {
            if (!confirm('Delete this assignment?')) return;

            fetch('index.php?action=delete_assign_teachers', {
                method: 'POST',
                body: new URLSearchParams({ id })
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) row.remove();
                else alert(data.error || 'Delete failed');
            });
        }
    });
});
