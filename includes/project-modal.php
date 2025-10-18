<div class="modal fade" id="projectModal" tabindex="-1" aria-labelledby="projectModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content bg-dark text-light border-secondary">
            <div class="modal-header border-secondary">
                <h2 class="modal-title fs-5" id="projectModalLabel"><?= t('projects.modal.title') ?></h2>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6 mb-4 mb-md-0">
                        <div id="projectCarousel" class="carousel slide" data-bs-ride="carousel">
                            <div class="carousel-inner rounded" id="project-carousel-inner"></div>
                            <button class="carousel-control-prev" type="button" data-bs-target="#projectCarousel" data-bs-slide="prev">
                                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                <span class="visually-hidden"><?= t('projects.modal.previous') ?></span>
                            </button>
                            <button class="carousel-control-next" type="button" data-bs-target="#projectCarousel" data-bs-slide="next">
                                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                <span class="visually-hidden"><?= t('projects.modal.next') ?></span>
                            </button>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <h3 id="project-modal-title" class="h4 mb-3"></h3>
                        <div class="d-flex flex-wrap gap-2 mb-3" id="project-modal-tags"></div>
                        <div class="mb-4" id="project-modal-description"></div>
                        <div class="mb-4">
                            <h4 class="h6 text-primary"><?= t('projects.modal.technologies') ?></h4>
                            <ul class="list-unstyled d-flex flex-wrap gap-2" id="project-modal-tech"></ul>
                        </div>
                        <div class="mb-4">
                            <h4 class="h6 text-primary"><?= t('projects.modal.features') ?></h4>
                            <ul class="list-unstyled" id="project-modal-features"></ul>
                        </div>
                    </div>
                </div>
                
                <div class="mt-4 pt-3 border-top border-secondary">
                    <h4 class="h6 text-primary mb-3"><?= t('projects.modal.code_sample') ?></h4>
                    <pre class="bg-black p-3 rounded"><code id="project-code-sample" class="language-javascript">// <?= t('projects.modal.code_loading') ?></code></pre>
                </div>
            </div>
            <div class="modal-footer border-secondary">
                <a href="#" class="btn btn-outline-light" id="project-live-demo" target="_blank">
                    <i class="bi bi-box-arrow-up-right me-2"></i><?= t('projects.modal.live_demo') ?>
                </a>
                <a href="#" class="btn btn-outline-light" id="project-source-code" target="_blank">
                    <i class="fab fa-github me-2"></i><?= t('projects.modal.source_code') ?>
                </a>
                <button type="button" class="btn btn-primary" data-bs-dismiss="modal"><?= t('projects.modal.close') ?></button>
            </div>
        </div>
    </div>
</div>