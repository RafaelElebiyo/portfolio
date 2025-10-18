<?php
require_once 'translation.php';
$current_page = basename($_SERVER['PHP_SELF']);
?>
<nav class="navbar navbar-expand-lg fixed-top bg-dark bg-opacity-90 backdrop-blur pt-5 pb-3">
    <div class="container">
        <div class="collapse navbar-collapse" id="navbarCollapse">
            <ul class="navbar-nav mx-auto mb-2 mb-lg-0">
                <li class="nav-item mx-2">
                    <a class="nav-link <?= ($current_page == 'index.php') ? 'active text-primary fw-bold' : 'text-light' ?>" href="index.php"><?= t('menu.home') ?></a>
                </li>
                <li class="nav-item mx-2">
                    <a class="nav-link <?= ($current_page == 'about.php') ? 'active text-primary fw-bold' : 'text-light' ?>" href="about.php"><?= t('menu.about') ?></a>
                </li>
                <li class="nav-item mx-2">
                    <a class="nav-link <?= ($current_page == 'projects.php') ? 'active text-primary fw-bold' : 'text-light' ?>" href="projects.php"><?= t('menu.projects') ?></a>
                </li>
                <li class="nav-item mx-2">
                    <a class="nav-link <?= ($current_page == 'resume.php') ? 'active text-primary fw-bold' : 'text-light' ?>" href="resume.php"><?= t('menu.resume') ?></a>
                </li>
                <li class="nav-item mx-2">
                    <a class="nav-link <?= ($current_page == 'contact.php') ? 'active text-primary fw-bold' : 'text-light' ?>" href="contact.php"><?= t('menu.contact') ?></a>
                </li>
            </ul>
            <div class="ms-3">
                <div class="dropdown">
                    <button class="btn btn-sm btn-outline-light dropdown-toggle" type="button" id="languageDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                        <?= strtoupper($_SESSION['lang']) ?>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="languageDropdown">
                        <li><a class="dropdown-item" href="<?= lang_url('es') ?>">ES - Español</a></li>
                        <li><a class="dropdown-item" href="<?= lang_url('en') ?>">EN - English</a></li>
                        <li><a class="dropdown-item" href="<?= lang_url('fr') ?>">FR - Français</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</nav>