// public/assets/js/darkmode.js
(() => {
  const KEY = "rrhh_theme"; // "dark" | "light"

  function apply(theme) {
    document.body.classList.toggle("dark", theme === "dark");
  }

  // init
  const saved = localStorage.getItem(KEY);
  if (saved === "dark" || saved === "light") {
    apply(saved);
  } else {
    // fallback: respeta sistema
    const prefersDark = window.matchMedia && window.matchMedia("(prefers-color-scheme: dark)").matches;
    apply(prefersDark ? "dark" : "light");
  }

  // API
  window.Theme = {
    toggle() {
      const isDark = document.body.classList.contains("dark");
      const next = isDark ? "light" : "dark";
      localStorage.setItem(KEY, next);
      apply(next);
      window.Toast?.show({ title: "Tema", message: next === "dark" ? "Modo oscuro activado" : "Modo claro activado", type: "ok" });
    }
  };
})();
