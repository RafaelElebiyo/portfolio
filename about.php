<?php
require_once 'includes/translation.php';
require_once 'services/resume_service.php';

$resumeService = new ResumeService();
$personalInfo = $resumeService->getPersonalInfo();
$skills = $resumeService->getTechnicalSkills();
$experience = $resumeService->getWorkExperience();
$current_page = 'about.php';
$page_title = t('about.title');
?>
<!DOCTYPE html>
<html lang="es" data-bs-theme="dark">
<head>
    <?php include 'includes/head.php'; ?>
    <style>
        .timeline-item { position: relative; padding-left: 30px; margin-bottom: 30px; }
        .timeline-item.left { padding-left: 0; padding-right: 30px; }
        .timeline-item.right { text-align: right; }
        .timeline-item:before, .timeline-item.left:before {
            content: ''; position: absolute; left: 0; top: 5px;
            width: 15px; height: 15px; border-radius: 50%;
            background-color: var(--primary-color);
        }
        .timeline-item.right:before { left: auto; right: 0; }
        .timeline-item:after {
            content: ''; position: absolute; left: 7px; top: 25px;
            bottom: -35px; width: 1px; background-color: var(--primary-color);
        }
        .timeline-item:last-child:after { display: none; }
        .timeline-date {
            font-weight: bold; color: var(--primary-color);
            margin-bottom: 10px;
        }
        .timeline-content { position: relative; }
    </style>
