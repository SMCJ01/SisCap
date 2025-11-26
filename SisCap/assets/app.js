// /SisCap/assets/app.js

document.addEventListener('DOMContentLoaded', () => {
    // Animación scroll-trigger
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(e => {
            if (e.isIntersecting) {
                e.target.classList.add('visible');
                e.target.classList.remove('loading');
                observer.unobserve(e.target);
            }
        });
    }, { threshold: 0.2 });

    document.querySelectorAll('.scroll-reveal').forEach(card => {
        card.classList.add('loading');
        observer.observe(card);
    });
});
