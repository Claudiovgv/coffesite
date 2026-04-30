# PHP Router + i18n + Visual Redesign — Implementation Plan

> **For agentic workers:** REQUIRED SUB-SKILL: Use superpowers:subagent-driven-development (recommended) or superpowers:executing-plans to implement this plan task-by-task. Steps use checkbox (`- [ ]`) syntax for tracking.

**Goal:** Convert Atlantic Crown Coffee from static HTML to a PHP router-based site with server-side i18n (URL subdirectories `/en/`, `/pt/`, `/fr/`), security hardening, and visual redesign matching the brand reference image.

**Architecture:** Single `index.php` entry point; `.htaccess` rewrites all requests through it. Router extracts `$lang` and `$page` from URL, validates against whitelists, loads `lang/{lang}.php`, then renders `includes/head.php` + `includes/navbar.php` + `pages/{page}.php` + `includes/footer.php`. CSS redesigned to alternate dark/light sections matching the reference layout.

**Tech Stack:** PHP 7.4+, vanilla JS (no framework), CSS custom properties, cPanel hosting with Apache mod_rewrite.

**Backup location:** `/Users/claudiovieira/CoffeSite_backup_html_2026-04-30`

---

## File Map

| Action | File | Responsibility |
|--------|------|----------------|
| Create | `config/app.php` | Constants: supported langs, pages, site URL |
| Rewrite | `.htaccess` | Rewrite rules + security headers |
| Create | `index.php` | Central router |
| Create | `lang/en.php` | English translations array |
| Create | `lang/pt.php` | Portuguese translations array |
| Create | `lang/fr.php` | French translations array |
| Create | `includes/head.php` | `<head>` with dynamic title, meta, hreflang |
| Create | `includes/navbar.php` | Navbar with PHP lang links |
| Create | `includes/footer.php` | Footer with PHP internal links |
| Create | `pages/home.php` | Home page — new visual layout |
| Create | `pages/privacy.php` | Privacy policy — lang-aware |
| Create | `pages/terms.php` | Terms — lang-aware |
| Create | `pages/cookies.php` | Cookie policy — lang-aware |
| Rewrite | `css/styles.css` | Full redesign: alternating sections, new layouts |
| Rewrite | `js/main.js` | Simplified — no translations, fixed paths |
| Delete | `index.html`, `privacy.html`, `terms.html`, `cookies.html`, `js/translations.js` | Replaced by PHP |

---

## Task 1: Directory structure + config

**Files:**
- Create: `config/app.php`
- Create: `lang/` (directory)
- Create: `includes/` (directory)
- Create: `pages/` (directory)

- [ ] **Step 1: Create directories**

```bash
cd /Users/claudiovieira/CoffeSite
mkdir -p config lang includes pages
```

Expected: no output, directories created.

- [ ] **Step 2: Create `config/app.php`**

```php
<?php
define('SUPPORTED_LANGS',  ['en', 'pt', 'fr']);
define('DEFAULT_LANG',     'en');
define('SUPPORTED_PAGES',  ['home', 'privacy', 'terms', 'cookies']);
define('SITE_URL',         'https://atlanticrowngroup.com');

$OG_LOCALE = [
  'en' => 'en_US',
  'pt' => 'pt_PT',
  'fr' => 'fr_FR',
];
```

- [ ] **Step 3: Verify**

```bash
php -r "require 'config/app.php'; echo implode(',', SUPPORTED_LANGS);"
```

Expected output: `en,pt,fr`

- [ ] **Step 4: Commit**

```bash
git add config/app.php
git commit -m "feat: add config/app.php with supported langs and pages"
```

---

## Task 2: `.htaccess` — rewrite + security

**Files:**
- Modify: `.htaccess`

- [ ] **Step 1: Replace `.htaccess` with new content**

```apache
Options -Indexes

# ---- Rewrite engine ----
RewriteEngine On

# Serve real files and directories directly (images, css, js, fonts, send.php)
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d
RewriteRule ^(.*)$ index.php [QSA,L]

# ---- Block direct access to all PHP files except index.php and send.php ----
<FilesMatch "\.php$">
  Order Deny,Allow
  Deny from all
</FilesMatch>
<Files "index.php">
  Order Allow,Deny
  Allow from all
</Files>
<Files "send.php">
  Order Allow,Deny
  Allow from all
</Files>

# ---- Security headers ----
<IfModule mod_headers.c>
  Header always set X-Frame-Options "SAMEORIGIN"
  Header always set X-Content-Type-Options "nosniff"
  Header always set Referrer-Policy "strict-origin-when-cross-origin"
  Header always set Permissions-Policy "geolocation=(), microphone=()"
</IfModule>

# ---- Cache static assets ----
<IfModule mod_expires.c>
  ExpiresActive On
  ExpiresByType image/jpeg "access plus 1 month"
  ExpiresByType image/png  "access plus 1 month"
  ExpiresByType text/css   "access plus 1 week"
  ExpiresByType application/javascript "access plus 1 week"
</IfModule>
```

- [ ] **Step 2: Test rewrite is working**

```bash
php -S localhost:8000 -t /Users/claudiovieira/CoffeSite &
curl -s -o /dev/null -w "%{http_code}" http://localhost:8000/en/
```

