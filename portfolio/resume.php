<?php
declare(strict_types=1);
require_once __DIR__ . '/includes/bootstrap.php';

$resumeService = new ResumeService();

// Trigger PDF download
if (isset($_GET['download'])) {
    require_once __DIR__ . '/includes/generate-pdf.php';
    exit;
}

$personalInfo  = $resumeService->getPersonalInfo() ?? [];
$workExperience= $resumeService->getWorkExperience();
$certifications= $resumeService->getCertifications();
$skills        = $resumeService->getTechnicalSkills();
$languages     = $resumeService->getLanguages();
$keyAchievements   = $resumeService->getKeyAchievements();
$professionalGoals = $resumeService->getProfessionalGoals();
$references        = $resumeService->getProfessionalReferences();
$page_title        = t('meta.resume_title');
$current_page      = 'resume.php';

$categoryNames = [
    'frontend' => 'Frontend', 'backend' => 'Backend', 'mobile' => 'Mobile',
    'design' => 'Design', 'devops' => 'DevOps', 'database' => 'Databases', 'other' => 'Other',
];
$proficiencyNames = [
    'basic' => t('resume.lang_basic'), 'intermediate' => t('resume.lang_intermediate'),
    'advanced' => t('resume.lang_advanced'), 'native' => t('resume.lang_native'),
];
$proficiencyMap = ['basic' => '25%', 'intermediate' => '50%', 'advanced' => '75%', 'native' => '100%'];

