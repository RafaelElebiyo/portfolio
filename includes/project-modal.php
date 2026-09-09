<div class="modal fade" id="projectModal" tabindex="-1" aria-labelledby="projectModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content bg-dark text-light border-secondary">
            <div class="modal-header border-secondary">
                <h2 class="modal-title fs-5" id="projectModalLabel"><?= t('projects.modal.title') ?></h2>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="project-media mb-4">
                    <div class="ratio ratio-16x9 bg-black rounded project-video-wrapper d-none" id="project-video-wrapper">
                        <iframe id="project-video-frame" src="" title="Video de proyecto" allow="autoplay; fullscreen" allowfullscreen></iframe>
                    </div>
                    <img id="project-media-fallback" src="" alt="" class="d-none w-100 rounded project-media-img">
                </div>

                <h3 id="project-modal-title" class="h3 mb-2"></h3>
                <div class="d-flex flex-wrap gap-2 mb-3" id="project-modal-tags"></div>
                <div class="mb-4 project-description" id="project-modal-description"></div>

                <div class="mb-4">
                    <h4 class="h6 text-primary"><?= t('projects.modal.technologies') ?></h4>
                    <ul class="list-unstyled d-flex flex-wrap gap-2" id="project-modal-tech"></ul>
                </div>

                <div class="mb-4">
                    <h4 class="h6 text-primary"><?= t('projects.modal.features') ?></h4>
                    <ul class="list-unstyled" id="project-modal-features"></ul>
                </div>

                <div class="pt-3 border-top border-secondary">
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