<?php if (empty($rows)): ?>
    <p>No hay solicitudes pendientes.</p>
<?php else: ?>
    <input type="hidden" id="csrf" value="<?= htmlspecialchars($csrf) ?>">
    <table>
        <thead>
            <tr>
                <th>Empleado</th>
                <th>Tipo</th>
                <th>Inicio</th>
                <th>Fin</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($rows as $r): ?>
            <tr data-id="<?= $r['id'] ?>">
                <td><?= htmlspecialchars($r['apellidos'] . ', ' . $r['nombre']) ?></td>
                <td><?= htmlspecialchars($r['tipo']) ?></td>
                <td><?= htmlspecialchars($r['fecha_inicio']) ?></td>
                <td><?= htmlspecialchars($r['fecha_fin']) ?></td>
                <td>
                    <button class="btn btn-success" data-action="approve">Aprobar</button>
                    <button class="btn btn-danger" data-action="reject">Rechazar</button>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php endif; ?>

<script>
document.addEventListener('click', async (e) => {
  const btn = e.target.closest('button[data-action]');
  if (!btn) return;

  const tr = btn.closest('tr[data-id]');
  if (!tr) return;

  const id = Number(tr.dataset.id || 0);
  if (!id) return;

  const accion = btn.dataset.action; // approve | reject
  const csrf = document.getElementById('csrf')?.value || '';
  const comentario = ''; // Sin comentario

  const payload = { csrf, id, accion, comentario };

  btn.disabled = true;
  try {
    const BASE = window.__BASE_URL__ || "";
    const url = `${BASE}/index.php?r=solicitudes/ajaxApprove`;

    const res = await fetch(url, {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify(payload)
    });

    let data = null;
    try { data = await res.json(); } catch { data = null; }

    if (!res.ok || !data || !data.ok) {
      const msg = (data && (data.error || data.mensaje))
        ? (data.error || data.mensaje)
        : `Error (${res.status})`;
      window.Toast?.show
        ? window.Toast.show({ title: "Error", message: msg, type: "err" })
        : alert(msg);
      return;
    }

    tr.remove();
    window.Toast?.show
      ? window.Toast.show({ title: "OK", message: data.mensaje || "Actualizado", type: "ok" })
      : null;

  } catch {
    window.Toast?.show
      ? window.Toast.show({ title: "Error", message: "Fallo de red", type: "err" })
      : alert("Fallo de red");
  } finally {
    btn.disabled = false;
  }
});
</script>