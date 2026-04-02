<?php
// $personalInfo must be set by the calling page
$_headerName  = Sanitizer::output($personalInfo['full_name']  ?? 'Portfolio');
$_headerTitle = Sanitizer::output($personalInfo['job_title']  ?? '');
?>
<header id="site-header" class="site-header" role="banner">
    <div class="container-fluid header-inner">
        <a href="index.php" class="brand-link" aria-label="<?= $_headerName ?> - Home">
            <span class="brand-name"><?= $_headerName ?></span>
            <span class="brand-tagline"><?= $_headerTitle ?></span>
        </a>

        <div class="header-controls">
            <!-- Theme toggle -->
            <button id="theme-toggle" class="ctrl-btn" aria-label="Toggle theme" title="Toggle theme">
                <i class="fa-solid fa-sun  icon-light" aria-hidden="true"></i>
                <i class="fa-solid fa-moon icon-dark"  aria-hidden="true"></i>
            </button>

            <!-- Language selector -->
            <div class="dropdown lang-dropdown">
                <button class="ctrl-btn dropdown-toggle" type="button"
                        data-bs-toggle="dropdown" aria-expanded="false"
                        aria-label="Select language">
                    <i class="fa-solid fa-globe" aria-hidden="true"></i>
                    <span class="lang-label"><?= strtoupper(current_lang()) ?></span>
                </button>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li><a class="dropdown-item <?= current_lang() === 'es' ? 'active' : '' ?>" href="<?= lang_url('es') ?>">
                        <i class="fa-solid fa-check me-2 <?= current_lang() !== 'es' ? 'invisible' : '' ?>"></i>ES — Español</a></li>
                    <li><a class="dropdown-item <?= current_lang() === 'en' ? 'active' : '' ?>" href="<?= lang_url('en') ?>">
                        <i class="fa-solid fa-check me-2 <?= current_lang() !== 'en' ? 'invisible' : '' ?>"></i>EN — English</a></li>
                    <li><a class="dropdown-item <?= current_lang() === 'fr' ? 'active' : '' ?>" href="<?= lang_url('fr') ?>">
                        <i class="fa-solid fa-check me-2 <?= current_lang() !== 'fr' ? 'invisible' : '' ?>"></i>FR — Français</a></li>
                </ul>
            </div>

            <!-- Mobile nav toggle -->
            <button class="ctrl-btn nav-toggler d-lg-none" type="button"
                    data-bs-toggle="collapse" data-bs-target="#mainNav"
                    aria-controls="mainNav" aria-expanded="false" aria-label="Toggle navigation">
                <i class="fa-solid fa-bars" aria-hidden="true"></i>
            </button>
        </div>
    </div>

    <?php include __DIR__ . '/navigation.php'; ?>
</header>
