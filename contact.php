<?php 
require_once 'includes/translation.php';
require_once 'services/resume_service.php';

$resumeService = new ResumeService();
$personalInfo = $resumeService->getPersonalInfo();
$current_page = 'contact.php';
$page_title = t('meta.contact_title');
?>
<!DOCTYPE html>
<html lang="es" data-bs-theme="dark">
<head>
    <?php include 'includes/head.php'; ?>
</head>
<body class="bg-dark text-light">
    <?php include 'includes/header.php'; ?>
    <?php include 'includes/navigation.php'; ?>
    <main class="container py-5">
        <section id="contact" class="mb-5">
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <h1 class="display-4 fw-bold mb-4 text-center"><?= t('contact.title') ?></h1>
                    <p class="lead text-center mb-5"><?= t('contact.subtitle') ?></p>
                    <?php if (isset($_GET['status'])): ?>
                        <div class="alert alert-<?= $_GET['status'] === 'success' ? 'success' : 'danger' ?> text-center mb-4">
                            <?= $_GET['status'] === 'success' ? t('contact.success_message') : t('contact.error_message') ?>
                        </div>
                    <?php endif; ?>
                    <div class="card bg-dark border-secondary">
                        <div class="card-body p-4 p-md-5">
                            <form id="contactForm" action="includes/contact-form.php" method="POST">
                                <div class="row">
                                    <div class="col-md-6 mb-4">
                                        <label for="name" class="form-label"><?= t('contact.form.name') ?></label>
                                        <input type="text" class="form-control bg-dark text-light border-secondary" id="name" name="name" required>
                                    </div>
                                    <div class="col-md-6 mb-4">
                                        <label for="email" class="form-label"><?= t('contact.form.email') ?></label>
                                        <input type="email" class="form-control bg-dark text-light border-secondary" id="email" name="email" required>
                                    </div>
                                </div>
                                <div class="mb-4">
                                    <label for="phone" class="form-label"><?= t('contact.form.phone') ?></label>
                                    <input type="tel" class="form-control bg-dark text-light border-secondary" id="phone" name="phone">
                                </div>
                                <div class="mb-4">
                                    <label for="subject" class="form-label"><?= t('contact.form.subject') ?></label>
                                    <input type="text" class="form-control bg-dark text-light border-secondary" id="subject" name="subject" required>
                                </div>
                                <div class="mb-4">
                                    <label for="message" class="form-label"><?= t('contact.form.message') ?></label>
                                    <textarea class="form-control bg-dark text-light border-secondary" id="message" name="message" rows="5" required></textarea>
                                </div>
                                <div class="d-grid">
                                    <button type="submit" class="btn btn-primary btn-lg"><?= t('contact.form.submit') ?></button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <section id="contact-info" class="py-5 border-top border-secondary">
            <div class="row text-center">
                <div class="col-md-4 mb-4 mb-md-0">
                    <div class="contact-icon mx-auto mb-3">
                        <svg width="40" height="40" fill="currentColor" class="bi bi-envelope-fill text-primary" viewBox="0 0 16 16">
                            <path d="M.05 3.555A2 2 0 0 1 2 2h12a2 2 0 0 1 1.95 1.555L8 8.414.05 3.555ZM0 4.697v7.104l5.803-3.558L0 4.697ZM6.761 8.83l-6.57 4.027A2 2 0 0 0 2 14h12a2 2 0 0 0 1.808-1.144l-6.57-4.027L8 9.586l-1.239-.757Zm3.436-.586L16 11.801V4.697l-5.803 3.546Z"/>
                        </svg>
                    </div>
                    <h3 class="h4"><?= t('contact.info_sections.email') ?></h3>
                    <p class="mb-0"><?= htmlspecialchars($personalInfo['email']) ?></p>
                </div>
                <div class="col-md-4 mb-4 mb-md-0">
                    <div class="contact-icon mx-auto mb-3">
                        <svg width="40" height="40" fill="currentColor" class="bi bi-geo-alt-fill text-primary" viewBox="0 0 16 16">
                            <path d="M8 16s6-5.686 6-10A6 6 0 0 0 2 6c0 4.314 6 10 6 10zm0-7a3 3 0 1 1 0-6 3 3 0 0 1 0 6z"/>
                        </svg>
                    </div>
                    <h3 class="h4"><?= t('contact.info_sections.location') ?></h3>
                    <p class="mb-0"><?= htmlspecialchars($personalInfo['location']) ?></p>
                </div>
                <div class="col-md-4">
                    <div class="contact-icon mx-auto mb-3">
                        <svg width="40" height="40" fill="currentColor" class="bi bi-telephone-fill text-primary" viewBox="0 0 16 16">
                            <path fill-rule="evenodd" d="M1.885.511a1.745 1.745 0 0 1 2.61.163L6.29 2.98c.329.423.445.974.315 1.494l-.547 2.19a.678.678 0 0 0 .178.643l2.457 2.457a.678.678 0 0 0 .644.178l2.189-.547a1.745 1.745 0 0 1 1.494.315l2.306 1.794c.829.645.905 1.87.163 2.611l-1.034 1.034c-.74.74-1.846 1.065-2.877.702a18.634 18.634 0 0 1-7.01-4.42 18.634 18.634 0 0 1-4.42-7.009c-.362-1.03-.037-2.137.703-2.877L1.885.511z"/>
                        </svg>
                    </div>
                    <h3 class="h4"><?= t('contact.info_sections.phone') ?></h3>
                    <p class="mb-0"><?= htmlspecialchars($personalInfo['phone']) ?></p>
                </div>
            </div>
        </section>
    </main>
    <?php include 'includes/footer.php'; ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>