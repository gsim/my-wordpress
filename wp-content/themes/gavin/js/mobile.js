document.addEventListener('DOMContentLoaded', function() {
    const toggle = document.querySelector('.menu-toggle');
    const nav = document.querySelector('.main-navigation');

    if (toggle && nav) {
        toggle.addEventListener('click', function() {
            nav.classList.toggle('active');

            // Accessibility: update aria-expanded
            const expanded = toggle.getAttribute('aria-expanded') === 'true';
            toggle.setAttribute('aria-expanded', !expanded);
        });
    }
});
