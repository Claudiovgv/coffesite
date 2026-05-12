<?php
$products = require __DIR__ . '/../config/products.php';
$product  = $products[$productSlug];
$tk       = $product['trans']; // 'brazil' | 'colombia' | 'kenya'
$name     = $t['coffees.' . $tk . '.name'];
$profile  = $t['coffees.' . $tk . '.profile'];
$desc     = $t['coffees.' . $tk . '.desc'];
?>

<!-- ======================================================
     PRODUCT HERO
     ====================================================== -->
<section class="product-hero">
  <div class="product-hero-inner">

    <div class="product-hero-left reveal">
      <a href="/<?= $lang ?>/#coffees" class="product-back"><?= $t['product.back'] ?></a>

      <div class="product-badges-row">
        <span class="product-badge-pill"><?= $t['product.single.origin'] ?></span>
        <span class="product-badge-pill"><?= $t['product.arabica'] ?></span>
      </div>

      <div class="product-flag-name">
        <img src="<?= $product['flag'] ?>" alt="<?= htmlspecialchars($name) ?>" class="product-flag" />
        <h1 class="product-name"><?= $name ?></h1>
      </div>

      <p class="product-profile"><?= $profile ?></p>
      <p class="product-desc"><?= $desc ?></p>

      <div class="product-specs">
        <div class="product-spec">
          <span class="spec-label"><?= $t['product.origin.label'] ?></span>
          <span class="spec-value"><?= $product['region'] ?></span>
        </div>
        <div class="product-spec">
          <span class="spec-label"><?= $t['product.roast.label'] ?></span>
          <span class="spec-value"><?= $product['roast'] ?></span>
        </div>
        <div class="product-spec">
          <span class="spec-label"><?= $t['product.process.label'] ?></span>
          <span class="spec-value"><?= $product['process'] ?></span>
        </div>
        <div class="product-spec">
          <span class="spec-label"><?= $t['product.variety.label'] ?></span>
          <span class="spec-value"><?= $product['variety'] ?></span>
        </div>
      </div>

      <div class="product-formats-inline">
        <p class="formats-inline-label"><?= $t['product.formats.title'] ?></p>
        <div class="formats-grid-inline">
          <div class="format-card-inline">
            <span class="format-weight-inline"><?= $t['product.format.250.weight'] ?></span>
            <span class="format-desc-inline"><?= $t['product.format.250.desc'] ?></span>
          </div>
          <div class="format-card-inline">
            <span class="format-weight-inline"><?= $t['product.format.1kg.weight'] ?></span>
            <span class="format-desc-inline"><?= $t['product.format.1kg.desc'] ?></span>
          </div>
        </div>
      </div>
    </div>

    <div class="product-hero-right">
      <img src="<?= $product['image'] ?>" alt="<?= htmlspecialchars($name) ?>" />
    </div>

  </div>
</section>


<!-- ======================================================
     ORDER / QUOTE FORM
     ====================================================== -->
<section class="product-order">
  <div class="product-order-inner">

    <div class="product-order-left reveal">
      <p class="section-label"><?= $t['product.order.title'] ?></p>
      <p class="product-order-desc"><?= $t['product.order.desc'] ?></p>
      <div class="contact-detail">
        <span class="detail-icon">&#9993;</span>
        <span>geral@atlanticcrowncoffee.com</span>
      </div>
      <div class="contact-detail">
        <span class="detail-icon">&#128205;</span>
        <span>Portugal</span>
      </div>
    </div>

    <div class="product-order-right reveal" data-delay="100">
      <form class="order-form" id="orderForm" novalidate>
        <input type="hidden" name="type"   value="order" />
        <input type="hidden" name="coffee" value="<?= htmlspecialchars($name) ?>" />

        <div class="form-row">
          <div class="form-group">
            <label for="o-name"><?= $t['product.order.name.label'] ?></label>
            <input type="text" id="o-name" name="name" placeholder="<?= htmlspecialchars($t['product.order.name.ph']) ?>" autocomplete="name" required />
          </div>
          <div class="form-group">
            <label for="o-email"><?= $t['product.order.email.label'] ?></label>
            <input type="email" id="o-email" name="email" placeholder="<?= htmlspecialchars($t['product.order.email.ph']) ?>" autocomplete="email" required />
          </div>
        </div>

        <div class="form-row">
          <div class="form-group">
            <label for="o-phone"><?= $t['product.order.phone.label'] ?></label>
            <input type="tel" id="o-phone" name="phone" placeholder="<?= htmlspecialchars($t['product.order.phone.ph']) ?>" autocomplete="tel" />
          </div>
          <div class="form-group">
            <label for="o-qty"><?= $t['product.order.qty.label'] ?></label>
            <input type="text" id="o-qty" name="qty" placeholder="<?= htmlspecialchars($t['product.order.qty.ph']) ?>" required />
          </div>
        </div>

        <div class="form-group">
          <label><?= $t['product.order.size.label'] ?></label>
          <div class="size-options">
            <label class="size-option">
              <input type="radio" name="size" value="250g" checked />
              <span><?= $t['product.order.size.250'] ?></span>
            </label>
            <label class="size-option">
              <input type="radio" name="size" value="1kg" />
              <span><?= $t['product.order.size.1kg'] ?></span>
            </label>
          </div>
        </div>

        <div class="form-group">
          <label for="o-notes"><?= $t['product.order.notes.label'] ?></label>
          <textarea id="o-notes" name="notes" placeholder="<?= htmlspecialchars($t['product.order.notes.ph']) ?>"></textarea>
        </div>

        <button type="submit" class="btn-gold-full"
                data-submit-label="<?= htmlspecialchars($t['product.order.submit']) ?>">
          <?= $t['product.order.submit'] ?>
        </button>
        <p class="form-message" id="orderMessage" aria-live="polite"></p>
      </form>
    </div>

  </div>
</section>
