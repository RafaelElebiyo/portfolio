<?php
declare(strict_types=1);
require_once __DIR__ . '/includes/bootstrap.php';

$resumeService = new ResumeService();
$personalInfo  = $resumeService->getPersonalInfo() ?? [];
$page_title    = t('meta.title');
$current_page  = 'index.php';
?>
<!DOCTYPE html>
<html lang="<?= current_lang() ?>" data-theme="dark">
<head><?php include 'includes/head.php'; ?></head>
<body>

<div id="page-loader" role="status" aria-label="Loading">
    <div class="loader-ring"></div>
</div>

<!-- Theme must init before render -->
<script src="assets/js/theme.js"></script>

<?php include 'includes/header.php'; ?>
<?php include 'includes/navigation.php'; ?>

<main id="main-content">

    <!-- ── HERO ───────────────────────────────────────────────── -->
    <section class="hero-section" aria-label="Introduction">
        <div class="hero-bg" aria-hidden="true"></div>
        <div class="container-main hero-content">
            <div class="row align-items-center g-5">
                <div class="col-lg-6">
                    <div class="hero-eyebrow anim-fade-up">
                        <i class="fa-solid fa-circle-dot fa-xs" aria-hidden="true"></i>
                        <?= t('hero.available') ?>
                    </div>
                    <h1 class="display-hero anim-fade-up anim-delay-1">
                        <?= Sanitizer::output($personalInfo['full_name'] ?? t('hero.title')) ?>
                    </h1>
                    <p class="hero-lead anim-fade-up anim-delay-2">
                        <?= Sanitizer::output($personalInfo['short_bio'] ?? t('hero.description')) ?>
                    </p>
                    <div class="hero-actions anim-fade-up anim-delay-3">
                        <a href="projects.php" class="btn btn-primary btn-lg">
                            <i class="fa-solid fa-code-branch" aria-hidden="true"></i>
                            <?= t('hero.projects_button') ?>
                        </a>
                        <a href="contact.php" class="btn btn-outline btn-lg">
                            <i class="fa-solid fa-paper-plane" aria-hidden="true"></i>
                            <?= t('hero.contact_button') ?>
                        </a>
                    </div>
                </div>

                <div class="col-lg-6 d-none d-lg-flex justify-content-center anim-fade-up anim-delay-2">
                    <div class="hero-image-wrap">
                        <div class="hero-image-ring">
                            <div class="hero-image-inner">
                                <?php if (!empty($personalInfo['profile_image'])): ?>
                                <img data-src="<?= Sanitizer::output($personalInfo['profile_image']) ?>"
                                     src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 1 1'/%3E"
                                     class="lazy-img"
                                     alt="<?= Sanitizer::output($personalInfo['full_name'] ?? '') ?>"
                                     width="360" height="360">
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

</main>

<?php include 'includes/footer.php'; ?>
<?php include 'includes/toast_and_modal.php'; ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="assets/js/toast.js"></script>
<script src="assets/js/lazy.js"></script>
<script src="assets/js/app.js"></script>
</body>
</html>
