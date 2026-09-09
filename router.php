<?php
$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$file = __DIR__ . $path;

if ($path !== '/' && is_file($file) && !is_dir($file)) {
    return false;
}

$route = rtrim(ltrim($path, '/'), '.php');

if ($route === '' || $route === 'index') {
    require __DIR__ . '/index.php';
    return true;
}

if (preg_match('#^(es|en|fr)(?:/(.*))?$#', $route, $m)) {
    $_SERVER['REQUEST_URI'] = '/' . ($m[2] ?? '');
    $sub = rtrim($m[2] ?? '', '.php');
    $pageFile = ($sub === '' || $sub === 'index') ? 'index' : $sub;
    if (in_array($pageFile, ['index', 'about', 'projects', 'resume', 'contact'])) {
        require __DIR__ . '/' . $pageFile . '.php';
        return true;
    }
}

if (in_array($route, ['about', 'projects', 'resume', 'contact'])) {
    require __DIR__ . '/' . $route . '.php';
    return true;
}

http_response_code(404);
echo '404 Not Found';
return true;