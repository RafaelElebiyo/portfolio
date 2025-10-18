<?php
require_once 'vendor/autoload.php';
require_once 'services/resume_service.php';

$resumeService = new ResumeService();

$personalInfo = $resumeService->getPersonalInfo();
$workExperience = $resumeService->getWorkExperience();
$certifications = $resumeService->getCertifications();
$skills = $resumeService->getTechnicalSkills();
$keyAchievements = $resumeService->getKeyAchievements();
$professionalGoals = $resumeService->getProfessionalGoals();
$languages = $resumeService->getLanguages();
$references = $resumeService->getProfessionalReferences();

$sectionTitles = [
    'experience' => 'Experiencia Profesional',
    'education' => 'Educación',
    'skills' => 'Habilidades Técnicas',
    'languages' => 'Idiomas',
    'recruiter_view' => 'Información para Reclutadores',
    'key_achievements' => 'Logros Clave',
    'professional_goals' => 'Metas Profesionales',
    'availability' => 'Disponibilidad',
    'present' => 'Presente',
    'references' => 'Referencias Profesionales'
];

$skillCategories = [
    'frontend' => 'Frontend',
    'backend' => 'Backend',
    'mobile' => 'Mobile',
    'design' => 'Diseño',
    'devops' => 'DevOps',
    'database' => 'Bases de Datos',
    'other' => 'Otras'
];

$languageLevels = [
    'basic' => 'Básico',
    'intermediate' => 'Intermedio',
    'advanced' => 'Avanzado',
    'native' => 'Nativo'
];

$availabilityStatuses = [
    'open' => 'Disponible para oportunidades',
    'busy' => 'Actualmente ocupado',
    'unavailable' => 'No disponible'
];

$employmentTypes = [
    'full-time' => 'Tiempo completo',
    'part-time' => 'Medio tiempo',
    'contract' => 'Contrato',
    'freelance' => 'Freelance',
    'internship' => 'Pasantía'
];

