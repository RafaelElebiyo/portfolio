<!-- Toast Container — injected by toast.js, never duplicated -->
<div id="toast-container"
     class="toast-container position-fixed bottom-0 end-0 p-3"
     aria-live="polite"
     aria-atomic="false"
     role="status">
</div>

<!-- Reusable project detail modal -->
<div class="modal fade" id="projectModal" tabindex="-1"
     aria-labelledby="projectModalTitle" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content project-modal-content">

            <div class="modal-header">
                <h2 class="modal-title fs-5" id="projectModalTitle">
                    <?= t('projects.modal.title') ?>
                </h2>
                <button type="button" class="btn-close" data-bs-dismiss="modal"
                        aria-label="<?= t('projects.modal.close') ?>"></button>
            </div>

            <div class="modal-body">
                <!-- Skeleton shown while data loads -->
                <div id="modal-skeleton" class="modal-skeleton" aria-hidden="false">
                    <div class="skel skel-img mb-3"></div>
                    <div class="skel skel-line w-75 mb-2"></div>
                    <div class="skel skel-line w-50 mb-4"></div>
                    <div class="skel skel-line mb-2"></div>
                    <div class="skel skel-line mb-2"></div>
                </div>

                <!-- Actual content -->
                <div id="modal-body-content" hidden>
                    <div class="row g-4">
                        <div class="col-lg-5">
                            <div id="projectCarousel" class="carousel slide rounded overflow-hidden" data-bs-ride="carousel">
                                <div class="carousel-inner" id="modal-carousel-inner"></div>
                                <button class="carousel-control-prev" type="button"
                                        data-bs-target="#projectCarousel" data-bs-slide="prev">
                                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                    <span class="visually-hidden"><?= t('projects.modal.previous') ?></span>
                                </button>
                                <button class="carousel-control-next" type="button"
                                        data-bs-target="#projectCarousel" data-bs-slide="next">
                                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                    <span class="visually-hidden"><?= t('projects.modal.next') ?></span>
                                </button>
                            </div>
                        </div>

                        <div class="col-lg-7">
                            <h3 id="modal-project-title" class="h4 mb-2"></h3>
                            <div id="modal-tags" class="d-flex flex-wrap gap-2 mb-3"></div>
                            <p id="modal-description" class="mb-3"></p>

                            <h4 class="modal-section-label">
                                <i class="fa-solid fa-microchip fa-xs me-1" aria-hidden="true"></i>
                                <?= t('projects.modal.technologies') ?>
                            </h4>
                            <ul id="modal-tech" class="tech-list mb-3"></ul>

                            <h4 class="modal-section-label">
                                <i class="fa-solid fa-list-check fa-xs me-1" aria-hidden="true"></i>
                                <?= t('projects.modal.features') ?>
                            </h4>
                            <ul id="modal-features" class="feature-list mb-0"></ul>
                        </div>
                    </div>

                    <div class="mt-4 pt-3 border-top">
                        <h4 class="modal-section-label">
                            <i class="fa-solid fa-terminal fa-xs me-1" aria-hidden="true"></i>
                            <?= t('projects.modal.code_sample') ?>
                        </h4>
                        <pre class="code-block"><code id="modal-code-sample"></code></pre>
                    </div>
                </div>
            </div>

            <div class="modal-footer">
                <a href="#" id="modal-live-link" class="btn btn-primary" target="_blank"
                   rel="noopener noreferrer">
                    <i class="fa-solid fa-arrow-up-right-from-square me-1" aria-hidden="true"></i>
                    <?= t('projects.modal.live_demo') ?>
                </a>
                <a href="#" id="modal-source-link" class="btn btn-outline-secondary" target="_blank"
                   rel="noopener noreferrer">
                    <i class="fa-brands fa-github me-1" aria-hidden="true"></i>
                    <?= t('projects.modal.source_code') ?>
                </a>
                <button type="button" class="btn btn-ghost" data-bs-dismiss="modal">
                    <?= t('projects.modal.close') ?>
                </button>
            </div>
        </div>
    </div>
</div>
