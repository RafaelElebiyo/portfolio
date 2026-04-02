<?php
declare(strict_types=1);
require_once __DIR__ . '/includes/bootstrap.php';

$resumeService = new ResumeService();
$projectsService = new ProjectsService();
$personalInfo  = $resumeService->getPersonalInfo() ?? [];
$featuredProjects = $projectsService->getFeaturedProjects();
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

    <!-- ── FEATURED PROJECTS CAROUSEL ─────────────────────────────── -->
    <?php if (!empty($featuredProjects)): ?>
    <section class="featured-section py-5" aria-label="Featured Projects">
        <div class="container-main">
            <div class="section-header text-center mb-5">
                <span class="section-eyebrow" data-aos="fade-up">
                    <i class="fa-solid fa-star text-warning" aria-hidden="true"></i>
                    <?= t('projects.featured') ?? 'Proyectos Destacados' ?>
                </span>
                <h2 class="section-title" data-aos="fade-up" data-aos-delay="100">
                    <?= t('projects.main_title') ?? 'Mis' ?> <span class="text-accent"><?= t('projects.projects') ?? 'Proyectos' ?></span>
                </h2>
            </div>
            
            <div id="featuredCarousel" class="carousel slide" data-bs-ride="carousel" data-aos="fade-up" data-aos-delay="200">
                <div class="carousel-indicators">
                    <?php foreach ($featuredProjects as $idx => $project): ?>
                    <button type="button" data-bs-target="#featuredCarousel" 
                            data-bs-slide-to="<?= $idx ?>" 
                            class="<?= $idx === 0 ? 'active' : '' ?>"
                            aria-current="<?= $idx === 0 ? 'true' : 'false' ?>"
                            aria-label="Slide <?= $idx + 1 ?>"></button>
                    <?php endforeach; ?>
                </div>
                
                <div class="carousel-inner">
                    <?php foreach ($featuredProjects as $idx => $project): ?>
                    <div class="carousel-item <?= $idx === 0 ? 'active' : '' ?>" data-bs-interval="6000">
                        <div class="row justify-content-center">
                            <div class="col-lg-11">
                                <div class="featured-card">
                                    <div class="row g-0">
                                        <div class="col-lg-6">
                                            <div class="featured-image">
                                                <?php if (!empty($project['cover_image'])): ?>
                                                <img src="<?= Sanitizer::output($project['cover_image']) ?>" 
                                                     alt="<?= Sanitizer::output($project['title']) ?>"
                                                     class="img-fluid">
                                                <?php else: ?>
                                                <div class="placeholder-img">
                                                    <i class="fa-solid fa-image fa-3x"></i>
                                                </div>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="featured-content">
                                                <span class="badge-admin badge-category">
                                                    <?= Sanitizer::output($project['category']) ?>
                                                </span>
                                                <h3 class="featured-title">
                                                    <?= Sanitizer::output($project['title']) ?>
                                                </h3>
                                                <div class="featured-meta">
                                                    <?php if (!empty($project['project_date'])): ?>
                                                    <span><i class="fa-regular fa-calendar"></i> <?= date('M Y', strtotime($project['project_date'])) ?></span>
                                                    <?php endif; ?>
                                                    <?php if (!empty($project['client_name'])): ?>
                                                    <span><i class="fa-solid fa-user"></i> <?= Sanitizer::output($project['client_name']) ?></span>
                                                    <?php endif; ?>
                                                </div>
                                                <p class="featured-desc">
                                                    <?= Sanitizer::output($project['short_description'] ?? '') ?>
                                                </p>
                                                <div class="featured-tech">
                                                    <?php if (!empty($project['technologies'])): ?>
                                                    <?php foreach (array_slice(explode(',', $project['technologies']), 0, 5) as $tech): ?>
                                                    <span class="tech-tag"><?= trim($tech) ?></span>
                                                    <?php endforeach; ?>
                                                    <?php endif; ?>
                                                </div>
                                                <div class="featured-actions">
                                                    <a href="projects.php?project=<?= $project['id'] ?>" class="btn btn-primary">
                                                        <i class="fa-solid fa-eye me-2"></i>
                                                        <?= t('projects.view_details') ?? 'Ver Detalles' ?>
                                                    </a>
                                                    <?php if (!empty($project['github_url']) && $project['github_url'] !== '#'): ?>
                                                    <a href="<?= Sanitizer::output($project['github_url']) ?>" 
                                                       class="btn btn-outline" target="_blank" rel="noopener">
                                                        <i class="fa-brands fa-github me-2"></i>
                                                        GitHub
                                                    </a>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
                
                <button class="carousel-control-prev" type="button" data-bs-target="#featuredCarousel" data-bs-slide="prev">
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                    <span class="visually-hidden"><?= t('projects.modal.previous') ?? 'Anterior' ?></span>
                </button>
                <button class="carousel-control-next" type="button" data-bs-target="#featuredCarousel" data-bs-slide="next">
                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                    <span class="visually-hidden"><?= t('projects.modal.next') ?? 'Siguiente' ?></span>
                </button>
            </div>
        </div>
    </section>
    <?php endif; ?>

</main>

<?php include 'includes/footer.php'; ?>
<?php include 'includes/toast_and_modal.php'; ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="assets/js/toast.js"></script>
<script src="assets/js/lazy.js"></script>
<script src="assets/js/app.js"></script>
</body>
</html>
