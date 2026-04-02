<?php
declare(strict_types=1);
require_once __DIR__ . '/../includes/bootstrap.php';
require_once __DIR__ . '/../middleware/AdminAuthMiddleware.php';
require_once __DIR__ . '/../helpers/CsrfToken.php';

AdminAuthMiddleware::requireAuth();

$current_page = 'admin_experience';
$page_title = t('admin.experience') ?? 'Experiencia - Admin';

$resumeService = new ResumeService();
$workExperience = $resumeService->getWorkExperience();
$currentUser = AdminAuthMiddleware::getCurrentUser();
$csrfToken = CsrfToken::get();
?>
<!DOCTYPE html>
<html lang="<?= current_lang() ?>" data-theme="dark">
<head>
    <?php include __DIR__ . '/../includes/head.php'; ?>
    <link href="../assets/css/main.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
    <style>
        :root { --header-h: 0px; --nav-h: 0px; }
        body { background: var(--bg-alt); color: var(--text); font-family: var(--font-body); }
        .admin-wrapper { display: flex; min-height: 100vh; }
        
        .admin-sidebar {
            width: 260px;
            background: var(--surface);
            border-right: 1px solid var(--border);
            position: fixed;
            left: 0;
            top: 0;
            bottom: 0;
            overflow-y: auto;
            z-index: var(--z-modal);
        }
        .sidebar-brand { padding: var(--space-6); border-bottom: 1px solid var(--border); }
        .sidebar-brand h4 { font-family: var(--font-display); color: var(--accent); margin: 0; font-size: var(--text-xl); }
        
        .sidebar-nav { padding: var(--space-4) 0; }
        .sidebar-nav .nav-link {
            display: flex;
            align-items: center;
            gap: var(--space-3);
            padding: var(--space-3) var(--space-6);
            color: var(--text-secondary);
            text-decoration: none;
            transition: all var(--t-fast) var(--ease);
            border-left: 3px solid transparent;
        }
        .sidebar-nav .nav-link:hover { background: var(--accent-subtle); color: var(--accent); }
        .sidebar-nav .nav-link.active { background: var(--accent-subtle); color: var(--accent); border-left-color: var(--accent); }
        .sidebar-nav .nav-link i { width: 20px; text-align: center; }
        
        .admin-main { flex: 1; margin-left: 260px; padding: var(--space-8); }
        .admin-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: var(--space-8); }
        .admin-title { font-family: var(--font-display); font-size: var(--text-3xl); font-weight: 700; color: var(--text); }
        
        .admin-card { background: var(--surface); border: 1px solid var(--border); border-radius: var(--radius-lg); overflow: hidden; margin-bottom: var(--space-6); }
        .admin-card-header { display: flex; justify-content: space-between; align-items: center; padding: var(--space-4) var(--space-6); border-bottom: 1px solid var(--border); }
        .admin-card-title { font-family: var(--font-display); font-size: var(--text-lg); font-weight: 600; color: var(--text); margin: 0; }
        
        .admin-table { width: 100%; border-collapse: collapse; }
        .admin-table th, .admin-table td { padding: var(--space-4) var(--space-6); text-align: left; border-bottom: 1px solid var(--border); }
        .admin-table th { background: var(--surface-raised); font-weight: 600; font-size: var(--text-sm); color: var(--text-secondary); }
        .admin-table tr:hover { background: var(--accent-subtle); }
        
        .experience-card {
            padding: var(--space-4) var(--space-6);
            border-bottom: 1px solid var(--border);
        }
        .experience-card:last-child { border-bottom: none; }
        .experience-card:hover { background: var(--accent-subtle); }
        
        .exp-header { display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: var(--space-2); }
        .exp-position { font-weight: 600; color: var(--text); font-size: var(--text-base); }
        .exp-company { color: var(--text-secondary); font-size: var(--text-sm); }
        .exp-dates { font-size: var(--text-xs); color: var(--text-muted); }
        .exp-type { font-size: var(--text-xs); color: var(--accent); }
        
        .empty-state {
            text-align: center;
            padding: var(--space-12);
            color: var(--text-muted);
        }
        .empty-state i { font-size: 3rem; opacity: 0.5; }
        
        .btn-admin { display: inline-flex; align-items: center; gap: var(--space-2); padding: var(--space-2) var(--space-4); border-radius: var(--radius-md); font-size: var(--text-sm); font-weight: 500; cursor: pointer; transition: all var(--t-fast) var(--ease); border: none; }
        .btn-admin-primary { background: var(--accent); color: var(--text-inverse); }
        .btn-admin-danger { background: var(--error); color: white; }
        .btn-admin-sm { padding: var(--space-1) var(--space-3); font-size: var(--text-xs); }
        
        @media (max-width: 991.98px) {
            .admin-sidebar { transform: translateX(-100%); transition: transform var(--t-base) var(--ease); }
            .admin-sidebar.show { transform: translateX(0); }
            .admin-main { margin-left: 0; padding: var(--space-4); }
            .admin-header { flex-direction: column; gap: var(--space-4); align-items: flex-start; }
            .admin-card { margin-bottom: var(--space-4); }
        }
        
        @media (max-width: 575.98px) {
            .admin-table { display: block; overflow-x: auto; }
            .admin-table th, .admin-table td { padding: var(--space-2) var(--space-3); font-size: var(--text-sm); }
        }
    </style>
