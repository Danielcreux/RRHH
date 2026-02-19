// public/assets/js/active-nav.js
(() => {
  const url = new URL(window.location.href);
  const r = url.searchParams.get("r") || "/index";

  // Marca activo por prefijo (modulo/accion)
  document.querySelectorAll(".nav-item").forEach(a => {
    const href = a.getAttribute("href") || "";
    const u = new URL(href, window.location.origin);
    const rr = u.searchParams.get("r");
    if (!rr) return;

    // criterio: mismo módulo (antes del /) o match exacto
    const modA = rr.split("/")[0];
    const modR = r.split("/")[0];

    if (rr === r || modA === modR) a.classList.add("active");
  });
})();
