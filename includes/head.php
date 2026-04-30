<!DOCTYPE html>
<html lang="<?= htmlspecialchars($lang) ?>">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title><?= htmlspecialchars($t['meta.' . $page . '.title']) ?></title>
  <meta name="description" content="<?= htmlspecialchars($t['meta.' . $page . '.description']) ?>" />
  <meta name="author" content="Atlantic Crown Coffee" />
  <?php if ($page !== 'home'): ?>
  <meta name="robots" content="noindex" />
  <?php else: ?>
  <meta name="robots" content="index, follow" />
  <link rel="canonical" href="<?= SITE_URL ?>/<?= $lang ?>/" />
  <?php endif; ?>

  <!-- hreflang -->
  <?php foreach (SUPPORTED_LANGS as $l): ?>
  <link rel="alternate" hreflang="<?= $l ?>" href="<?= SITE_URL ?>/<?= $l ?>/<?= $page !== 'home' ? $page : '' ?>" />
  <?php endforeach; ?>
  <link rel="alternate" hreflang="x-default" href="<?= SITE_URL ?>/en/" />

  <!-- Open Graph -->
  <meta property="og:type"        content="website" />
  <meta property="og:url"         content="<?= SITE_URL ?>/<?= $lang ?>/<?= $page !== 'home' ? $page : '' ?>" />
  <meta property="og:title"       content="<?= htmlspecialchars($t['meta.' . $page . '.title']) ?>" />
  <meta property="og:description" content="<?= htmlspecialchars($t['meta.' . $page . '.description']) ?>" />
  <meta property="og:image"       content="<?= SITE_URL ?>/images/hero-cafe-interior.jpg" />
  <meta property="og:locale"      content="<?= htmlspecialchars($ogLocale) ?>" />
  <meta property="og:site_name"   content="Atlantic Crown Coffee" />

  <!-- Twitter Card -->
  <meta name="twitter:card"        content="summary_large_image" />
  <meta name="twitter:title"       content="<?= htmlspecialchars($t['meta.' . $page . '.title']) ?>" />
  <meta name="twitter:description" content="<?= htmlspecialchars($t['meta.' . $page . '.description']) ?>" />
  <meta name="twitter:image"       content="<?= SITE_URL ?>/images/hero-cafe-interior.jpg" />

  <!-- Favicon -->
  <link rel="icon"             type="image/png" sizes="32x32" href="/images/favicon_browser-logo.png" />
  <link rel="apple-touch-icon" href="/images/favicon_browser-logo.png" />

  <!-- Structured Data (home only) -->
  <?php if ($page === 'home'): ?>
  <script type="application/ld+json">
  {
    "@context": "https://schema.org",
    "@type": "Organization",
    "name": "Atlantic Crown Coffee",
    "url": "<?= SITE_URL ?>",
    "logo": "<?= SITE_URL ?>/images/favicon_browser-logo.png",
    "description": "Premium roasted coffee in Portugal, crafted for hotels and fine dining.",
    "email": "geral@atlanticcrowncoffee.com",
    "address": { "@type": "PostalAddress", "addressCountry": "PT" }
  }
  </script>
  <?php endif; ?>

  <link rel="stylesheet" href="/css/styles.css" />
</head>
<body>
