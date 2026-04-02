<?php
declare(strict_types=1);

// ── 1. Load config ────────────────────────────────────────────────────────────
$config = require __DIR__ . '/../config/app.php';

// ── 2. PHP environment ────────────────────────────────────────────────────────
date_default_timezone_set($config['timezone']);
ini_set('display_errors', $config['debug'] ? '1' : '0');
ini_set('log_errors', '1');
ini_set('error_log', __DIR__ . '/../error_logs/app.log');
error_reporting(E_ALL);

// ── 3. Session (secure settings) ─────────────────────────────────────────────
if (session_status() === PHP_SESSION_NONE) {
    session_set_cookie_params([
        'lifetime' => 0,
        'path'     => '/',
        'secure'   => !empty($_SERVER['HTTPS']),
        'httponly' => true,
        'samesite' => 'Lax',
    ]);
    session_start();
}

// ── 4. Autoload vendor & helpers ──────────────────────────────────────────────
$vendorAutoload = __DIR__ . '/../vendor/autoload.php';
if (file_exists($vendorAutoload)) {
    require_once $vendorAutoload;
}

require_once __DIR__ . '/../helpers/Sanitizer.php';
require_once __DIR__ . '/../helpers/translation.php';

// ── 5. Middleware ─────────────────────────────────────────────────────────────
require_once __DIR__ . '/../middleware/SecurityHeaders.php';
require_once __DIR__ . '/../middleware/CsrfMiddleware.php';
require_once __DIR__ . '/../middleware/RateLimiter.php';
SecurityHeaders::apply();

// ── 6. Services ───────────────────────────────────────────────────────────────
require_once __DIR__ . '/../services/Database.php';
require_once __DIR__ . '/../services/BaseService.php';
require_once __DIR__ . '/../services/ResumeService.php';
require_once __DIR__ . '/../services/ProjectsService.php';
require_once __DIR__ . '/../services/ContactService.php';

// ── 7. I18n ───────────────────────────────────────────────────────────────────
init_lang($config['supported_langs'], $config['default_lang']);

// ── 8. Global error handler ───────────────────────────────────────────────────
set_exception_handler(function (Throwable $e) use ($config): void {
    error_log('Uncaught: ' . $e->getMessage() . ' in ' . $e->getFile() . ':' . $e->getLine());
    if (!$config['debug']) {
        http_response_code(500);
        include __DIR__ . '/../error_pages/500.php';
        exit;
    }
    throw $e; // Re-throw in debug mode for visibility
});
