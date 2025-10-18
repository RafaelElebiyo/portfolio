<?php
require_once 'includes/head.php';
require_once 'services/resume_service.php';

$resumeService = new ResumeService();
$personalInfo = $resumeService->getPersonalInfo();
?>
<!DOCTYPE html>
<html lang="es" data-bs-theme="dark">
<head>  
    <?php require_once 'includes/head.php'; ?>
</head>

<body class="bg-dark text-light">
    <?php include 'includes/header.php'; ?>
    <?php include 'includes/navigation.php'; ?>
    
    <main class="container-fluid px-0">
        <section id="hero" class="d-flex align-items-center min-vh-75">
            <div class="container py-5">
                <div class="row align-items-center">
                    <div class="col-lg-6">
                        <h1 class="display-3 fw-bold mb-4"><?= t('hero.title') ?></h1>
                        <h2 class="fs-1 mb-4 text-primary"><?= htmlspecialchars($personalInfo['job_title']) ?></h2>
                        <p class="lead mb-4"><?= htmlspecialchars($personalInfo['short_bio']) ?></p>
                        <div class="d-flex gap-3">
                            <a href="projects.php" class="btn btn-primary btn-lg px-4"><?= t('hero.projects_button') ?></a>
                            <a href="contact.php" class="btn btn-outline-light btn-lg px-4"><?= t('hero.contact_button') ?></a>
                        </div>
                    </div>
                    <div class="col-lg-6 d-none d-lg-block">
                        <div class="hero-illustration">
                            <img src="<?= htmlspecialchars($personalInfo['profile_image']) ?>" alt="<?= htmlspecialchars($personalInfo['full_name']) ?>" class="img-fluid rounded-circle">
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>
    
    <?php include 'includes/footer.php'; ?>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="assets/js/main.js"></script>
</body>
</html>