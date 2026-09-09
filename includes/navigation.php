<?php
require_once 'translation.php';
$currentRoute = rtrim(basename(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH)), '.php');
if ($currentRoute === '' || $currentRoute === 'index') {
    $currentRoute = 'index';
}
$navLinks = [
    'index' => t('menu.home'),
    'about' => t('menu.about'),
    'projects' => t('menu.projects'),
    'resume' => t('menu.resume'),
    'contact' => t('menu.contact')
];
$hrefFor = fn($route) => $route === 'index' ? '/' : '/' . $route;
?>
<nav class="navbar navbar-expand-lg fixed-top bg-dark bg-opacity-90 backdrop-blur pt-5 pb-3">
    <div class="container">
        <div class="collapse navbar-collapse" id="navbarCollapse">
            <ul class="navbar-nav mx-auto mb-2 mb-lg-0">
                <?php foreach ($navLinks as $route => $label): ?>
                <li class="nav-item mx-2">
                    <a class="nav-link <?= ($currentRoute === $route) ? 'active text-primary fw-bold' : 'text-light' ?>" href="<?= $hrefFor($route) ?>"><?= $label ?></a>
                </li>
                <?php endforeach; ?>
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