$skillsByCategory = [];
foreach ($skills as $s) $skillsByCategory[$s['category']][] = $s;
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
<div class="container-main section-pad">

    <!-- HEADER ROW -->
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-4 mb-5 pb-4"
         style="border-bottom:2px solid var(--border)">
        <div class="d-flex align-items-center gap-4">
            <?php if (!empty($personalInfo['profile_image'])): ?>
            <img data-src="<?= Sanitizer::output($personalInfo['profile_image']) ?>"
                 src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 1 1'/%3E"
                 class="lazy-img rounded-circle"
                 alt="<?= Sanitizer::output($personalInfo['full_name'] ?? '') ?>"
                 style="width:80px;height:80px;object-fit:cover;border:2px solid var(--accent)">
            <?php endif; ?>
            <div>
                <h1 class="h3 mb-1 text-accent"><?= Sanitizer::output($personalInfo['full_name'] ?? '') ?></h1>
                <p style="color:var(--text-secondary);font-size:var(--text-sm)"><?= Sanitizer::output($personalInfo['job_title'] ?? '') ?></p>
            </div>
        </div>
        <div class="d-flex flex-wrap gap-3">
            <a href="resume.php?download" class="btn btn-primary">
                <i class="fa-solid fa-file-pdf" aria-hidden="true"></i>
                <?= t('resume.download') ?>
            </a>
            <a href="#recruiter-view" class="btn btn-outline">
                <i class="fa-solid fa-user-tie" aria-hidden="true"></i>
                <?= t('resume.recruiter_view') ?>
            </a>
        </div>
    </div>

    <!-- CONTACT STRIP -->
    <div class="d-flex flex-wrap gap-5 mb-5" style="font-size:var(--text-sm);color:var(--text-secondary)">
        <?php foreach (['envelope' => 'email', 'phone' => 'phone', 'location-dot' => 'location'] as $icon => $field): ?>
        <?php if (!empty($personalInfo[$field])): ?>
        <span><i class="fa-solid fa-<?= $icon ?> me-2 text-accent" aria-hidden="true"></i><?= Sanitizer::output($personalInfo[$field]) ?></span>
        <?php endif; ?>
        <?php endforeach; ?>
    </div>

    <!-- ── EXPERIENCE ──────────────────────────────────── -->
    <section class="mb-5" aria-label="Work experience">
        <h2 class="resume-section-title">
            <i class="fa-solid fa-briefcase" aria-hidden="true"></i>
            <?= t('resume.sections.experience') ?>
        </h2>
        <div class="timeline">
            <?php foreach ($workExperience as $work): ?>
            <?php $achievements = $resumeService->getWorkAchievements((int)$work['id']); ?>
            <div class="timeline-item" data-animate>
                <div class="timeline-card">
                    <div class="timeline-date">
                        <?= date('Y', strtotime($work['start_date'])) ?> —
                        <?= $work['is_current'] ? t('about.present') : date('Y', strtotime($work['end_date'] ?? 'now')) ?>
                        &nbsp;|&nbsp; <?= Sanitizer::output($work['employment_type'] ?? '') ?>
                    </div>
                    <h3 class="timeline-role"><?= Sanitizer::output($work['position']) ?></h3>
                    <p class="timeline-company">
                        <i class="fa-solid fa-building fa-xs" aria-hidden="true"></i>
                        <?= Sanitizer::output($work['company']) ?>
                        <?php if (!empty($work['location'])): ?> — <?= Sanitizer::output($work['location']) ?><?php endif; ?>
                    </p>
                    <?php if (!empty($achievements)): ?>
                    <ul class="timeline-list">
                        <?php foreach ($achievements as $a): ?>
                        <li><?= Sanitizer::output($a['achievement']) ?></li>
                        <?php endforeach; ?>
                    </ul>
                    <?php endif; ?>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </section>

    <!-- ── EDUCATION ──────────────────────────────────── -->
    <section class="mb-5 section-divider pt-5" aria-label="Education">
        <h2 class="resume-section-title">
            <i class="fa-solid fa-graduation-cap" aria-hidden="true"></i>
            <?= t('resume.sections.education') ?>
        </h2>
        <div class="timeline">
            <?php foreach ($certifications as $cert): ?>
            <div class="timeline-item" data-animate>
                <div class="timeline-card">
                    <div class="timeline-date"><?= date('Y', strtotime($cert['issue_date'])) ?></div>
                    <h3 class="timeline-role"><?= Sanitizer::output($cert['name']) ?></h3>
                    <p class="timeline-company">
                        <i class="fa-solid fa-building-columns fa-xs" aria-hidden="true"></i>
                        <?= Sanitizer::output($cert['issuing_organization']) ?>
                    </p>
                    <?php if (!empty($cert['credential_id'])): ?>
                    <p style="font-size:var(--text-xs);color:var(--text-muted)">
                        <i class="fa-solid fa-id-badge me-1" aria-hidden="true"></i>
                        <?= Sanitizer::output($cert['credential_id']) ?>
                    </p>
                    <?php endif; ?>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </section>

    <!-- ── SKILLS ──────────────────────────────────────── -->
    <section class="mb-5 section-divider pt-5" aria-label="Technical skills">
        <h2 class="resume-section-title">
            <i class="fa-solid fa-code" aria-hidden="true"></i>
            <?= t('resume.sections.skills') ?>
        </h2>
        <div class="row g-5">
            <?php foreach ($skillsByCategory as $cat => $catSkills): ?>
            <div class="col-md-6" data-animate>
                <h3 class="h6 mb-4 text-accent"><?= Sanitizer::output($categoryNames[$cat] ?? $cat) ?></h3>
                <?php foreach ($catSkills as $skill): ?>
                <div class="skill-bar-wrap">
                    <div class="skill-bar-header">
                        <span><?= Sanitizer::output($skill['name']) ?>
                            <?php if (!empty($skill['years_of_experience'])): ?>
                            <small style="color:var(--text-muted);font-size:.75em">(<?= (int)$skill['years_of_experience'] ?> yr)</small>
                            <?php endif; ?>
                        </span>
                        <span class="text-accent"><?= (int)$skill['proficiency'] ?>%</span>
                    </div>
                    <div class="skill-bar-track">
                        <div class="skill-bar-fill" data-width="<?= (int)$skill['proficiency'] ?>%"
                             role="progressbar" aria-valuenow="<?= (int)$skill['proficiency'] ?>"
                             aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                    <?php $tools = $resumeService->getTechnicalTools((int)$skill['id']); ?>
                    <?php if (!empty($tools)): ?>
                    <div class="d-flex flex-wrap gap-1 mt-2">
                        <?php foreach ($tools as $tool): ?>
                        <span style="font-size:.7rem;padding:.15rem .5rem;background:var(--surface-raised);border:1px solid var(--border);border-radius:var(--radius-full);color:var(--text-muted)">
                            <?= Sanitizer::output($tool['name']) ?>
                        </span>
                        <?php endforeach; ?>
                    </div>
                    <?php endif; ?>
                </div>
                <?php endforeach; ?>
            </div>
            <?php endforeach; ?>
        </div>
    </section>

    <!-- ── RECRUITER VIEW ─────────────────────────────── -->
    <section id="recruiter-view" class="mb-5 section-divider pt-5"
             style="background:var(--surface);border:1px solid var(--border);border-radius:var(--radius-xl);padding:var(--space-8)"
             aria-label="Recruiter information">
        <h2 class="resume-section-title">
            <i class="fa-solid fa-user-tie" aria-hidden="true"></i>
            <?= t('resume.recruiter_view') ?>
        </h2>
        <div class="row g-5 mb-5">
            <div class="col-md-6" data-animate>
                <h3 class="h6 text-accent mb-4"><?= t('resume.recruiter_section.key_achievements') ?></h3>
                <ul class="timeline-list">
                    <?php foreach ($keyAchievements as $ach): ?>
                    <li><?= Sanitizer::output($ach['achievement']) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
            <div class="col-md-6" data-animate>
                <h3 class="h6 text-accent mb-4"><?= t('resume.recruiter_section.professional_goals') ?></h3>
                <ul class="timeline-list">
                    <?php foreach ($professionalGoals as $goal): ?>
                    <li><?= Sanitizer::output($goal['goal']) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>
        <div data-animate>
            <h3 class="h6 text-accent mb-2"><?= t('resume.recruiter_section.availability') ?></h3>
            <span style="font-size:var(--text-sm);color:var(--text-secondary)"><?= Sanitizer::output($personalInfo['availability_status'] ?? '') ?></span>
        </div>
    </section>

    <!-- ── LANGUAGES ──────────────────────────────────── -->
    <section class="mb-5 section-divider pt-5" aria-label="Languages">
        <h2 class="resume-section-title">
            <i class="fa-solid fa-language" aria-hidden="true"></i>
            <?= t('resume.sections.languages') ?>
        </h2>
        <div class="row g-3">
            <?php foreach ($languages as $lang): ?>
            <?php $p = strtolower($lang['proficiency']); ?>
            <div class="col-md-4" data-animate>
                <div class="lang-bar-row">
                    <span class="lang-bar-name"><?= Sanitizer::output($lang['name']) ?></span>
                    <div class="lang-bar-track">
                        <div class="lang-bar-fill" data-width="<?= $proficiencyMap[$p] ?? '50%' ?>"></div>
                    </div>
                    <span class="lang-bar-label">
                        <?= Sanitizer::output($proficiencyNames[$p] ?? $p) ?>
                        <?php if (!empty($lang['certified_level'])): ?>
                        (<?= Sanitizer::output($lang['certified_level']) ?>)
                        <?php endif; ?>
                    </span>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </section>

    <!-- ── REFERENCES ─────────────────────────────────── -->
    <?php if (!empty($references)): ?>
    <section class="mb-5 section-divider pt-5" aria-label="Professional references">
        <h2 class="resume-section-title">
            <i class="fa-solid fa-address-card" aria-hidden="true"></i>
            <?= t('resume.references') ?>
        </h2>
        <div class="row g-4">
            <?php foreach ($references as $ref): ?>
            <div class="col-md-6" data-animate>
                <div class="reference-card">
                    <h3 class="h6 mb-1"><?= Sanitizer::output($ref['name']) ?></h3>
                    <p style="font-size:var(--text-sm);color:var(--text-secondary);margin-bottom:var(--space-3)">
                        <?= Sanitizer::output($ref['position']) ?>, <?= Sanitizer::output($ref['company']) ?>
                    </p>
                    <p style="font-size:var(--text-sm);color:var(--text-muted);margin-bottom:.25rem">
                        <i class="fa-solid fa-envelope me-2" aria-hidden="true"></i><?= Sanitizer::output($ref['email']) ?>
                    </p>
                    <p style="font-size:var(--text-sm);color:var(--text-muted)">
                        <i class="fa-solid fa-phone me-2" aria-hidden="true"></i><?= Sanitizer::output($ref['phone']) ?>
                    </p>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </section>
    <?php endif; ?>

</div>
</main>

<?php include 'includes/footer.php'; ?>
<?php include 'includes/toast_and_modal.php'; ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="assets/js/toast.js"></script>
<script src="assets/js/lazy.js"></script>
<script src="assets/js/app.js"></script>
</body>
</html>
