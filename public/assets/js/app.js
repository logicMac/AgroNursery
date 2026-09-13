document.querySelectorAll('[data-confirm]').forEach(el => {
    el.addEventListener('click', e => {
        if (!confirm(el.dataset.confirm)) e.preventDefault();
    });
});

document.querySelectorAll('.table-search').forEach(input => {
    input.addEventListener('input', () => {
        const table = document.getElementById(input.dataset.target);
        if (!table) return;
        const q = input.value.toLowerCase();
        table.querySelectorAll('tbody tr').forEach(row => {
            const text = row.textContent.toLowerCase();
            row.style.display = text.includes(q) ? '' : 'none';
        });
    });
});

// Modal system
function openModal(id) {
    const modal = document.getElementById(id);
    if (!modal) return;
    modal.classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}

function closeModal(modal) {
    if (!modal) return;
    modal.classList.add('hidden');
    document.body.style.overflow = '';
}

document.querySelectorAll('[data-modal-open]').forEach(el => {
    el.addEventListener('click', () => openModal(el.dataset.modalOpen));
});

document.addEventListener('click', e => {
    const closeBtn = e.target.closest('[data-modal-close]');
    if (!closeBtn) return;
    const modal = closeBtn.closest('.modal');
    closeModal(modal);
});

document.addEventListener('keydown', e => {
    if (e.key === 'Escape') {
        document.querySelectorAll('.modal:not(.hidden)').forEach(modal => closeModal(modal));
    }
});
