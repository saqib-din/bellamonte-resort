'use strict';

var rtl_flag = false;
var dark_flag = false;

// On page load: apply saved theme or system default
document.addEventListener('DOMContentLoaded', function () {
  if (typeof Storage !== 'undefined') {
    var savedTheme = localStorage.getItem('theme');
    if (savedTheme === 'dark' || savedTheme === 'light') {
      layout_change(savedTheme);
    } else {
      layout_change_default(); // Apply system theme if no saved theme
    }
  } else {
    console.warn('Web Storage API is not supported in this browser.');
  }
});

// Apply theme based on system preference
function layout_change_default() {
  let dark_layout = window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light';
  layout_change(dark_layout);

  // Set "default" button active (if exists)
  const btn_control = document.querySelector('.theme-layout .btn[data-value="default"]');
  if (btn_control) {
    btn_control.classList.add('active');
  }

  // Listen to system theme changes
  window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', (event) => {
    if (!localStorage.getItem('theme')) {
      const newTheme = event.matches ? 'dark' : 'light';
      layout_change(newTheme);
    }
  });
}

// Handle manual theme selection buttons
var layout_btn = document.querySelectorAll('.theme-layout .btn');
for (var t = 0; t < layout_btn.length; t++) {
  if (layout_btn[t]) {
    layout_btn[t].addEventListener('click', function (event) {
      event.stopPropagation();
      var targetElement = event.target;

      if (targetElement.tagName === 'SPAN') targetElement = targetElement.parentNode;
      if (targetElement.tagName === 'SPAN') targetElement = targetElement.parentNode;

      const value = targetElement.getAttribute('data-value');

      if (value === 'true') {
        localStorage.setItem('theme', 'light');
        layout_change('light');
      } else if (value === 'false') {
        localStorage.setItem('theme', 'dark');
        layout_change('dark');
      } else {
        localStorage.removeItem('theme');
        layout_change_default();
      }
    });
  }
}

// Apply dark or light theme
function layout_change(layout) {
  document.body.setAttribute('data-pc-theme', layout);

  const defaultBtn = document.querySelector('.theme-layout .btn[data-value="default"]');
  if (defaultBtn) defaultBtn.classList.remove('active');

   const welcomeCard = document.querySelector('.welcome-banner');

  if (layout === 'dark') {
    dark_flag = true;

    if (welcomeCard) {
      welcomeCard.classList.remove('bg-blue-800');
    }

    updateLogo('.pc-sidebar .m-header .logo-lg', '../assets/images/logo-white.svg');
    updateLogo('.navbar-brand .logo-lg', '../assets/images/logo-white.svg');
    updateLogo('.auth-main.v1 .auth-sidefooter img', '../assets/images/logo-white.svg');
    updateLogo('.footer-top .footer-logo', '../assets/images/logo-white.svg');
    updateActiveButton('.theme-layout .btn[data-value="false"]');
  } else {
    dark_flag = false;

    if (welcomeCard) {
      welcomeCard.classList.add('bg-blue-800');
    }

    updateLogo('.pc-sidebar .m-header .logo-lg', '../assets/images/logo-dark.svg');
    updateLogo('.navbar-brand .logo-lg', '../assets/images/logo-dark.svg');
    updateLogo('.auth-main.v1 .auth-sidefooter img', '../assets/images/logo-dark.svg');
    updateLogo('.footer-top .footer-logo', '../assets/images/logo-dark.svg');
    updateActiveButton('.theme-layout .btn[data-value="true"]');
  }
}

function updateLogo(selector, newSrc) {
  const logo = document.querySelector(selector);
  if (logo) {
    logo.setAttribute('src', newSrc);
  }
}

function updateActiveButton(selector) {
  const activeBtn = document.querySelector('.theme-layout .btn.active');
  if (activeBtn) activeBtn.classList.remove('active');

  const newActiveBtn = document.querySelector(selector);
  if (newActiveBtn) newActiveBtn.classList.add('active');
}

// Optional: Preset color, RTL, layout box, caption logic remains unchanged
// Keep your previous functions like preset_change(), layout_caption_change(), layout_rtl_change(), etc.

