<?php
declare(strict_types=1);

/**
 * Translation helper.
 * Loads the correct lang file and provides t() / lang_url() helpers.
 */

function init_lang(array $supportedLangs = ['es','en','fr'], string $defaultLang = 'es'): void
{
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    if (!isset($_SESSION['lang'])) {
        $_SESSION['lang'] = $defaultLang;
    }

    // Allow switching via ?lang=XX (then redirect to clean URL)
    if (isset($_GET['lang'])) {
        $requested = Sanitizer::whitelist($_GET['lang'], $supportedLangs, $defaultLang);
        $_SESSION['lang'] = $requested;
        $clean = strtok($_SERVER['REQUEST_URI'], '?');
        header('Location: ' . $clean);
        exit;
    }

    // Load translations into global
    $langFile = __DIR__ . '/../lang/' . $_SESSION['lang'] . '.php';
    $GLOBALS['translations'] = file_exists($langFile)
        ? include $langFile
        : include __DIR__ . '/../lang/' . $defaultLang . '.php';
}

/**
 * Translate a dot-notation key.
 * Example: t('menu.home') → 'Inicio'
 */
function t(string $key): string
{
    $parts = explode('.', $key);
    $value = $GLOBALS['translations'] ?? [];
    foreach ($parts as $part) {
        if (!is_array($value) || !array_key_exists($part, $value)) {
            return $key; // Return key as fallback
        }
        $value = $value[$part];
    }
    return is_string($value) ? $value : $key;
}

/**
 * Build a URL that switches to the given language.
 */
function lang_url(string $lang): string
{
    $url = strtok($_SERVER['REQUEST_URI'] ?? '/', '?');
    return $url . '?lang=' . rawurlencode($lang);
}

/**
 * Return current active language code.
 */
function current_lang(): string
{
    return $_SESSION['lang'] ?? 'es';
}
