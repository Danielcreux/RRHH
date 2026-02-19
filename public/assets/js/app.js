// public/assets/js/app.js
(() => {
  window.$ = (sel, root=document) => root.querySelector(sel);
  window.$$ = (sel, root=document) => Array.from(root.querySelectorAll(sel));

  if (!window.Toast) window.Toast = { show: () => {} };

  // Confirmaciones por data-confirm
  document.addEventListener("submit", async (e) => {
    const form = e.target;
    if (!(form instanceof HTMLFormElement)) return;

    const msg = form.getAttribute("data-confirm");
    if (!msg) return;

    e.preventDefault();
    const ok = await window.Modal.open({
      title: "Confirmar acción",
      message: msg,
      okText: "Sí, continuar",
      cancelText: "Cancelar"
    });
    if (ok) form.submit();
  });
})();
