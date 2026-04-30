# Design Spec — Atlantic Crown Coffee: PHP Router + i18n + Visual Redesign
**Date:** 2026-04-30  
**Branch:** feature/mobile-i18n-legal  
**Status:** Approved

---

## Context

The site is currently a static HTML/CSS/JS site with client-side i18n via `translations.js`. The goal is to migrate to a PHP-based architecture with a minimal central router, server-side i18n with URL-based language detection (`/en/`, `/pt/`, `/fr/`), improved security, and a visual redesign matching the brand reference image (`ideias/WhatsApp Image 2026-04-30 at 20.04.16.jpeg`).

The hosting is cPanel (cpp77.webserver.pt) with PHP and MySQL available. MySQL is not needed now but the architecture must not preclude it.

A full backup of the HTML version is at `/Users/claudiovieira/CoffeSite_backup_html_2026-04-30`.

---

## Goals

1. **PHP base** — all pages served via PHP, no raw `.html` files
2. **Minimal router** — single `index.php` entry point, URL-based routing
3. **Server-side i18n** — `/en/`, `/pt/`, `/fr/` subdirectories, `hreflang` tags, meta tags per language
4. **Security hardening** — `.htaccess` blocks direct PHP access, HTTP security headers, whitelist-only routing
5. **Visual redesign** — match the reference image layout with alternating dark/light sections
6. **Future-ready** — structure supports MySQL, new pages, and framework migration without rewrites

---

## Architecture

### Entry Point & Routing

Single `index.php` at the root. All requests rewritten via `.htaccess`. The router:

1. Parses the URL to extract `$lang` and `$page`
2. Validates both against whitelists — anything outside returns a 404
3. Loads the language array from `lang/{lang}.php`
4. Loads `includes/head.php`, `includes/navbar.php`, the page template from `pages/{page}.php`, and `includes/footer.php`

URL patterns:
- `/` → redirect to `/en/`
- `/en/` → lang=en, page=home
- `/pt/` → lang=pt, page=home
- `/fr/privacy` → lang=fr, page=privacy
- Anything else → 404

### File Structure

```
/
├── index.php                  ← router (single entry point)
├── send.php                   ← contact form handler (unchanged)
├── .htaccess                  ← rewrite rules + security headers
├── config/
│   └── app.php                ← SUPPORTED_LANGS, DEFAULT_LANG, SUPPORTED_PAGES, SITE_URL
├── lang/
│   ├── en.php                 ← all EN strings (replaces translations.js EN section)
│   ├── pt.php                 ← all PT strings
│   └── fr.php                 ← all FR strings
├── includes/
│   ├── head.php               ← <head> with dynamic hreflang, title, og tags
│   ├── navbar.php             ← navbar with active lang links (PHP hrefs, no JS lang switch)
│   └── footer.php             ← footer
├── pages/
│   ├── home.php               ← home page content (all sections)
│   ├── privacy.php            ← privacy policy content
│   ├── terms.php              ← terms & conditions content
│   └── cookies.php            ← cookie policy content
├── css/
│   └── styles.css             ← redesigned to match reference layout
├── js/
│   └── main.js                ← behavior only (menu, scroll, form fetch) — no translations
├── images/                    ← unchanged
├── Fonts/                     ← unchanged
├── Logo/                      ← unchanged
└── Favicon/                   ← unchanged
```

---

## Security

### `.htaccess`

```apache
RewriteEngine On

# Route everything through index.php
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d
RewriteRule ^(.*)$ index.php [QSA,L]

# Block direct access to all PHP files except index.php and send.php
<FilesMatch "\.php$">
  Order Deny,Allow
  Deny from all
</FilesMatch>
<Files "index.php">
  Allow from all
</Files>
<Files "send.php">
  Allow from all
</Files>

# Security headers
Header always set X-Frame-Options "SAMEORIGIN"
Header always set X-Content-Type-Options "nosniff"
Header always set Referrer-Policy "strict-origin-when-cross-origin"
Header always set Permissions-Policy "geolocation=(), microphone=()"
```

### Router security rules

- `$lang` validated against `SUPPORTED_LANGS` — any unknown value → 404
- `$page` validated against `SUPPORTED_PAGES` — any unknown value → 404
- No user input ever reaches `include()` directly — only pre-validated internal constants
- All output from lang arrays escaped with `htmlspecialchars()` at render time

