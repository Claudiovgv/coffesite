/* =========================================================
   Atlantic Crown Coffee — main.js
   ========================================================= */

// ----- i18n state -----
let currentLang = 'pt';

function setLanguage(lang) {
  if (!TRANSLATIONS[lang]) return;
  currentLang = lang;

  document.documentElement.lang = lang;

  document.querySelectorAll('[data-i18n]').forEach(el => {
    const key = el.dataset.i18n;
    const value = TRANSLATIONS[lang][key];
    if (value !== undefined) el.innerHTML = value;
  });

  document.querySelectorAll('[data-i18n-placeholder]').forEach(el => {
    const key = el.dataset.i18nPlaceholder;
    const value = TRANSLATIONS[lang][key];
    if (value !== undefined) el.placeholder = value;
  });

  const langCurrent = document.querySelector('.lang-current');
  if (langCurrent) langCurrent.textContent = lang.toUpperCase();

  document.querySelectorAll('#langDropdown button').forEach(btn => {
    btn.classList.toggle('active', btn.dataset.lang === lang);
  });

  const langBtn = document.getElementById('langBtn');
  if (langBtn) langBtn.setAttribute('aria-label', `Language: ${lang.toUpperCase()}`);

  // Toggle legal page language sections
  document.querySelectorAll('.legal-lang').forEach(el => {
    el.style.display = el.dataset.lang === lang ? 'block' : 'none';
  });

  localStorage.setItem('lang', lang);
}

function detectLanguage() {
  const saved = localStorage.getItem('lang');
  if (saved && TRANSLATIONS[saved]) return saved;

  const langs = navigator.languages || [navigator.language || 'pt'];
  for (const l of langs) {
    const code = l.slice(0, 2).toLowerCase();
    if (TRANSLATIONS[code]) return code;
  }

  return 'pt';
}

// ----- Navbar: scroll behaviour -----
const navbar = document.querySelector('.navbar');
const navToggle = document.querySelector('.nav-toggle');
const navLinks  = document.querySelector('.nav-links');

window.addEventListener('scroll', () => {
  if (window.scrollY > 60) {
    navbar.classList.add('scrolled');
  } else {
    navbar.classList.remove('scrolled');
  }
});

// Mobile menu toggle
if (navToggle) {
  navToggle.addEventListener('click', () => {
    const isOpen = navToggle.classList.toggle('open');
    navLinks.classList.toggle('open');
    navToggle.setAttribute('aria-expanded', isOpen);
  });

  // Close mobile menu on link click
  document.querySelectorAll('.nav-links a').forEach(link => {
    link.addEventListener('click', () => {
      navToggle.classList.remove('open');
      navLinks.classList.remove('open');
      navToggle.setAttribute('aria-expanded', 'false');
    });
  });
}

// ----- Lang switcher -----
const langBtn     = document.getElementById('langBtn');
const langDropdown = document.getElementById('langDropdown');

if (langBtn && langDropdown) {
  langBtn.addEventListener('click', (e) => {
    e.stopPropagation();
    const isOpen = langDropdown.classList.toggle('open');
    langBtn.setAttribute('aria-expanded', isOpen);
  });

  langDropdown.querySelectorAll('button[data-lang]').forEach(btn => {
    btn.addEventListener('click', () => {
      setLanguage(btn.dataset.lang);
      langDropdown.classList.remove('open');
      langBtn.setAttribute('aria-expanded', 'false');
    });
  });

  document.addEventListener('click', (e) => {
    if (!e.target.closest('#langSwitcher')) {
      langDropdown.classList.remove('open');
      langBtn.setAttribute('aria-expanded', 'false');
    }
  });

  document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape') {
      langDropdown.classList.remove('open');
      langBtn.setAttribute('aria-expanded', 'false');
    }
  });
}

// ----- Hero: bg zoom on load -----
const heroBg = document.querySelector('.hero-bg');
if (heroBg) {
  window.addEventListener('load', () => {
    heroBg.classList.add('loaded');
  });
}


// ----- Reveal on scroll -----
const reveals = document.querySelectorAll('.reveal');

const observer = new IntersectionObserver((entries) => {
  entries.forEach(entry => {
    if (entry.isIntersecting) {
      // staggered delay via data-delay attribute
      const delay = entry.target.dataset.delay || 0;
      setTimeout(() => entry.target.classList.add('visible'), delay);
      observer.unobserve(entry.target);
    }
  });
}, { threshold: 0.12 });

reveals.forEach(el => observer.observe(el));


// ----- Contact form: mailto handler -----
const contactForm = document.getElementById('contactForm');
const formMessage = document.getElementById('formMessage');

if (contactForm) {
  contactForm.addEventListener('submit', function (e) {
    e.preventDefault();

    const name    = document.getElementById('name').value.trim();
    const email   = document.getElementById('email').value.trim();
    const phone   = document.getElementById('phone').value.trim();
    const subject = document.getElementById('subject').value.trim();
    const message = document.getElementById('message').value.trim();

    const t = TRANSLATIONS[currentLang];

    if (!name || !email || !message) {
      formMessage.textContent = t['form.error'];
      formMessage.style.color = '#e07070';
      return;
    }

    const labelName    = t['contact.form.name.label'].replace(' *', '');
    const labelPhone   = t['contact.form.phone.label'];
    const labelSubject = t['contact.form.subject.label'];
    const body = encodeURIComponent(
      `${labelName}: ${name}\nEmail: ${email}\n${labelPhone}: ${phone}\n${labelSubject}: ${subject}\n\n${message}`
    );
    const mailSubject = encodeURIComponent(t['form.subject.default']);

    window.location.href = `mailto:geral@atlanticcrowncoffee.com?subject=${mailSubject}&body=${body}`;

    formMessage.textContent = t['form.opening'];
    formMessage.style.color = '#c9a84c';

    setTimeout(() => { formMessage.textContent = ''; }, 4000);
  });
}

// ----- Init language -----
setLanguage(detectLanguage());

// ----- Privacy notice banner -----
const privacyBanner = document.getElementById('privacyBanner');
const privacyAccept = document.getElementById('privacyBannerAccept');

if (privacyBanner && privacyAccept) {
  if (!localStorage.getItem('privacy_notice_accepted')) {
    privacyBanner.style.display = 'flex';
  }
  privacyAccept.addEventListener('click', () => {
    localStorage.setItem('privacy_notice_accepted', '1');
    privacyBanner.style.display = 'none';
  });
}
