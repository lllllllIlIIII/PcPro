document.addEventListener('DOMContentLoaded', () => {
    // On sélectionne tous les éléments qu'on veut animer (cartes, gros textes, boutons)
    const elementsToReveal = document.querySelectorAll('.card, h1, h2, .btn');

    // On prépare leur état initial (invisibles et légèrement décalés vers le bas)
    elementsToReveal.forEach(el => {
        el.style.opacity = '0';
        el.style.transform = 'translateY(20px)';
        el.style.transition = 'opacity 0.6s ease-out, transform 0.6s ease-out';
    });

    // On crée l'observateur : il va regarder quand les éléments entrent dans l'écran
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                // Dès que l'élément apparaît à l'écran, on le rend visible et à sa place
                entry.target.style.opacity = '1';
                entry.target.style.transform = 'translateY(0)';

                // Optionnel : on arrête de l'observer une fois qu'il est apparu
                observer.unobserve(entry.target);
            }
        });
    }, {
        threshold: 0.1 // L'animation se déclenche quand 10% de l'élément est visible
    });

    // On dit à l'observateur de surveiller nos éléments
    elementsToReveal.forEach(el => observer.observe(el));
});