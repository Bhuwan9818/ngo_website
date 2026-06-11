// ===== MOBILE NAV TOGGLE =====
document.addEventListener('DOMContentLoaded', function () {
  const hamburger = document.getElementById('hamburger');
  const mobileMenu = document.getElementById('mobileMenu');

  if (!hamburger || !mobileMenu) return;

  // Toggle menu on hamburger click
  hamburger.addEventListener('click', function () {
    hamburger.classList.toggle('open');
    mobileMenu.classList.toggle('open');
  });

  // Close menu when a link inside it is clicked
  mobileMenu.querySelectorAll('a').forEach(function (link) {
    link.addEventListener('click', function () {
      hamburger.classList.remove('open');
      mobileMenu.classList.remove('open');
    });
  });

  // Close menu if user taps/clicks outside of it
  document.addEventListener('click', function (e) {
    const isClickInsideMenu = mobileMenu.contains(e.target);
    const isClickOnHamburger = hamburger.contains(e.target);

    if (!isClickInsideMenu && !isClickOnHamburger && mobileMenu.classList.contains('open')) {
      hamburger.classList.remove('open');
      mobileMenu.classList.remove('open');
    }
  });

  // Close menu if window is resized to desktop size
  window.addEventListener('resize', function () {
    if (window.innerWidth > 768) { // matches your CSS breakpoint
      hamburger.classList.remove('open');
      mobileMenu.classList.remove('open');
    }
  });
});

// ===== CONTACT FORM HANDLER =====
function handleForm(event) {
  event.preventDefault();
  alert('Thank you for reaching out! We will get back to you soon.');
  event.target.reset();
}

// ===== NEWSLETTER FORM HANDLER =====
function handleNewsletterForm(event) {
  event.preventDefault();
  alert('Thanks for subscribing to our newsletter!');
  event.target.reset();
}
