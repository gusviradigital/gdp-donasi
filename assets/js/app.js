import '../css/app.css'

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
})
