<footer class="site-footer" role="contentinfo">
    <div class="container footer-grid">
        <div class="footer-col footer-brand">
            <span class="brand-name"><?= Sanitizer::output($personalInfo['full_name'] ?? '') ?></span>
            <p class="footer-bio"><?= Sanitizer::output($personalInfo['professional_summary'] ?? '') ?></p>
            <div class="social-links" aria-label="Social media links">
                <a href="#" class="social-btn" aria-label="LinkedIn" rel="noopener noreferrer" target="_blank">
                    <i class="fa-brands fa-linkedin-in" aria-hidden="true"></i>
                </a>
                <a href="#" class="social-btn" aria-label="GitHub" rel="noopener noreferrer" target="_blank">
                    <i class="fa-brands fa-github" aria-hidden="true"></i>
                </a>
                <a href="#" class="social-btn" aria-label="Twitter / X" rel="noopener noreferrer" target="_blank">
                    <i class="fa-brands fa-x-twitter" aria-hidden="true"></i>
                </a>
            </div>
        </div>

        <div class="footer-col">
            <h3 class="footer-heading"><?= t('footer.quick_links') ?></h3>
            <ul class="footer-links" role="list">
                <li><a href="index.php"><i class="fa-solid fa-chevron-right fa-xs me-1" aria-hidden="true"></i><?= t('menu.home') ?></a></li>
                <li><a href="about.php"><i class="fa-solid fa-chevron-right fa-xs me-1" aria-hidden="true"></i><?= t('menu.about') ?></a></li>
                <li><a href="projects.php"><i class="fa-solid fa-chevron-right fa-xs me-1" aria-hidden="true"></i><?= t('menu.projects') ?></a></li>
                <li><a href="resume.php"><i class="fa-solid fa-chevron-right fa-xs me-1" aria-hidden="true"></i><?= t('menu.resume') ?></a></li>
                <li><a href="contact.php"><i class="fa-solid fa-chevron-right fa-xs me-1" aria-hidden="true"></i><?= t('menu.contact') ?></a></li>
            </ul>
        </div>

        <div class="footer-col">
            <h3 class="footer-heading"><?= t('footer.connect') ?></h3>
            <address class="footer-contact">
                <a href="mailto:<?= Sanitizer::output($personalInfo['email'] ?? '') ?>" class="footer-contact-item">
                    <i class="fa-solid fa-envelope" aria-hidden="true"></i>
                    <span><?= Sanitizer::output($personalInfo['email'] ?? '') ?></span>
                </a>
                <?php if (!empty($personalInfo['phone'])): ?>
                <a href="tel:<?= Sanitizer::output($personalInfo['phone'] ?? '') ?>" class="footer-contact-item">
                    <i class="fa-solid fa-phone" aria-hidden="true"></i>
                    <span><?= Sanitizer::output($personalInfo['phone'] ?? '') ?></span>
                </a>
                <?php endif; ?>
                <?php if (!empty($personalInfo['location'])): ?>
                <span class="footer-contact-item">
                    <i class="fa-solid fa-location-dot" aria-hidden="true"></i>
                    <span><?= Sanitizer::output($personalInfo['location'] ?? '') ?></span>
                </span>
                <?php endif; ?>
            </address>
        </div>
    </div>

    <div class="footer-bottom">
        <div class="container">
            <p>&copy; <?= date('Y') ?> <?= Sanitizer::output($personalInfo['full_name'] ?? '') ?>. <?= t('footer.copyright') ?></p>
        </div>
    </div>
</footer>
