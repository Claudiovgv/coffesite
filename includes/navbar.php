<?php
$pageSuffix = ($page !== 'home') ? $page : '';
$homeUrl    = '/' . $lang . '/';
?>
<nav class="navbar" role="navigation" aria-label="Main navigation">
  <a href="<?= $homeUrl ?>" class="nav-logo" aria-label="Atlantic Crown Coffee">
    <span class="logo-name">Atlantic Crown</span>
    <span class="logo-sub">— Coffee —</span>
  </a>

  <div class="nav-end">
    <ul class="nav-links">
      <li><a href="<?= $homeUrl ?>#coffees"><?= $t['nav.products'] ?></a></li>
      <li><a href="<?= $homeUrl ?>#story"><?= $t['nav.story'] ?></a></li>
      <li><a href="<?= $homeUrl ?>#contact"><?= $t['nav.contact'] ?></a></li>
    </ul>

    <div class="lang-switcher">
      <?php foreach (SUPPORTED_LANGS as $l): ?>
      <a href="/<?= $l ?>/<?= $pageSuffix ?>"
         class="lang-option<?= $l === $lang ? ' active' : '' ?>"
         hreflang="<?= $l ?>"><?= strtoupper($l) ?></a>
      <?php endforeach; ?>
    </div>

    <button class="nav-toggle" aria-label="Open menu" aria-expanded="false">
      <span></span><span></span><span></span>
    </button>
  </div>
</nav>
