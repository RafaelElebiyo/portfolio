/**
 * ProjectModal — populates and shows the reusable project detail modal.
 */
const ProjectModal = (() => {
  let bsModal;

  function getModal() {
    const el = document.getElementById('projectModal');
    if (!el) return null;
    if (!bsModal) bsModal = new bootstrap.Modal(el);
    return bsModal;
  }

  function showSkeleton(show) {
    document.getElementById('modal-skeleton')?.toggleAttribute('hidden', !show);
    document.getElementById('modal-body-content')?.toggleAttribute('hidden', show);
  }

  function populate(data) {
    // Title
    document.getElementById('modal-project-title').textContent = data.title ?? '';

    // Tags
    const tagsEl = document.getElementById('modal-tags');
    tagsEl.innerHTML = '';
    if (data.category) {
      const tag = document.createElement('span');
      tag.className = 'tech-list';
      tag.innerHTML = `<li style="background:var(--accent-subtle);border-color:var(--accent);color:var(--accent)">${data.category}</li>`;
      tagsEl.appendChild(tag);
    }

    // Description
    document.getElementById('modal-description').textContent = data.full_description ?? data.short_description ?? '';

    // Technologies
    const techEl = document.getElementById('modal-tech');
    techEl.innerHTML = '';
    (data.technologies ?? '').split(',').filter(Boolean).forEach(tech => {
      const li = document.createElement('li');
      li.textContent = tech.trim();
      techEl.appendChild(li);
    });

    // Features
    const featEl = document.getElementById('modal-features');
    featEl.innerHTML = '';
    (data.features ?? '').split('|||').filter(Boolean).forEach(feature => {
      const li = document.createElement('li');
      li.textContent = feature.trim();
      featEl.appendChild(li);
    });

    // Carousel
    const carouselEl = document.getElementById('modal-carousel-inner');
    carouselEl.innerHTML = '';
    if (data.cover_image) {
      carouselEl.innerHTML = `
        <div class="carousel-item active">
          <img src="${data.cover_image}" class="d-block w-100" alt="${data.title ?? 'Project'}" loading="lazy"
               style="aspect-ratio:16/9;object-fit:cover;">
        </div>`;
    }

    // Code sample
    const codeEl = document.getElementById('modal-code-sample');
    if (data.code_samples) {
      const first = data.code_samples.split('|||')[0].split(':::');
      codeEl.className  = `language-${(first[0] ?? 'javascript').toLowerCase()}`;
      codeEl.textContent = first[1] ?? '// No code sample available';
    } else {
      codeEl.textContent = '// No code sample available';
    }

    // Links
    const liveLink = document.getElementById('modal-live-link');
    const srcLink  = document.getElementById('modal-source-link');
    liveLink.href  = data.project_url ?? '#';
    srcLink.href   = data.github_url  ?? '#';

    if (!data.project_url) liveLink.classList.add('disabled');
    else                   liveLink.classList.remove('disabled');
    if (!data.github_url)  srcLink.classList.add('disabled');
    else                   srcLink.classList.remove('disabled');
  }

  function open(data) {
    const modal = getModal();
    if (!modal) return;

    showSkeleton(true);
    modal.show();

    // Simulate brief async load for smooth UX
    setTimeout(() => {
      populate(data);
      showSkeleton(false);
    }, 180);
  }

  function init() {
    document.addEventListener('click', e => {
      const btn = e.target.closest('[data-project]');
      if (!btn) return;
      try {
        const data = JSON.parse(btn.getAttribute('data-project'));
        open(data);
      } catch (err) {
        Toast?.error('Could not load project details.');
      }
    });
  }

  return { init, open };
})();

document.addEventListener('DOMContentLoaded', () => ProjectModal.init());
