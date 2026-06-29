/* Enkrix IMS — Application JavaScript */

// Live search debounce for inventory/assignment tables
(function () {
  const searchInput = document.querySelector('input[name="search"]');
  if (searchInput) {
    let timer;
    searchInput.addEventListener('input', () => {
      clearTimeout(timer);
      timer = setTimeout(() => {
        searchInput.closest('form')?.submit();
      }, 500);
    });
  }
})();

// Confirm delete helpers (inline with data attributes)
document.querySelectorAll('[data-confirm]').forEach(el => {
  el.addEventListener('click', e => {
    if (!confirm(el.dataset.confirm)) e.preventDefault();
  });
});

// Auto-dismiss toasts after 5s
document.querySelectorAll('.toast').forEach(t => {
  setTimeout(() => t.remove(), 5000);
});
