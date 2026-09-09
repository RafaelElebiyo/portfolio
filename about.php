<?php
require_once 'includes/translation.php';
require_once 'services/resume_service.php';

$resumeService = new ResumeService();
$personalInfo = $resumeService->getPersonalInfo();
$skills = $resumeService->getTechnicalSkills();
$experience = $resumeService->getWorkExperience();
$current_page = 'about.php';
$page_title = t('about.title');

$categoryLabels = [
    'frontend' => t('about.categories.frontend'),
    'backend' => t('about.categories.backend'),
    'database' => t('about.categories.database'),
    'devops' => t('about.categories.devops'),
    'other' => t('about.categories.other')
];
$categories = [];
foreach ($skills as $skill) {
    $categories[$skill['category'] ?? 'other'][] = $skill;
}
?>
<!DOCTYPE html>
<html lang="<?= htmlspecialchars($_SESSION['lang']) ?>" data-bs-theme="dark">
<head>
    <?php include 'includes/head.php'; ?>
</head>
<body class="bg-dark text-light">
    <?php include 'includes/header.php'; ?>
    <?php include 'includes/navigation.php'; ?>
    
    <main>
        <section id="about-hero" class="py-5">
            <div class="container py-lg-4">
                <div class="row align-items-center g-5">
                    <div class="col-lg-6 order-lg-2">
                        <div class="about-photo-frame reveal">
                            <div class="about-photo-ring"></div>
                            <div class="about-photo-wrap">
                                <img src="<?= htmlspecialchars($personalInfo['profile_image']) ?>" alt="<?= htmlspecialchars($personalInfo['full_name']) ?>" class="about-photo">
                            </div>
                            <span class="about-badge badge-1"><i class="fa-brands fa-react"></i><span>React</span></span>
                            <span class="about-badge badge-2"><i class="fa-solid fa-brain"></i><span>AI &amp; LLM</span></span>
                            <span class="about-badge badge-3"><i class="fa-brands fa-docker"></i><span>Docker</span></span>
                        </div>
                    </div>
                    <div class="col-lg-6 order-lg-1">
                        <span class="about-eyebrow"><?= htmlspecialchars($personalInfo['job_title']) ?></span>
                        <h1 class="display-3 fw-bold mb-3"><?= t('about.heading') ?> <span class="text-gradient"><?= htmlspecialchars($personalInfo['full_name']) ?></span></h1>
                        <div class="mb-4">
                            <span class="badge about-pill py-2 px-3">
                                <i class="bi bi-check-circle-fill me-2"></i><?= t('about.availability') ?>
                            </span>
                        </div>
                        <p class="lead mb-4 about-bio"><?= htmlspecialchars($personalInfo['short_bio']) ?></p>
                        <p class="text-light mb-4 about-long-bio"><?= htmlspecialchars($personalInfo['long_bio']) ?></p>
                        <div class="d-flex flex-wrap gap-3">
                            <a href="#skills" class="btn btn-about btn-lg px-4"><?= t('about.skills_button') ?></a>
                            <a href="#experience" class="btn btn-outline-light btn-lg px-4"><?= t('about.experience_button') ?></a>
                        </div>
                        <div class="row g-3 mt-4">
                            <div class="col-auto">
                                <small class="text-muted d-block mb-1"><?= t('about.location_label') ?></small>
                                <span><i class="fa-solid fa-location-dot about-accent me-2"></i><?= htmlspecialchars($personalInfo['location']) ?></span>
                            </div>
                            <div class="col-auto">
                                <small class="text-muted d-block mb-1"><?= t('about.email_label') ?></small>
                                <a href="mailto:<?= htmlspecialchars($personalInfo['email']) ?>" class="text-light text-decoration-none">
                                    <i class="fa-solid fa-envelope about-accent me-2"></i><?= htmlspecialchars($personalInfo['email']) ?>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section id="skills" class="py-5 border-top border-secondary">
            <div class="container">
                <h2 class="text-center mb-5 fw-bold display-6"><?= t('about.skills_title') ?> <span class="about-accent"><?= t('about.skills') ?></span></h2>
                
                <div class="row g-4">
                    <?php $catOrder = ['frontend', 'backend', 'database', 'devops', 'other']; ?>
                    <?php foreach ($catOrder as $ci => $catKey): ?>
                        <?php if (!empty($categories[$catKey])): ?>
                        <div class="col-md-6 col-xl-4">
                            <div class="skill-category-card h-100 border-secondary rounded reveal">
                                <h3 class="h5 mb-4 d-flex align-items-center gap-3">
                                    <span class="skill-category-label"><?= $categoryLabels[$catKey] ?? ucfirst($catKey) ?></span>
                                </h3>
                                <?php foreach ($categories[$catKey] as $skill): ?>
                                <div class="skill-item mb-3">
                                    <div class="d-flex justify-content-between mb-1 small">
                                        <span class="text"><?= htmlspecialchars($skill['name']) ?></span>
                                        <span class="about-accent" data-counter><?= (int) $skill['proficiency'] ?>%</span>
                                    </div>
                                    <div class="progress skill-progress">
                                        <div class="progress-bar skill-bar" data-width="<?= (int) $skill['proficiency'] ?>" style="width: 0;"></div>
                                    </div>
                                </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </div>
            </div>
        </section>

        <section id="experience" class="py-5 border-top border-secondary">
            <div class="container">
                <h2 class="text-center mb-5 fw-bold display-6"><?= t('about.my') ?> <span class="about-accent"><?= t('about.experience') ?></span></h2>
                
                <div class="about-timeline">
                    <?php foreach ($experience as $exp): ?>
                    <?php $achievements = $resumeService->getWorkAchievements($exp['id']); ?>
                    <div class="about-timeline-item reveal">
                        <div class="about-timeline-dot"></div>
                        <div class="about-timeline-card">
                            <div class="d-flex flex-wrap justify-content-between align-items-start gap-2 mb-2">
                                <h3 class="h4 mb-0"><?= htmlspecialchars($exp['position']) ?></h3>
                                <span class="about-timeline-date"><?= date('Y', strtotime($exp['start_date'])) ?> – <?= $exp['is_current'] ? t('about.present') : date('Y', strtotime($exp['end_date'])) ?></span>
                            </div>
                            <p class="about-accent mb-3">
                                <i class="fa-solid fa-building me-2"></i><?= htmlspecialchars($exp['company']) ?>
                                <?php if (!empty($exp['location'])): ?> · <?= htmlspecialchars($exp['location']) ?><?php endif; ?>
                            </p>
                            <p><?= htmlspecialchars($exp['description']) ?></p>
                            <?php if (!empty($achievements)): ?>
                            <div class="mt-3">
                                <h4 class="h6 mb-2"><?= t('about.achievements') ?></h4>
                                <ul class="about-achievements list-unstyled">
                                    <?php foreach ($achievements as $achievement): ?>
                                    <li>
                                        <i class="bi bi-check-circle-fill about-accent me-2"></i>
                                        <?= htmlspecialchars($achievement['achievement']) ?>
                                        <?php if (!empty($achievement['metric_value'])): ?>
                                        <span class="badge about-pill ms-1">
                                            <?= htmlspecialchars($achievement['metric_value']) ?> <?= htmlspecialchars($achievement['metric_unit']) ?>
                                        </span>
                                        <?php endif; ?>
                                    </li>
                                    <?php endforeach; ?>
                                </ul>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </section>
    </main>
    
    <?php include 'includes/footer.php'; ?>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="assets/js/main.js"></script>
    <script src="assets/js/about.js"></script>
</body>
</html>