<?php
declare(strict_types=1);

/**
 * CSRF protection middleware.
 * Generates per-session tokens and validates them on POST requests.
 */
class CsrfMiddleware
{
    private const TOKEN_KEY = '_csrf_token';
    private const TOKEN_TS  = '_csrf_ts';

    /**
     * Generate (or rotate) the CSRF token.
     * Call this when rendering any form.
     */
    public static function generate(int $ttl = 3600): string
    {
        if (session_status() === PHP_SESSION_NONE) session_start();

        $needsNew = empty($_SESSION[self::TOKEN_KEY])
                 || (time() - ($_SESSION[self::TOKEN_TS] ?? 0)) > $ttl;

        if ($needsNew) {
            $_SESSION[self::TOKEN_KEY] = bin2hex(random_bytes(32));
            $_SESSION[self::TOKEN_TS]  = time();
        }

        return $_SESSION[self::TOKEN_KEY];
    }

    /**
     * Validate the CSRF token from a POST request.
     * Returns true if valid, false otherwise.
     */
    public static function validate(): bool
    {
        if (session_status() === PHP_SESSION_NONE) session_start();

        $submitted = $_POST[self::TOKEN_KEY] ?? $_SERVER['HTTP_X_CSRF_TOKEN'] ?? '';
        $stored    = $_SESSION[self::TOKEN_KEY] ?? '';

        if (empty($stored) || empty($submitted)) return false;

        return hash_equals($stored, $submitted);
    }

    /**
     * Output a hidden form field with the CSRF token.
     */
    public static function field(int $ttl = 3600): string
    {
        $token = self::generate($ttl);
        return sprintf(
            '<input type="hidden" name="%s" value="%s">',
            self::TOKEN_KEY,
            htmlspecialchars($token, ENT_QUOTES, 'UTF-8')
        );
    }

    /**
     * Abort with 403 if the current POST request has an invalid token.
     */
    public static function requireValid(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') return;

        if (!self::validate()) {
            http_response_code(403);
            // Rotate token to prevent token fixation after failure
            unset($_SESSION[self::TOKEN_KEY], $_SESSION[self::TOKEN_TS]);
            include __DIR__ . '/../error_pages/403.php';
            exit;
        }
    }
}
