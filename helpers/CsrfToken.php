<?php
declare(strict_types=1);

class CsrfToken
{
    private const TOKEN_NAME = 'csrf_token';
    private const TOKEN_LENGTH = 32;

    public static function generate(): string
    {
        if (!isset($_SESSION[self::TOKEN_NAME])) {
            $_SESSION[self::TOKEN_NAME] = bin2hex(random_bytes(self::TOKEN_LENGTH));
        }
        return $_SESSION[self::TOKEN_NAME];
    }

    public static function get(): string
    {
        return self::generate();
    }

    public static function validate(string $token): bool
    {
        if (!isset($_SESSION[self::TOKEN_NAME])) {
            return false;
        }
        return hash_equals($_SESSION[self::TOKEN_NAME], $token);
    }

    public static function field(): string
    {
        return '<input type="hidden" name="csrf_token" value="' . self::get() . '">';
    }
}