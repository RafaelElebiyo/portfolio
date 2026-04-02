<?php
declare(strict_types=1);

require_once __DIR__ . '/../includes/bootstrap.php';

use Dompdf\Dompdf;
use Dompdf\Options;

$resumeService     = new ResumeService();
$personalInfo      = $resumeService->getPersonalInfo()       ?? [];
$workExperience    = $resumeService->getWorkExperience();
$certifications    = $resumeService->getCertifications();
$skills            = $resumeService->getTechnicalSkills();
$keyAchievements   = $resumeService->getKeyAchievements();
$professionalGoals = $resumeService->getProfessionalGoals();
$languages         = $resumeService->getLanguages();

$s = fn($v) => htmlspecialchars((string)($v ?? ''), ENT_QUOTES, 'UTF-8');

$langLevels = ['basic' => 'Basic', 'intermediate' => 'Intermediate', 'advanced' => 'Advanced', 'native' => 'Native'];
$profMap    = ['basic' => '25%', 'intermediate' => '50%', 'advanced' => '75%', 'native' => '100%'];

ob_start();
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<style>
  body{font-family:Arial,sans-serif;font-size:11px;color:#1e293b;margin:0;padding:0}
  .page{padding:30px 36px}
  h1{font-size:22px;color:#14B8A6;margin:0 0 2px}
  h2{font-size:13px;color:#14B8A6;border-bottom:1.5px solid #14B8A6;padding-bottom:4px;margin:18px 0 10px}
  h3{font-size:11px;font-weight:700;margin:0 0 2px}
  .subtitle{font-size:11px;color:#475569;margin:0 0 10px}
  .meta{font-size:10px;color:#64748b;margin-bottom:16px}
  .section{margin-bottom:16px}
  .exp-item{margin-bottom:12px;padding-left:14px;border-left:2px solid #14B8A6}
  .exp-date{font-size:9px;color:#14B8A6;font-weight:700;text-transform:uppercase;letter-spacing:.04em}
  .exp-company{font-size:10px;color:#475569;margin:1px 0 4px}
  ul{margin:4px 0 0 12px;padding:0}
  li{margin-bottom:2px}
  .skill-row{display:flex;align-items:center;margin-bottom:6px}
  .skill-name{width:130px;font-size:10px}
  .skill-track{flex:1;height:4px;background:#e2e8f0;border-radius:2px;overflow:hidden}
  .skill-fill{height:100%;background:#14B8A6;border-radius:2px}
  .skill-pct{width:28px;font-size:9px;text-align:right;color:#94a3b8}
  .cols{display:flex;gap:20px}
  .col{flex:1}
</style>
</head>
<body>
<div class="page">
  <h1><?= $s($personalInfo['full_name'] ?? '') ?></h1>
  <div class="subtitle"><?= $s($personalInfo['job_title'] ?? '') ?></div>
  <div class="meta"><?= $s($personalInfo['email'] ?? '') ?> &nbsp;|&nbsp; <?= $s($personalInfo['phone'] ?? '') ?> &nbsp;|&nbsp; <?= $s($personalInfo['location'] ?? '') ?></div>

  <div class="section">
    <h2>Professional Experience</h2>
    <?php foreach ($workExperience as $w): $achs = $resumeService->getWorkAchievements((int)$w['id']); ?>
    <div class="exp-item">
      <div class="exp-date"><?= date('Y', strtotime($w['start_date'])) ?> — <?= $w['is_current'] ? 'Present' : date('Y', strtotime($w['end_date'] ?? 'now')) ?></div>
      <h3><?= $s($w['position']) ?></h3>
      <div class="exp-company"><?= $s($w['company']) ?></div>
      <?php if ($achs): ?><ul><?php foreach ($achs as $a): ?><li><?= $s($a['achievement']) ?></li><?php endforeach; ?></ul><?php endif; ?>
    </div>
    <?php endforeach; ?>
  </div>

  <div class="section">
    <h2>Education</h2>
    <?php foreach ($certifications as $c): ?>
    <div class="exp-item">
      <div class="exp-date"><?= date('Y', strtotime($c['issue_date'])) ?></div>
      <h3><?= $s($c['name']) ?></h3>
      <div class="exp-company"><?= $s($c['issuing_organization']) ?></div>
    </div>
    <?php endforeach; ?>
  </div>

  <div class="section">
    <h2>Technical Skills</h2>
    <?php foreach ($skills as $sk): ?>
    <div class="skill-row">
      <span class="skill-name"><?= $s($sk['name']) ?></span>
      <div class="skill-track"><div class="skill-fill" style="width:<?= (int)$sk['proficiency'] ?>%"></div></div>
      <span class="skill-pct"><?= (int)$sk['proficiency'] ?>%</span>
    </div>
    <?php endforeach; ?>
  </div>

  <div class="cols">
    <div class="col">
      <h2>Key Achievements</h2>
      <ul><?php foreach ($keyAchievements as $a): ?><li><?= $s($a['achievement']) ?></li><?php endforeach; ?></ul>
    </div>
    <div class="col">
      <h2>Professional Goals</h2>
      <ul><?php foreach ($professionalGoals as $g): ?><li><?= $s($g['goal']) ?></li><?php endforeach; ?></ul>
    </div>
  </div>

  <div class="section">
    <h2>Languages</h2>
    <?php foreach ($languages as $l): $p = strtolower($l['proficiency']); ?>
    <div class="skill-row">
      <span class="skill-name"><?= $s($l['name']) ?></span>
      <div class="skill-track"><div class="skill-fill" style="width:<?= $profMap[$p] ?? '50%' ?>"></div></div>
      <span class="skill-pct"><?= $langLevels[$p] ?? $p ?></span>
    </div>
    <?php endforeach; ?>
  </div>
</div>
</body>
</html>
<?php
$html = ob_get_clean();

$options = new Options();
$options->set('isRemoteEnabled', false);
$options->set('defaultFont', 'Arial');

$dompdf = new Dompdf($options);
$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'portrait');
$dompdf->render();

$filename = preg_replace('/[^A-Za-z0-9_\-]/', '_', $personalInfo['full_name'] ?? 'CV') . '_CV.pdf';
$dompdf->stream($filename, ['Attachment' => true]);
exit;
