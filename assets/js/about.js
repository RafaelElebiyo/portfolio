document.addEventListener('DOMContentLoaded', function() {
    const revealObserver = new IntersectionObserver(function(entries) {
        entries.forEach(function(entry) {
            if (entry.isIntersecting) {
                entry.target.classList.add('in-view');
                const delay = entry.target.dataset.delay || 0;
                entry.target.style.transitionDelay = delay + 's';

                entry.target.querySelectorAll('.skill-bar').forEach(function(bar) {
                    bar.style.width = bar.dataset.width + '%';
                });

                entry.target.querySelectorAll('.stat-value[data-count]').forEach(function(el) {
                    animateCounter(el);
                });

                revealObserver.unobserve(entry.target);
            }
        });
    }, { threshold: 0.25 });

    document.querySelectorAll('.reveal').forEach(function(el) {
        revealObserver.observe(el);
    });

    function animateCounter(el) {
        const target = parseInt(el.dataset.count, 10) || 0;
        const duration = 1200;
        const start = performance.now();

        function step(now) {
            const progress = Math.min((now - start) / duration, 1);
            const eased = 1 - Math.pow(1 - progress, 3);
            el.textContent = Math.round(target * eased);
            if (progress < 1) {
                requestAnimationFrame(step);
            }
        }

        requestAnimationFrame(step);
    }
});