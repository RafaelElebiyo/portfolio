<div class="projects-grid">
    <div class="row g-4" id="projects-container">
        <?php foreach ($projects as $project): 
            $features = explode('|||', $project['features'] ?? '');
            $technologies = explode(',', $project['technologies'] ?? '');
            $codeSamples = explode('|||', $project['code_samples'] ?? '');
        ?>
            <div class="col-md-6 col-lg-4 project-card" data-category="<?= $project['category'] ?>" data-date="<?= $project['project_date'] ?>" data-popularity="<?= $project['popularity'] ?>">
                <div class="card h-100 bg-dark border-secondary project-card-inner">
                    <img src="<?= $project['cover_image'] ?>" class="card-img-top" alt="<?= $project['title'] ?>">
                    <div class="card-body">
                        <div class="d-flex flex-wrap gap-2 mb-3">
                            <span class="badge bg-primary"><?= ucfirst($project['category']) ?></span>
                        </div>
                        <h3 class="h5 card-title"><?= $project['title'] ?></h3>
                        <p class="card-text"><?= $project['short_description'] ?></p>
                    </div>
                    <div class="card-footer bg-dark border-top-0">
                        <button class="btn btn-sm btn-outline-light project-details-btn" 
                                data-project='<?= htmlspecialchars(json_encode($project), ENT_QUOTES, 'UTF-8') ?>'>
                            Ver Detalles
                        </button>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>