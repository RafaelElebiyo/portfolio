<?php
declare(strict_types=1);
require_once __DIR__ . '/includes/bootstrap.php';

$resumeService = new ResumeService();
$personalInfo  = $resumeService->getPersonalInfo() ?? [];
$skills        = $resumeService->getTechnicalSkills();
$experience    = $resumeService->getWorkExperience();
$page_title    = t('about.title');
$current_page  = 'about.php';
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

    <!-- ── ABOUT HERO ──────────────────────────────────────── -->
    <section class="section-pad" aria-label="About">
        <div class="container-main">
            <div class="row align-items-center g-5">
                <div class="col-lg-6">
                    <div class="section-tag"><?= t('about.heading') ?></div>
                    <h1 class="mb-4">
                        <?= t('about.heading') ?>
                        <span class="text-accent"><?= Sanitizer::output($personalInfo['full_name'] ?? '') ?></span>
                    </h1>
                    <p class="hero-lead"><?= Sanitizer::output($personalInfo['job_title'] ?? '') ?></p>
                    <p style="color:var(--text-secondary);line-height:1.8;margin-bottom:var(--space-8)">
                        <?= Sanitizer::output($personalInfo['long_bio'] ?? '') ?>
                    </p>
                    <div class="d-flex flex-wrap gap-3">
                        <a href="#skills" class="btn btn-primary">
                            <i class="fa-solid fa-bolt" aria-hidden="true"></i>
                            <?= t('about.skills_button') ?>
                        </a>
                        <a href="#experience" class="btn btn-outline">
                            <i class="fa-solid fa-briefcase" aria-hidden="true"></i>
                            <?= t('about.experience_button') ?>
                        </a>
                    </div>
                </div>
                <div class="col-lg-6 d-none d-lg-block">
                    <div class="surface-card p-4 text-center"
                         style="border-color:var(--accent);box-shadow:var(--shadow-accent)">
                        <?php if (!empty($personalInfo['profile_image'])): ?>
                        <img data-src="<?= Sanitizer::output($personalInfo['profile_image']) ?>"
                             src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 1 1'/%3E"
                             class="lazy-img img-fluid rounded-lg"
                             alt="<?= Sanitizer::output($personalInfo['full_name'] ?? '') ?>"
                             style="border-radius:var(--radius-lg)">
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ── SKILLS ───────────────────────────────────────────── -->
    <section id="skills" class="section-pad section-divider" aria-label="Skills">
        <div class="container-main">
            <div class="section-header" data-animate>
                <div class="section-tag"><?= t('about.skills_title') ?></div>
                <h2 class="section-title">
                    <?= t('about.technical_skills') ?>
                    <span class="text-accent"><?= t('about.skills') ?></span>
                </h2>
            </div>

            <div class="row g-5">
                <!-- Technical skills bars -->
                <div class="col-lg-6" data-animate>
                    <h3 class="h5 mb-5"><?= t('about.technical_skills') ?></h3>
                    <?php foreach ($skills as $skill): ?>
                    <div class="skill-bar-wrap">
                        <div class="skill-bar-header">
                            <span><?= Sanitizer::output($skill['name']) ?></span>
                            <span class="text-accent"><?= (int)$skill['proficiency'] ?>%</span>
                        </div>
                        <div class="skill-bar-track">
                            <div class="skill-bar-fill"
                                 data-width="<?= (int)$skill['proficiency'] ?>%"
                                 role="progressbar"
                                 aria-valuenow="<?= (int)$skill['proficiency'] ?>"
                                 aria-valuemin="0" aria-valuemax="100"
                                 aria-label="<?= Sanitizer::output($skill['name']) ?> proficiency"></div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>

                <!-- Soft skills -->
                <div class="col-lg-6" data-animate>
                    <h3 class="h5 mb-5"><?= t('about.professional_skills') ?></h3>
                    <div class="d-flex flex-column gap-4">
                        <div class="soft-skill-card">
                            <div class="soft-skill-icon">
                                <i class="fa-solid fa-users" aria-hidden="true"></i>
                            </div>
                            <div>
                                <h4 class="soft-skill-title"><?= t('about.teamwork') ?></h4>
                                <p class="soft-skill-desc"><?= t('about.teamwork_desc') ?></p>
                            </div>
                        </div>
                        <div class="soft-skill-card">
                            <div class="soft-skill-icon">
                                <i class="fa-solid fa-lightbulb" aria-hidden="true"></i>
                            </div>
                            <div>
                                <h4 class="soft-skill-title"><?= t('about.problem_solving') ?></h4>
                                <p class="soft-skill-desc"><?= t('about.problem_solving_desc') ?></p>
                            </div>
                        </div>
                        <div class="soft-skill-card">
                            <div class="soft-skill-icon">
                                <i class="fa-solid fa-arrow-trend-up" aria-hidden="true"></i>
                            </div>
                            <div>
                                <h4 class="soft-skill-title"><?= t('about.continuous_learning') ?></h4>
                                <p class="soft-skill-desc"><?= t('about.continuous_learning_desc') ?></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ── EXPERIENCE TIMELINE ────────────────────────────── -->
    <section id="experience" class="section-pad section-divider" aria-label="Work experience">
        <div class="container-main">
            <div class="section-header" data-animate>
                <div class="section-tag"><?= t('about.my') ?></div>
                <h2 class="section-title">
                    <?= t('about.my') ?>
                    <span class="text-accent"><?= t('about.experience') ?></span>
                </h2>
            </div>

            <div class="timeline" style="max-width:720px;margin-inline:auto">
                <?php foreach ($experience as $exp): ?>
                <?php $achievements = $resumeService->getWorkAchievements((int)$exp['id']); ?>
                <div class="timeline-item" data-animate>
                    <div class="timeline-card">
                        <div class="timeline-date">
                            <i class="fa-regular fa-calendar me-1" aria-hidden="true"></i>
                            <?= date('Y', strtotime($exp['start_date'])) ?>
                            —
                            <?= $exp['is_current'] ? t('about.present') : date('Y', strtotime($exp['end_date'] ?? 'now')) ?>
                        </div>
                        <h3 class="timeline-role"><?= Sanitizer::output($exp['position']) ?></h3>
                        <p class="timeline-company">
                            <i class="fa-solid fa-building fa-xs me-1" aria-hidden="true"></i>
                            <?= Sanitizer::output($exp['company']) ?>
                        </p>
                        <p class="timeline-desc"><?= Sanitizer::output($exp['description'] ?? '') ?></p>
                        <?php if (!empty($achievements)): ?>
                        <ul class="timeline-list">
                            <?php foreach ($achievements as $ach): ?>
                            <li><?= Sanitizer::output($ach['achievement']) ?></li>
                            <?php endforeach; ?>
                        </ul>
                        <?php endif; ?>
                    </div>
                </div>
                <?php endforeach; ?>
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
