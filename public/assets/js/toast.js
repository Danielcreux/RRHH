// public/assets/js/toast.js
window.Toast = (() => {
  const stackId = "toastStack";

  function ensureStack() {
    let el = document.getElementById(stackId);
    if (!el) {
      el = document.createElement("div");
      el.id = stackId;
      el.className = "toast-stack";
      document.body.appendChild(el);
    }
    return el;
  }

  function show({ title = "Info", message = "", type = "" , timeout = 2600 } = {}) {
    const stack = ensureStack();
    const t = document.createElement("div");
    t.className = `toast ${type}`.trim();

    t.innerHTML = `
      <div class="t-title"></div>
      <div class="t-msg"></div>
    `;
    t.querySelector(".t-title").textContent = title;
    t.querySelector(".t-msg").textContent = message;

    stack.appendChild(t);

    const kill = () => {
      t.style.opacity = "0";
      t.style.transform = "translateY(6px)";
      setTimeout(() => t.remove(), 180);
    };

    const timer = setTimeout(kill, timeout);
    t.addEventListener("click", () => {
      clearTimeout(timer);
      kill();
    });
  }

  return { show };
})();
