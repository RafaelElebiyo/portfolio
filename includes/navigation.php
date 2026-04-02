<?php $current_page = basename($_SERVER['PHP_SELF']); ?>
<nav id="mainNav" class="main-nav collapse navbar-collapse" role="navigation" aria-label="Main navigation">
    <div class="container-fluid nav-inner">
        <ul class="nav-list" role="list">
            <?php
            $navItems = [
                ['href' => 'index.php',    'icon' => 'fa-house',       'label' => t('menu.home')],
                ['href' => 'about.php',    'icon' => 'fa-user',        'label' => t('menu.about')],
                ['href' => 'projects.php', 'icon' => 'fa-code-branch', 'label' => t('menu.projects')],
                ['href' => 'resume.php',   'icon' => 'fa-file-lines',  'label' => t('menu.resume')],
                ['href' => 'contact.php',  'icon' => 'fa-envelope',    'label' => t('menu.contact')],
            ];
            foreach ($navItems as $item):
                $isActive = ($current_page === $item['href']);
            ?>
            <li class="nav-item" role="listitem">
                <a href="<?= $item['href'] ?>"
                   class="nav-link <?= $isActive ? 'active' : '' ?>"
                   <?= $isActive ? 'aria-current="page"' : '' ?>>
                    <i class="fa-solid <?= $item['icon'] ?> nav-icon" aria-hidden="true"></i>
                    <span><?= $item['label'] ?></span>
                </a>
            </li>
            <?php endforeach; ?>
        </ul>
    </div>
</nav>
