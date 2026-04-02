<?php
declare(strict_types=1);
require_once __DIR__ . '/includes/bootstrap.php';

$projectsService = new ProjectsService();
$resumeService   = new ResumeService();
$personalInfo    = $resumeService->getPersonalInfo() ?? [];
$projects        = $projectsService->getAllProjects();
$page_title      = t('projects.page_title');
$current_page    = 'projects.php';
$perPage         = (int)($config['pagination']['projects_per_page'] ?? 6);
?>
<!DOCTYPE html>
<html lang="<?= current_lang() ?>" data-theme="dark">
<head><?php include 'includes/head.php'; ?></head>
<body>

<div id="page-loader" role="status" aria-label="Loading"><div class="loader-ring"></div></div>
<script src="assets/js/theme.js"></script>

<?php include 'includes/header.php'; ?>
<?php include 'includes/navigation.php'; ?>

<main id="main-content">

    <!-- ── HEADER ─────────────────────────────────────────── -->
    <section class="section-pad-sm" aria-label="Projects header">
        <div class="container-main text-center">
            <div class="section-tag"><?= t('projects.main_title') ?></div>
            <h1 class="section-title">
                <?= t('projects.main_title') ?>
                <span class="text-accent"><?= t('projects.projects') ?></span>
            </h1>
            <p class="section-subtitle"><?= t('projects.subtitle') ?></p>
        </div>
    </section>

    <!-- ── FILTERS ────────────────────────────────────────── -->
    <section class="section-divider" style="padding-block:var(--space-6)" aria-label="Project filters">
        <div class="container-main">
            <div class="filter-group" role="group" aria-label="Filter by category">
                <button class="filter-btn active" data-filter="all">
                    <i class="fa-solid fa-border-all fa-xs me-1" aria-hidden="true"></i>
                    <?= t('projects.filters.all') ?>
                </button>
                <button class="filter-btn" data-filter="web">
                    <i class="fa-solid fa-globe fa-xs me-1" aria-hidden="true"></i>
                    <?= t('projects.filters.web') ?>
                </button>
                <button class="filter-btn" data-filter="mobile">
                    <i class="fa-solid fa-mobile-screen fa-xs me-1" aria-hidden="true"></i>
                    <?= t('projects.filters.mobile') ?>
                </button>
                <button class="filter-btn" data-filter="cross-platform">
                    <i class="fa-solid fa-layer-group fa-xs me-1" aria-hidden="true"></i>
                    <?= t('projects.filters.cross_platform') ?>
                </button>
                <button class="filter-btn" data-filter="cms">
                    <i class="fa-solid fa-database fa-xs me-1" aria-hidden="true"></i>
                    <?= t('projects.filters.cms') ?>
                </button>
                <button class="filter-btn" data-filter="cloud">
                    <i class="fa-solid fa-cloud fa-xs me-1" aria-hidden="true"></i>
                    <?= t('projects.filters.cloud') ?>
                </button>
            </div>

            <!-- Sort -->
            <div class="d-flex justify-content-center gap-3 mt-2">
                <label class="d-flex align-items-center gap-2 cursor-pointer" style="font-size:var(--text-sm);color:var(--text-secondary)">
                    <input type="radio" name="sort" value="date" checked class="form-check-input">
                    <?= t('projects.filters.sort_date') ?>
                </label>
                <label class="d-flex align-items-center gap-2 cursor-pointer" style="font-size:var(--text-sm);color:var(--text-secondary)">
                    <input type="radio" name="sort" value="popularity" class="form-check-input">
                    <?= t('projects.filters.sort_popular') ?>
                </label>
            </div>
        </div>
    </section>

    <!-- ── PROJECTS GRID ──────────────────────────────────── -->
    <section class="section-pad" aria-label="Projects grid">
        <div class="container-main">
            <?php if (empty($projects)): ?>
            <div class="text-center py-5" style="color:var(--text-muted)">
                <i class="fa-solid fa-folder-open fa-3x mb-3" aria-hidden="true"></i>
                <p>No projects found yet. Add some in the database.</p>
            </div>
            <?php else: ?>
            <div class="row g-4" id="projects-container">
                <?php foreach ($projects as $project): ?>
                <div class="col-sm-6 col-lg-4 project-card"
                     data-category="<?= Sanitizer::output($project['category'] ?? '') ?>"
                     data-date="<?= Sanitizer::output($project['project_date'] ?? '') ?>"
                     data-popularity="<?= (int)($project['popularity'] ?? 0) ?>">
                    <article class="card-v2">
                        <div class="card-img-wrap">
                            <img data-src="<?= Sanitizer::output($project['cover_image'] ?? 'assets/img/placeholder.svg') ?>"
                                 src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 9'/%3E"
                                 class="lazy-img"
                                 alt="<?= Sanitizer::output($project['title']) ?>"
                                 width="600" height="338">
                            <span class="card-badge"><?= Sanitizer::output($project['category'] ?? '') ?></span>
                        </div>
                        <div class="card-body-v2">
                            <h2 class="card-title-v2"><?= Sanitizer::output($project['title']) ?></h2>
                            <p class="card-text-v2"><?= Sanitizer::output($project['short_description'] ?? '') ?></p>
                        </div>
                        <div class="card-footer-v2">
                            <div class="d-flex flex-wrap gap-1">
                                <?php foreach (array_slice(explode(',', $project['technologies'] ?? ''), 0, 3) as $tech): ?>
                                <?php if (trim($tech)): ?>
                                <span style="font-size:.7rem;padding:.15rem .5rem;background:var(--surface-raised);border:1px solid var(--border);border-radius:var(--radius-full);color:var(--text-muted)">
                                    <?= Sanitizer::output(trim($tech)) ?>
                                </span>
                                <?php endif; ?>
                                <?php endforeach; ?>
                            </div>
                            <button class="btn btn-sm btn-primary"
                                    data-project='<?= htmlspecialchars(json_encode($project, JSON_THROW_ON_ERROR | JSON_HEX_APOS | JSON_HEX_QUOT), ENT_QUOTES, 'UTF-8') ?>'
                                    aria-label="View details for <?= Sanitizer::output($project['title']) ?>">
                                <i class="fa-solid fa-eye fa-xs" aria-hidden="true"></i>
                                <?= t('projects.view_details') ?>
                            </button>
                        </div>
                    </article>
                </div>
                <?php endforeach; ?>
            </div>

            <!-- Pagination -->
            <nav id="projects-pagination" class="pagination-v2" aria-label="Projects pagination"></nav>
            <?php endif; ?>
        </div>
    </section>

</main>

<?php include 'includes/footer.php'; ?>
<?php include 'includes/toast_and_modal.php'; ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="assets/js/toast.js"></script>
<script src="assets/js/lazy.js"></script>
<script src="assets/js/pagination.js"></script>
<script src="assets/js/modal.js"></script>
<script src="assets/js/app.js"></script>
<script>
document.addEventListener('DOMContentLoaded', () => {
    Pagination.init({
        containerId:  'projects-container',
        paginationId: 'projects-pagination',
        perPage: <?= $perPage ?>,
    });
});
</script>
</body>
</html>
