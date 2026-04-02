<?php
declare(strict_types=1);
require_once __DIR__ . '/../includes/bootstrap.php';
require_once __DIR__ . '/../middleware/AdminAuthMiddleware.php';
require_once __DIR__ . '/../helpers/CsrfToken.php';

AdminAuthMiddleware::requireAuth();

$current_page = 'admin_contact';
$page_title = t('admin.messages') ?? 'Mensajes - Admin';

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
        
        .empty-state {
            text-align: center;
            padding: var(--space-12);
            color: var(--text-muted);
        }
        .empty-state i {
            font-size: 3rem;
            margin-bottom: var(--space-4);
            opacity: 0.5;
        }
        
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
                <a href="experiencia.php" class="nav-link">
                    <i class="fa-solid fa-briefcase"></i><span><?= t('admin.experience') ?? 'Experiencia' ?></span>
                </a>
                <a href="mensajes.php" class="nav-link active">
                    <i class="fa-solid fa-envelope"></i><span><?= t('admin.messages') ?? 'Mensajes' ?></span>
                </a>
            </nav>
        </aside>

        <main class="admin-main">
            <div class="admin-header">
                <h1 class="admin-title"><?= t('admin.messages') ?? 'Mensajes de Contacto' ?></h1>
            </div>

            <div class="admin-card">
                <div class="empty-state">
                    <i class="fa-regular fa-envelope"></i>
                    <p><?= t('admin.no_messages') ?? 'No hay mensajes aún' ?></p>
                    <small><?= t('admin.no_messages_desc') ?? 'Los mensajes recibidos aparecerán aquí' ?></small>
                </div>
            </div>
        </main>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
