<?php
declare(strict_types=1);

require_once __DIR__ . '/../includes/bootstrap.php';

header('Content-Type: application/json; charset=UTF-8');

function json_out(bool $ok, string $message, int $status = 200): never
{
    http_response_code($status);
    echo json_encode(['ok' => $ok, 'message' => $message], JSON_THROW_ON_ERROR);
    exit;
}

// Only accept POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    json_out(false, 'Method not allowed.', 405);
}

// CSRF check
if (!CsrfMiddleware::validate()) {
    json_out(false, 'Security token invalid. Please refresh and try again.', 403);
}

// Rate limiting
if (!RateLimiter::allow('contact', $config['security']['rate_limit_contact'], $config['security']['rate_limit_window'])) {
    $retry = RateLimiter::retryAfter('contact', $config['security']['rate_limit_window']);
    $mins  = (int)ceil($retry / 60);
    json_out(false, "Too many requests. Please try again in {$mins} minute(s).", 429);
}

// Sanitize inputs
$data = [
    'name'    => Sanitizer::string($_POST['name']    ?? ''),
    'email'   => Sanitizer::email($_POST['email']    ?? ''),
    'phone'   => Sanitizer::phone($_POST['phone']    ?? ''),
    'subject' => Sanitizer::string($_POST['subject'] ?? '', 200),
    'message' => Sanitizer::message($_POST['message'] ?? ''),
    '_hp'     => $_POST['_hp'] ?? '', // honeypot
];

// Validate
$error = Sanitizer::validateContactForm($data);
if ($error !== null) {
    json_out(false, $error, 422);
}

// Send
$contactService = new ContactService($config['mail']);
$result         = $contactService->send($data);

if ($result['ok']) {
    json_out(true, t('contact.success_message'));
} else {
    error_log('Contact form send failed: ' . ($result['error'] ?? 'unknown'));
    json_out(false, t('contact.error_message'), 500);
}
