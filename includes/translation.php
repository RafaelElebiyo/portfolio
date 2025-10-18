<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['lang'])) {
    $_SESSION['lang'] = 'es';
}

if (isset($_GET['lang']) && in_array($_GET['lang'], ['es', 'en', 'fr'])) {
    $_SESSION['lang'] = $_GET['lang'];
    header('Location: ' . strtok($_SERVER['REQUEST_URI'], '?'));
    exit;
}

$langFile = __DIR__ . '/../lang/' . $_SESSION['lang'] . '.php';
if (file_exists($langFile)) {
    $translations = include $langFile;
} else {
    $translations = include __DIR__ . '/../lang/es.php';
}

function t($key) {
    global $translations;
    $keys = explode('.', $key);
    $value = $translations;
    foreach ($keys as $k) {
        if (isset($value[$k])) {
            $value = $value[$k];
        } else {
            return $key;
        }
    }
    return $value;
}

function lang_url($lang) {
    $url = $_SERVER['REQUEST_URI'];
    $url = preg_replace('/([?&])lang=[^&]*(&|$)/', '$1', $url);
    $url = rtrim($url, '?&');
    $separator = strpos($url, '?') === false ? '?' : '&';
    return $url . $separator . 'lang=' . $lang;
}