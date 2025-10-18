<?php
require_once 'includes/translation.php';
require_once 'services/projects_services.php';

$portfolioService = new PortfolioService();
$current_page = 'projects.php';
$page_title = t('projects.page_title');
$projects = $portfolioService->getAllProjects();
?>
<!DOCTYPE html>
<html lang="es" data-bs-theme="dark">
<head>
    <?php include 'includes/head.php'; ?>
</head>
<body class="bg-dark text-light">
    <?php include 'includes/header.php'; ?>
    <?php include 'includes/navigation.php'; ?>
    
    <main class="container py-5">
        <section class="mb-5 text-center">
            <h1 class="display-4 fw-bold mb-3"><?= t('projects.main_title') ?> <span class="text-primary"><?= t('projects.projects') ?></span></h1>
            <p class="lead mx-auto" style="max-width: 700px;"><?= t('projects.subtitle') ?></p>
        </section>

        <?php include 'includes/projects-filters.php'; ?>
        <?php include 'includes/projects-grid.php'; ?>
    </main>
    
    <?php include 'includes/footer.php'; ?>
    <?php include 'includes/project-modal.php'; ?>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="assets/js/projects.js"></script>
</body>
</html>