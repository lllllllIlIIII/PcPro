document.addEventListener('DOMContentLoaded', () => {
    const selects = document.querySelectorAll('.composant-select');
    const affichagePrix = document.getElementById('prix-total');
    const sousTotal = document.getElementById('sous-total');
    const prixCache = document.getElementById('prix_total_cache');

    const getIdFromSelect = (idSelect) => {
        const select = document.getElementById(idSelect);
        if (select && select.selectedIndex > -1) {
            return select.options[select.selectedIndex].getAttribute('data-id') || 0;
        }
        return 0;
    };

    const mettreAJourPrix = () => {
        const data = {
            cpu: getIdFromSelect('select-cpu'),
            gpu: getIdFromSelect('select-gpu'),
            ram: getIdFromSelect('select-ram'),
            mb: getIdFromSelect('select-mb'),
            ssd: getIdFromSelect('select-ssd'),
            cool: getIdFromSelect('select-cool'),
            boitier: getIdFromSelect('select-case'),
            psu: getIdFromSelect('select-psu')
        };

        fetch('admin/src/php/ajax/calcul_prix.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify(data)
        })
            .then(response => response.json())
            .then(resultat => {
                if (affichagePrix) {
                    affichagePrix.innerText = resultat.total;

                    if (prixCache) {
                        prixCache.value = resultat.total;
                    }

                    affichagePrix.style.color = 'var(--tech-primary, #66fcf1)';
                    setTimeout(() => affichagePrix.style.color = '', 300);
                }

                if (sousTotal) {
                    sousTotal.innerText = resultat.total + ' €';
                }
            })
            .catch(error => console.error('Erreur lors du calcul :', error));
    };

    selects.forEach(select => {
        select.addEventListener('change', mettreAJourPrix);
    });
});