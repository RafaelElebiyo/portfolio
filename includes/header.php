<?php
require_once 'services/service.php'; 
require_once 'services/resume_service.php'; 

$resumeService = new ResumeService();

$personalInfo = $resumeService->getPersonalInfo();

$page_title = htmlspecialchars($personalInfo['full_name'])." | ".htmlspecialchars($personalInfo['job_title']);
$current_page = basename($_SERVER['PHP_SELF']);
?>
<header class="fixed-top bg-dark bg-opacity-90 backdrop-blur border-bottom border-secondary">
    <div class="container d-flex justify-content-between align-items-center py-3">
        <a href="index.php" class="text-decoration-none">
            <h1 class="m-0 fs-4 text-primary fw-bold"><?= htmlspecialchars($personalInfo['full_name']) ?></h1>
            <span class="text-light fs-6"><?= htmlspecialchars($personalInfo['job_title']) ?></span>
        </a>
        
        <button class="navbar-toggler d-lg-none border-0 px-2" type="button" data-bs-toggle="collapse" data-bs-target="#navbarCollapse" aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
    </div>
</header>