import '../css/app.css'
import initSearch from './components/search';

// Dark mode toggle
document.addEventListener('DOMContentLoaded', () => {
  const darkModeToggle = document.querySelector('[data-dark-toggle]')
  if (darkModeToggle) {
    darkModeToggle.addEventListener('click', () => {
      document.documentElement.classList.toggle('dark')
      localStorage.setItem('theme', document.documentElement.classList.contains('dark') ? 'dark' : 'light')
    })
  }

  // On page load or when changing themes
  if (localStorage.theme === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
    document.documentElement.classList.add('dark')
  } else {
    document.documentElement.classList.remove('dark')
  }

  // Initialize search
  initSearch();

  // Mobile menu toggle
  const mobileMenuButton = document.querySelector('.mobile-menu-button');
  const mobileMenu = document.querySelector('.mobile-menu');

  if (mobileMenuButton && mobileMenu) {
    mobileMenuButton.addEventListener('click', () => {
      mobileMenu.classList.toggle('hidden');
    });
  }
})
