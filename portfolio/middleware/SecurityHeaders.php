<?php
declare(strict_types=1);

/**
 * SecurityHeaders — sets HTTP security headers on every response.
 * Call SecurityHeaders::apply() at bootstrap time.
 */
class SecurityHeaders
{
    public static function apply(): void
    {
        // Prevent clickjacking
        header('X-Frame-Options: DENY');

        // Stop MIME-type sniffing
        header('X-Content-Type-Options: nosniff');

        // Basic XSS protection for older browsers
        header('X-XSS-Protection: 1; mode=block');

        // Control referrer info
        header('Referrer-Policy: strict-origin-when-cross-origin');

        // Permissions policy — least privilege
        header('Permissions-Policy: camera=(), microphone=(), geolocation=()');

        // Content Security Policy — tighten as needed per page
        header(
            "Content-Security-Policy: " .
            "default-src 'self'; " .
            "script-src 'self' 'unsafe-inline' https://cdn.jsdelivr.net https://cdnjs.cloudflare.com; " .
            "style-src 'self' 'unsafe-inline' https://cdn.jsdelivr.net https://cdnjs.cloudflare.com https://fonts.googleapis.com; " .
            "font-src 'self' https://fonts.gstatic.com https://cdnjs.cloudflare.com; " .
            "img-src 'self' data: https:; " .
            "connect-src 'self'; " .
            "frame-ancestors 'none';"
        );

        // Enable HSTS (only meaningful in production behind HTTPS)
        if (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') {
            header('Strict-Transport-Security: max-age=31536000; includeSubDomains');
        }

        // Remove PHP fingerprint
        header_remove('X-Powered-By');
    }
}
