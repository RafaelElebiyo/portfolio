/**
 * Theme — dark / light toggle with localStorage persistence.
 */
const Theme = (() => {
  const KEY = 'portfolio_theme';
  const root = document.documentElement;

  function get()  { return localStorage.getItem(KEY) ?? (window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light'); }
  function apply(theme) {
    root.setAttribute('data-theme', theme);
    localStorage.setItem(KEY, theme);
  }
  function toggle() { apply(get() === 'dark' ? 'light' : 'dark'); }
  function init()   { apply(get()); }

  return { init, toggle, get };
})();

// Apply before DOM renders to prevent flash
Theme.init();

document.addEventListener('DOMContentLoaded', () => {
  document.getElementById('theme-toggle')?.addEventListener('click', () => Theme.toggle());
});
