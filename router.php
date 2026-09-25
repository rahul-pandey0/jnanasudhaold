<?php
// Simple router for PHP built-in server to mimic rewrite to index.php
$uri = urldecode(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));

// Serve existing files (assets, images, etc.)
$path = __DIR__ . $uri;
if ($uri !== '/' && file_exists($path) && is_file($path)) {
    return false; // Let the server handle the static file
}

// Otherwise, forward everything to index.php
require __DIR__ . '/index.php';
