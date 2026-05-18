// Fichier : assets/js/admin_filtre.js

document.addEventListener('DOMContentLoaded', () => {
    const filterBtns = document.querySelectorAll('.btn-filter');
    const rows = document.querySelectorAll('.row-order');

    filterBtns.forEach(btn => {
        btn.addEventListener('click', () => {
            // 1. Gestion du style des boutons
            filterBtns.forEach(b => {
                b.classList.remove('active');
                b.style.backgroundColor = 'transparent';
                b.style.color = 'var(--tech-primary, #66fcf1)';
            });
            btn.classList.add('active');
            btn.style.backgroundColor = 'var(--tech-primary, #66fcf1)';
            btn.style.color = '#000';

            // 2. Récupération de la valeur du filtre
            const filterValue = btn.getAttribute('data-filter');

            // 3. Affichage ou masquage des lignes
            rows.forEach(row => {
                if (filterValue === 'all' || row.getAttribute('data-type') === filterValue) {
                    row.style.display = ''; // Affiche la ligne
                } else {
                    row.style.display = 'none'; // Cache la ligne
                }
            });
        });
    });
});