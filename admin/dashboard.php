<?php
declare(strict_types=1);
require_once __DIR__ . '/../includes/bootstrap.php';
require_once __DIR__ . '/../middleware/AdminAuthMiddleware.php';
require_once __DIR__ . '/../helpers/CsrfToken.php';

AdminAuthMiddleware::requireAuth();

$current_page = 'admin';
$page_title = t('admin.dashboard') ?? 'Admin Dashboard';

$resumeService = new ResumeService();
$projectsService = new ProjectsService();
$adminService = new AdminService();

$stats = [
    'projects' => $projectsService->getProjectsCount(),
    'skills' => count($resumeService->getTechnicalSkills()),
    'certifications' => count($resumeService->getCertifications()),
    'languages' => count($resumeService->getLanguages()),
];

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
        /* Admin overrides using portfolio v2 design tokens */
        :root {
            --header-h: 0px;
            --nav-h: 0px;
        }
        body { 
            background: var(--bg-alt); 
            color: var(--text);
            font-family: var(--font-body);
        }
        .admin-wrapper { display: flex; min-height: 100vh; }
        
        /* Sidebar */
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
        .sidebar-brand {
            padding: var(--space-6);
            border-bottom: 1px solid var(--border);
        }
        .sidebar-brand h4 {
            font-family: var(--font-display);
            color: var(--accent);
            margin: 0;
            font-size: var(--text-xl);
        }
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
        .sidebar-nav .nav-link:hover {
            background: var(--accent-subtle);
            color: var(--accent);
        }
        .sidebar-nav .nav-link.active {
            background: var(--accent-subtle);
            color: var(--accent);
            border-left-color: var(--accent);
        }
        .sidebar-nav .nav-link i {
            width: 20px;
            text-align: center;
        }
        
        /* Main Content */
        .admin-main {
            flex: 1;
            margin-left: 260px;
            padding: var(--space-8);
        }
        .admin-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: var(--space-8);
        }
        .admin-title {
            font-family: var(--font-display);
            font-size: var(--text-3xl);
            font-weight: 700;
            color: var(--text);
        }
        
        /* Stats Cards */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: var(--space-6);
            margin-bottom: var(--space-8);
        }
        .stat-card {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: var(--radius-lg);
            padding: var(--space-6);
            transition: all var(--t-base) var(--ease);
        }
        .stat-card:hover {
            transform: translateY(-4px);
            box-shadow: var(--shadow-md);
        }
        .stat-card .stat-icon {
            width: 48px;
            height: 48px;
            border-radius: var(--radius-md);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: var(--text-xl);
            margin-bottom: var(--space-4);
        }
        .stat-card .stat-value {
            font-family: var(--font-display);
            font-size: var(--text-4xl);
            font-weight: 700;
            color: var(--text);
            line-height: 1;
        }
        .stat-card .stat-label {
            font-size: var(--text-sm);
            color: var(--text-muted);
            margin-top: var(--space-2);
        }
        
        /* Tables */
        .admin-card {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: var(--radius-lg);
            overflow: hidden;
            margin-bottom: var(--space-6);
        }
        .admin-card-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: var(--space-4) var(--space-6);
            border-bottom: 1px solid var(--border);
        }
        .admin-card-title {
            font-family: var(--font-display);
            font-size: var(--text-lg);
            font-weight: 600;
            color: var(--text);
            margin: 0;
        }
        .admin-table {
            width: 100%;
            border-collapse: collapse;
        }
        .admin-table th,
        .admin-table td {
            padding: var(--space-4) var(--space-6);
            text-align: left;
            border-bottom: 1px solid var(--border);
        }
        .admin-table th {
            background: var(--surface-raised);
            font-weight: 600;
            font-size: var(--text-sm);
            color: var(--text-secondary);
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }
        .admin-table tr:hover {
            background: var(--accent-subtle);
        }
        .admin-table tr:last-child td {
            border-bottom: none;
        }
        
        /* Buttons */
        .btn-admin {
            display: inline-flex;
            align-items: center;
            gap: var(--space-2);
            padding: var(--space-2) var(--space-4);
            border-radius: var(--radius-md);
            font-size: var(--text-sm);
            font-weight: 500;
            cursor: pointer;
            transition: all var(--t-fast) var(--ease);
            border: none;
            text-decoration: none;
        }
        .btn-admin-primary {
            background: var(--accent);
            color: var(--text-inverse);
        }
        .btn-admin-primary:hover {
            background: var(--accent-dark);
        }
        .btn-admin-success {
            background: var(--success);
            color: white;
        }
        .btn-admin-danger {
            background: var(--error);
            color: white;
        }
        .btn-admin-sm {
            padding: var(--space-1) var(--space-3);
            font-size: var(--text-xs);
        }
        
        /* Badges */
        .badge-admin {
            display: inline-block;
            padding: var(--space-1) var(--space-3);
            border-radius: var(--radius-sm);
            font-size: var(--text-xs);
            font-weight: 500;
        }
        .badge-featured {
            background: var(--accent-subtle);
            color: var(--accent);
        }
        .badge-normal {
            background: var(--surface-raised);
            color: var(--text-muted);
        }
        
        /* Info List */
        .info-list {
            padding: var(--space-4);
        }
        .info-item {
            padding: var(--space-3) 0;
            border-bottom: 1px solid var(--border);
        }
        .info-item:last-child {
            border-bottom: none;
        }
        .info-label {
            font-size: var(--text-xs);
            color: var(--text-muted);
            text-transform: uppercase;
            letter-spacing: 0.05em;
            margin-bottom: var(--space-1);
        }
        .info-value {
            font-size: var(--text-base);
            color: var(--text);
            font-weight: 500;
        }
        
        /* Responsive */
        @media (max-width: 991.98px) {
            .admin-sidebar {
                transform: translateX(-100%);
                transition: transform var(--t-base) var(--ease);
            }
            .admin-sidebar.show {
                transform: translateX(0);
            }
            .admin-main {
                margin-left: 0;
                padding: var(--space-4);
            }
            .admin-header {
                flex-direction: column;
                gap: var(--space-4);
                align-items: flex-start;
            }
            .stats-grid {
                grid-template-columns: repeat(2, 1fr);
                gap: var(--space-4);
            }
            .stat-card {
                padding: var(--space-4);
            }
            .stat-card .stat-value {
                font-size: var(--text-2xl);
            }
            .stat-card .stat-icon {
                width: 40px;
                height: 40px;
                font-size: var(--text-lg);
            }
        }
        
        @media (max-width: 575.98px) {
            .stats-grid {
                grid-template-columns: 1fr;
            }
            .admin-card {
                margin-bottom: var(--space-4);
            }
            .admin-table {
                display: block;
                overflow-x: auto;
            }
            .admin-table th,
            .admin-table td {
                padding: var(--space-2) var(--space-3);
                font-size: var(--text-sm);
            }
            .admin-card-header {
                flex-direction: column;
                gap: var(--space-2);
                align-items: flex-start;
            }
        }
        
        /* Color utilities */
        .text-accent { color: var(--accent); }
        .text-success { color: var(--success); }
        .text-warning { color: var(--warning); }
        .text-danger { color: var(--error); }
        .text-muted { color: var(--text-muted); }
        
        .bg-primary-subtle { background: var(--accent-subtle); }
        .bg-success-subtle { background: rgba(16, 185, 129, 0.15); }
        .bg-warning-subtle { background: rgba(245, 158, 11, 0.15); }
        .bg-info-subtle { background: rgba(59, 130, 246, 0.15); }
    </style>
