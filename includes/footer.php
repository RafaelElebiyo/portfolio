<?php
require_once 'translation.php';
?>
<footer class="bg-dark text-light py-5 border-top border-secondary">
    <div class="container">
        <div class="row">
            <div class="col-lg-4 mb-4 mb-lg-0">
                <h3 class="h5 text-primary mb-3"><?= t('footer.title') ?></h3>
                <p class="small"><?= htmlspecialchars($personalInfo['professional_summary']) ?></p>
            </div>
            <div class="col-lg-4 mb-4 mb-lg-0">
                <h4 class="h6 text-primary mb-3"><?= t('footer.quick_links') ?></h4>
                <ul class="list-unstyled">
                    <li class="mb-2"><a href="/" class="text-light text-decoration-none"><?= t('menu.home') ?></a></li>
                    <li class="mb-2"><a href="/projects" class="text-light text-decoration-none"><?= t('menu.projects') ?></a></li>
                    <li class="mb-2"><a href="/resume" class="text-light text-decoration-none"><?= t('menu.resume') ?></a></li>
                    <li class="mb-2"><a href="/contact" class="text-light text-decoration-none"><?= t('menu.contact') ?></a></li>
                </ul>
            </div>
            <div class="col-lg-4">
                <h4 class="h6 text-primary mb-3"><?= t('footer.connect') ?></h4>
                <div class="social-links d-flex gap-3" data-social="instagram,facebook,linkedin,github"></div>
                <div class="mt-3">
                    <a href="mailto:<?= htmlspecialchars($personalInfo['email']) ?>" class="text-light small"><?= htmlspecialchars($personalInfo['email']) ?></a>
                </div>
            </div>
        </div>
        <div class="row mt-5">
            <div class="col-12 text-center">
                <p class="small text-muted mb-0">&copy; <?= date('Y') ?> <?= htmlspecialchars($personalInfo['full_name']) ?>. <?= t('footer.copyright') ?></p>
            </div>
        </div>
    </div>
</footer>
<script src="assets/seeds/redes.js"></script>
<script src="assets/js/social.js"></script>