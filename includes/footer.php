<?php $homeUrl = '/' . $lang . '/'; ?>
<footer class="footer">
  <div class="footer-inner container">
    <div class="footer-brand">
      <span class="footer-logo-name">Atlantic Crown</span>
      <span class="footer-logo-sub">— Coffee —</span>
      <p class="footer-tagline"><?= $t['footer.tagline'] ?></p>
    </div>

    <div class="footer-nav">
      <ul>
        <li><a href="<?= $homeUrl ?>"><?= $t['footer.nav.home'] ?></a></li>
        <li><a href="<?= $homeUrl ?>#coffees"><?= $t['footer.nav.products'] ?></a></li>
        <li><a href="<?= $homeUrl ?>#story"><?= $t['footer.nav.story'] ?></a></li>
        <li><a href="<?= $homeUrl ?>#contact"><?= $t['footer.nav.contact'] ?></a></li>
      </ul>
      <ul class="footer-legal">
        <li><a href="/<?= $lang ?>/privacy"><?= $t['footer.privacy'] ?></a></li>
        <li><a href="/<?= $lang ?>/cookies"><?= $t['footer.cookies'] ?></a></li>
        <li><a href="/<?= $lang ?>/terms"><?= $t['footer.terms'] ?></a></li>
      </ul>
    </div>
  </div>

  <div class="footer-bottom">
    <div class="container">
      <p class="footer-location">&#9655; <?= $t['location'] ?></p>
      <p class="footer-copy"><?= $t['footer.copy'] ?></p>
    </div>
  </div>
</footer>

<!-- Privacy banner -->
<div class="privacy-banner" id="privacyBanner" role="alert" style="display:none">
  <p class="privacy-banner-text">
    <?= $t['banner.text'] ?>
    <a href="/<?= $lang ?>/privacy"><?= $t['footer.privacy'] ?></a>.
  </p>
  <button class="privacy-banner-btn" id="privacyBannerAccept"><?= $t['banner.accept'] ?></button>
</div>

<script>
  const FORM_STRINGS = {
    error:   <?= json_encode($t['form.error']) ?>,
    success: <?= json_encode($t['form.success']) ?>,
    sending: <?= json_encode($t['form.sending']) ?>
  };
  const ORDER_STRINGS = {
    error:   <?= json_encode($t['form.order.error']) ?>,
    success: <?= json_encode($t['form.order.success']) ?>,
    sending: <?= json_encode($t['form.order.sending']) ?>
  };
  const CURRENT_LANG = <?= json_encode($lang) ?>;
</script>
<script src="/js/main.js" defer></script>
</body>
</html>
