function initializeMobileMenu() {
    const toggleButton = document.querySelector('[data-mobile-menu-toggle]');
    const panel = document.querySelector('[data-mobile-menu-panel]');

    if (toggleButton === null || panel === null) {
        return;
    }

    const openPanel = () => {
        panel.classList.remove('hidden');
        panel.classList.add('flex');
        toggleButton.setAttribute('aria-expanded', 'true');
    };

    const closePanel = () => {
        panel.classList.remove('flex');
        panel.classList.add('hidden');
        toggleButton.setAttribute('aria-expanded', 'false');
    };

    const isOpen = () => toggleButton.getAttribute('aria-expanded') === 'true';

    toggleButton.addEventListener('click', (event) => {
        event.stopPropagation();

        if (isOpen()) {
            closePanel();

            return;
        }

        openPanel();
    });

    document.addEventListener('click', (event) => {
        if (!isOpen()) {
            return;
        }

        if (panel.contains(event.target) || toggleButton.contains(event.target)) {
            return;
        }

        closePanel();
    });

    document.addEventListener('keydown', (event) => {
        if (event.key === 'Escape' && isOpen()) {
            closePanel();
            toggleButton.focus();
        }
    });
}

document.addEventListener('DOMContentLoaded', initializeMobileMenu);
