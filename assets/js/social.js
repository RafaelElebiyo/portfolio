document.addEventListener('DOMContentLoaded', async function() {
    const ICONS = {
        instagram: 'fa-brands fa-instagram',
        facebook: 'fa-brands fa-facebook-f',
        linkedin: 'fa-brands fa-linkedin-in',
        github: 'fa-brands fa-github'
    };

    let accounts = {};
    try {
        const response = await fetch('assets/seeds/redes.js');
        const source = await response.text();
        const evaluated = new Function('return ' + source + ';')();
        accounts = Array.isArray(evaluated) ? evaluated[0] || {} : evaluated || {};
    } catch (error) {
        console.warn('No se pudieron cargar las redes sociales:', error);
    }

    document.querySelectorAll('[data-social]').forEach(container => {
        const networks = (container.dataset.social || '').split(',').map(n => n.trim()).filter(Boolean);
        networks.forEach(network => {
            const url = accounts[network];
            if (!url) {
                return;
            }
            const link = document.createElement('a');
            link.href = url;
            link.target = '_blank';
            link.rel = 'noopener noreferrer';
            link.setAttribute('aria-label', network);
            link.title = network.charAt(0).toUpperCase() + network.slice(1);

            const icon = document.createElement('i');
            icon.className = ICONS[network] || 'fa-solid fa-link';
            link.appendChild(icon);
            container.appendChild(link);
        });
    });
});