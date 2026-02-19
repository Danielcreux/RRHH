<div class="page-head">
  <h1>TalentIA</h1>
  <p class="muted">Gestión de candidatos (módulo interno)</p>
</div>

<div class="card" style="display:flex; gap:.6rem; flex-wrap:wrap; align-items:center;">
  <a class="btn btn-secundario" href="<?= $_base_url ?>/?r=dashboard/index">← Volver al dashboard</a>
  <a class="btn btn-primario" href="<?= $_base_url ?>/?r=talentia/createForm">+ Nuevo candidato</a>
</div>

<div class="card" style="margin-top:1rem;">
  <h3 style="margin-top:0;">Candidatos</h3>

  <div class="table-wrap" style="margin-top:.8rem;">
    <table>
      <thead>
        <tr>
          <th>Nombre</th>
          <th>Email</th>
          <th>Teléfono</th>
          <th>Experiencia</th>
          <th>Registro</th>
          <th>Acción</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach (($rows ?? []) as $r): ?>
          <tr>
            <td><?= htmlspecialchars($r['nombre']) ?></td>
            <td><?= htmlspecialchars($r['email'] ?? '-') ?></td>
            <td><?= htmlspecialchars($r['telefono'] ?? '-') ?></td>
            <td><?= htmlspecialchars(($r['experiencia_anios'] ?? '-') . ' años') ?></td>
            <td><?= htmlspecialchars($r['fecha_registro'] ?? '-') ?></td>
            <td style="display:flex; gap:.4rem; flex-wrap:wrap;">
              <a class="btn btn-secundario" href="<?= $_base_url ?>/?r=talentia/ver&id=<?= (int)$r['id'] ?>">Ver</a>

              <form method="post" action="<?= $_base_url ?>/?r=talentia/delete" onsubmit="return confirm('¿Eliminar candidato?');">
                <input type="hidden" name="csrf" value="<?= htmlspecialchars($csrf) ?>">
                <input type="hidden" name="id" value="<?= (int)$r['id'] ?>">
                <button class="btn btn-peligro" type="submit">Eliminar</button>
              </form>
            </td>
          </tr>
        <?php endforeach; ?>

        <?php if (empty($rows)): ?>
          <tr><td colspan="6">No hay candidatos aún.</td></tr>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</div>
