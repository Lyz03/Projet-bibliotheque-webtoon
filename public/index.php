<?php

$page = $_GET['p'] ?? 'home';
$page = strip_tags($page);

/**
 * @param string $page
 * @param string $title
 */
function getPage(string $page, string $title): void {
    require __DIR__ . '/../parts/header.php';
    require file_exists($page) ? $page : __DIR__ . '/../pages/404.php';
    require __DIR__ . '/../parts/footer.php';
}

getPage(sprintf(__DIR__ . "/../pages/%s.php", strtolower($page)), ucfirst($page));