</head>
<body>
    <div class="admin-wrapper">
        <aside class="admin-sidebar">
            <div class="sidebar-brand">
                <h4><i class="fa-solid fa-gauge-high me-2"></i>Admin</h4>
            </div>
            <nav class="sidebar-nav">
                <a href="../index.php" class="nav-link" target="_blank">
                    <i class="fa-solid fa-house"></i><span><?= t('admin.view_site') ?? 'Ver Sitio' ?></span>
                </a>
                <a href="dashboard.php" class="nav-link active">
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
                <a href="mensajes.php" class="nav-link">
                    <i class="fa-solid fa-envelope"></i><span><?= t('admin.messages') ?? 'Mensajes' ?></span>
                </a>
                <hr class="my-3" style="border-color: var(--border);">
                <div class="px-4 py-2">
                    <small class="text-muted d-block mb-1"><?= t('admin.logged_as') ?? 'Logged as' ?></small>
                    <strong class="text-accent"><?= Sanitizer::output($currentUser['full_name'] ?? '') ?></strong>
                </div>
                <a href="#" class="nav-link text-danger" onclick="logout(event)">
                    <i class="fa-solid fa-sign-out-alt"></i><span><?= t('admin.logout') ?? 'Cerrar Sesión' ?></span>
                </a>
            </nav>
        </aside>

        <main class="admin-main">
            <div class="admin-header">
                <h1 class="admin-title"><?= t('admin.dashboard') ?? 'Dashboard' ?></h1>
                <span class="text-muted"><?= date('d/m/Y H:i') ?></span>
            </div>

            <!-- Stats -->
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-icon bg-primary-subtle text-accent">
                        <i class="fa-solid fa-folder"></i>
                    </div>
                    <div class="stat-value"><?= $stats['projects'] ?></div>
                    <div class="stat-label"><?= t('admin.projects') ?? 'Proyectos' ?></div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon bg-success-subtle text-success">
                        <i class="fa-solid fa-code"></i>
                    </div>
                    <div class="stat-value"><?= $stats['skills'] ?></div>
                    <div class="stat-label"><?= t('admin.skills') ?? 'Habilidades' ?></div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon bg-warning-subtle text-warning">
                        <i class="fa-solid fa-certificate"></i>
                    </div>
                    <div class="stat-value"><?= $stats['certifications'] ?></div>
                    <div class="stat-label"><?= t('admin.certifications') ?? 'Certificados' ?></div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon bg-info-subtle" style="color: #3b82f6;">
                        <i class="fa-solid fa-globe"></i>
                    </div>
                    <div class="stat-value"><?= $stats['languages'] ?></div>
                    <div class="stat-label"><?= t('admin.languages') ?? 'Idiomas' ?></div>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-8">
                    <!-- Projects Table -->
                    <div class="admin-card">
                        <div class="admin-card-header">
                            <h3 class="admin-card-title"><?= t('admin.recent_projects') ?? 'Proyectos Recientes' ?></h3>
                            <button class="btn-admin btn-admin-primary" onclick="addProject()">
                                <i class="fa-solid fa-plus"></i> <?= t('admin.add') ?? 'Añadir' ?>
                            </button>
                        </div>
                        <table class="admin-table">
                            <thead>
                                <tr>
                                    <th><?= t('admin.title') ?? 'Título' ?></th>
                                    <th><?= t('admin.category') ?? 'Categoría' ?></th>
                                    <th><?= t('admin.status') ?? 'Estado' ?></th>
                                    <th><?= t('admin.actions') ?? 'Acciones' ?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($projectsService->getAllProjects() as $project): ?>
                                <tr>
                                    <td><?= Sanitizer::output($project['title']) ?></td>
                                    <td><span class="badge-admin badge-normal"><?= Sanitizer::output($project['category']) ?></span></td>
                                    <td>
                                        <?php if (!empty($project['is_featured'])): ?>
                                            <span class="badge-admin badge-featured"><?= t('admin.featured') ?? 'Destacado' ?></span>
                                        <?php else: ?>
                                            <span class="badge-admin badge-normal"><?= t('admin.normal') ?? 'Normal' ?></span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <button class="btn-admin btn-admin-primary btn-admin-sm" onclick="editProject(<?= $project['id'] ?>)">
                                            <i class="fa-solid fa-pen"></i>
                                        </button>
                                        <button class="btn-admin btn-admin-danger btn-admin-sm" onclick="deleteProject(<?= $project['id'] ?>)">
                                            <i class="fa-solid fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="col-lg-4">
                    <!-- Personal Info -->
                    <div class="admin-card">
                        <div class="admin-card-header">
                            <h3 class="admin-card-title"><?= t('admin.personal_info') ?? 'Información Personal' ?></h3>
                        </div>
                        <div class="info-list">
                            <?php $personal = $resumeService->getPersonalInfo(); ?>
                            <div class="info-item">
                                <div class="info-label"><?= t('admin.name') ?? 'Nombre' ?></div>
                                <div class="info-value"><?= Sanitizer::output($personal['full_name'] ?? '') ?></div>
                            </div>
                            <div class="info-item">
                                <div class="info-label"><?= t('admin.job_title') ?? 'Título' ?></div>
                                <div class="info-value"><?= Sanitizer::output($personal['job_title'] ?? '') ?></div>
                            </div>
                            <div class="info-item">
                                <div class="info-label"><?= t('admin.email') ?? 'Email' ?></div>
                                <div class="info-value"><?= Sanitizer::output($personal['email'] ?? '') ?></div>
                            </div>
                            <div class="info-item">
                                <div class="info-label"><?= t('admin.location') ?? 'Ubicación' ?></div>
                                <div class="info-value"><?= Sanitizer::output($personal['location'] ?? '') ?></div>
                            </div>
                            <div class="info-item">
                                <button class="btn-admin btn-admin-primary w-100 mt-3" onclick="editPersonalInfo()">
                                    <i class="fa-solid fa-pen me-2"></i><?= t('admin.edit') ?? 'Editar' ?>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <!-- Modal for Projects -->
    <div class="modal fade" id="projectModal" tabindex="-1" aria-labelledby="projectModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content" style="background: var(--surface); border: 1px solid var(--border);">
                <div class="modal-header" style="border-bottom: 1px solid var(--border);">
                    <h5 class="modal-title" id="projectModalLabel"><?= t('admin.add_project') ?? 'Añadir Proyecto' ?></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" style="filter: invert(1);"></button>
                </div>
                <form id="projectForm">
                    <div class="modal-body">
                        <input type="hidden" name="csrf_token" value="<?= $csrfToken ?>">
                        <input type="hidden" name="action" value="create_project">
                        <input type="hidden" name="id" id="projectId">
                        
                        <div class="mb-3">
                            <label class="form-label"><?= t('admin.title') ?? 'Título' ?> *</label>
                            <input type="text" class="form-control" name="title" id="projectTitle" required style="background: var(--bg); border: 1px solid var(--border); color: var(--text);">
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label"><?= t('admin.description') ?? 'Descripción' ?></label>
                            <textarea class="form-control" name="description" id="projectDescription" rows="3" style="background: var(--bg); border: 1px solid var(--border); color: var(--text);"></textarea>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label"><?= t('admin.category') ?? 'Categoría' ?></label>
                                <select class="form-select" name="category" id="projectCategory" style="background: var(--bg); border: 1px solid var(--border); color: var(--text);">
                                    <option value="web">Web</option>
                                    <option value="mobile">Mobile</option>
                                    <option value="cross-platform">Cross-Platform</option>
                                    <option value="cms">CMS</option>
                                    <option value="cloud">Cloud</option>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label"><?= t('admin.tech_stack') ?? 'Tech Stack' ?></label>
                                <input type="text" class="form-control" name="tech_stack" id="projectTechStack" style="background: var(--bg); border: 1px solid var(--border); color: var(--text);">
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label"><?= t('admin.image_url') ?? 'URL de Imagen' ?></label>
                                <input type="text" class="form-control" name="image_url" id="projectImageUrl" style="background: var(--bg); border: 1px solid var(--border); color: var(--text);">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label"><?= t('admin.live_url') ?? 'URL en Vivo' ?></label>
                                <input type="text" class="form-control" name="live_url" id="projectLiveUrl" style="background: var(--bg); border: 1px solid var(--border); color: var(--text);">
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label"><?= t('admin.github_url') ?? 'GitHub URL' ?></label>
                            <input type="text" class="form-control" name="github_url" id="projectGithubUrl" style="background: var(--bg); border: 1px solid var(--border); color: var(--text);">
                        </div>
                        
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="is_featured" id="projectFeatured" style="accent-color: var(--accent);">
                            <label class="form-check-label" for="projectFeatured"><?= t('admin.featured') ?? 'Destacado' ?></label>
                        </div>
                    </div>
                    <div class="modal-footer" style="border-top: 1px solid var(--border);">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" style="background: var(--surface-raised); color: var(--text); border: 1px solid var(--border);"><?= t('admin.cancel') ?? 'Cancelar' ?></button>
                        <button type="submit" class="btn btn-primary" style="background: var(--accent); border: none;"><?= t('admin.save') ?? 'Guardar' ?></button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal for Personal Info -->
    <div class="modal fade" id="personalInfoModal" tabindex="-1" aria-labelledby="personalInfoModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content" style="background: var(--surface); border: 1px solid var(--border);">
                <div class="modal-header" style="border-bottom: 1px solid var(--border);">
                    <h5 class="modal-title" id="personalInfoModalLabel"><?= t('admin.edit_personal_info') ?? 'Editar Información Personal' ?></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" style="filter: invert(1);"></button>
                </div>
                <form id="personalInfoForm">
                    <div class="modal-body">
                        <input type="hidden" name="csrf_token" value="<?= $csrfToken ?>">
                        <input type="hidden" name="action" value="update_personal_info">
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label"><?= t('admin.full_name') ?? 'Nombre Completo' ?> *</label>
                                <input type="text" class="form-control" name="full_name" id="piFullName" required style="background: var(--bg); border: 1px solid var(--border); color: var(--text);">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label"><?= t('admin.job_title') ?? 'Título Profesional' ?></label>
                                <input type="text" class="form-control" name="job_title" id="piJobTitle" style="background: var(--bg); border: 1px solid var(--border); color: var(--text);">
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label"><?= t('admin.email') ?? 'Email' ?> *</label>
                                <input type="email" class="form-control" name="email" id="piEmail" required style="background: var(--bg); border: 1px solid var(--border); color: var(--text);">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label"><?= t('admin.phone') ?? 'Teléfono' ?></label>
                                <input type="text" class="form-control" name="phone" id="piPhone" style="background: var(--bg); border: 1px solid var(--border); color: var(--text);">
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label"><?= t('admin.location') ?? 'Ubicación' ?></label>
                            <input type="text" class="form-control" name="location" id="piLocation" style="background: var(--bg); border: 1px solid var(--border); color: var(--text);">
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label"><?= t('admin.short_bio') ?? 'Biografía Corta' ?></label>
                            <textarea class="form-control" name="short_bio" id="piShortBio" rows="3" style="background: var(--bg); border: 1px solid var(--border); color: var(--text);"></textarea>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label"><?= t('admin.availability') ?? 'Disponibilidad' ?></label>
                            <select class="form-select" name="availability_status" id="piAvailability" style="background: var(--bg); border: 1px solid var(--border); color: var(--text);">
                                <option value="open"><?= t('admin.open') ?? 'Abierto' ?></option>
                                <option value="busy"><?= t('admin.busy') ?? 'Ocupado' ?></option>
                                <option value="unavailable"><?= t('admin.unavailable') ?? 'No Disponible' ?></option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer" style="border-top: 1px solid var(--border);">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" style="background: var(--surface-raised); color: var(--text); border: 1px solid var(--border);"><?= t('admin.cancel') ?? 'Cancelar' ?></button>
                        <button type="submit" class="btn btn-primary" style="background: var(--accent); border: none;"><?= t('admin.save') ?? 'Guardar' ?></button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content" style="background: var(--surface); border: 1px solid var(--border);">
                <div class="modal-header" style="border-bottom: 1px solid var(--border);">
                    <h5 class="modal-title" id="deleteModalLabel"><?= t('admin.confirm_delete') ?? 'Confirmar Eliminación' ?></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" style="filter: invert(1);"></button>
                </div>
                <div class="modal-body">
                    <p><?= t('admin.delete_warning') ?? '¿Está seguro de que desea eliminar este elemento? Esta acción no se puede deshacer.' ?></p>
                    <input type="hidden" name="delete_id" id="deleteId">
                    <input type="hidden" name="delete_type" id="deleteType">
                </div>
                <div class="modal-footer" style="border-top: 1px solid var(--border);">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" style="background: var(--surface-raised); color: var(--text); border: 1px solid var(--border);"><?= t('admin.cancel') ?? 'Cancelar' ?></button>
                    <button type="button" class="btn btn-danger" id="confirmDeleteBtn" style="background: var(--error); border: none;"><?= t('admin.delete') ?? 'Eliminar' ?></button>
                </div>
            </div>
        </div>
    </div>

    <!-- Toast Notification -->
    <div id="toastContainer" style="position: fixed; top: 20px; right: 20px; z-index: 9999;"></div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        const csrfToken = '<?= $csrfToken ?>';
        let projectModal, personalModal, deleteModal;

        document.addEventListener('DOMContentLoaded', function() {
            projectModal = new bootstrap.Modal(document.getElementById('projectModal'));
            personalModal = new bootstrap.Modal(document.getElementById('personalInfoModal'));
            deleteModal = new bootstrap.Modal(document.getElementById('deleteModal'));
            
            // Project form submit
            document.getElementById('projectForm').addEventListener('submit', handleProjectSubmit);
            
            // Personal info form submit
            document.getElementById('personalInfoForm').addEventListener('submit', handlePersonalInfoSubmit);
            
            // Delete confirm
            document.getElementById('confirmDeleteBtn').addEventListener('click', handleDelete);
            
            // Pre-fill personal info
            const personal = <?= json_encode($personal ?? []) ?>;
            if (personal) {
                document.getElementById('piFullName').value = personal.full_name || '';
                document.getElementById('piJobTitle').value = personal.job_title || '';
                document.getElementById('piEmail').value = personal.email || '';
                document.getElementById('piPhone').value = personal.phone || '';
                document.getElementById('piLocation').value = personal.location || '';
                document.getElementById('piShortBio').value = personal.short_bio || '';
                document.getElementById('piAvailability').value = personal.availability_status || 'open';
            }
        });

        // Projects
        function editProject(id) {
            const projects = <?= json_encode($projectsService->getAllProjects()) ?>;
            const project = projects.find(p => p.id == id);
            if (project) {
                document.getElementById('projectModalLabel').textContent = '<?= t('admin.edit_project') ?? 'Editar Proyecto' ?>';
                document.querySelector('#projectForm input[name="action"]').value = 'update_project';
                document.getElementById('projectId').value = project.id;
                document.getElementById('projectTitle').value = project.title || '';
                document.getElementById('projectDescription').value = project.description || '';
                document.getElementById('projectCategory').value = project.category || 'web';
                document.getElementById('projectTechStack').value = project.tech_stack || '';
                document.getElementById('projectImageUrl').value = project.image_url || '';
                document.getElementById('projectLiveUrl').value = project.live_url || '';
                document.getElementById('projectGithubUrl').value = project.github_url || '';
                document.getElementById('projectFeatured').checked = project.is_featured == 1;
                projectModal.show();
            }
        }

        function addProject() {
            document.getElementById('projectModalLabel').textContent = '<?= t('admin.add_project') ?? 'Añadir Proyecto' ?>';
            document.querySelector('#projectForm input[name="action"]').value = 'create_project';
            document.getElementById('projectForm').reset();
            document.getElementById('projectId').value = '';
            projectModal.show();
        }

        function deleteProject(id) {
            document.getElementById('deleteId').value = id;
            document.getElementById('deleteType').value = 'project';
            deleteModal.show();
        }

        async function handleProjectSubmit(e) {
            e.preventDefault();
            const formData = new FormData(e.target);
            
            try {
                const response = await fetch('api.php', {
                    method: 'POST',
                    body: formData
                });
                const result = await response.json();
                
                if (result.success) {
                    showToast(result.message, 'success');
                    projectModal.hide();
                    setTimeout(() => location.reload(), 1000);
                } else {
                    showToast(result.message, 'danger');
                }
            } catch (err) {
                showToast('Error: ' + err.message, 'danger');
            }
        }

        // Personal Info
        function editPersonalInfo() {
            personalModal.show();
        }

        async function handlePersonalInfoSubmit(e) {
            e.preventDefault();
            const formData = new FormData(e.target);
            
            try {
                const response = await fetch('api.php', {
                    method: 'POST',
                    body: formData
                });
                const result = await response.json();
                
                if (result.success) {
                    showToast(result.message, 'success');
                    personalModal.hide();
                    setTimeout(() => location.reload(), 1000);
                } else {
                    showToast(result.message, 'danger');
                }
            } catch (err) {
                showToast('Error: ' + err.message, 'danger');
            }
        }

        // Delete
        async function handleDelete() {
            const id = document.getElementById('deleteId').value;
            const type = document.getElementById('deleteType').value;
            const action = 'delete_' + type;
            
            const formData = new FormData();
            formData.append('action', action);
            formData.append('id', id);
            formData.append('csrf_token', csrfToken);
            
            try {
                const response = await fetch('api.php', {
                    method: 'POST',
                    body: formData
                });
                const result = await response.json();
                
                if (result.success) {
                    showToast(result.message, 'success');
                    deleteModal.hide();
                    setTimeout(() => location.reload(), 1000);
                } else {
                    showToast(result.message, 'danger');
                }
            } catch (err) {
                showToast('Error: ' + err.message, 'danger');
            }
        }

        // Logout
        function logout(e) {
            e.preventDefault();
            if (confirm('<?= t('admin.logout_confirm') ?? '¿Cerrar sesión?' ?>')) {
                fetch('../logout.php', { method: 'POST' })
                    .then(() => window.location.href = '../login.php');
            }
        }

        // Toast notifications
        function showToast(message, type) {
            const toast = document.createElement('div');
            toast.className = 'toast show';
            toast.style.cssText = 'min-width: 250px; margin-bottom: 10px; background: var(--surface); border: 1px solid var(--border); color: var(--text);';
            toast.innerHTML = '<div class="toast-body" style="display: flex; align-items: center;"><i class="fa-solid fa-' + (type === 'success' ? 'check-circle text-success' : 'circle-exclamation text-danger') + ' me-2"></i>' + message + '</div>';
            document.getElementById('toastContainer').appendChild(toast);
            setTimeout(() => toast.remove(), 5000);
        }
    </script>
</body>
</html>
