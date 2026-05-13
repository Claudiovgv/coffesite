<?php
if (session_status() === PHP_SESSION_NONE) session_start();
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
require_once __DIR__ . '/maintenance.php';
require_once __DIR__ . '/config/app.php';

/* ---- Parse URL ---- */
$requestUri = strtok($_SERVER['REQUEST_URI'], '?');
$requestUri = trim($requestUri, '/');
$segments   = explode('/', $requestUri, 2);

$lang = $segments[0] ?? '';
$slug = $segments[1] ?? '';

/* ---- Root redirect ---- */
if ($lang === '') {
    header('Location: /' . DEFAULT_LANG . '/', true, 302);
    exit;
}

/* ---- Validate lang ---- */
if (!in_array($lang, SUPPORTED_LANGS, true)) {
    http_response_code(404);
    $lang     = DEFAULT_LANG;
    $page     = 'home';
    $t        = require __DIR__ . '/lang/' . $lang . '.php';
    $ogLocale = OG_LOCALE[$lang] ?? 'en_US';
    include __DIR__ . '/includes/head.php';
    include __DIR__ . '/includes/navbar.php';
    echo '<main style="min-height:60vh;display:flex;align-items:center;justify-content:center;">';
    echo '<p style="color:#c9a84c;font-size:1.5rem;">404 — Page not found.</p>';
    echo '</main>';
    include __DIR__ . '/includes/footer.php';
    exit;
}

/* ---- Map slug to page ---- */
$pageMap = [
    ''         => 'home',
    'privacy'  => 'privacy',
    'terms'    => 'terms',
    'cookies'  => 'cookies',
];

$productSlug = null;
$page        = $pageMap[$slug] ?? null;

/* ---- Product pages: /en/products/{slug} ---- */
if ($page === null && preg_match('#^products/([a-z0-9-]+)$#', $slug, $m)) {
    $products = require __DIR__ . '/config/products.php';
    if (isset($products[$m[1]])) {
        $page        = 'product';
        $productSlug = $m[1];
    }
}

/* ---- 404 for unknown pages ---- */
if ($page === null || !in_array($page, SUPPORTED_PAGES, true)) {
    http_response_code(404);
    $page     = 'home';
    $t        = require __DIR__ . '/lang/' . $lang . '.php';
    $ogLocale = OG_LOCALE[$lang] ?? 'en_US';
    include __DIR__ . '/includes/head.php';
    include __DIR__ . '/includes/navbar.php';
    echo '<main style="min-height:60vh;display:flex;align-items:center;justify-content:center;">';
    echo '<p style="color:#c9a84c;font-size:1.5rem;">404 — Page not found.</p>';
    echo '</main>';
    include __DIR__ . '/includes/footer.php';
    exit;
}

/* ---- Load translations ---- */
$t = require __DIR__ . '/lang/' . $lang . '.php';

/* ---- OG locale ---- */
$ogLocale = OG_LOCALE[$lang] ?? 'en_US';

/* ---- Render ---- */
include __DIR__ . '/includes/head.php';
include __DIR__ . '/includes/navbar.php';
include __DIR__ . '/pages/' . $page . '.php';
include __DIR__ . '/includes/footer.php';