---

## i18n — Server-Side

### Language detection (router)

1. Check URL segment 1 (`/en/`, `/pt/`, `/fr/`)
2. If valid lang → use it
3. If root `/` → redirect to `/en/` (default)
4. If unknown → 404

No browser language auto-detection (removed by previous request, kept removed).

### Language files (`lang/en.php` etc.)

Each returns a flat associative array:

```php
<?php
return [
  // Meta
  'meta.home.title'            => 'Atlantic Crown Coffee — Premium Roasted Coffee from Portugal',
  'meta.home.description'      => 'Born from the spirit of discovery. Premium coffee roasted in Portugal.',
  'meta.privacy.title'         => 'Privacy Policy — Atlantic Crown Coffee',
  'meta.privacy.description'   => 'How Atlantic Crown Coffee handles your personal data.',
  // Nav
  'nav.products'               => 'Products',
  'nav.story'                  => 'Our Story',
  'nav.contact'                => 'Contact',
  // Hero
  'hero.headline'              => 'Born from the spirit of discovery.',
  'hero.tagline'               => 'Premium Portuguese Heritage. Exceptional Coffee.',
  'hero.cta'                   => 'Contact Us →',
  // ... all other sections
];
```

### `includes/head.php`

Generates dynamically:
- `<title>` from `$t['meta.'.$page.'.title']`
- `<meta name="description">` from `$t['meta.'.$page.'.description']`
- `hreflang` links for all 3 languages + `x-default`
- `og:url` and `og:locale` with current lang/page
- `canonical` with current lang/page URL

### Navbar language switcher

Replaced with plain PHP links — no JS needed:

```php
<a href="/en/<?= $page !== 'home' ? $page : '' ?>" class="<?= $lang==='en' ? 'active' : '' ?>">EN</a>
<a href="/pt/<?= $page !== 'home' ? $page : '' ?>" class="<?= $lang==='pt' ? 'active' : '' ?>">PT</a>
<a href="/fr/<?= $page !== 'home' ? $page : '' ?>" class="<?= $lang==='fr' ? 'active' : '' ?>">FR</a>
```

### `js/main.js`

`translations.js` is deleted. `main.js` retains only:
- Mobile menu toggle
- Scroll-based animations (IntersectionObserver)
- Contact form submission via `fetch()` to `send.php`
- Cookie banner logic (if present)

---

## Visual Redesign

Based on the reference image (`ideias/WhatsApp Image 2026-04-30 at 20.04.16.jpeg`).

### Section layout

| # | Section | Background | Layout |
|---|---------|-----------|--------|
| 1 | Hero | Dark (marble/black) with gold | 50/50: headline left, product bags right |
| 2 | Our Story | Light (cream/white) | 60/40: text left, ship image right |
| 3 | Our Coffees | Dark with coffee bean background | 3 origin columns left + 4 feature badges grid right |
| 4 | Why Partner With Us | Light | 4 equal columns with icon + title + text |
| 5 | CTA / Contact | Dark | 2 blocks: contacts left, CTA text right |

### Design tokens

- **Primary dark:** `#0d0c0a` (near black)
- **Gold accent:** `#c9a84c`
- **Light bg:** `#f5f0e8` (warm cream)
- **Body text dark:** `#1a1714`
- **Body text light:** `#f5f0e8`
- **Font headline:** existing bold/serif style (keep Montserrat or match reference)

### Coffees section — 3 origins

Replace the current 2-product cards with 3 origin columns:
- **Brazil** — Smooth & Balanced (chocolate, nuts, caramel)
- **Colombia** — Rich & Fruity (bright acidity, red fruits, cacao)
- **Kenya / Ethiopia** — Vibrant & Complex (floral, citrus, refined sweetness)

Each column: small origin map icon + country name + flavour profile text.

---

## Out of Scope

- MySQL / database integration (architecture supports it, not implemented now)
- User accounts or admin panel
- E-commerce / cart
- Blog or CMS
- Framework migration (Laravel/Slim)

---

## Backup

Full backup of the HTML version at: `/Users/claudiovieira/CoffeSite_backup_html_2026-04-30`
