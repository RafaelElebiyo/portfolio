<?php
declare(strict_types=1);
require_once __DIR__ . '/includes/bootstrap.php';

$resumeService = new ResumeService();
$personalInfo  = $resumeService->getPersonalInfo() ?? [];
$page_title    = t('meta.contact_title');
$current_page  = 'contact.php';
$csrfField     = CsrfMiddleware::field();
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

    <section class="section-pad" aria-label="Contact">
        <div class="container-main">
            <div class="section-header" data-animate>
                <div class="section-tag"><?= t('menu.contact') ?></div>
                <h1 class="section-title"><?= t('contact.title') ?></h1>
                <p class="section-subtitle"><?= t('contact.subtitle') ?></p>
            </div>

            <div class="row g-5 justify-content-center">

                <!-- FORM -->
                <div class="col-lg-7" data-animate>
                    <div class="surface-card p-5">
                        <form id="contactForm" novalidate autocomplete="off" aria-label="Contact form">

                            <!-- CSRF -->
                            <?= $csrfField ?>

                            <!-- Honeypot (hidden from humans) -->
                            <div class="honeypot" aria-hidden="true">
                                <label for="_hp">Leave empty</label>
                                <input type="text" id="_hp" name="_hp" tabindex="-1" autocomplete="off">
                            </div>

                            <div class="row g-4">
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label class="form-label" for="name"><?= t('contact.form.name') ?></label>
                                        <input type="text" id="name" name="name" class="form-control"
                                               required minlength="2" maxlength="100"
                                               autocomplete="name"
                                               aria-required="true"
                                               placeholder="John Doe">
                                        <span class="form-error" role="alert"></span>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label class="form-label" for="email"><?= t('contact.form.email') ?></label>
                                        <input type="email" id="email" name="email" class="form-control"
                                               required autocomplete="email"
                                               aria-required="true"
                                               placeholder="john@example.com">
                                        <span class="form-error" role="alert"></span>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="form-label" for="phone"><?= t('contact.form.phone') ?></label>
                                <input type="tel" id="phone" name="phone" class="form-control"
                                       autocomplete="tel" maxlength="30"
                                       placeholder="+1 234 567 890">
                            </div>

                            <div class="form-group">
                                <label class="form-label" for="subject"><?= t('contact.form.subject') ?></label>
                                <input type="text" id="subject" name="subject" class="form-control"
                                       required minlength="3" maxlength="200"
                                       aria-required="true"
                                       placeholder="<?= t('contact.form.subject') ?>">
                                <span class="form-error" role="alert"></span>
                            </div>

                            <div class="form-group">
                                <label class="form-label" for="message"><?= t('contact.form.message') ?></label>
                                <textarea id="message" name="message" class="form-control"
                                          required minlength="10" maxlength="2000"
                                          rows="6" aria-required="true"
                                          placeholder="<?= t('contact.form.message') ?>"></textarea>
                                <span class="form-error" role="alert"></span>
                            </div>

                            <button type="submit" class="btn btn-primary btn-lg w-100">
                                <i class="fa-solid fa-paper-plane" aria-hidden="true"></i>
                                <?= t('contact.form.submit') ?>
                            </button>
                        </form>
                    </div>
                </div>

                <!-- INFO CARDS -->
                <div class="col-lg-4" data-animate>
                    <div class="d-flex flex-column gap-4">
                        <div class="contact-info-card">
                            <div class="contact-info-icon">
                                <i class="fa-solid fa-envelope" aria-hidden="true"></i>
                            </div>
                            <h3 class="h6 mb-1"><?= t('contact.info_sections.email') ?></h3>
                            <a href="mailto:<?= Sanitizer::output($personalInfo['email'] ?? '') ?>"
                               style="font-size:var(--text-sm);color:var(--text-secondary);word-break:break-all">
                                <?= Sanitizer::output($personalInfo['email'] ?? '') ?>
                            </a>
                        </div>
                        <div class="contact-info-card">
                            <div class="contact-info-icon">
                                <i class="fa-solid fa-location-dot" aria-hidden="true"></i>
                            </div>
                            <h3 class="h6 mb-1"><?= t('contact.info_sections.location') ?></h3>
                            <span style="font-size:var(--text-sm);color:var(--text-secondary)">
                                <?= Sanitizer::output($personalInfo['location'] ?? '') ?>
                            </span>
                        </div>
                        <div class="contact-info-card">
                            <div class="contact-info-icon">
                                <i class="fa-solid fa-phone" aria-hidden="true"></i>
                            </div>
                            <h3 class="h6 mb-1"><?= t('contact.info_sections.phone') ?></h3>
                            <a href="tel:<?= Sanitizer::output($personalInfo['phone'] ?? '') ?>"
                               style="font-size:var(--text-sm);color:var(--text-secondary)">
                                <?= Sanitizer::output($personalInfo['phone'] ?? '') ?>
                            </a>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>

</main>

<?php include 'includes/footer.php'; ?>
<?php include 'includes/toast_and_modal.php'; ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="assets/js/toast.js"></script>
<script src="assets/js/lazy.js"></script>
<script src="assets/js/app.js"></script>
</body>
</html>
