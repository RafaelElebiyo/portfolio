/**
 * app.js — global initialisation and shared behaviour.
 */

document.addEventListener('DOMContentLoaded', () => {

  /* ── Page loader ─────────────────────────────────────── */
  const loader = document.getElementById('page-loader');
  if (loader) {
    loader.classList.add('loaded');
    setTimeout(() => loader.remove(), 500);
  }

  /* ── Mobile nav: close on link click ─────────────────── */
  document.querySelectorAll('.main-nav .nav-link').forEach(link => {
    link.addEventListener('click', () => {
      const nav = document.getElementById('mainNav');
      if (nav?.classList.contains('show')) {
        nav.classList.remove('show');
      }
    });
  });

  /* ── Header scroll shadow ────────────────────────────── */
  const header = document.getElementById('site-header');
  if (header) {
    const update = () => {
      header.style.boxShadow = window.scrollY > 10
        ? 'var(--shadow-md)' : 'none';
    };
    window.addEventListener('scroll', update, { passive: true });
    update();
  }

  /* ── Smooth scroll for anchor links ──────────────────── */
  document.querySelectorAll('a[href^="#"]').forEach(a => {
    a.addEventListener('click', e => {
      const id = a.getAttribute('href');
      if (id === '#') return;
      const target = document.querySelector(id);
      if (target) {
        e.preventDefault();
        target.scrollIntoView({ behavior: 'smooth', block: 'start' });
      }
    });
  });

  /* ── Contact form ─────────────────────────────────────── */
  const contactForm = document.getElementById('contactForm');
  if (contactForm) initContactForm(contactForm);

  /* ── Projects filter ──────────────────────────────────── */
  initProjectsFilter();

  /* ── Auto-dismiss flash alerts ───────────────────────── */
  document.querySelectorAll('.flash-alert[data-auto-dismiss]').forEach(el => {
    setTimeout(() => {
      el.style.transition = 'opacity .5s';
      el.style.opacity = '0';
      setTimeout(() => el.remove(), 500);
    }, parseInt(el.dataset.autoDismiss ?? '4000'));
  });

});

/* ────────────────────────────────────────────────────────
   Contact form — client-side validation + AJAX submit
   ──────────────────────────────────────────────────────── */
function initContactForm(form) {
  const fields = {
    name:    { el: form.querySelector('#name'),    min: 2,  label: 'Name' },
    email:   { el: form.querySelector('#email'),   type: 'email', label: 'Email' },
    subject: { el: form.querySelector('#subject'), min: 3,  label: 'Subject' },
    message: { el: form.querySelector('#message'), min: 10, label: 'Message' },
  };

  function validateField(key) {
    const { el, min, type } = fields[key];
    if (!el) return true;
    const val = el.value.trim();
    let ok = val.length > 0;
    if (ok && min)        ok = val.length >= min;
    if (ok && type === 'email') ok = /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(val);
    el.classList.toggle('is-invalid', !ok);
    el.classList.toggle('is-valid',    ok);
    return ok;
  }

  // Live validation on blur
  Object.keys(fields).forEach(key => {
    fields[key].el?.addEventListener('blur', () => validateField(key));
    fields[key].el?.addEventListener('input', () => {
      if (fields[key].el.classList.contains('is-invalid')) validateField(key);
    });
  });

  form.addEventListener('submit', async e => {
    e.preventDefault();

    // Full validation pass
    const allOk = Object.keys(fields).reduce((acc, key) => validateField(key) && acc, true);
    if (!allOk) {
      Toast?.warning('Please fix the errors before submitting.', { title: 'Validation error' });
      return;
    }

    const submitBtn = form.querySelector('[type="submit"]');
    submitBtn?.classList.add('is-loading');
    submitBtn && (submitBtn.disabled = true);

    try {
      const resp = await fetch('includes/contact-handler.php', {
        method: 'POST',
        body:   new FormData(form),
      });
      const json = await resp.json();

      if (json.ok) {
        Toast?.success(json.message ?? 'Message sent successfully!', { title: 'Sent' });
        form.reset();
        Object.values(fields).forEach(({ el }) => { el?.classList.remove('is-valid','is-invalid'); });
      } else {
        Toast?.error(json.message ?? 'Error sending message. Please try again.', { title: 'Error' });
      }
    } catch {
      Toast?.error('Network error. Please check your connection.', { title: 'Error' });
    } finally {
      submitBtn?.classList.remove('is-loading');
      submitBtn && (submitBtn.disabled = false);
    }
  });
}

/* ────────────────────────────────────────────────────────
   Projects filter
   ──────────────────────────────────────────────────────── */
function initProjectsFilter() {
  const filterGroup = document.querySelector('.filter-group');
  if (!filterGroup) return;

  filterGroup.addEventListener('click', e => {
    const btn = e.target.closest('.filter-btn');
    if (!btn) return;

    filterGroup.querySelectorAll('.filter-btn').forEach(b => b.classList.remove('active'));
    btn.classList.add('active');

    const filter = btn.dataset.filter ?? 'all';
    document.querySelectorAll('#projects-container .project-card').forEach(card => {
      const show = filter === 'all' || card.dataset.category === filter;
      card.style.display = show ? '' : 'none';
    });

    Pagination?.reset('projects-container');
  });

  // Sort
  document.querySelectorAll('[name="sort"]').forEach(radio => {
    radio.addEventListener('change', () => {
      const container = document.getElementById('projects-container');
      if (!container) return;
      const cards = Array.from(container.children);
      cards.sort((a, b) => {
        if (radio.value === 'date') return new Date(b.dataset.date ?? 0) - new Date(a.dataset.date ?? 0);
        return parseInt(b.dataset.popularity ?? 0) - parseInt(a.dataset.popularity ?? 0);
      });
      cards.forEach(c => container.appendChild(c));
      Pagination?.reset('projects-container');
    });
  });
}
