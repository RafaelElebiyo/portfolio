<?php
declare(strict_types=1);
require_once __DIR__ . '/../includes/bootstrap.php';
require_once __DIR__ . '/../middleware/AdminAuthMiddleware.php';
require_once __DIR__ . '/../helpers/CsrfToken.php';

AdminAuthMiddleware::requireAuth();

$current_page = 'admin_skills';
$page_title = t('admin.skills') ?? 'Habilidades - Admin';

$resumeService = new ResumeService();
$skills = $resumeService->getTechnicalSkills();
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
        
        .admin-card { background: var(--surface); border: 1px solid var(--border); border-radius: var(--radius-lg); overflow: hidden; }
        .admin-card-header { display: flex; justify-content: space-between; align-items: center; padding: var(--space-4) var(--space-6); border-bottom: 1px solid var(--border); }
        .admin-card-title { font-family: var(--font-display); font-size: var(--text-lg); font-weight: 600; color: var(--text); margin: 0; }
        
        .admin-table { width: 100%; border-collapse: collapse; }
        .admin-table th, .admin-table td { padding: var(--space-4) var(--space-6); text-align: left; border-bottom: 1px solid var(--border); }
        .admin-table th { background: var(--surface-raised); font-weight: 600; font-size: var(--text-sm); color: var(--text-secondary); }
        .admin-table tr:hover { background: var(--accent-subtle); }
        
        .progress-bar-custom {
            width: 100%;
            height: 8px;
            background: var(--surface-raised);
            border-radius: var(--radius-full);
            overflow: hidden;
        }
        .progress-bar-fill {
            height: 100%;
            background: var(--accent);
            border-radius: var(--radius-full);
            transition: width var(--t-base) var(--ease);
        }
        
        .btn-admin { display: inline-flex; align-items: center; gap: var(--space-2); padding: var(--space-2) var(--space-4); border-radius: var(--radius-md); font-size: var(--text-sm); font-weight: 500; cursor: pointer; transition: all var(--t-fast) var(--ease); border: none; }
        .btn-admin-primary { background: var(--accent); color: var(--text-inverse); }
        .btn-admin-danger { background: var(--error); color: white; }
        .btn-admin-sm { padding: var(--space-1) var(--space-3); font-size: var(--text-xs); }
        
        @media (max-width: 991.98px) {
            .admin-sidebar { transform: translateX(-100%); transition: transform var(--t-base) var(--ease); }
            .admin-sidebar.show { transform: translateX(0); }
            .admin-main { margin-left: 0; padding: var(--space-4); }
            .admin-header { flex-direction: column; gap: var(--space-4); align-items: flex-start; }
            .stats-grid { grid-template-columns: repeat(2, 1fr); gap: var(--space-4); }
            .admin-card { margin-bottom: var(--space-4); }
        }
        
        @media (max-width: 575.98px) {
            .stats-grid { grid-template-columns: 1fr; }
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
                <a href="habilidades.php" class="nav-link active">
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
                <a href="experiencia.php" class="nav-link">
                    <i class="fa-solid fa-briefcase"></i><span><?= t('admin.experience') ?? 'Experiencia' ?></span>
                </a>
                <a href="mensajes.php" class="nav-link">
                    <i class="fa-solid fa-envelope"></i><span><?= t('admin.messages') ?? 'Mensajes' ?></span>
                </a>
            </nav>
        </aside>

        <main class="admin-main">
            <div class="admin-header">
                <h1 class="admin-title"><?= t('admin.skills') ?? 'Habilidades Técnicas' ?></h1>
                <button class="btn-admin btn-admin-primary" onclick="addSkill()">
                    <i class="fa-solid fa-plus"></i> <?= t('admin.add') ?? 'Añadir' ?>
                </button>
            </div>

            <div class="admin-card">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th><?= t('admin.name') ?? 'Nombre' ?></th>
                            <th><?= t('admin.category') ?? 'Categoría' ?></th>
                            <th><?= t('admin.proficiency') ?? 'Nivel' ?></th>
                            <th><?= t('admin.experience') ?? 'Años' ?></th>
                            <th><?= t('admin.actions') ?? 'Acciones' ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($skills as $skill): ?>
                        <tr>
                            <td><?= $skill['id'] ?></td>
                            <td><?= Sanitizer::output($skill['name']) ?></td>
                            <td><?= Sanitizer::output($skill['category']) ?></td>
                            <td style="width: 200px;">
                                <div class="progress-bar-custom">
                                    <div class="progress-bar-fill" style="width: <?= $skill['proficiency'] ?? 0 ?>%"></div>
                                </div>
                                <small class="text-muted"><?= $skill['proficiency'] ?? 0 ?>%</small>
                            </td>
                            <td><?= $skill['years_of_experience'] ?? 0 ?></td>
                            <td>
                                <button class="btn-admin btn-admin-primary btn-admin-sm" onclick="editSkill(<?= $skill['id'] ?>)">
                                    <i class="fa-solid fa-pen"></i>
                                </button>
                                <button class="btn-admin btn-admin-danger btn-admin-sm" onclick="deleteSkill(<?= $skill['id'] ?>)">
                                    <i class="fa-solid fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </main>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function addSkill() { alert('<?= t('admin.coming_soon') ?? 'Funcionalidad en desarrollo' ?>'); }
        function editSkill(id) { alert('<?= t('admin.coming_soon') ?? 'Funcionalidad en desarrollo' ?>: ' + id); }
        function deleteSkill(id) { if (confirm('<?= t('admin.confirm_delete') ?? '¿Eliminar?' ?>')) { alert('<?= t('admin.coming_soon') ?? 'Funcionalidad en desarrollo' ?>: ' + id); } }
    </script>
</body>
</html>
