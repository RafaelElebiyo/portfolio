document.addEventListener('DOMContentLoaded', function() {
    const filterButtons = document.querySelectorAll('.filter-btn');
    const projectCards = document.querySelectorAll('.project-card');
    
    filterButtons.forEach(button => {
        button.addEventListener('click', function() {
            filterButtons.forEach(btn => btn.classList.remove('active'));
            this.classList.add('active');
            
            const filterValue = this.dataset.filter;
            
            projectCards.forEach(card => {
                card.style.display = (filterValue === 'all' || card.dataset.category === filterValue) ? 'block' : 'none';
            });
        });
    });
    
    const sortRadios = document.querySelectorAll('input[name="sort"]');
    sortRadios.forEach(radio => {
        radio.addEventListener('change', function() {
            const container = document.getElementById('projects-container');
            const projects = Array.from(container.children);
            
            projects.sort((a, b) => {
                return this.id === 'sort-date' ? 
                    new Date(b.dataset.date) - new Date(a.dataset.date) : 
                    parseInt(b.dataset.popularity || 0) - parseInt(a.dataset.popularity || 0);
            });
            
            projects.forEach(project => container.appendChild(project));
        });
    });
    
    const projectModal = new bootstrap.Modal(document.getElementById('projectModal'));
    const projectDetailButtons = document.querySelectorAll('.project-details-btn');
    
    projectDetailButtons.forEach(button => {
        button.addEventListener('click', function() {
            const projectData = JSON.parse(this.dataset.project);
            showProjectDetails(projectData);
        });
    });
    
    function showProjectDetails(project) {
        document.getElementById('project-modal-title').textContent = project.title || 'Proyecto sin título';
        document.getElementById('project-modal-description').innerHTML = `<p>${project.full_description || 'Descripción no disponible'}</p>`;
        
        const techContainer = document.getElementById('project-modal-tech');
        techContainer.innerHTML = project.technologies ? 
            project.technologies.split(',').map(tech => `<li><span class="badge bg-secondary">${tech}</span></li>`).join('') : '';
        
        const featuresContainer = document.getElementById('project-modal-features');
        featuresContainer.innerHTML = project.features ? 
            project.features.split('|||').map(feature => `<li class="mb-2"><i class="bi bi-check-circle-fill text-primary me-2"></i>${feature}</li>`).join('') : '';
        
        const carouselInner = document.getElementById('project-carousel-inner');
        carouselInner.innerHTML = project.cover_image ? 
            `<div class="carousel-item active"><img src="${project.cover_image}" class="d-block w-100" alt="${project.title}"></div>` : '';
        
        const codeSampleElement = document.getElementById('project-code-sample');
        if (project.code_samples && project.code_samples.split('|||').length > 0) {
            const firstSample = project.code_samples.split('|||')[0].split(':::');
            codeSampleElement.textContent = firstSample[1] || '// No hay código disponible';
            codeSampleElement.className = `language-${(firstSample[0] || 'javascript').toLowerCase()}`;
        } else {
            codeSampleElement.textContent = '// No hay muestras de código disponibles';
        }
        
        document.getElementById('project-live-demo').href = project.project_url || '#';
        document.getElementById('project-source-code').href = project.github_url || '#';
        
        projectModal.show();
        
        if (typeof Prism !== 'undefined') Prism.highlightAll();
    }
});