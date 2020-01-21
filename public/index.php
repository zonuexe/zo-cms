<?php

require_once __DIR__ . '/../vendor/autoload.php';

$config = require(__DIR__ . '/../config.php');

$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

if (strpos($path, '//') === 0) {
    $path = '/404';
} elseif (preg_match('@/\z@', $path)) {
    $path .= 'index';
}

if ($path === '/404') {
    http_response_code(404);
}

$content_path = "{$config['content']}{$path}.md";

if (!is_file($content_path)) {
    $content = <<<MARKDOWN
# 404 Not Found

MARKDOWN;
} else {
    $content = file_get_contents($content_path);
}

$title = trim(strtr($path, ['/' => '']));

if (isset($config['parts']['header'])) {
    include $config['parts']['header'];
}

echo $content;

if (isset($config['parts']['footer'])) {
    include $config['parts']['footer'];
}
