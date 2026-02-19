<div class="page-head">
  <h1>Mis solicitudes</h1>
  <p class="muted">Historial</p>
</div>

<div class="card">
  <a class="btn btn-primario" href="<?= $_base_url ?>/?r=solicitudes/createForm">+ Nueva solicitud</a>
</div>

<div class="table-wrap" style="margin-top:1rem;">
  <table>
    <thead><tr><th>Tipo</th><th>Inicio</th><th>Fin</th><th>Estado</th><th>Creada</th></tr></thead>
    <tbody>
      <?php foreach ($rows as $r): ?>
        <tr>
          <td><?= htmlspecialchars($r['tipo']) ?></td>
          <td><?= htmlspecialchars($r['fecha_inicio']) ?></td>
          <td><?= htmlspecialchars($r['fecha_fin']) ?></td>
          <td><?= htmlspecialchars($r['estado']) ?></td>
          <td><?= htmlspecialchars($r['created_at']) ?></td>
        </tr>
      <?php endforeach; ?>
      <?php if (!$rows): ?><tr><td colspan="5">Sin solicitudes</td></tr><?php endif; ?>
    </tbody>
  </table>
</div>