</head>
<body>
    <div class="admin-wrapper">
        <aside class="admin-sidebar">
            <div class="sidebar-brand">
                <h4><i class="fa-solid fa-gauge-high me-2"></i>Admin</h4>
            </div>
            <nav class="sidebar-nav">
                <a href="dashboard.php" class="nav-link">
                    <i class="fa-solid fa-chart-line"></i><span><?= t('admin.dashboard') ?? 'Dashboard' ?></span>
                </a>
                <a href="proyectos.php" class="nav-link">
                    <i class="fa-solid fa-folder"></i><span><?= t('admin.projects') ?? 'Proyectos' ?></span>
                </a>
                <a href="habilidades.php" class="nav-link">
                    <i class="fa-solid fa-code"></i><span><?= t('admin.skills') ?? 'Habilidades' ?></span>
                </a>
                <a href="certificaciones.php" class="nav-link">
                    <i class="fa-solid fa-certificate"></i><span><?= t('admin.certifications') ?? 'Certificados' ?></span>
                </a>
                <a href="diplomas.php" class="nav-link">
                    <i class="fa-solid fa-graduation-cap"></i><span><?= t('admin.diplomas') ?? 'Diplomas' ?></span>
                </a>
                <a href="idiomas.php" class="nav-link">
                    <i class="fa-solid fa-globe"></i><span><?= t('admin.languages') ?? 'Idiomas' ?></span>
                </a>
                <a href="experiencia.php" class="nav-link active">
                    <i class="fa-solid fa-briefcase"></i><span><?= t('admin.experience') ?? 'Experiencia' ?></span>
                </a>
                <a href="mensajes.php" class="nav-link">
                    <i class="fa-solid fa-envelope"></i><span><?= t('admin.messages') ?? 'Mensajes' ?></span>
                </a>
            </nav>
        </aside>

        <main class="admin-main">
            <div class="admin-header">
                <h1 class="admin-title"><?= t('admin.experience') ?? 'Experiencia Laboral' ?></h1>
                <button class="btn-admin btn-admin-primary" onclick="addExperience()">
                    <i class="fa-solid fa-plus"></i> <?= t('admin.add') ?? 'Añadir' ?>
                </button>
            </div>

            <?php if (empty($workExperience)): ?>
            <div class="admin-card">
                <div class="empty-state">
                    <i class="fa-solid fa-briefcase"></i>
                    <p><?= t('admin.no_experience') ?? 'No hay experiencia laboral' ?></p>
                    <small><?= t('admin.no_experience_desc') ?? 'Añade tu historial laboral' ?></small>
                </div>
            </div>
            <?php else: ?>
            <div class="admin-card">
                <?php foreach ($workExperience as $work): ?>
                <?php $achievements = $resumeService->getWorkAchievements((int)$work['id']); ?>
                <div class="experience-card">
                    <div class="exp-header">
                        <div>
                            <div class="exp-position"><?= Sanitizer::output($work['position']) ?></div>
                            <div class="exp-company">
                                <i class="fa-solid fa-building me-1"></i>
                                <?= Sanitizer::output($work['company']) ?>
                                <?php if (!empty($work['location'])): ?>
                                <span class="ms-2">— <?= Sanitizer::output($work['location']) ?></span>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div>
                            <button class="btn-admin btn-admin-primary btn-admin-sm" onclick="editExperience(<?= $work['id'] ?>)">
                                <i class="fa-solid fa-pen"></i>
                            </button>
                            <button class="btn-admin btn-admin-danger btn-admin-sm" onclick="deleteExperience(<?= $work['id'] ?>)">
                                <i class="fa-solid fa-trash"></i>
                            </button>
                        </div>
                    </div>
                    <div class="exp-dates">
                        <i class="fa-regular fa-calendar me-1"></i>
                        <?= date('M Y', strtotime($work['start_date'])) ?> — 
                        <?= $work['is_current'] ? t('about.present') ?? 'Presente' : date('M Y', strtotime($work['end_date'] ?? 'now')) ?>
                        <span class="exp-type ms-2"><?= Sanitizer::output($work['employment_type']) ?></span>
                    </div>
                    <?php if (!empty($work['description'])): ?>
                    <p class="mt-2 text-secondary" style="font-size: var(--text-sm);"><?= Sanitizer::output($work['description']) ?></p>
                    <?php endif; ?>
                    <?php if (!empty($achievements)): ?>
                    <ul class="mt-2 mb-0" style="font-size: var(--text-sm); color: var(--text-secondary);">
                        <?php foreach ($achievements as $ach): ?>
                        <li><i class="fa-solid fa-check text-accent me-2"></i><?= Sanitizer::output($ach['achievement']) ?></li>
                        <?php endforeach; ?>
                    </ul>
                    <?php endif; ?>
                </div>
                <?php endforeach; ?>
            </div>
            <?php endif; ?>
        </main>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function addExperience() { alert('<?= t('admin.coming_soon') ?? 'Funcionalidad en desarrollo' ?>'); }
        function editExperience(id) { alert('<?= t('admin.coming_soon') ?? 'Funcionalidad en desarrollo' ?>: ' + id); }
        function deleteExperience(id) { if (confirm('<?= t('admin.confirm_delete') ?? '¿Eliminar?' ?>')) { alert('<?= t('admin.coming_soon') ?? 'Funcionalidad en desarrollo' ?>: ' + id); } }
    </script>
</body>
</html>
