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
      submitBtn.textContent = submitBtn.dataset.submitLabel || 'Send';
      setTimeout(() => { formMessage.textContent = ''; }, 6000);
    }
  });
}

// ----- Order form (product pages) -----
const orderForm    = document.getElementById('orderForm');
const orderMessage = document.getElementById('orderMessage');

if (orderForm && typeof ORDER_STRINGS !== 'undefined') {
  orderForm.addEventListener('submit', async function (e) {
    e.preventDefault();

    const name  = document.getElementById('o-name').value.trim();
    const email = document.getElementById('o-email').value.trim();
    const qty   = document.getElementById('o-qty').value.trim();

    if (!name || !email || !qty) {
      orderMessage.textContent = ORDER_STRINGS.error;
      orderMessage.style.color = '#e07070';
      return;
    }

    const submitBtn = orderForm.querySelector('button[type="submit"]');
    submitBtn.disabled = true;
    submitBtn.textContent = ORDER_STRINGS.sending;

    try {
      const formData = new FormData(orderForm);
      const response = await fetch('/send.php', { method: 'POST', body: formData });
      const result   = await response.json();

      if (result.success) {
        orderMessage.textContent = ORDER_STRINGS.success;
        orderMessage.style.color = '#c9a84c';
        orderForm.reset();
      } else {
        orderMessage.textContent = result.message || ORDER_STRINGS.error;
        orderMessage.style.color = '#e07070';
      }
    } catch {
      orderMessage.textContent = ORDER_STRINGS.error;
      orderMessage.style.color = '#e07070';
    } finally {
      submitBtn.disabled = false;
      submitBtn.textContent = submitBtn.dataset.submitLabel;
      setTimeout(() => { orderMessage.textContent = ''; }, 6000);
    }
  });
}

// ----- Nav dropdown (mobile toggle) -----
document.querySelectorAll('.nav-item-dropdown').forEach(item => {
  const trigger = item.querySelector('.nav-dropdown-trigger');
  trigger.addEventListener('click', function (e) {
    const isMobile = window.innerWidth <= 768;
    if (!isMobile) return;
    e.preventDefault();
    item.classList.toggle('open');
  });
});

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
