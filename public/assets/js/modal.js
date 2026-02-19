// public/assets/js/modal.js
window.Modal = (() => {
  let backdrop, modal, titleEl, msgEl, btnOk, btnCancel;
  let resolver = null;

  function ensure() {
    if (modal) return;

    backdrop = document.createElement("div");
    backdrop.className = "modal-backdrop";
    backdrop.addEventListener("click", () => close(false));

    modal = document.createElement("div");
    modal.className = "modal";

    modal.innerHTML = `
      <div class="modal-card" role="dialog" aria-modal="true" aria-labelledby="mTitle">
        <div class="modal-title" id="mTitle"></div>
        <div class="modal-msg"></div>
        <div class="modal-actions">
          <button class="btn btn-secundario" data-cancel type="button">Cancelar</button>
          <button class="btn btn-primario" data-ok type="button">Confirmar</button>
        </div>
      </div>
    `;

    titleEl = modal.querySelector(".modal-title");
    msgEl = modal.querySelector(".modal-msg");
    btnOk = modal.querySelector("[data-ok]");
    btnCancel = modal.querySelector("[data-cancel]");

    btnOk.addEventListener("click", () => close(true));
    btnCancel.addEventListener("click", () => close(false));

    document.addEventListener("keydown", (e) => {
      if (!modal.classList.contains("open")) return;
      if (e.key === "Escape") close(false);
    });

    document.body.appendChild(backdrop);
    document.body.appendChild(modal);
  }

  function open({ title="Confirmación", message="¿Continuar?", okText="Confirmar", cancelText="Cancelar" } = {}) {
    ensure();
    titleEl.textContent = title;
    msgEl.textContent = message;
    btnOk.textContent = okText;
    btnCancel.textContent = cancelText;

    backdrop.classList.add("open");
    modal.classList.add("open");
    btnOk.focus();

    return new Promise((resolve) => {
      resolver = resolve;
    });
  }

  function close(result) {
    if (!modal) return;
    backdrop.classList.remove("open");
    modal.classList.remove("open");
    if (resolver) resolver(!!result);
    resolver = null;
  }

  return { open };
})();
