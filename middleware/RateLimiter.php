<?php
declare(strict_types=1);

/**
 * Session-based rate limiter.
 * Suitable for contact forms and other light endpoints.
 *
 * For production under high load, replace storage with Redis.
 */
class RateLimiter
{
    /**
     * Check if the current IP/session has exceeded the limit.
     *
     * @param string $action      Unique key for the action being limited
     * @param int    $maxRequests Maximum allowed requests in the window
     * @param int    $windowSecs  Time window in seconds
     *
     * @return bool true = allowed, false = rate-limited
     */
    public static function allow(string $action, int $maxRequests = 5, int $windowSecs = 3600): bool
    {
        if (session_status() === PHP_SESSION_NONE) session_start();

        $key  = 'rl_' . $action . '_' . self::fingerprint();
        $now  = time();
        $data = $_SESSION[$key] ?? ['count' => 0, 'start' => $now];

        // Reset window if expired
        if (($now - $data['start']) >= $windowSecs) {
            $data = ['count' => 0, 'start' => $now];
        }

        $data['count']++;
        $_SESSION[$key] = $data;

        return $data['count'] <= $maxRequests;
    }

    /**
     * How many seconds remain in the current window, or 0 if not limited.
     */
    public static function retryAfter(string $action, int $windowSecs = 3600): int
    {
        if (session_status() === PHP_SESSION_NONE) session_start();

        $key  = 'rl_' . $action . '_' . self::fingerprint();
        $data = $_SESSION[$key] ?? null;

        if ($data === null) return 0;

        $elapsed = time() - $data['start'];
        $remaining = $windowSecs - $elapsed;

        return max(0, (int)$remaining);
    }

    /**
     * Create a stable, privacy-safe fingerprint for the client.
     * Uses IP + User-Agent hash — not stored outside the session.
     */
    private static function fingerprint(): string
    {
        $ip = $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';
        $ua = $_SERVER['HTTP_USER_AGENT'] ?? '';
        return substr(hash('sha256', $ip . $ua), 0, 16);
    }
}
