document.addEventListener('DOMContentLoaded', () => {
    const filterBtns = document.querySelectorAll('.btn-filter');
    const rows = document.querySelectorAll('.row-order');

    filterBtns.forEach(btn => {
        btn.addEventListener('click', () => {
            filterBtns.forEach(b => {
                b.classList.remove('active');
                b.style.backgroundColor = 'transparent';
                b.style.color = 'var(--tech-primary, #66fcf1)';
            });
            btn.classList.add('active');
            btn.style.backgroundColor = 'var(--tech-primary, #66fcf1)';
            btn.style.color = '#000';

            const filterValue = btn.getAttribute('data-filter');

            rows.forEach(row => {
                if (filterValue === 'all' || row.getAttribute('data-type') === filterValue) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        });
    });
});