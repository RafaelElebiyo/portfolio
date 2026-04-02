/**
 * Toast — lightweight notification system.
 * Usage: Toast.show('Message', 'success' | 'error' | 'warning' | 'info', { title, duration })
 */
const Toast = (() => {
  const ICONS = {
    success: 'fa-circle-check',
    error:   'fa-circle-xmark',
    warning: 'fa-triangle-exclamation',
    info:    'fa-circle-info',
  };

  const DEFAULTS = { duration: 4000, title: '' };

  function getContainer() {
    let c = document.getElementById('toast-container');
    if (!c) {
      c = document.createElement('div');
      c.id = 'toast-container';
      c.className = 'toast-container position-fixed bottom-0 end-0 p-3';
      c.setAttribute('aria-live', 'polite');
      c.setAttribute('aria-atomic', 'false');
      document.body.appendChild(c);
    }
    return c;
  }

  function show(message, type = 'info', opts = {}) {
    const options  = { ...DEFAULTS, ...opts };
    const icon     = ICONS[type] ?? ICONS.info;
    const el       = document.createElement('div');
    el.className   = `toast-v2 toast-${type}`;
    el.setAttribute('role', 'alert');
    el.style.position = 'relative';
    el.innerHTML = `
      <span class="toast-icon" aria-hidden="true"><i class="fa-solid ${icon}"></i></span>
      <div class="toast-body">
        ${options.title ? `<div class="toast-title">${options.title}</div>` : ''}
        <div class="toast-message">${message}</div>
      </div>
      <button class="toast-close" aria-label="Dismiss">
        <i class="fa-solid fa-xmark" aria-hidden="true"></i>
      </button>
      <div class="toast-progress" style="animation-duration:${options.duration}ms; color: var(--accent);"></div>
    `;

    const dismiss = () => {
      el.classList.add('toast-closing');
      el.addEventListener('animationend', () => el.remove(), { once: true });
    };

    el.querySelector('.toast-close').addEventListener('click', dismiss);
    const timer = setTimeout(dismiss, options.duration);

    // Pause on hover
    el.addEventListener('mouseenter', () => {
      clearTimeout(timer);
      el.querySelector('.toast-progress')?.style.setProperty('animation-play-state','paused');
    });

    getContainer().appendChild(el);
    return { dismiss };
  }

  return { show,
    success: (m, o) => show(m, 'success', o),
    error:   (m, o) => show(m, 'error',   o),
    warning: (m, o) => show(m, 'warning', o),
    info:    (m, o) => show(m, 'info',    o),
  };
})();

window.Toast = Toast;
