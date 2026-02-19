// public/assets/js/attendance.js
(() => {
  const btn = document.getElementById("btnFichar");
  if (!btn) return;

  const box = document.getElementById("estadoFichaje");
  const csrf = document.getElementById("csrf")?.value || "";

  function render(data) {
    if (!box) return;
    box.style.display = "block";
    const salida = data.salida ? data.salida : "-";
    box.innerHTML = `
      <div style="display:flex; justify-content:space-between; gap:1rem; flex-wrap:wrap;">
        <div>
          <div class="meta">Fecha</div>
          <div><strong>${data.fecha || "-"}</strong></div>
        </div>
        <div>
          <div class="meta">Entrada</div>
          <div><strong>${data.entrada || "-"}</strong></div>
        </div>
        <div>
          <div class="meta">Salida</div>
          <div><strong>${salida}</strong></div>
        </div>
        <div>
          <div class="meta">Minutos</div>
          <div><strong>${data.minutos ?? 0}</strong></div>
        </div>
      </div>
    `;
  }

  btn.addEventListener("click", async () => {
    btn.disabled = true;

    try {
      const BASE = window.__BASE_URL__ || "";
      const url = `${BASE}/index.php?r=asistencia/ajaxClock`;

      const res = await fetch(url, {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ csrf })
      });

      let data = null;
      try { data = await res.json(); } catch { data = null; }

      if (!res.ok || !data || !data.ok) {
        const msg = (data && (data.error || data.mensaje))
          ? (data.error || data.mensaje)
          : `Error de fichaje (${res.status})`;
        window.Toast?.show({ title: "Error", message: msg, type: "err" });
        return;
      }

      window.Toast?.show({ title: "OK", message: data.mensaje || "Registrado", type: "ok" });
      render(data);

    } catch {
      window.Toast?.show({ title: "Error", message: "Fallo de red", type: "err" });
    } finally {
      btn.disabled = false;
    }
  });
})();
