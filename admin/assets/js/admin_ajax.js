document.addEventListener('DOMContentLoaded', () => {

    const editableCells = document.querySelectorAll('td[contenteditable="true"]');

    editableCells.forEach(cell => {

        cell.addEventListener('focus', function() {
            this.setAttribute('data-original', this.innerText.trim());
        });

        cell.addEventListener('blur', function() {
            let nouveau = this.innerText.trim();
            let original = this.getAttribute('data-original');
            let id = this.getAttribute('id');
            let champ = this.getAttribute('data-champ');

            if (nouveau !== original) {
                fetch(`ajaxUpdatePC.php?id_produit=${id}&champ=${champ}&nouveau=${encodeURIComponent(nouveau)}`)
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            this.style.backgroundColor = 'rgba(16, 185, 129, 0.2)';
                            this.style.transition = 'background-color 0.5s ease';
                            setTimeout(() => this.style.backgroundColor = 'transparent', 1000);
                        } else {
                            alert("Erreur : " + data.message);
                            this.innerText = original;
                        }
                    })
                    .catch(error => {
                        console.error('Erreur AJAX:', error);
                        this.innerText = original;
                    });
            }
        });

        cell.addEventListener('keydown', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                this.blur();
            }
        });
    });
});