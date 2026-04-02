/**
 * Lazy — lazy-loads images and triggers scroll-based animations.
 */
const Lazy = (() => {
  let imgObserver, animObserver;

  function initImages() {
    if (!('IntersectionObserver' in window)) {
      // Fallback: load all immediately
      document.querySelectorAll('img[data-src]').forEach(img => {
        img.src = img.dataset.src;
        img.classList.add('loaded');
      });
      return;
    }

    imgObserver = new IntersectionObserver((entries) => {
      entries.forEach(entry => {
        if (!entry.isIntersecting) return;
        const img = entry.target;
        img.src = img.dataset.src;
        if (img.dataset.srcset) img.srcset = img.dataset.srcset;
        img.classList.add('loaded');
        imgObserver.unobserve(img);
      });
    }, { rootMargin: '200px 0px' });

    document.querySelectorAll('img[data-src]').forEach(img => imgObserver.observe(img));
  }

  function initAnimations() {
    if (!('IntersectionObserver' in window)) return;

    animObserver = new IntersectionObserver((entries) => {
      entries.forEach(entry => {
        if (entry.isIntersecting) {
          entry.target.classList.add('in-view');
          animObserver.unobserve(entry.target);
        }
      });
    }, { threshold: 0.1, rootMargin: '0px 0px -40px 0px' });

    document.querySelectorAll('[data-animate]').forEach(el => animObserver.observe(el));
  }

  function initSkillBars() {
    if (!('IntersectionObserver' in window)) {
      document.querySelectorAll('.skill-bar-fill, .lang-bar-fill').forEach(bar => {
        bar.style.width = bar.dataset.width;
      });
      return;
    }

    const barObserver = new IntersectionObserver((entries) => {
      entries.forEach(entry => {
        if (entry.isIntersecting) {
          entry.target.style.width = entry.target.dataset.width;
          barObserver.unobserve(entry.target);
        }
      });
    }, { threshold: 0.3 });

    document.querySelectorAll('.skill-bar-fill, .lang-bar-fill').forEach(bar => barObserver.observe(bar));
  }

  function init() {
    initImages();
    initAnimations();
    initSkillBars();
  }

  // Re-run for dynamically added content (e.g. after pagination)
  function refresh() { init(); }

  return { init, refresh };
})();

document.addEventListener('DOMContentLoaded', () => Lazy.init());
