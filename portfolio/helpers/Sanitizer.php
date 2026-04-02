<?php
declare(strict_types=1);

/**
 * Sanitizer — centralises all input cleaning.
 * Uses filter_var + strip_tags to prevent XSS and injection.
 */
class Sanitizer
{
    /**
     * Sanitise a plain string (no HTML allowed).
     */
    public static function string(mixed $value, int $maxLen = 500): string
    {
        $value = filter_var((string)($value ?? ''), FILTER_SANITIZE_SPECIAL_CHARS);
        $value = strip_tags($value);
        $value = trim($value);
        return mb_substr($value, 0, $maxLen);
    }

    /**
     * Sanitise and validate an email address.
     * Returns the clean email or empty string if invalid.
     */
    public static function email(mixed $value): string
    {
        $clean = filter_var(trim((string)($value ?? '')), FILTER_SANITIZE_EMAIL);
        return filter_var($clean, FILTER_VALIDATE_EMAIL) ? $clean : '';
    }

    /**
     * Sanitise a phone number (digits, spaces, +, -, (, ) only).
     */
    public static function phone(mixed $value): string
    {
        $clean = preg_replace('/[^\d\s\+\-\(\)\.ext]/', '', (string)($value ?? ''));
        return mb_substr(trim($clean), 0, 30);
    }

    /**
     * Sanitise a multi-line message, preserving line breaks.
     */
    public static function message(mixed $value, int $maxLen = 2000): string
    {
        $value = strip_tags((string)($value ?? ''));
        $value = htmlspecialchars_decode(trim($value));
        return mb_substr($value, 0, $maxLen);
    }

    /**
     * Sanitise for safe HTML output (escaping).
     */
    public static function output(mixed $value): string
    {
        return htmlspecialchars((string)($value ?? ''), ENT_QUOTES | ENT_HTML5, 'UTF-8');
    }

    /**
     * Validate that a string is in a whitelist of allowed values.
     */
    public static function whitelist(mixed $value, array $allowed, string $default = ''): string
    {
        $value = (string)($value ?? '');
        return in_array($value, $allowed, true) ? $value : $default;
    }

    /**
     * Validate required fields and return first error or null.
     */
    public static function validateContactForm(array $data): ?string
    {
        if (empty($data['name'])) return 'Name is required.';
        if (empty($data['email'])) return 'A valid email is required.';
        if (empty($data['subject'])) return 'Subject is required.';
        if (empty($data['message'])) return 'Message is required.';
        if (mb_strlen($data['message']) < 10) return 'Message is too short.';
        // Honeypot — bots fill this, humans leave it empty
        if (!empty($data['_hp'])) return 'Spam detected.';
        return null;
    }
}
