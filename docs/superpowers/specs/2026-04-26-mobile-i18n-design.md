# Mobile Improvements + i18n — Design Spec
**Date:** 2026-04-26
**Project:** Atlantic Crown Coffee (static HTML/CSS/JS site)

---

## 1. Scope

Two independent improvements to the existing single-page site:

1. **Mobile typography & spacing fixes** — reduce oversized text and excessive padding on screens ≤600px, keeping the hero section untouched.
2. **Language selector (EN / PT / FR)** — auto-detect from browser, manual override via globe+dropdown in navbar, preference persisted in `localStorage`.

---

## 2. Mobile Improvements

### What changes
Only `css/styles.css` is modified. The hero block (`@media (max-width: 600px)`) is **not touched**.

| Element | Current | New |
|---|---|---|
| Section vertical padding (`≤600px`) | `4rem 0` | `2.5rem 0` |
| `.section-title` (`≤600px`) | `1.8rem` fixed | `clamp(1.35rem, 5vw, 1.75rem)` |
| `.story-card` padding (`≤600px`) | `2rem 1.4rem` | `1.6rem 1.2rem` |
| `.story-card-body` font-size | `0.84rem` | `0.82rem` |
| `.story-card-closing` font-size | `0.92rem` | `0.88rem` |
| `.feature-card` padding (`≤600px`) | `1.8rem 1.2rem` | `1.4rem 1rem` |
| `.container` padding (`≤600px`) | `0 1.2rem` | `0 1rem` |
| Form inputs font-size | `0.88rem` | `16px` (prevents iOS auto-zoom) |

### What does NOT change
- The hero section (all `.hero-*` rules at `≤600px`) — kept exactly as-is.
- The `≤375px` breakpoint — kept as-is.
- Desktop and tablet breakpoints — untouched.

---

## 3. i18n System

### Architecture
- **Zero external dependencies** — plain JS objects and DOM manipulation.
- Three files are modified/created: `js/translations.js` (new), `js/main.js` (extended), `index.html` (data attributes added).

### `js/translations.js`
A single exported object `TRANSLATIONS` with top-level keys `pt`, `en`, `fr`. Each language contains all translatable strings keyed by dot-notation IDs, e.g.:

```js
const TRANSLATIONS = {
  pt: {
    "nav.products": "Produtos",
    "nav.history": "A Nossa História",
    "nav.contact": "Contacto",
    "hero.tagline": "Café premium torrado em Portugal · Para quem não aceita nada menos do que o excecional",
    "hero.cta": "Contacte-nos →",
    // … all sections
  },
  en: { /* English strings */ },
  fr: { /* French strings */ }
};
```

### `index.html` changes
Every translatable element receives a `data-i18n` attribute with its key:

```html
<a href="#produtos" data-i18n="nav.products">Produtos</a>
<h1 data-i18n="hero.h1">Nascida do espírito<br>da descoberta.</h1>
```

Elements that contain HTML (e.g. `<br>` tags) use `innerHTML` substitution. Elements with plain text use `textContent`.

The navbar gets a new language button inserted between the hamburger and the `<ul>`:

```html
<div class="lang-switcher">
  <button class="lang-btn" aria-label="Select language" aria-expanded="false">
    <!-- globe SVG -->
    <span class="lang-current">PT</span>
  </button>
  <ul class="lang-dropdown">
    <li><button data-lang="pt">Português</button></li>
    <li><button data-lang="en">English</button></li>
    <li><button data-lang="fr">Français</button></li>
  </ul>
</div>
```

### Language selection logic (`js/main.js`)

```
Priority order:
  1. localStorage.getItem('lang')         ← manual override persists across visits
  2. navigator.language / navigator.languages  ← browser locale
  3. fallback: 'pt'
```

Browser detection maps: `pt*` → `pt`, `fr*` → `fr`, `en*` → `en`, anything else → `pt`.

`setLanguage(lang)` function:
1. Updates `document.documentElement.lang`
2. Queries all `[data-i18n]` elements and replaces content from `TRANSLATIONS[lang]`
3. Updates `.lang-current` text to show active language code (uppercase)
4. Highlights the active option in the dropdown
5. Saves to `localStorage`

The dropdown opens/closes on button click, closes on outside click and on Esc key.

### Form validation messages
The form error/success messages in `main.js` are also translated — the `formMessage` strings reference the current active language from `TRANSLATIONS[currentLang]`.

### Strings to translate

All visible user-facing text in the page:
- Navbar links (3 items)
- Hero: tagline paragraph, CTA button
- Products section: label, title, subtitle, both product tags/names/descriptions, "Saber Mais" buttons
- Story section: brand name sub, headline, all body paragraphs, closing statement
- Features section: label, title, subtitle, all 4 feature titles and texts
- Contact section: label, title, description, contact details, all form labels/placeholders, submit button
- Footer: tagline, nav links, copyright
- Form validation messages (empty fields warning, success message)

---

## 4. Files Changed

| File | Change |
|---|---|
| `css/styles.css` | Mobile breakpoint adjustments (section 2 above) + lang-switcher styles |
| `index.html` | `data-i18n` attributes on all translatable elements + lang switcher HTML in navbar |
| `js/translations.js` | **New file** — PT/EN/FR string objects |
| `js/main.js` | Language detection, `setLanguage()`, dropdown toggle logic |

---

## 5. Out of Scope

- URL-based routing (`/en/`, `/pt/`) — not needed for a single-page static site
- Server-side language detection
- RTL language support
- SEO `hreflang` tags (can be added later manually)
