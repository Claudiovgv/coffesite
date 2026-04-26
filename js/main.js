/* =========================================================
   Atlantic Crown Coffee — main.js
   ========================================================= */

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
    navToggle.classList.toggle('open');
    navLinks.classList.toggle('open');
  });
}

// Close mobile menu on link click
document.querySelectorAll('.nav-links a').forEach(link => {
  link.addEventListener('click', () => {
    navToggle.classList.remove('open');
    navLinks.classList.remove('open');
  });
});


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

    if (!name || !email || !message) {
      formMessage.textContent = 'Por favor preencha o nome, email e mensagem.';
      formMessage.style.color = '#e07070';
      return;
    }

    const body = encodeURIComponent(
      `Nome: ${name}\nEmail: ${email}\nTelefone: ${phone}\nAssunto: ${subject}\n\n${message}`
    );
    const mailSubject = encodeURIComponent(`Encomenda / Informação — Atlantic Crown Coffee`);

    // Replace the email below with the actual contact email
    window.location.href = `mailto:geral@atlanticcrowncoffee.com?subject=${mailSubject}&body=${body}`;

    formMessage.textContent = 'A abrir o seu cliente de email...';
    formMessage.style.color = '#c9a84c';

    setTimeout(() => { formMessage.textContent = ''; }, 4000);
  });
}
