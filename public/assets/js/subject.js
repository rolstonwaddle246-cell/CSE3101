document.addEventListener('DOMContentLoaded', () => {

    const body = document.getElementById('subjectsTableBody');
    const addBtn = document.getElementById('add-subject');
    const template = document.getElementById('newSubjectTemplate');

    const searchInput = document.getElementById('searchInput');
    const subjectFilter = document.getElementById('subjectFilter');
    const gradeFilter = document.getElementById('gradeFilter');

    /* ========= FILTER ========= */
    function filterTable() {
        const search = searchInput.value.toLowerCase();
        const subject = subjectFilter.value.toLowerCase();
        const grade = gradeFilter.value;

        [...body.rows].forEach(row => {
            if (!row.dataset.id || row.dataset.id === 'new') return;

            const subjectText = row.querySelector('.subject').textContent.toLowerCase();
            const gradeText = row.querySelector('.grade').textContent;

            const matches =
                (!search || row.textContent.toLowerCase().includes(search)) &&
                (!subject || subjectText.includes(subject)) &&
                (!grade || gradeText === grade);

            row.style.display = matches ? '' : 'none';

            // ✅ Highlight matched rows
            if (matches && (search || subject || grade)) {
                row.classList.add('highlight-row');
            } else {
                row.classList.remove('highlight-row');
            }
        });
    }

    searchInput.oninput = filterTable;
    subjectFilter.onchange = filterTable;
    gradeFilter.onchange = filterTable;

    /* ========= ADD ========= */
    addBtn.onclick = e => {
        e.preventDefault();
        if (body.querySelector('.new-row')) return;

        const row = template.cloneNode(true);
        row.classList.remove('d-none');
        row.classList.add('new-row');
        row.removeAttribute('id');
        row.dataset.id = 'new';
        body.prepend(row);
    };

    /* ========= TABLE ACTIONS ========= */
    body.onclick = e => {
        const row = e.target.closest('tr');
        if (!row) return;

        /* EDIT */
        if (e.target.classList.contains('edit-btn')) {
            row.dataset.subject = row.querySelector('.subject').textContent;
            row.dataset.grade = row.querySelector('.grade').textContent;
            row.dataset.clazz = row.querySelector('.class').textContent;

            row.querySelector('.subject').innerHTML =
                `<input class="form-control subject-input" value="${row.dataset.subject}">`;

            const gradeSelect = template.querySelector('.grade-select').cloneNode(true);
            [...gradeSelect.options].forEach(o => o.textContent === row.dataset.grade && (o.selected = true));
            row.querySelector('.grade').replaceChildren(gradeSelect);

            row.querySelector('.class').innerHTML =
                `<input type="number" class="form-control class-input" value="${row.dataset.clazz}">`;

            row.querySelector('.action-buttons').innerHTML = `
                <button class="btn btn-success btn-sm save-edit">Save</button>
                <button class="btn btn-secondary btn-sm cancel-edit">Cancel</button>
            `;
        }

        /* CANCEL */
        if (e.target.classList.contains('cancel-edit')) {
            row.querySelector('.subject').textContent = row.dataset.subject;
            row.querySelector('.grade').textContent = row.dataset.grade;
            row.querySelector('.class').textContent = row.dataset.clazz;
            row.querySelector('.action-buttons').innerHTML = `
                <button class="btn btn-info btn-sm edit-btn">Edit</button>
                <button class="btn btn-danger btn-sm delete-btn">Delete</button>
            `;
        }

        if (e.target.classList.contains('cancel-btn')) {
            row.remove();
        }

        /* SAVE (NEW / EDIT) */
        if (e.target.classList.contains('save-btn') || e.target.classList.contains('save-edit')) {
            const subject = row.querySelector('.subject-input').value.trim();
            const grade = row.querySelector('.grade-select').value;
            const clazz = row.querySelector('.class-input').value;

            if (!subject || !grade || !clazz) {
                alert('All fields are required');
                return;
            }

            const action = e.target.classList.contains('save-edit') ? 'update_subject' : 'store_subject';

            const payload = { subject_name: subject, grade_id: grade, number_of_class: clazz };
            if (action === 'update_subject') payload.id = row.dataset.id;

            fetch(`index.php?action=${action}`, {
                method: 'POST',
                body: new URLSearchParams(payload)
            })
            .then(r => r.json())
            .then(d => d.success ? location.reload() : alert(d.error || 'Save failed'));
        }

        /* DELETE */
        if (e.target.classList.contains('delete-btn')) {
            if (!confirm('Delete this subject?')) return;

            fetch('index.php?action=delete_subject', {
                method: 'POST',
                body: new URLSearchParams({ id: row.dataset.id })
            })
            .then(r => r.json())
            .then(d => d.success ? row.remove() : alert(d.error || 'Delete failed'));
        }
    };
});
