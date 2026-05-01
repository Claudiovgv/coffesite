<?php /* pages/home.php — variables available: $t, $lang, $page */ ?>

<!-- ======================================================
     HERO
     ====================================================== -->
<section class="hero" id="inicio">
  <div class="hero-left reveal">
    <div class="hero-brand-row">
      <img src="/images/apenas-simbolo.png" alt="" class="hero-brand-logo" aria-hidden="true" />
      <div class="hero-brand-text">
        <span class="hero-brand-name">Atlantic Crown</span>
        <span class="hero-brand-sub">— Coffee —</span>
      </div>
    </div>
    <h1 class="hero-headline"><?= $t['hero.headline'] ?></h1>
    <p class="hero-tagline"><?= $t['hero.tagline'] ?></p>
    <a href="#contact" class="btn-gold"><?= $t['hero.cta'] ?></a>
  </div>
  <div class="hero-right">
    <img src="/images/hero-cafe-interior.jpg" alt="Atlantic Crown Coffee — premium experience" />
  </div>
</section>


<!-- ======================================================
     STORY
     ====================================================== -->
<section class="story" id="story">
  <div class="story-inner">
    <div class="story-content reveal">
      <p class="section-label"><?= $t['story.label'] ?></p>
      <h2 class="section-title"><?= $t['story.headline'] ?></h2>
      <div class="story-body">
        <p><?= $t['story.body.1'] ?></p>
        <p><?= $t['story.body.2'] ?></p>
        <p><?= $t['story.body.3'] ?></p>
        <p><?= $t['story.body.4'] ?></p>
        <p><?= $t['story.closing'] ?></p>
      </div>
    </div>
    <div class="story-image">
      <img src="/images/brand-story.jpg" alt="Portuguese maritime heritage" loading="lazy" />
    </div>
  </div>
</section>


<!-- ======================================================
     COFFEES
     ====================================================== -->
<section class="coffees" id="coffees">
  <div class="coffees-inner">
    <div class="coffees-left reveal">
      <p class="section-label"><?= $t['coffees.label'] ?></p>
      <h2 class="section-title"><?= $t['coffees.label'] ?></h2>
      <p class="coffees-subtitle"><?= $t['coffees.subtitle'] ?></p>

      <div class="origins-grid">
        <div class="origin-card">
          <div class="origin-icon">&#127809;</div>
          <p class="origin-name"><?= $t['coffees.brazil.name'] ?></p>
          <p class="origin-profile"><?= $t['coffees.brazil.profile'] ?></p>
          <p class="origin-desc"><?= $t['coffees.brazil.desc'] ?></p>
        </div>
        <div class="origin-card">
          <div class="origin-icon">&#9749;</div>
          <p class="origin-name"><?= $t['coffees.colombia.name'] ?></p>
          <p class="origin-profile"><?= $t['coffees.colombia.profile'] ?></p>
          <p class="origin-desc"><?= $t['coffees.colombia.desc'] ?></p>
        </div>
        <div class="origin-card">
          <div class="origin-icon">&#127807;</div>
          <p class="origin-name"><?= $t['coffees.kenya.name'] ?></p>
          <p class="origin-profile"><?= $t['coffees.kenya.profile'] ?></p>
          <p class="origin-desc"><?= $t['coffees.kenya.desc'] ?></p>
        </div>
      </div>
    </div>

    <div class="coffees-right">
      <div class="coffees-right-bg">
        <img src="/images/product-premium-coffee-1000g.jpg" alt="" aria-hidden="true" loading="lazy" />
      </div>
      <div class="coffees-right-overlay"></div>
      <div class="badges-grid reveal" data-delay="150">
        <div class="badge">
          <div class="badge-icon">&#10022;</div>
          <p class="badge-title"><?= $t['badge.quality.title'] ?></p>
          <p class="badge-text"><?= $t['badge.quality.text'] ?></p>
        </div>
        <div class="badge">
          <div class="badge-icon">&#9812;</div>
          <p class="badge-title"><?= $t['badge.heritage.title'] ?></p>
          <p class="badge-text"><?= $t['badge.heritage.text'] ?></p>
        </div>
        <div class="badge">
          <div class="badge-icon">&#9706;</div>
          <p class="badge-title"><?= $t['badge.packaging.title'] ?></p>
          <p class="badge-text"><?= $t['badge.packaging.text'] ?></p>
        </div>
        <div class="badge">
          <div class="badge-icon">&#127963;</div>
          <p class="badge-title"><?= $t['badge.hospitality.title'] ?></p>
          <p class="badge-text"><?= $t['badge.hospitality.text'] ?></p>
        </div>
      </div>
    </div>
  </div>
