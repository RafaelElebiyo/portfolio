<?php
require_once 'includes/translation.php';
require_once 'services/resume_service.php';

$resumeService = new ResumeService();
$current_page = 'resume.php';
$page_title = t('meta.resume_title');

if (isset($_GET['download'])) {
    include 'includes/generate-pdf.php';
    exit;
}

$personalInfo = $resumeService->getPersonalInfo();
$workExperience = $resumeService->getWorkExperience();
$certifications = $resumeService->getCertifications();
$skills = $resumeService->getTechnicalSkills();
$keyAchievements = $resumeService->getKeyAchievements();
$professionalGoals = $resumeService->getProfessionalGoals();
$languages = $resumeService->getLanguages();
$references = $resumeService->getProfessionalReferences();
?>

<!DOCTYPE html>
<html lang="es" data-bs-theme="dark">
<head>
    <?php include 'includes/head.php'; ?>
    <style>
        .timeline-item { position: relative; padding-left: 30px; margin-bottom: 30px; }
        .timeline-item:before { content: ''; position: absolute; left: 0; top: 5px; width: 15px; height: 15px; border-radius: 50%; background-color: var(--primary-color); }
        .timeline-item:after { content: ''; position: absolute; left: 7px; top: 25px; bottom: -35px; width: 1px; background-color: var(--primary-color); }
        .timeline-item:last-child:after { display: none; }
        .skill-bar { height: 8px; background-color: #333; border-radius: 4px; margin-bottom: 15px; }
        .skill-progress { height: 100%; border-radius: 4px; background-color: var(--primary-color); width: 0; transition: width 1s ease-in-out; }
        .pdf-section { background-color: rgba(74, 74, 74, 0.1); border-left: 3px solid var(--primary-color); padding: 15px; margin-bottom: 20px; }
        .tool-item { margin-bottom: 10px; }
        .reference-card { background-color: rgba(74, 74, 74, 0.1); padding: 15px; border-radius: 5px; margin-bottom: 15px; }
    </style>
</head>
<body class="bg-dark text-light">
    <?php include 'includes/header.php'; ?>
    <?php include 'includes/navigation.php'; ?>
    <main class="container py-5">
        <section class="mb-5 text-center">
            <h1 class="display-4 fw-bold mb-3">Curriculum Vitae</h1>
            <div class="d-flex justify-content-center gap-3">
                <a href="resume.php?download" class="btn btn-primary btn-lg px-4">
                    <i class="bi bi-download me-2"></i>Descargar PDF
                </a>
                <a href="#recruiter-view" class="btn btn-outline-light btn-lg px-4">
                    Vista para Reclutadores
                </a>
            </div>
        </section>
        <section class="mb-5">
            <div class="row align-items-center">
                <div class="col-md-3 text-center mb-4 mb-md-0">
                    <img src="<?= $personalInfo['profile_image'] ?? 'profile-default.jpg' ?>" alt="<?= $personalInfo['full_name'] ?>" class="img-fluid rounded-circle" style="width: 150px; height: 150px; object-fit: cover;">
                </div>
                <div class="col-md-9">
                    <h2 class="text-primary mb-3"><?= $personalInfo['full_name'] ?></h2>
                    <p class="lead"><?= $personalInfo['job_title'] ?></p>
                    <div class="d-flex flex-wrap gap-4">
                        <div><i class="bi bi-envelope-fill text-primary me-2"></i> <?= $personalInfo['email'] ?></div>
                        <div><i class="bi bi-phone-fill text-primary me-2"></i> <?= $personalInfo['phone'] ?></div>
                        <div><i class="bi bi-geo-alt-fill text-primary me-2"></i> <?= $personalInfo['location'] ?></div>
                    </div>
                </div>
            </div>
        </section>
        <section class="mb-5">
            <h2 class="border-bottom border-secondary pb-2 mb-4"><i class="bi bi-briefcase-fill text-primary me-2"></i>Experiencia Profesional</h2>
            <div class="timeline">
                <?php foreach ($workExperience as $work): ?>
                <div class="timeline-item">
                    <h3 class="h4"><?= $work['position'] ?></h3>
                    <p class="text-primary mb-2"><?= $work['company'] ?> | <?= date('Y', strtotime($work['start_date'])) ?> - <?= $work['is_current'] ? 'Presente' : date('Y', strtotime($work['end_date'])) ?></p>
                    <p class="text-muted"><?= $work['employment_type'] ?> | <?= $work['location'] ?></p>
                    <ul>
                        <?php $achievements = $resumeService->getWorkAchievements($work['id']); ?>
                        <?php foreach ($achievements as $achievement): ?>
                        <li><?= $achievement['achievement'] ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
                <?php endforeach; ?>
            </div>
        </section>
        <section class="mb-5">
            <h2 class="border-bottom border-secondary pb-2 mb-4"><i class="bi bi-mortarboard-fill text-primary me-2"></i>Educación</h2>
            <div class="timeline">
                <?php foreach ($certifications as $cert): ?>
                <div class="timeline-item">
                    <h3 class="h4"><?= $cert['name'] ?></h3>
                    <p class="text-primary mb-2"><?= $cert['issuing_organization'] ?> | <?= date('Y', strtotime($cert['issue_date'])) ?></p>
                    <?php if ($cert['credential_id']): ?>
                    <p class="text-muted">Credencial: <?= $cert['credential_id'] ?></p>
                    <?php endif; ?>
                </div>
                <?php endforeach; ?>
            </div>
        </section>
        <section class="mb-5">
            <h2 class="border-bottom border-secondary pb-2 mb-4"><i class="bi bi-code-slash text-primary me-2"></i>Habilidades Técnicas</h2>
            <div class="row">
                <?php 
                $categoryNames = [
                    'frontend' => 'Frontend',
                    'backend' => 'Backend',
                    'mobile' => 'Mobile',
                    'design' => 'Diseño',
                    'devops' => 'DevOps',
                    'database' => 'Bases de Datos',
                    'other' => 'Otras'
                ];
                
                $categories = [];
                foreach ($skills as $skill) {
                    $categories[$skill['category']][] = $skill;
                }
                ?>
                <?php foreach ($categories as $category => $categorySkills): ?>
                <div class="col-md-6 mb-4">
                    <h3 class="h5"><?= $categoryNames[$category] ?? $category ?></h3>
                    <?php foreach ($categorySkills as $skill): ?>
                    <div class="skill-item mb-3">
                        <div class="d-flex justify-content-between">
                            <span><?= $skill['name'] ?> (<?= $skill['years_of_experience'] ?? '0' ?> años)</span>
                            <span><?= $skill['proficiency'] ?>%</span>
                        </div>
                        <div class="skill-bar">
                            <div class="skill-progress" data-width="<?= $skill['proficiency'] ?>%"></div>
                        </div>
                        <?php $tools = $resumeService->getTechnicalTools($skill['id']); ?>
                        <?php if (!empty($tools)): ?>
                        <div class="mt-2">
                            <small class="text-muted">Herramientas:</small>
                            <div class="d-flex flex-wrap gap-2 mt-1">
                                <?php foreach ($tools as $tool): ?>
                                <span class="badge bg-secondary"><?= $tool['name'] ?> (<?= $tool['proficiency'] ?>%)</span>
                                <?php endforeach; ?>
                            </div>
                        </div>
                        <?php endif; ?>
                    </div>
                    <?php endforeach; ?>
                </div>
                <?php endforeach; ?>
            </div>
        </section>
        <section id="recruiter-view" class="mb-5 pdf-section">
            <h2 class="border-bottom border-primary pb-2 mb-4"><i class="bi bi-person-badge-fill text-primary me-2"></i>Información para Reclutadores</h2>
            <div class="row">
                <div class="col-md-6 mb-4">
                    <h3 class="h5 text-primary">Logros Clave</h3>
                    <ul>
                        <?php foreach ($keyAchievements as $achievement): ?>
                        <li class="mb-2"><?= $achievement['achievement'] ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
                <div class="col-md-6 mb-4">
                    <h3 class="h5 text-primary">Metas Profesionales</h3>
                    <ul>
                        <?php foreach ($professionalGoals as $goal): ?>
                        <li class="mb-2"><?= $goal['goal'] ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>
            <div class="mt-4">
                <h3 class="h5 text-primary">Disponibilidad</h3>
                <p><?= ucfirst($personalInfo['availability_status']) ?></p>
            </div>
        </section>
        <section class="mb-5">
            <h2 class="border-bottom border-secondary pb-2 mb-4"><i class="bi bi-translate text-primary me-2"></i>Idiomas</h2>
            <div class="row">
                <?php 
                $proficiencyNames = [
                    'basic' => 'Básico',
                    'intermediate' => 'Intermedio',
                    'advanced' => 'Avanzado',
                    'native' => 'Nativo'
                ];
                
                foreach ($languages as $language): 
                    $proficiency = strtolower($language['proficiency']);
                ?>
                <div class="col-md-4 mb-3">
                    <h3 class="h6"><?= $language['name'] ?></h3>
                    <div class="skill-bar">
                        <?php 
                        $proficiencyMap = [
                            'basic' => '25%',
                            'intermediate' => '50%',
                            'advanced' => '75%',
                            'native' => '100%'
                        ];
                        ?>
                        <div class="skill-progress" data-width="<?= $proficiencyMap[$proficiency] ?>"></div>
                    </div>
                    <small class="text-muted"><?= $proficiencyNames[$proficiency] ?? $proficiency ?><?= $language['certified_level'] ? ' (' . $language['certified_level'] . ')' : '' ?></small>
                </div>
                <?php endforeach; ?>
            </div>
        </section>
        <?php if (!empty($references)): ?>
        <section class="mb-5">
            <h2 class="border-bottom border-secondary pb-2 mb-4"><i class="bi bi-people-fill text-primary me-2"></i>Referencias Profesionales</h2>
            <div class="row">
                <?php foreach ($references as $reference): ?>
                <div class="col-md-6">
                    <div class="reference-card">
                        <h3 class="h5"><?= $reference['name'] ?></h3>
                        <p class="mb-1"><?= $reference['position'] ?>, <?= $reference['company'] ?></p>
                        <p class="mb-1"><i class="bi bi-envelope-fill text-primary me-2"></i> <?= $reference['email'] ?></p>
                        <p class="mb-1"><i class="bi bi-telephone-fill text-primary me-2"></i> <?= $reference['phone'] ?></p>
                        <p class="mb-0"><small class="text-muted">Relación: <?= $reference['relationship'] ?></small></p>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </section>
        <?php endif; ?>
    </main>
    <?php include 'includes/footer.php'; ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const skillBars = document.querySelectorAll('.skill-progress');
        skillBars.forEach(bar => {
            const width = bar.getAttribute('data-width');
            bar.style.width = width;
        });
    });
    </script>
</body>
</html>