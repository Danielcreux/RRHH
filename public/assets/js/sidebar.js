// public/assets/js/sidebar.js
(() => {
  const KEY = "rrhh_sidebar_collapsed";

  function applyCollapsed(isCollapsed) {
    document.body.classList.toggle("sidebar-collapsed", isCollapsed);
  }

  function applyMobileOpen(isOpen) {
    document.body.classList.toggle("sidebar-open", isOpen);
    document.body.style.overflow = isOpen ? "hidden" : "";
  }

  const saved = localStorage.getItem(KEY);
  if (saved === "1") applyCollapsed(true);

  document.addEventListener("click", (e) => {
    const btn = e.target.closest("[data-toggle-sidebar]");
    if (!btn) return;
    const now = !document.body.classList.contains("sidebar-collapsed");
    applyCollapsed(now);
    localStorage.setItem(KEY, now ? "1" : "0");
  });

  document.addEventListener("click", (e) => {
    const btn = e.target.closest("[data-open-sidebar]");
    if (!btn) return;
    const now = !document.body.classList.contains("sidebar-open");
    applyMobileOpen(now);
  });

  document.addEventListener("click", (e) => {
    if (!document.body.classList.contains("sidebar-open")) return;
    const sidebar = document.querySelector(".sidebar");
    const clickedInside = sidebar && sidebar.contains(e.target);
    const clickedTopbarBtn = e.target.closest("[data-open-sidebar]");
    if (!clickedInside && !clickedTopbarBtn) applyMobileOpen(false);
  });

  document.addEventListener("keydown", (e) => {
    if (e.key === "Escape") applyMobileOpen(false);
  });
})();