</section>


<!-- ======================================================
     WHY PARTNER
     ====================================================== -->
<section class="why" id="why">
  <div class="container">
    <header class="why-header reveal">
      <p class="section-label"><?= $t['why.label'] ?></p>
    </header>
    <div class="why-grid">
      <div class="why-card reveal" data-delay="0">
        <div class="why-icon">&#9670;</div>
        <p class="why-title"><?= $t['why.brand.title'] ?></p>
        <p class="why-text"><?= $t['why.brand.text'] ?></p>
      </div>
      <div class="why-card reveal" data-delay="80">
        <div class="why-icon">&#9650;</div>
        <p class="why-title"><?= $t['why.growth.title'] ?></p>
        <p class="why-text"><?= $t['why.growth.text'] ?></p>
      </div>
      <div class="why-card reveal" data-delay="160">
        <div class="why-icon">&#10022;</div>
        <p class="why-title"><?= $t['why.quality.title'] ?></p>
        <p class="why-text"><?= $t['why.quality.text'] ?></p>
      </div>
      <div class="why-card reveal" data-delay="240">
        <div class="why-icon">&#9812;</div>
        <p class="why-title"><?= $t['why.premium.title'] ?></p>
        <p class="why-text"><?= $t['why.premium.text'] ?></p>
      </div>
    </div>
  </div>
</section>


<!-- ======================================================
     CONTACT
     ====================================================== -->
<section class="contact" id="contact">
  <div class="contact-inner">
    <div class="contact-left reveal">
      <p class="section-label"><?= $t['contact.label'] ?></p>
      <p class="contact-desc"><?= $t['contact.desc'] ?></p>
      <div class="contact-detail">
        <span class="detail-icon">&#9993;</span>
        <span>geral@atlanticcrowncoffee.com</span>
      </div>
      <div class="contact-detail">
        <span class="detail-icon">&#128205;</span>
        <span>Portugal</span>
      </div>
      <div class="contact-detail">
        <span class="detail-icon">&#9728;</span>
        <span>www.atlanticrowngroup.com</span>
      </div>
    </div>

    <div class="contact-right reveal" data-delay="100">
      <h2 class="section-title"><?= $t['contact.title'] ?></h2>
      <form class="contact-form" id="contactForm" novalidate>
        <div class="form-row">
          <div class="form-group">
            <label for="name"><?= $t['contact.form.name.label'] ?></label>
            <input type="text" id="name" name="name" placeholder="<?= htmlspecialchars($t['contact.form.name.ph']) ?>" autocomplete="name" required />
          </div>
          <div class="form-group">
            <label for="email"><?= $t['contact.form.email.label'] ?></label>
            <input type="email" id="email" name="email" placeholder="<?= htmlspecialchars($t['contact.form.email.ph']) ?>" autocomplete="email" required />
          </div>
        </div>
        <div class="form-row">
          <div class="form-group">
            <label for="phone"><?= $t['contact.form.phone.label'] ?></label>
            <input type="tel" id="phone" name="phone" placeholder="<?= htmlspecialchars($t['contact.form.phone.ph']) ?>" autocomplete="tel" />
          </div>
          <div class="form-group">
            <label for="subject"><?= $t['contact.form.subject.label'] ?></label>
            <input type="text" id="subject" name="subject" placeholder="<?= htmlspecialchars($t['contact.form.subject.ph']) ?>" />
          </div>
        </div>
        <div class="form-group">
          <label for="message"><?= $t['contact.form.message.label'] ?></label>
          <textarea id="message" name="message" placeholder="<?= htmlspecialchars($t['contact.form.message.ph']) ?>" required></textarea>
        </div>
        <button type="submit" class="btn-gold-full" data-submit-label="<?= htmlspecialchars($t['contact.form.submit']) ?>">
          <?= $t['contact.form.submit'] ?>
        </button>
        <p class="form-message" id="formMessage" aria-live="polite"></p>
      </form>
    </div>
  </div>
</section>
