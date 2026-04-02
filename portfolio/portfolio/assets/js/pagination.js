/**
 * Pagination — client-side pagination for a list of card elements.
 *
 * Usage:
 *   Pagination.init({
 *     containerId:   'projects-container',  // parent that holds the cards
 *     paginationId:  'projects-pagination', // where to render pagination controls
 *     perPage:       6,
 *   });
 */
const Pagination = (() => {
  const instances = new Map();

  function init({ containerId, paginationId, perPage = 6 }) {
    const container  = document.getElementById(containerId);
    const paginEl    = document.getElementById(paginationId);
    if (!container || !paginEl) return;

    const state = { currentPage: 1, perPage, items: [] };
    instances.set(containerId, state);

    function getVisible() {
      return Array.from(container.children).filter(el => el.style.display !== 'none');
    }

    function render() {
      state.items = getVisible();
      const total = Math.ceil(state.items.length / state.perPage);

      // Clamp current page
      if (state.currentPage > total) state.currentPage = Math.max(1, total);

      const start = (state.currentPage - 1) * state.perPage;
      const end   = start + state.perPage;

      state.items.forEach((el, i) => {
        el.style.visibility = '';
        el.setAttribute('aria-hidden', (i < start || i >= end) ? 'true' : 'false');
        el.style.display = (i >= start && i < end) ? '' : 'none';
      });

      renderControls(total);
      Lazy?.refresh();
    }

    function goTo(page) {
      state.currentPage = page;
      render();
      container.scrollIntoView({ behavior: 'smooth', block: 'start' });
    }

    function renderControls(total) {
      paginEl.innerHTML = '';
      if (total <= 1) return;

      const frag = document.createDocumentFragment();

      // Prev
      const prev = btn('<i class="fa-solid fa-chevron-left fa-xs" aria-hidden="true"></i>', 'Previous page');
      prev.disabled = state.currentPage === 1;
      prev.addEventListener('click', () => goTo(state.currentPage - 1));
      frag.appendChild(prev);

      // Page numbers — show max 5 centered around current
      const range = pageRange(state.currentPage, total);
      range.forEach(n => {
        if (n === '…') {
          const ellipsis = document.createElement('span');
          ellipsis.className = 'page-btn';
          ellipsis.style.cursor = 'default';
          ellipsis.textContent = '…';
          frag.appendChild(ellipsis);
        } else {
          const b = btn(n, `Go to page ${n}`);
          if (n === state.currentPage) b.classList.add('active');
          b.setAttribute('aria-current', n === state.currentPage ? 'page' : undefined);
          b.addEventListener('click', () => goTo(n));
          frag.appendChild(b);
        }
      });

      // Next
      const next = btn('<i class="fa-solid fa-chevron-right fa-xs" aria-hidden="true"></i>', 'Next page');
      next.disabled = state.currentPage === total;
      next.addEventListener('click', () => goTo(state.currentPage + 1));
      frag.appendChild(next);

      paginEl.appendChild(frag);
    }

    function btn(html, label) {
      const b = document.createElement('button');
      b.className = 'page-btn';
      b.innerHTML = html;
      b.setAttribute('aria-label', label);
      return b;
    }

    function pageRange(current, total) {
      if (total <= 7) return Array.from({ length: total }, (_, i) => i + 1);
      const pages = [];
      if (current <= 4) {
        pages.push(1,2,3,4,5,'…',total);
      } else if (current >= total - 3) {
        pages.push(1,'…', total-4, total-3, total-2, total-1, total);
      } else {
        pages.push(1,'…', current-1, current, current+1, '…', total);
      }
      return pages;
    }

    // Expose re-render for filter changes
    state.render = render;
    render();
  }

  function reset(containerId) {
    const state = instances.get(containerId);
    if (state) { state.currentPage = 1; state.render(); }
  }

  return { init, reset };
})();