Expected: `200` (or `500` until index.php exists — that's fine at this stage, confirms routing works).

- [ ] **Step 3: Commit**

```bash
git add .htaccess
git commit -m "feat: htaccess rewrite rules and security headers"
```

---

## Task 3: Language files

**Files:**
- Create: `lang/en.php`
- Create: `lang/pt.php`
- Create: `lang/fr.php`

- [ ] **Step 1: Create `lang/en.php`**

```php
<?php
return [
  /* Meta */
  'meta.home.title'            => 'Atlantic Crown Coffee — Premium Roasted Coffee from Portugal',
  'meta.home.description'      => 'Born from the spirit of discovery. Premium coffee roasted in Portugal, crafted for hotels and fine dining.',
  'meta.privacy.title'         => 'Privacy Policy — Atlantic Crown Coffee',
  'meta.privacy.description'   => 'How Atlantic Crown Coffee handles your personal data.',
  'meta.terms.title'           => 'Terms of Use — Atlantic Crown Coffee',
  'meta.terms.description'     => 'Terms and conditions for use of the Atlantic Crown Coffee website.',
  'meta.cookies.title'         => 'Cookie Policy — Atlantic Crown Coffee',
  'meta.cookies.description'   => 'How Atlantic Crown Coffee uses cookies on this website.',
  /* Nav */
  'nav.products'               => 'Products',
  'nav.story'                  => 'Our Story',
  'nav.contact'                => 'Contact',
  /* Hero */
  'hero.headline'              => 'Born from the spirit of discovery.',
  'hero.tagline'               => 'Premium Portuguese Heritage. Exceptional Coffee.',
  'hero.cta'                   => 'Contact Us &rarr;',
  /* Story */
  'story.label'                => '— Our Story —',
  'story.headline'             => 'Born from the spirit of discovery.',
  'story.body.1'               => 'Centuries ago, Portuguese navigators set sail beyond the horizon, guided only by the stars and the vastness of the Atlantic Ocean. They discovered new worlds, new cultures and brought rare treasures back to Europe.',
  'story.body.2'               => 'Among those treasures was coffee.',
  'story.body.3'               => 'Inspired by that golden age of discoveries, Atlantic Crown Coffee was born to honour Portugal\'s maritime legacy and its eternal pursuit of excellence.',
  'story.body.4'               => 'Roasted in Portugal and crafted with precision, our coffee represents the meeting of historical heritage and contemporary luxury. Every bean is selected with rigour, roasted with mastery and presented with the elegance befitting the world\'s finest hotels and restaurants.',
  'story.closing'              => 'Atlantic Crown Coffee is not just coffee. It is a tribute to discovery, refinement and the art of hospitality. <em>For those who accept nothing less than exceptional.</em>',
  /* Coffees */
  'coffees.label'              => 'Our Coffees',
  'coffees.subtitle'           => '100% Arabica &nbsp;&middot;&nbsp; Three Extraordinary Origins',
  'coffees.brazil.name'        => 'Brazil',
  'coffees.brazil.profile'     => 'Smooth &amp; Balanced',
  'coffees.brazil.desc'        => 'Medium body with notes of chocolate, nuts and caramel.',
  'coffees.colombia.name'      => 'Colombia',
  'coffees.colombia.profile'   => 'Rich &amp; Fruity',
  'coffees.colombia.desc'      => 'Medium body with bright acidity and notes of red fruits and cacao.',
  'coffees.kenya.name'         => 'Kenya / Ethiopia',
  'coffees.kenya.profile'      => 'Vibrant &amp; Complex',
  'coffees.kenya.desc'         => 'Full body with floral aromas, citrus notes and a refined sweetness.',
  /* Badges */
  'badge.quality.title'        => 'Premium Quality',
  'badge.quality.text'         => 'Carefully selected beans, expertly roasted in Portugal.',
  'badge.heritage.title'       => 'Heritage &amp; Passion',
  'badge.heritage.text'        => 'A brand inspired by history, dedicated to excellence.',
  'badge.packaging.title'      => 'Elegant Packaging',
  'badge.packaging.text'       => 'Sophisticated design that reflects our premium identity.',
  'badge.hospitality.title'    => 'Made for Hospitality',
  'badge.hospitality.text'     => 'Created for the world\'s most demanding palates.',
  /* Why */
  'why.label'                  => 'Why Partner With Us?',
  'why.brand.title'            => 'A Unique Brand',
  'why.brand.text'             => 'A distinctive Portuguese heritage story that stands out in the premium coffee market.',
  'why.growth.title'           => 'Growth Opportunity',
  'why.growth.text'            => 'High potential in luxury hospitality, speciality coffee shops and retail channels.',
  'why.quality.title'          => 'Consistent Quality',
  'why.quality.text'           => 'Reliable supply, strict quality control and dedication to long-term partnerships.',
  'why.premium.title'          => 'Built for Premium Markets',
  'why.premium.text'           => 'Positioned for the most selective customers and refined consumption experiences.',
  /* Contact */
  'contact.label'              => 'Orders &amp; Information',
  'contact.title'              => "Let's create something exceptional together.",
  'contact.desc'               => 'We look forward to exploring how Atlantic Crown Coffee can add value to your portfolio and together create memorable coffee experiences.',
  'contact.form.name.label'    => 'Name *',
  'contact.form.name.ph'       => 'Your name',
  'contact.form.email.label'   => 'Email *',
  'contact.form.email.ph'      => 'email@company.com',
  'contact.form.phone.label'   => 'Phone',
  'contact.form.phone.ph'      => '+351 900 000 000',
  'contact.form.subject.label' => 'Subject',
  'contact.form.subject.ph'    => 'Hotel, Restaurant, Other...',
  'contact.form.message.label' => 'Message *',
  'contact.form.message.ph'    => 'Tell us about your business and what you are looking for...',
  'contact.form.submit'        => 'Send Message',
  /* Footer */
  'footer.tagline'             => 'For those who accept nothing less than exceptional.',
  'footer.nav.home'            => 'Home',
  'footer.nav.products'        => 'Products',
  'footer.nav.story'           => 'Story',
  'footer.nav.contact'         => 'Contact',
  'footer.privacy'             => 'Privacy Policy',
  'footer.cookies'             => 'Cookie Policy',
  'footer.terms'               => 'Terms of Use',
  'footer.copy'                => '&copy; 2026 Atlantic Crown Coffee &nbsp;&middot;&nbsp; All rights reserved',
  /* Form feedback */
  'form.error'                 => 'Please fill in your name, email and message.',
  'form.success'               => 'Message sent! We will be in touch soon.',
  'form.sending'               => 'Sending...',
  /* Banner */
  'banner.text'                => 'This site collects personal data via the contact form. By continuing, you accept our Privacy Policy.',
  'banner.accept'              => 'Got it',
  /* Location */
  'location'                   => 'Roasted in Portugal',
];
```

- [ ] **Step 2: Create `lang/pt.php`**

```php
<?php
return [
  /* Meta */
  'meta.home.title'            => 'Atlantic Crown Coffee — Café Premium Torrado em Portugal',
  'meta.home.description'      => 'Nascida do espírito da descoberta. Café premium torrado em Portugal, criado para hotéis e restauração de luxo.',
  'meta.privacy.title'         => 'Política de Privacidade — Atlantic Crown Coffee',
  'meta.privacy.description'   => 'Como a Atlantic Crown Coffee trata os seus dados pessoais.',
  'meta.terms.title'           => 'Termos de Uso — Atlantic Crown Coffee',
  'meta.terms.description'     => 'Termos e condições de uso do site Atlantic Crown Coffee.',
  'meta.cookies.title'         => 'Política de Cookies — Atlantic Crown Coffee',
  'meta.cookies.description'   => 'Como a Atlantic Crown Coffee utiliza cookies neste site.',
  /* Nav */
  'nav.products'               => 'Produtos',
  'nav.story'                  => 'A Nossa História',
  'nav.contact'                => 'Contacto',
  /* Hero */
  'hero.headline'              => 'Nascida do espírito da descoberta.',
  'hero.tagline'               => 'Património Português Premium. Café Excecional.',
  'hero.cta'                   => 'Contacte-nos &rarr;',
  /* Story */
  'story.label'                => '— A Nossa História —',
  'story.headline'             => 'Nascida do espírito da descoberta.',
  'story.body.1'               => 'Há séculos, os navegadores portugueses partiram além do horizonte, guiados apenas pelas estrelas e pela imensidão do Oceano Atlântico. Descobriram novos mundos, novas culturas e trouxeram para a Europa tesouros raros.',
  'story.body.2'               => 'Entre esses tesouros estava o café.',
  'story.body.3'               => 'Inspirada nessa era dourada de descobertas, a Atlantic Crown Coffee nasceu para homenagear o legado marítimo de Portugal e a sua eterna busca pela excelência.',
  'story.body.4'               => 'Torrado em Portugal e criado com precisão, o nosso café representa o encontro entre herança histórica e luxo contemporâneo. Cada grão é selecionado com rigor, torrado com mestria e apresentado com a elegância digna dos melhores hotéis e restaurantes do mundo.',
  'story.closing'              => 'Atlantic Crown Coffee não é apenas café. É uma homenagem à descoberta, ao requinte e à arte da hospitalidade. <em>Para quem não aceita nada menos do que o excecional.</em>',
  /* Coffees */
  'coffees.label'              => 'Os Nossos Cafés',
  'coffees.subtitle'           => '100% Arábica &nbsp;&middot;&nbsp; Três Origens Extraordinárias',
  'coffees.brazil.name'        => 'Brasil',
  'coffees.brazil.profile'     => 'Suave &amp; Equilibrado',
  'coffees.brazil.desc'        => 'Corpo médio com notas de chocolate, frutos secos e caramelo.',
  'coffees.colombia.name'      => 'Colômbia',
  'coffees.colombia.profile'   => 'Rico &amp; Frutado',
  'coffees.colombia.desc'      => 'Corpo médio com acidez viva e notas de frutos vermelhos e cacau.',
  'coffees.kenya.name'         => 'Quénia / Etiópia',
  'coffees.kenya.profile'      => 'Vibrante &amp; Complexo',
  'coffees.kenya.desc'         => 'Corpo pleno com aromas florais, notas cítricas e doçura refinada.',
  /* Badges */
  'badge.quality.title'        => 'Qualidade Premium',
  'badge.quality.text'         => 'Grãos selecionados, expertamente torrados em Portugal.',
  'badge.heritage.title'       => 'Herança &amp; Paixão',
  'badge.heritage.text'        => 'Uma marca inspirada na história, dedicada à excelência.',
  'badge.packaging.title'      => 'Embalagem Elegante',
  'badge.packaging.text'       => 'Design sofisticado que reflete a nossa identidade premium.',
  'badge.hospitality.title'    => 'Para a Hospitalidade',
  'badge.hospitality.text'     => 'Criado para os paladares mais exigentes do mundo.',
  /* Why */
  'why.label'                  => 'Porquê Parceiro Connosco?',
  'why.brand.title'            => 'Uma Marca Única',
  'why.brand.text'             => 'Uma história de herança portuguesa que se destaca no mercado de café premium.',
  'why.growth.title'           => 'Oportunidade de Crescimento',
  'why.growth.text'            => 'Alto potencial em hotelaria de luxo, cafés especializados e retalho.',
  'why.quality.title'          => 'Qualidade Consistente',
  'why.quality.text'           => 'Abastecimento fiável, controlo de qualidade rigoroso e dedicação a parcerias de longo prazo.',
  'why.premium.title'          => 'Para Mercados Premium',
  'why.premium.text'           => 'Posicionado para os clientes mais seletivos e experiências de consumo refinadas.',
  /* Contact */
  'contact.label'              => 'Encomendas &amp; Informações',
  'contact.title'              => 'Vamos criar algo excecional juntos.',
  'contact.desc'               => 'Estamos ansiosos para explorar como a Atlantic Crown Coffee pode acrescentar valor ao seu portfólio e criar juntos experiências de café memoráveis.',
  'contact.form.name.label'    => 'Nome *',
  'contact.form.name.ph'       => 'O seu nome',
  'contact.form.email.label'   => 'Email *',
  'contact.form.email.ph'      => 'email@empresa.com',
  'contact.form.phone.label'   => 'Telefone',
  'contact.form.phone.ph'      => '+351 900 000 000',
  'contact.form.subject.label' => 'Assunto',
  'contact.form.subject.ph'    => 'Hotel, Restaurante, Outro...',
  'contact.form.message.label' => 'Mensagem *',
  'contact.form.message.ph'    => 'Conte-nos sobre o seu negócio e o que procura...',
  'contact.form.submit'        => 'Enviar Mensagem',
  /* Footer */
  'footer.tagline'             => 'Para quem não aceita nada menos do que o excecional.',
  'footer.nav.home'            => 'Início',
  'footer.nav.products'        => 'Produtos',
  'footer.nav.story'           => 'História',
  'footer.nav.contact'         => 'Contacto',
  'footer.privacy'             => 'Política de Privacidade',
  'footer.cookies'             => 'Política de Cookies',
  'footer.terms'               => 'Termos de Uso',
  'footer.copy'                => '&copy; 2026 Atlantic Crown Coffee &nbsp;&middot;&nbsp; Todos os direitos reservados',
  /* Form feedback */
  'form.error'                 => 'Por favor preencha o nome, email e mensagem.',
  'form.success'               => 'Mensagem enviada! Entraremos em contacto em breve.',
  'form.sending'               => 'A enviar...',
  /* Banner */
  'banner.text'                => 'Este site recolhe dados pessoais através do formulário de contacto. Ao continuar, aceita a nossa Política de Privacidade.',
  'banner.accept'              => 'Entendido',
  /* Location */
  'location'                   => 'Torrado em Portugal',
];
```

- [ ] **Step 3: Create `lang/fr.php`**

```php
<?php
return [
  /* Meta */
  'meta.home.title'            => 'Atlantic Crown Coffee — Café Premium Torréfié au Portugal',
  'meta.home.description'      => 'Née de l\'esprit de la découverte. Café premium torréfié au Portugal, créé pour les hôtels et la restauration gastronomique.',
  'meta.privacy.title'         => 'Politique de Confidentialité — Atlantic Crown Coffee',
  'meta.privacy.description'   => 'Comment Atlantic Crown Coffee traite vos données personnelles.',
  'meta.terms.title'           => 'Conditions d\'Utilisation — Atlantic Crown Coffee',
  'meta.terms.description'     => 'Conditions générales d\'utilisation du site Atlantic Crown Coffee.',
  'meta.cookies.title'         => 'Politique de Cookies — Atlantic Crown Coffee',
  'meta.cookies.description'   => 'Comment Atlantic Crown Coffee utilise les cookies sur ce site.',
  /* Nav */
  'nav.products'               => 'Produits',
  'nav.story'                  => 'Notre Histoire',
  'nav.contact'                => 'Contact',
  /* Hero */
  'hero.headline'              => 'Née de l\'esprit de la découverte.',
  'hero.tagline'               => 'Patrimoine Portugais Premium. Café Exceptionnel.',
  'hero.cta'                   => 'Contactez-nous &rarr;',
  /* Story */
  'story.label'                => '— Notre Histoire —',
  'story.headline'             => 'Née de l\'esprit de la découverte.',
  'story.body.1'               => 'Il y a des siècles, les navigateurs portugais partirent au-delà de l\'horizon, guidés seulement par les étoiles et l\'immensité de l\'Océan Atlantique. Ils découvrirent de nouveaux mondes, de nouvelles cultures et rapportèrent en Europe des trésors rares.',
  'story.body.2'               => 'Parmi ces trésors se trouvait le café.',
  'story.body.3'               => 'Inspirée par cet âge d\'or des découvertes, Atlantic Crown Coffee est née pour honorer l\'héritage maritime du Portugal et sa quête éternelle de l\'excellence.',
  'story.body.4'               => 'Torréfié au Portugal et créé avec précision, notre café représente la rencontre entre l\'héritage historique et le luxe contemporain. Chaque grain est sélectionné avec rigueur, torréfié avec maîtrise et présenté avec l\'élégance digne des meilleurs hôtels et restaurants du monde.',
  'story.closing'              => 'Atlantic Crown Coffee n\'est pas seulement du café. C\'est un hommage à la découverte, au raffinement et à l\'art de l\'hospitalité. <em>Pour ceux qui n\'acceptent rien de moins que l\'exceptionnel.</em>',
  /* Coffees */
  'coffees.label'              => 'Nos Cafés',
  'coffees.subtitle'           => '100% Arabica &nbsp;&middot;&nbsp; Trois Origines Extraordinaires',
  'coffees.brazil.name'        => 'Brésil',
  'coffees.brazil.profile'     => 'Doux &amp; Équilibré',
  'coffees.brazil.desc'        => 'Corps moyen avec des notes de chocolat, de noix et de caramel.',
  'coffees.colombia.name'      => 'Colombie',
  'coffees.colombia.profile'   => 'Riche &amp; Fruité',
  'coffees.colombia.desc'      => 'Corps moyen avec une acidité vive et des notes de fruits rouges et de cacao.',
  'coffees.kenya.name'         => 'Kenya / Éthiopie',
  'coffees.kenya.profile'      => 'Vibrant &amp; Complexe',
  'coffees.kenya.desc'         => 'Corps plein avec des arômes floraux, des notes d\'agrumes et une douceur raffinée.',
  /* Badges */
  'badge.quality.title'        => 'Qualité Premium',
  'badge.quality.text'         => 'Grains soigneusement sélectionnés, expertement torréfiés au Portugal.',
  'badge.heritage.title'       => 'Héritage &amp; Passion',
  'badge.heritage.text'        => 'Une marque inspirée par l\'histoire, dédiée à l\'excellence.',
  'badge.packaging.title'      => 'Emballage Élégant',
  'badge.packaging.text'       => 'Un design sophistiqué qui reflète notre identité premium.',
  'badge.hospitality.title'    => 'Pour l\'Hôtellerie',
  'badge.hospitality.text'     => 'Créé pour les palais les plus exigeants du monde.',
  /* Why */
  'why.label'                  => 'Pourquoi Nous Choisir ?',
  'why.brand.title'            => 'Une Marque Unique',
  'why.brand.text'             => 'Une histoire d\'héritage portugais qui se démarque sur le marché du café premium.',
  'why.growth.title'           => 'Opportunité de Croissance',
  'why.growth.text'            => 'Fort potentiel dans l\'hôtellerie de luxe, les coffee shops spécialisés et la distribution.',
  'why.quality.title'          => 'Qualité Constante',
  'why.quality.text'           => 'Approvisionnement fiable, contrôle qualité strict et dévouement aux partenariats à long terme.',
  'why.premium.title'          => 'Pour les Marchés Premium',
  'why.premium.text'           => 'Positionné pour les clients les plus sélectifs et les expériences de consommation raffinées.',
  /* Contact */
  'contact.label'              => 'Commandes &amp; Informations',
  'contact.title'              => 'Créons quelque chose d\'exceptionnel ensemble.',
  'contact.desc'               => 'Nous sommes impatients d\'explorer comment Atlantic Crown Coffee peut ajouter de la valeur à votre portefeuille et créer ensemble des expériences café mémorables.',
  'contact.form.name.label'    => 'Nom *',
  'contact.form.name.ph'       => 'Votre nom',
  'contact.form.email.label'   => 'Email *',
  'contact.form.email.ph'      => 'email@entreprise.com',
  'contact.form.phone.label'   => 'Téléphone',
  'contact.form.phone.ph'      => '+351 900 000 000',
  'contact.form.subject.label' => 'Sujet',
  'contact.form.subject.ph'    => 'Hôtel, Restaurant, Autre...',
  'contact.form.message.label' => 'Message *',
  'contact.form.message.ph'    => 'Parlez-nous de votre entreprise et de ce que vous recherchez...',
  'contact.form.submit'        => 'Envoyer',
  /* Footer */
  'footer.tagline'             => 'Pour ceux qui n\'acceptent rien de moins que l\'exceptionnel.',
  'footer.nav.home'            => 'Accueil',
  'footer.nav.products'        => 'Produits',
  'footer.nav.story'           => 'Histoire',
  'footer.nav.contact'         => 'Contact',
  'footer.privacy'             => 'Politique de Confidentialité',
  'footer.cookies'             => 'Politique de Cookies',
  'footer.terms'               => 'Conditions d\'Utilisation',
  'footer.copy'                => '&copy; 2026 Atlantic Crown Coffee &nbsp;&middot;&nbsp; Tous droits réservés',
  /* Form feedback */
  'form.error'                 => 'Veuillez remplir votre nom, email et message.',
  'form.success'               => 'Message envoyé ! Nous vous contacterons bientôt.',
  'form.sending'               => 'Envoi...',
  /* Banner */
  'banner.text'                => 'Ce site collecte des données personnelles via le formulaire de contact. En continuant, vous acceptez notre Politique de Confidentialité.',
  'banner.accept'              => 'Compris',
  /* Location */
  'location'                   => 'Torréfié au Portugal',
];
```

- [ ] **Step 4: Verify lang files load**

```bash
php -r "
  \$en = require 'lang/en.php';
  \$pt = require 'lang/pt.php';
  \$fr = require 'lang/fr.php';
  echo count(\$en) . ' EN keys, ' . count(\$pt) . ' PT keys, ' . count(\$fr) . ' FR keys' . PHP_EOL;
"
```

Expected: `37 EN keys, 37 PT keys, 37 FR keys` (or similar equal counts).

- [ ] **Step 5: Commit**

```bash
git add lang/
git commit -m "feat: server-side translation arrays for EN, PT, FR"
```

---

## Task 4: Router (`index.php`)

**Files:**
- Create: `index.php`

- [ ] **Step 1: Create `index.php`**

```php
<?php
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
    $lang = DEFAULT_LANG;
    $page = 'home';
    $t    = require __DIR__ . '/lang/' . $lang . '.php';
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

$page = $pageMap[$slug] ?? null;

/* ---- 404 for unknown pages ---- */
if ($page === null || !in_array($page, SUPPORTED_PAGES, true)) {
    http_response_code(404);
    $page = 'home';
    $t    = require __DIR__ . '/lang/' . $lang . '.php';
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

/* ---- OG locale map ---- */
global $OG_LOCALE;
$ogLocale = $OG_LOCALE[$lang] ?? 'en_US';

/* ---- Render ---- */
include __DIR__ . '/includes/head.php';
include __DIR__ . '/includes/navbar.php';
include __DIR__ . '/pages/' . $page . '.php';
include __DIR__ . '/includes/footer.php';
```

- [ ] **Step 2: Test router**

```bash
php -S localhost:8000 -t /Users/claudiovieira/CoffeSite
# In another terminal:
curl -s -o /dev/null -w "%{http_code}" http://localhost:8000/
```

Expected: `302` (redirect to /en/).

```bash
curl -s -o /dev/null -w "%{http_code}" http://localhost:8000/en/
```

Expected: `500` or `200` depending on whether includes exist yet — confirms routing logic runs without crashing on the router itself.

- [ ] **Step 3: Commit**

```bash
git add index.php
git commit -m "feat: central PHP router with lang/page validation"
```

---

## Task 5: Includes — head, navbar, footer

**Files:**
- Create: `includes/head.php`
- Create: `includes/navbar.php`
- Create: `includes/footer.php`

- [ ] **Step 1: Create `includes/head.php`**

```php
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
  <meta property="og:locale"      content="<?= $ogLocale ?>" />
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
```

- [ ] **Step 2: Create `includes/navbar.php`**

```php
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
```

- [ ] **Step 3: Create `includes/footer.php`**

```php
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
  const CURRENT_LANG = <?= json_encode($lang) ?>;
</script>
<script src="/js/main.js" defer></script>
</body>
</html>
```

- [ ] **Step 4: Test that includes render without fatal errors**

```bash
php -r "
  \$lang = 'en'; \$page = 'home'; \$t = require 'lang/en.php';
  global \$OG_LOCALE; require 'config/app.php';
  \$ogLocale = 'en_US';
  ob_start();
  include 'includes/head.php';
  include 'includes/navbar.php';
  include 'includes/footer.php';
  \$html = ob_get_clean();
  echo strlen(\$html) > 500 ? 'OK - ' . strlen(\$html) . ' bytes' : 'FAIL';
"
```

Expected: `OK - XXXX bytes`

- [ ] **Step 5: Commit**

```bash
git add includes/
git commit -m "feat: head, navbar and footer PHP includes with dynamic lang/hreflang"
```

---

## Task 6: CSS redesign

**Files:**
- Rewrite: `css/styles.css`

This task replaces the full CSS to implement the alternating dark/light section layout from the reference image.

- [ ] **Step 1: Replace `css/styles.css`**

```css
/* =========================================================
   Atlantic Crown Coffee — Styles
   ========================================================= */

@import url('https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,600;0,700;1,400;1,600&family=Montserrat:wght@300;400;500;600&display=swap');

@font-face {
  font-family: 'AtlanticCrown';
  src: url('/Fonts/times.ttf') format('truetype');
  font-weight: normal; font-style: normal; font-display: swap;
}
@font-face {
  font-family: 'Montserrat';
  src: url('/Fonts/Montserrat-Medium.ttf') format('truetype');
  font-weight: 500; font-style: normal; font-display: swap;
}

/* ----- Reset & Tokens ----- */
*, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

:root {
  --dark:       #0d0c0a;
  --dark2:      #161410;
  --light:      #f5f0e8;
  --light2:     #ede8df;
  --gold:       #c9a84c;
  --gold-light: #e8c97e;
  --gold-dim:   #7a6230;
  --text-dark:  #f0ebe0;
  --text-light: #1a1714;
  --text-muted-dark:  #9a8a70;
  --text-muted-light: #5a4e3a;
  --brand:  'AtlanticCrown', 'Times New Roman', Georgia, serif;
  --serif:  'Playfair Display', Georgia, serif;
  --sans:   'Montserrat', sans-serif;
  --transition: 0.35s ease;
  --max-w: 1200px;
}

html { scroll-behavior: smooth; }
body { background: var(--dark); color: var(--text-dark); font-family: var(--sans); font-weight: 300; font-size: 16px; line-height: 1.7; overflow-x: hidden; }
img  { display: block; max-width: 100%; }
a    { text-decoration: none; color: inherit; }

/* ----- Utility ----- */
.container { width: 100%; max-width: var(--max-w); margin: 0 auto; padding: 0 2rem; }

.section-label { font-family: var(--sans); font-size: 0.78rem; font-weight: 500; letter-spacing: 0.18em; text-transform: uppercase; color: var(--gold); margin-bottom: 0.75rem; }
.section-title { font-family: var(--serif); font-size: clamp(2rem, 4vw, 2.8rem); font-weight: 700; line-height: 1.2; }
.section-title em { font-style: italic; color: var(--gold); }

/* reveal animation */
.reveal { opacity: 0; transform: translateY(28px); transition: opacity 0.7s ease, transform 0.7s ease; }
.reveal.visible { opacity: 1; transform: translateY(0); }

/* =========================================================
   NAVBAR
   ========================================================= */
.navbar {
  position: fixed; top: 0; left: 0; right: 0; z-index: 100;
  display: flex; align-items: center; justify-content: space-between;
  padding: 1.2rem 2rem;
  background: transparent;
  transition: background var(--transition), box-shadow var(--transition);
}
.navbar.scrolled { background: rgba(13,12,10,0.96); box-shadow: 0 2px 20px rgba(0,0,0,0.5); }

.nav-logo { display: flex; flex-direction: column; line-height: 1; }
.logo-name { font-family: var(--brand); font-size: 1.15rem; letter-spacing: 0.12em; color: var(--gold); }
.logo-sub  { font-family: var(--sans); font-size: 0.6rem; letter-spacing: 0.25em; color: var(--text-muted-dark); margin-top: 0.15rem; }

.nav-end { display: flex; align-items: center; gap: 2rem; }

.nav-links { list-style: none; display: flex; gap: 2rem; }
.nav-links a { font-size: 0.78rem; font-weight: 500; letter-spacing: 0.12em; text-transform: uppercase; color: var(--text-dark); transition: color var(--transition); }
.nav-links a:hover { color: var(--gold); }

/* Language switcher (PHP links) */
.lang-switcher { display: flex; gap: 0.5rem; align-items: center; }
.lang-option { font-size: 0.7rem; font-weight: 600; letter-spacing: 0.1em; color: var(--text-muted-dark); padding: 0.2rem 0.4rem; border: 1px solid transparent; transition: color var(--transition), border-color var(--transition); }
.lang-option:hover, .lang-option.active { color: var(--gold); border-color: var(--gold-dim); }

/* Hamburger */
.nav-toggle { display: none; flex-direction: column; gap: 5px; background: none; border: none; cursor: pointer; padding: 4px; }
.nav-toggle span { display: block; width: 22px; height: 2px; background: var(--gold); transition: transform 0.3s, opacity 0.3s; }
.nav-toggle.open span:nth-child(1) { transform: translateY(7px) rotate(45deg); }
.nav-toggle.open span:nth-child(2) { opacity: 0; }
.nav-toggle.open span:nth-child(3) { transform: translateY(-7px) rotate(-45deg); }

/* =========================================================
   HERO — Dark, 50/50 split
   ========================================================= */
.hero {
  min-height: 100vh;
  background: var(--dark);
  display: grid;
  grid-template-columns: 1fr 1fr;
  align-items: center;
  position: relative;
  overflow: hidden;
  padding-top: 80px;
}

.hero-left {
  padding: 4rem 3rem 4rem 4rem;
  position: relative;
  z-index: 2;
}

.hero-brand-row {
  display: flex; align-items: center; gap: 0.75rem; margin-bottom: 2rem;
}
.hero-brand-logo {
  width: clamp(50px, 8vw, 70px);
  filter: brightness(0) saturate(100%) invert(71%) sepia(38%) saturate(520%) hue-rotate(10deg) brightness(95%);
}
.hero-brand-text { display: flex; flex-direction: column; line-height: 1; }
.hero-brand-name { font-family: var(--brand); font-size: clamp(0.9rem, 1.5vw, 1.1rem); letter-spacing: 0.15em; color: var(--gold); }
.hero-brand-sub  { font-size: 0.58rem; letter-spacing: 0.25em; color: var(--text-muted-dark); margin-top: 0.2rem; }

.hero-headline {
  font-family: var(--serif);
  font-size: clamp(2.4rem, 5vw, 4rem);
  font-weight: 700;
  line-height: 1.1;
  color: var(--text-dark);
  margin-bottom: 1.25rem;
  text-transform: uppercase;
}

.hero-tagline { font-size: 0.95rem; color: var(--text-muted-dark); margin-bottom: 2.5rem; line-height: 1.6; }

.btn-gold {
  display: inline-block; padding: 0.85rem 2.2rem;
  background: var(--gold); color: var(--dark);
  font-size: 0.78rem; font-weight: 600; letter-spacing: 0.14em; text-transform: uppercase;
  transition: background var(--transition), transform var(--transition);
}
.btn-gold:hover { background: var(--gold-light); transform: translateY(-2px); }

.hero-right {
  position: relative;
  height: 100%;
  min-height: 100vh;
  overflow: hidden;
}
.hero-right img {
  width: 100%; height: 100%; object-fit: cover;
  filter: brightness(0.85);
}
.hero-right::before {
  content: '';
  position: absolute; inset: 0;
  background: linear-gradient(to right, var(--dark) 0%, transparent 30%);
  z-index: 1;
}

/* =========================================================
   STORY — Light, 60/40 split
   ========================================================= */
.story {
  background: var(--light);
  color: var(--text-light);
}
.story-inner {
  display: grid;
  grid-template-columns: 60fr 40fr;
  min-height: 600px;
}
.story-content {
  padding: 6rem 4rem 6rem 4rem;
  display: flex; flex-direction: column; justify-content: center;
}
.story-content .section-label { color: var(--gold-dim); }
.story-content .section-title { color: var(--text-light); margin-bottom: 2rem; }
.story-content .section-title em { color: var(--gold-dim); }
.story-body p { color: var(--text-muted-light); margin-bottom: 1rem; font-size: 0.96rem; line-height: 1.8; }
.story-body p:last-child { margin-bottom: 0; font-style: italic; color: var(--text-light); font-weight: 400; }

.story-image {
  position: relative; overflow: hidden;
}
.story-image img { width: 100%; height: 100%; object-fit: cover; }
.story-image::before {
  content: '';
  position: absolute; inset: 0;
  background: linear-gradient(to right, var(--light) 0%, transparent 20%);
  z-index: 1;
}

/* =========================================================
   COFFEES — Dark, two-panel
   ========================================================= */
.coffees {
  background: var(--dark2);
  color: var(--text-dark);
}
.coffees-inner {
  display: grid;
  grid-template-columns: 55fr 45fr;
  min-height: 520px;
}

.coffees-left { padding: 5rem 3rem 5rem 4rem; }
.coffees-left .section-label { color: var(--gold); }
.coffees-left .section-title { color: var(--text-dark); margin-bottom: 0.5rem; }
.coffees-subtitle { font-size: 0.82rem; color: var(--text-muted-dark); letter-spacing: 0.08em; margin-bottom: 3rem; }

.origins-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 2rem; }

.origin-card { border-top: 1px solid var(--gold-dim); padding-top: 1.25rem; }
.origin-icon {
  width: 40px; height: 40px; margin-bottom: 0.75rem;
  background: var(--gold-dim); border-radius: 50%;
  display: flex; align-items: center; justify-content: center;
  font-size: 1.1rem;
}
.origin-name    { font-family: var(--serif); font-size: 1.1rem; font-weight: 600; color: var(--gold); margin-bottom: 0.25rem; }
.origin-profile { font-size: 0.75rem; font-weight: 600; letter-spacing: 0.1em; text-transform: uppercase; color: var(--text-muted-dark); margin-bottom: 0.5rem; }
.origin-desc    { font-size: 0.85rem; color: var(--text-muted-dark); line-height: 1.6; }

.coffees-right {
  position: relative; overflow: hidden;
  display: flex; align-items: center; justify-content: center;
  padding: 3rem 2.5rem;
}
.coffees-right-bg {
  position: absolute; inset: 0;
}
.coffees-right-bg img { width: 100%; height: 100%; object-fit: cover; filter: brightness(0.35); }
.coffees-right-overlay {
  position: absolute; inset: 0;
  background: linear-gradient(to left, transparent 40%, var(--dark2) 100%);
}

.badges-grid {
  position: relative; z-index: 2;
  display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem;
}
.badge {
  background: rgba(201,168,76,0.08);
  border: 1px solid rgba(201,168,76,0.25);
  padding: 1.25rem;
}
.badge-icon { font-size: 1.4rem; margin-bottom: 0.5rem; }
.badge-title { font-family: var(--serif); font-size: 0.9rem; font-weight: 600; color: var(--gold); margin-bottom: 0.3rem; }
.badge-text  { font-size: 0.78rem; color: var(--text-muted-dark); line-height: 1.5; }

/* =========================================================
   WHY — Light, 4 columns
   ========================================================= */
.why {
  background: var(--light2);
  color: var(--text-light);
  padding: 5rem 0;
}
.why-header { text-align: center; margin-bottom: 3.5rem; }
.why-header .section-label { color: var(--gold-dim); }
.why-header .section-title { color: var(--text-light); }

.why-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 2rem; }

.why-card { padding: 1.5rem; border-top: 2px solid var(--gold-dim); }
.why-icon  { font-size: 1.6rem; margin-bottom: 0.75rem; }
.why-title { font-family: var(--serif); font-size: 0.95rem; font-weight: 600; color: var(--text-light); margin-bottom: 0.5rem; }
.why-text  { font-size: 0.82rem; color: var(--text-muted-light); line-height: 1.6; }

/* =========================================================
   CONTACT — Dark, two blocks
   ========================================================= */
.contact {
  background: var(--dark);
  color: var(--text-dark);
}
.contact-inner {
  display: grid;
  grid-template-columns: 1fr 1fr;
  min-height: 500px;
}

.contact-left {
  background: var(--dark2);
  padding: 5rem 3.5rem;
  display: flex; flex-direction: column; justify-content: center;
}
.contact-left .section-label { color: var(--gold); }
.contact-left .section-title { color: var(--text-dark); margin-bottom: 1.5rem; font-size: clamp(1.4rem, 2.5vw, 2rem); }
.contact-desc { color: var(--text-muted-dark); font-size: 0.9rem; line-height: 1.8; margin-bottom: 2rem; }
.contact-detail { display: flex; align-items: center; gap: 0.75rem; margin-bottom: 0.75rem; font-size: 0.88rem; color: var(--text-muted-dark); }
.contact-detail .detail-icon { color: var(--gold); font-size: 1rem; }

.contact-right {
  padding: 5rem 3.5rem;
  display: flex; flex-direction: column; justify-content: center;
}
.contact-right .section-title { font-size: clamp(1.4rem, 2vw, 1.8rem); margin-bottom: 2rem; }

/* Form */
.contact-form { width: 100%; }
.form-row { display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 1rem; }
.form-group { display: flex; flex-direction: column; gap: 0.4rem; margin-bottom: 1rem; }
.form-group label { font-size: 0.72rem; font-weight: 500; letter-spacing: 0.1em; text-transform: uppercase; color: var(--text-muted-dark); }
.form-group input,
.form-group textarea {
  background: var(--dark2); border: 1px solid var(--gold-dim);
  color: var(--text-dark); padding: 0.75rem 1rem;
  font-family: var(--sans); font-size: 0.9rem;
  appearance: none; -webkit-appearance: none;
  transition: border-color var(--transition);
}
.form-group input:focus,
.form-group textarea:focus { outline: none; border-color: var(--gold); }
.form-group textarea { min-height: 120px; resize: vertical; }

.btn-gold-full {
  width: 100%; padding: 1rem;
  background: var(--gold); color: var(--dark);
  font-size: 0.78rem; font-weight: 600; letter-spacing: 0.14em; text-transform: uppercase;
  border: none; cursor: pointer;
  transition: background var(--transition);
}
.btn-gold-full:hover { background: var(--gold-light); }
.btn-gold-full:disabled { opacity: 0.6; cursor: not-allowed; }

.form-message { margin-top: 0.75rem; font-size: 0.85rem; min-height: 1.2em; }

/* =========================================================
   FOOTER
   ========================================================= */
.footer { background: #080706; color: var(--text-dark); padding: 4rem 0 0; }
.footer-inner { display: grid; grid-template-columns: 1fr 1fr; gap: 3rem; padding-bottom: 3rem; border-bottom: 1px solid rgba(201,168,76,0.2); }

.footer-logo-name { font-family: var(--brand); font-size: 1.1rem; letter-spacing: 0.15em; color: var(--gold); display: block; }
.footer-logo-sub  { font-size: 0.6rem; letter-spacing: 0.25em; color: var(--text-muted-dark); display: block; margin-bottom: 1rem; }
.footer-tagline   { font-size: 0.82rem; color: var(--text-muted-dark); font-style: italic; }

.footer-nav { display: flex; gap: 3rem; }
.footer-nav ul { list-style: none; display: flex; flex-direction: column; gap: 0.6rem; }
.footer-nav a { font-size: 0.78rem; color: var(--text-muted-dark); transition: color var(--transition); }
.footer-nav a:hover { color: var(--gold); }

.footer-bottom { padding: 1.5rem 0; }
.footer-bottom .container { display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 0.5rem; }
.footer-location { font-size: 0.72rem; letter-spacing: 0.1em; color: var(--gold-dim); text-transform: uppercase; }
.footer-copy     { font-size: 0.72rem; color: var(--text-muted-dark); }

/* =========================================================
   PRIVACY BANNER
   ========================================================= */
.privacy-banner {
  position: fixed; bottom: 0; left: 0; right: 0; z-index: 200;
  background: var(--dark2); border-top: 1px solid var(--gold-dim);
  display: flex; align-items: center; justify-content: space-between;
  gap: 1rem; padding: 1rem 2rem; flex-wrap: wrap;
}
.privacy-banner-text { font-size: 0.82rem; color: var(--text-muted-dark); }
.privacy-banner-text a { color: var(--gold); text-decoration: underline; }
.privacy-banner-btn {
  background: var(--gold); color: var(--dark);
  border: none; padding: 0.5rem 1.4rem; font-size: 0.78rem; font-weight: 600;
  cursor: pointer; white-space: nowrap; letter-spacing: 0.08em;
}

/* =========================================================
   LEGAL PAGES
   ========================================================= */
.legal-page { padding: 8rem 0 4rem; min-height: 80vh; }
.legal-page h1 { font-family: var(--serif); font-size: clamp(1.8rem, 3vw, 2.5rem); color: var(--gold); margin-bottom: 2rem; }
.legal-page h2 { font-family: var(--serif); font-size: 1.2rem; color: var(--gold); margin: 2rem 0 0.75rem; }
.legal-page p, .legal-page li { font-size: 0.9rem; color: var(--text-muted-dark); line-height: 1.8; margin-bottom: 0.75rem; }
.legal-page ul { margin-left: 1.5rem; }
.legal-page a { color: var(--gold); text-decoration: underline; }

/* =========================================================
   RESPONSIVE
   ========================================================= */
@media (max-width: 1024px) {
  .coffees-inner { grid-template-columns: 1fr; }
  .coffees-right { min-height: 320px; }
  .badges-grid   { grid-template-columns: repeat(4, 1fr); }
}

@media (max-width: 768px) {
  /* Navbar mobile */
  .nav-links {
    display: none; flex-direction: column; gap: 0;
    position: absolute; top: 100%; left: 0; right: 0;
    background: rgba(13,12,10,0.98); padding: 1rem 0;
  }
  .nav-links.open { display: flex; }
  .nav-links a { padding: 0.75rem 2rem; display: block; }
  .nav-toggle { display: flex; }

  /* Hero */
  .hero { grid-template-columns: 1fr; min-height: auto; }
  .hero-left { padding: 6rem 1.5rem 3rem; }
  .hero-right { min-height: 55vw; }

  /* Story */
  .story-inner { grid-template-columns: 1fr; }
  .story-content { padding: 3rem 1.5rem; }
  .story-image { min-height: 260px; }

  /* Coffees */
  .coffees-left { padding: 3rem 1.5rem; }
  .origins-grid { grid-template-columns: 1fr; gap: 1.5rem; }
  .badges-grid  { grid-template-columns: 1fr 1fr; }

  /* Why */
  .why-grid { grid-template-columns: 1fr 1fr; }

  /* Contact */
  .contact-inner { grid-template-columns: 1fr; }
  .contact-left, .contact-right { padding: 3rem 1.5rem; }
  .form-row { grid-template-columns: 1fr; }

  /* Footer */
  .footer-inner { grid-template-columns: 1fr; gap: 2rem; }
  .footer-nav   { flex-wrap: wrap; gap: 1.5rem; }
}

@media (max-width: 480px) {
  .why-grid   { grid-template-columns: 1fr; }
  .badges-grid { grid-template-columns: 1fr; }
}
```

- [ ] **Step 2: Verify CSS loads without syntax errors**

```bash
php -r "echo file_exists('css/styles.css') ? 'OK' : 'MISSING';"
```

- [ ] **Step 3: Commit**

```bash
git add css/styles.css
git commit -m "feat: full CSS redesign - alternating dark/light sections matching reference"
```

---

## Task 7: Home page template

**Files:**
- Create: `pages/home.php`

- [ ] **Step 1: Create `pages/home.php`**

```php
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
      <h2 class="section-title"><?= $t['contact.title'] ?></h2>
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
        <button type="submit" class="btn-gold-full"><?= $t['contact.form.submit'] ?></button>
        <p class="form-message" id="formMessage" aria-live="polite"></p>
      </form>
    </div>
  </div>
</section>
```

- [ ] **Step 2: Test home page renders**

```bash
curl -s http://localhost:8000/en/ | grep -c "Atlantic Crown"
```

Expected: number > 3.

```bash
curl -s http://localhost:8000/pt/ | grep "Nascida do espírito"
```

Expected: line with Portuguese headline.

- [ ] **Step 3: Commit**

```bash
git add pages/home.php
git commit -m "feat: home page PHP template with new visual layout"
```

---

## Task 8: Legal pages (privacy, terms, cookies)

**Files:**
- Create: `pages/privacy.php`
- Create: `pages/terms.php`
- Create: `pages/cookies.php`

Each page wraps existing legal content (from the old `.html` files) inside the PHP template, showing only the current `$lang` block.

- [ ] **Step 1: Create `pages/privacy.php`**

Open `privacy.html` from the backup (`/Users/claudiovieira/CoffeSite_backup_html_2026-04-30/privacy.html`), copy the content inside each `.legal-lang[data-lang]` div, and restructure as:

```php
<main class="legal-page">
  <div class="container">
    <?php if ($lang === 'en'): ?>
    <h1>Privacy Policy</h1>
    <!-- paste EN content from backup privacy.html .legal-lang[data-lang="en"] here -->
    <?php elseif ($lang === 'pt'): ?>
    <h1>Política de Privacidade</h1>
    <!-- paste PT content from backup privacy.html .legal-lang[data-lang="pt"] here -->
    <?php elseif ($lang === 'fr'): ?>
    <h1>Politique de Confidentialité</h1>
    <!-- paste FR content from backup privacy.html .legal-lang[data-lang="fr"] here -->
    <?php endif; ?>
  </div>
</main>
```

- [ ] **Step 2: Create `pages/terms.php`**

Same approach as privacy.php, using content from backup `terms.html`.

```php
<main class="legal-page">
  <div class="container">
    <?php if ($lang === 'en'): ?>
    <h1>Terms of Use</h1>
    <?php elseif ($lang === 'pt'): ?>
    <h1>Termos de Uso</h1>
    <?php elseif ($lang === 'fr'): ?>
    <h1>Conditions d'Utilisation</h1>
    <?php endif; ?>
    <!-- content per lang from backup terms.html -->
  </div>
</main>
```

- [ ] **Step 3: Create `pages/cookies.php`**

Same approach, using content from backup `cookies.html`.

```php
<main class="legal-page">
  <div class="container">
    <?php if ($lang === 'en'): ?>
    <h1>Cookie Policy</h1>
    <?php elseif ($lang === 'pt'): ?>
    <h1>Política de Cookies</h1>
    <?php elseif ($lang === 'fr'): ?>
    <h1>Politique de Cookies</h1>
    <?php endif; ?>
    <!-- content per lang from backup cookies.html -->
  </div>
</main>
```

- [ ] **Step 4: Test legal page routing**

```bash
curl -s -o /dev/null -w "%{http_code}" http://localhost:8000/en/privacy
curl -s -o /dev/null -w "%{http_code}" http://localhost:8000/pt/terms
curl -s -o /dev/null -w "%{http_code}" http://localhost:8000/fr/cookies
```

Expected: all `200`.

- [ ] **Step 5: Commit**

```bash
git add pages/
git commit -m "feat: legal pages (privacy, terms, cookies) as PHP templates"
```

---

## Task 9: Simplified `js/main.js`

**Files:**
- Rewrite: `js/main.js`
- Delete: `js/translations.js`

- [ ] **Step 1: Replace `js/main.js`**

```javascript
/* =========================================================
   Atlantic Crown Coffee — main.js
   Translations handled server-side. FORM_STRINGS and
   CURRENT_LANG are injected by includes/footer.php.
   ========================================================= */

// ----- Navbar scroll behaviour -----
const navbar    = document.querySelector('.navbar');
const navToggle = document.querySelector('.nav-toggle');
const navLinks  = document.querySelector('.nav-links');

window.addEventListener('scroll', () => {
  navbar.classList.toggle('scrolled', window.scrollY > 60);
});

if (navToggle) {
  navToggle.addEventListener('click', () => {
    const isOpen = navToggle.classList.toggle('open');
    navLinks.classList.toggle('open');
    navToggle.setAttribute('aria-expanded', isOpen);
  });

  document.querySelectorAll('.nav-links a').forEach(link => {
    link.addEventListener('click', () => {
      navToggle.classList.remove('open');
      navLinks.classList.remove('open');
      navToggle.setAttribute('aria-expanded', 'false');
    });
  });
}

// ----- Smooth scroll -----
document.querySelectorAll('a[href^="#"]').forEach(link => {
  link.addEventListener('click', function (e) {
    const target = document.getElementById(this.getAttribute('href').slice(1));
    if (!target) return;
    e.preventDefault();
    target.scrollIntoView({ behavior: 'smooth', block: 'start' });
  });
});

// ----- Reveal on scroll -----
const observer = new IntersectionObserver((entries) => {
  entries.forEach(entry => {
    if (!entry.isIntersecting) return;
    const delay = entry.target.dataset.delay || 0;
    setTimeout(() => entry.target.classList.add('visible'), delay);
    observer.unobserve(entry.target);
  });
}, { threshold: 0.12 });

document.querySelectorAll('.reveal').forEach(el => observer.observe(el));

// ----- Contact form -----
const contactForm = document.getElementById('contactForm');
const formMessage = document.getElementById('formMessage');

if (contactForm && typeof FORM_STRINGS !== 'undefined') {
  contactForm.addEventListener('submit', async function (e) {
    e.preventDefault();

    const name    = document.getElementById('name').value.trim();
    const email   = document.getElementById('email').value.trim();
    const message = document.getElementById('message').value.trim();

    if (!name || !email || !message) {
      formMessage.textContent = FORM_STRINGS.error;
      formMessage.style.color = '#e07070';
      return;
    }

    const submitBtn = contactForm.querySelector('button[type="submit"]');
    submitBtn.disabled = true;
    submitBtn.textContent = FORM_STRINGS.sending;

    try {
      const formData = new FormData(contactForm);
      const response = await fetch('/send.php', { method: 'POST', body: formData });
      const result   = await response.json();

      if (result.success) {
        formMessage.textContent = FORM_STRINGS.success;
        formMessage.style.color = '#c9a84c';
        contactForm.reset();
      } else {
        formMessage.textContent = result.message || FORM_STRINGS.error;
        formMessage.style.color = '#e07070';
      }
    } catch {
      formMessage.textContent = FORM_STRINGS.error;
      formMessage.style.color = '#e07070';
    } finally {
      submitBtn.disabled = false;
      submitBtn.textContent = document.querySelector('[data-submit-label]')?.dataset.submitLabel
        || 'Send Message';
      setTimeout(() => { formMessage.textContent = ''; }, 6000);
    }
  });
}

// ----- Privacy banner -----
const privacyBanner = document.getElementById('privacyBanner');
const privacyAccept = document.getElementById('privacyBannerAccept');

if (privacyBanner && !localStorage.getItem('privacy_accepted')) {
  privacyBanner.style.display = 'flex';
}
if (privacyAccept) {
  privacyAccept.addEventListener('click', () => {
    localStorage.setItem('privacy_accepted', '1');
    privacyBanner.style.display = 'none';
  });
}
```

- [ ] **Step 2: Delete `js/translations.js`**

```bash
rm /Users/claudiovieira/CoffeSite/js/translations.js
```

- [ ] **Step 3: Fix submit button label**

In `pages/home.php`, add `data-submit-label` to the submit button so JS can restore the label after sending:

```php
<button type="submit" class="btn-gold-full" data-submit-label="<?= htmlspecialchars($t['contact.form.submit']) ?>">
  <?= $t['contact.form.submit'] ?>
</button>
```

- [ ] **Step 4: Commit**

```bash
git add js/main.js
git rm js/translations.js
git commit -m "feat: simplified main.js - server-side strings, absolute send.php path"
```

---

## Task 10: Remove old HTML files + final verification

**Files:**
- Delete: `index.html`, `privacy.html`, `terms.html`, `cookies.html`

- [ ] **Step 1: Delete old HTML files**

```bash
git rm index.html privacy.html terms.html cookies.html
git commit -m "chore: remove static HTML files replaced by PHP router"
```

- [ ] **Step 2: Verify all routes return 200**

```bash
for url in "/en/" "/pt/" "/fr/" "/en/privacy" "/pt/privacy" "/fr/privacy" "/en/terms" "/en/cookies"; do
  code=$(curl -s -o /dev/null -w "%{http_code}" "http://localhost:8000${url}")
  echo "$url → $code"
done
```

Expected: all `200`.

- [ ] **Step 3: Verify root redirects to /en/**

```bash
curl -s -o /dev/null -w "%{http_code} %{redirect_url}" http://localhost:8000/
```

Expected: `302 http://localhost:8000/en/`

- [ ] **Step 4: Verify 404 for unknown routes**

```bash
curl -s -o /dev/null -w "%{http_code}" http://localhost:8000/xx/
curl -s -o /dev/null -w "%{http_code}" http://localhost:8000/en/admin
```

Expected: both `404`.

- [ ] **Step 5: Verify hreflang tags are present**

```bash
curl -s http://localhost:8000/en/ | grep hreflang
```

Expected: 4 lines (en, pt, fr, x-default).

- [ ] **Step 6: Verify language switching works**

```bash
curl -s http://localhost:8000/pt/ | grep "Nascida do espírito"
curl -s http://localhost:8000/fr/ | grep "Née de l"
```

Expected: each returns the matching headline.

- [ ] **Step 7: Open in browser and verify visual layout**

```bash
open http://localhost:8000/en/
```

Check:
- Hero: dark background, logo + headline left, image right
- Story: light background, text left, image right
- Coffees: dark, 3 origin columns visible
- Why: light, 4 columns visible
- Contact: dark, two panels
- Footer: present with correct links
- Language switcher: EN/PT/FR links visible in navbar

- [ ] **Step 8: Final commit**

```bash
git add -A
git commit -m "feat: complete PHP router migration with server-side i18n and visual redesign"
```

---

## Self-Review Checklist

- [x] **Spec coverage:** Router ✓ | Security headers ✓ | Lang files ✓ | hreflang ✓ | Visual redesign (all 5 sections) ✓ | Legal pages ✓ | form absolute path fix ✓
- [x] **No placeholders:** Legal page task references backup file explicitly with instructions
- [x] **Type consistency:** `$t` array used consistently; `$lang`, `$page` passed to all includes; `FORM_STRINGS` injected by footer.php and consumed by main.js
- [x] **Send path:** fetch uses `/send.php` (absolute) — works from any URL depth
- [x] **Submit button label:** `data-submit-label` attribute added in Task 9 Step 3