$html = '
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>CV - ' . $personalInfo['full_name'] . '</title>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        h1, h2, h3 { color: #4a4a4a; }
        h1 { font-size: 22px; border-bottom: 2px solid #4a4a4a; padding-bottom: 5px; }
        h2 { font-size: 16px; border-bottom: 1px solid #ddd; padding-bottom: 3px; margin-top: 20px; }
        .header { text-align: center; margin-bottom: 20px; }
        .contact-info { margin: 10px 0; font-size: 12px; }
        .timeline-item { margin-bottom: 15px; position: relative; padding-left: 15px; }
        .timeline-item:before { content: ""; position: absolute; left: 0; top: 5px; width: 8px; height: 8px; border-radius: 50%; background-color: #4a4a4a; }
        .timeline-date { font-weight: bold; color: #4a4a4a; font-size: 14px; }
        .skill-bar { height: 5px; background-color: #eee; border-radius: 3px; margin-bottom: 10px; }
        .skill-progress { height: 100%; border-radius: 3px; background-color: #4a4a4a; }
        ul { padding-left: 15px; }
        li { margin-bottom: 3px; }
        .recruiter-section { background-color: #f5f5f5; padding: 10px; margin: 15px 0; border-left: 3px solid #4a4a4a; }
        .reference-card { background-color: #f5f5f5; padding: 10px; margin: 10px 0; border-radius: 3px; }
        .tool-item { font-size: 12px; margin-left: 10px; }
    </style>
</head>
<body>
    <div class="header">
        <h1>' . $personalInfo['full_name'] . '</h1>
        <p>' . $personalInfo['job_title'] . '</p>
        <div class="contact-info">
            <span>' . $personalInfo['email'] . ' | ' . $personalInfo['phone'] . ' | ' . $personalInfo['location'] . '</span>
        </div>
    </div>

    <h2>' . $sectionTitles['experience'] . '</h2>';
    
foreach ($workExperience as $work) {
    $html .= '
    <div class="timeline-item">
        <h3>' . $work['position'] . '</h3>
        <div class="timeline-date">' . $work['company'] . ' | ' . date('Y', strtotime($work['start_date'])) . ' - ' . ($work['is_current'] ? $sectionTitles['present'] : date('Y', strtotime($work['end_date']))) . ' | ' . ($employmentTypes[$work['employment_type']] ?? $work['employment_type']) . ' | ' . $work['location'] . '</div>
        <ul>';
    
    $achievements = $resumeService->getWorkAchievements($work['id']);
    foreach ($achievements as $achievement) {
        $html .= '<li>' . $achievement['achievement'] . '</li>';
    }
    
    $html .= '
        </ul>
    </div>';
}

$html .= '
    <h2>' . $sectionTitles['education'] . '</h2>';
    
foreach ($certifications as $cert) {
    $html .= '
    <div class="timeline-item">
        <h3>' . $cert['name'] . '</h3>
        <div class="timeline-date">' . $cert['issuing_organization'] . ' | ' . date('Y', strtotime($cert['issue_date'])) . ($cert['credential_id'] ? ' | Credencial: ' . $cert['credential_id'] : '') . '</div>
    </div>';
}

$html .= '
    <h2>' . $sectionTitles['skills'] . '</h2>
    <div style="display: flex; justify-content: space-between; flex-wrap: wrap;">';
    
$categories = [];
foreach ($skills as $skill) {
    $categories[$skill['category']][] = $skill;
}

foreach ($categories as $category => $categorySkills) {
    $html .= '
    <div style="width: 48%;">
        <h3>' . ($skillCategories[$category] ?? $category) . '</h3>';
    
    foreach ($categorySkills as $skill) {
        $html .= '
        <div>
            <div>' . $skill['name'] . ' (' . ($skill['years_of_experience'] ?? '0') . ' años) - ' . $skill['proficiency'] . '%</div>
            <div class="skill-bar">
                <div class="skill-progress" style="width: ' . $skill['proficiency'] . '%;"></div>
            </div>';
        
        $tools = $resumeService->getTechnicalTools($skill['id']);
        if (!empty($tools)) {
            $html .= '<div style="margin-left: 10px; font-size: 12px;">';
            foreach ($tools as $tool) {
                $html .= '<div class="tool-item">' . $tool['name'] . ' (' . $tool['proficiency'] . '%)</div>';
            }
            $html .= '</div>';
        }
        
        $html .= '
        </div>';
    }
    
    $html .= '
    </div>';
}

$html .= '
    </div>

    <div class="recruiter-section">
        <h2>' . $sectionTitles['recruiter_view'] . '</h2>
        <div style="display: flex; justify-content: space-between; flex-wrap: wrap;">
            <div style="width: 48%;">
                <h3>' . $sectionTitles['key_achievements'] . '</h3>
                <ul>';
                
foreach ($keyAchievements as $achievement) {
    $html .= '<li>' . $achievement['achievement'] . '</li>';
}

$html .= '
                </ul>
            </div>
            <div style="width: 48%;">
                <h3>' . $sectionTitles['professional_goals'] . '</h3>
                <ul>';
                
foreach ($professionalGoals as $goal) {
    $html .= '<li>' . $goal['goal'] . '</li>';
}

$html .= '
                </ul>
            </div>
        </div>
        <h3>' . $sectionTitles['availability'] . '</h3>
        <p>' . ($availabilityStatuses[strtolower($personalInfo['availability_status'])] ?? $personalInfo['availability_status']) . '</p>
    </div>

    <h2>' . $sectionTitles['languages'] . '</h2>
    <div style="display: flex; justify-content: space-between; flex-wrap: wrap;">';
    
foreach ($languages as $language) {
    $proficiency = strtolower($language['proficiency']);
    $proficiencyMap = [
        'basic' => '25%',
        'intermediate' => '50%',
        'advanced' => '75%',
        'native' => '100%'
    ];
    
    $html .= '
    <div style="width: 30%;">
        <div>' . $language['name'] . '</div>
        <div class="skill-bar">
            <div class="skill-progress" style="width: ' . $proficiencyMap[$proficiency] . ';"></div>
        </div>
        <small>' . ($languageLevels[$proficiency] ?? $proficiency) . ($language['certified_level'] ? ' (' . $language['certified_level'] . ')' : '') . '</small>
    </div>';
}

$html .= '
    </div>';

if (!empty($references)) {
    $html .= '
    <h2>' . $sectionTitles['references'] . '</h2>
    <div style="display: flex; justify-content: space-between; flex-wrap: wrap;">';
    
    foreach ($references as $reference) {
        $html .= '
        <div style="width: 48%;">
            <div class="reference-card">
                <h3>' . $reference['name'] . '</h3>
                <p>' . $reference['position'] . ', ' . $reference['company'] . '</p>
                <p>Email: ' . $reference['email'] . '</p>
                <p>Teléfono: ' . $reference['phone'] . '</p>
                <p><small>Relación: ' . $reference['relationship'] . '</small></p>
            </div>
        </div>';
    }
    
    $html .= '
    </div>';
}

$html .= '
</body>
</html>';

use Dompdf\Dompdf;
use Dompdf\Options;

$options = new Options();
$options->set('isRemoteEnabled', true);
$options->set('defaultFont', 'Arial');

$dompdf = new Dompdf($options);
$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'portrait');
$dompdf->render();
$dompdf->stream($personalInfo['full_name'] . '_CV.pdf', ['Attachment' => true]);
exit;
?>