</head>
<body class="bg-dark text-light">
    <?php include 'includes/header.php'; ?>
    <?php include 'includes/navigation.php'; ?>
    
    <main class="container py-5">
        <section id="about" class="mb-5">
            <div class="row align-items-center">
                <div class="col-lg-6 mb-4 mb-lg-0">
                    <h1 class="display-4 fw-bold mb-4"><?= t('about.heading') ?> <span class="text-primary"><?= htmlspecialchars($personalInfo['full_name']) ?></span></h1>
                    <p class="lead"><?= htmlspecialchars($personalInfo['job_title']) ?></p>
                    <p><?= htmlspecialchars($personalInfo['long_bio']) ?></p>
                    <div class="d-flex gap-3 mt-4">
                        <a href="#skills" class="btn btn-primary px-4"><?= t('about.skills_button') ?></a>
                        <a href="#experience" class="btn btn-outline-light px-4"><?= t('about.experience_button') ?></a>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="about-image bg-primary bg-opacity-10 p-4 rounded">
                        <img src="<?= htmlspecialchars($personalInfo['profile_image']) ?>" alt="<?= htmlspecialchars($personalInfo['full_name']) ?>" class="img-fluid rounded shadow">
                    </div>
                </div>
            </div>
        </section>

        <section id="skills" class="py-5 border-top border-secondary">
            <h2 class="text-center mb-5 fw-bold"><?= t('about.skills_title') ?> <span class="text-primary"><?= t('about.skills') ?></span></h2>
            
            <div class="row">
                <div class="col-md-6 mb-5 mb-md-0">
                    <h3 class="h4 mb-4"><?= t('about.technical_skills') ?></h3>
                    <?php foreach ($skills as $skill): ?>
                    <div class="skill-item mb-4">
                        <div class="d-flex justify-content-between mb-2">
                            <span><?= htmlspecialchars($skill['name']) ?></span>
                            <span><?= $skill['proficiency'] ?>%</span>
                        </div>
                        <div class="progress" style="height: 8px;">
                            <div class="progress-bar bg-primary" role="progressbar" style="width: <?= $skill['proficiency'] ?>%;"></div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
                
                <div class="col-md-6">
                    <h3 class="h4 mb-4"><?= t('about.professional_skills') ?></h3>
                    <div class="soft-skill-item d-flex align-items-start mb-4">
                        <div class="me-3 text-primary">
                            <svg width="24" height="24" fill="currentColor" class="bi bi-people-fill" viewBox="0 0 16 16">
                                <path d="M7 14s-1 0-1-1 1-4 5-4 5 3 5 4-1 1-1 1H7zm4-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6z"/>
                                <path fill-rule="evenodd" d="M5.216 14A2.238 2.238 0 0 1 5 13c0-1.355.68-2.75 1.936-3.72A6.325 6.325 0 0 0 5 9c-4 0-5 3-5 4s1 1 1 1h4.216z"/>
                                <path d="M4.5 8a2.5 2.5 0 1 0 0-5 2.5 2.5 0 0 0 0 5z"/>
                            </svg>
                        </div>
                        <div>
                            <h4 class="h5"><?= t('about.teamwork') ?></h4>
                            <p class="mb-0"><?= t('about.teamwork_desc') ?></p>
                        </div>
                    </div>
                    <div class="soft-skill-item d-flex align-items-start mb-4">
                        <div class="me-3 text-primary">
                            <svg width="24" height="24" fill="currentColor" class="bi bi-lightbulb-fill" viewBox="0 0 16 16">
                                <path d="M2 6a6 6 0 1 1 10.174 4.31c-.203.196-.359.4-.453.619l-.762 1.769A.5.5 0 0 1 10.5 13h-5a.5.5 0 0 1-.46-.302l-.761-1.77a1.964 1.964 0 0 0-.453-.618A5.984 5.984 0 0 1 2 6zm3 8.5a.5.5 0 0 1 .5-.5h5a.5.5 0 0 1 0 1l-.224.447a1 1 0 0 1-.894.553H6.618a1 1 0 0 1-.894-.553L5.5 15a.5.5 0 0 1-.5-.5z"/>
                            </svg>
                        </div>
                        <div>
                            <h4 class="h5"><?= t('about.problem_solving') ?></h4>
                            <p class="mb-0"><?= t('about.problem_solving_desc') ?></p>
                        </div>
                    </div>
                    <div class="soft-skill-item d-flex align-items-start">
                        <div class="me-3 text-primary">
                            <svg width="24" height="24" fill="currentColor" class="bi bi-arrow-repeat" viewBox="0 0 16 16">
                                <path d="M11.534 7h3.932a.25.25 0 0 1 .192.41l-1.966 2.36a.25.25 0 0 1-.384 0l-1.966-2.36a.25.25 0 0 1 .192-.41zm-11 2h3.932a.25.25 0 0 0 .192-.41L2.692 6.23a.25.25 0 0 0-.384 0L.342 8.59A.25.25 0 0 0 .534 9z"/>
                                <path fill-rule="evenodd" d="M8 3c-1.552 0-2.94.707-3.857 1.818a.5.5 0 1 1-.771-.636A6.002 6.002 0 0 1 13.917 7H12.9A5.002 5.002 0 0 0 8 3zM3.1 9a5.002 5.002 0 0 0 8.757 2.182.5.5 0 1 1 .771.636A6.002 6.002 0 0 1 2.083 9H3.1z"/>
                            </svg>
                        </div>
                        <div>
                            <h4 class="h5"><?= t('about.continuous_learning') ?></h4>
                            <p class="mb-0"><?= t('about.continuous_learning_desc') ?></p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section id="experience" class="py-5 border-top border-secondary">
            <h2 class="text-center mb-5 fw-bold"><?= t('about.my') ?> <span class="text-primary"><?= t('about.experience') ?></span></h2>
            
            <div class="timeline">
                <?php foreach ($experience as $exp): ?>
                <div class="timeline-item <?= $exp['display_order'] % 2 === 0 ? 'right' : 'left' ?>">
                    <div class="timeline-content p-4 rounded bg-dark bg-opacity-50 border border-secondary">
                        <h3 class="h4"><?= htmlspecialchars($exp['position']) ?></h3>
                        <span class="text-primary"><?= htmlspecialchars($exp['company']) ?> | <?= date('Y', strtotime($exp['start_date'])) ?> - <?= $exp['is_current'] ? t('about.present') : date('Y', strtotime($exp['end_date'])) ?></span>
                        <p class="mt-2"><?= htmlspecialchars($exp['description']) ?></p>
                        <?php $achievements = $resumeService->getWorkAchievements($exp['id']); ?>
                        <?php if (!empty($achievements)): ?>
                        <ul class="mt-3">
                            <?php foreach ($achievements as $achievement): ?>
                            <li><?= htmlspecialchars($achievement['achievement']) ?></li>
                            <?php endforeach; ?>
                        </ul>
                        <?php endif; ?>
                    </div>
                    <div class="timeline-date"><?= date('Y', strtotime($exp['start_date'])) ?></div>
                </div>
                <?php endforeach; ?>
            </div>
        </section>
    </main>
    
    <?php include 'includes/footer.php'; ?>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="assets/js/main.js"></script>
</body>
</html>