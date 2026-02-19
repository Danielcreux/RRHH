<div class="page-head">
  <h1>Nómina</h1>
  <p class="muted"><?= htmlspecialchars($row['apellidos'] . ', ' . $row['nombre']) ?> · <?= (int)$row['periodo_mes'] ?>/<?= (int)$row['periodo_anio'] ?></p>
</div>

<div class="card">
  <div class="grid-2">
    <div>
      <div class="meta">Salario base: <strong><?= htmlspecialchars($row['salario_base']) ?></strong></div>
      <div class="meta">Bonos: <strong><?= htmlspecialchars($row['bonos']) ?></strong></div>
      <div class="meta">Deducciones: <strong><?= htmlspecialchars($row['deducciones']) ?></strong></div>
      <div class="meta">Total: <strong><?= htmlspecialchars($row['total']) ?></strong></div>
    </div>
    <div>
      <div class="meta">Estado: <strong><?= htmlspecialchars($row['estado']) ?></strong></div>
      <div class="meta">Creada: <?= htmlspecialchars($row['created_at']) ?></div>
    </div>
  </div>

  <?php if (in_array($_user['rol'] ?? '', ['super_admin','rrhh'], true)): ?>
    <form method="post" action="<?= $_base_url ?>/?r=nominas/approve" style="margin-top:1rem; display:flex; gap:.6rem;">
      <input type="hidden" name="csrf" value="<?= htmlspecialchars($csrf) ?>">
      <input type="hidden" name="id" value="<?= (int)$row['id'] ?>">
      <?php if ($row['estado'] === 'draft'): ?>
        <button class="btn btn-primario" type="submit" name="accion" value="approve">Aprobar</button>
      <?php elseif ($row['estado'] === 'approved'): ?>
        <button class="btn btn-primario" type="submit" name="accion" value="paid">Marcar como pagada</button>
      <?php endif; ?>
      <a class="btn btn-secundario" href="<?= $_base_url ?>/?r=nominas/listar">Volver</a>
    </form>
  <?php endif; ?>
</div>
