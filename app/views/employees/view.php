<div class="page-head">
  <h1>Perfil de empleado</h1>
  <p class="muted"><?= htmlspecialchars($emp['apellidos'] . ', ' . $emp['nombre']) ?></p>
</div>

<div class="card">
  <div style="display:flex; gap:1rem; align-items:center; flex-wrap:wrap;">
    <div style="width:72px; height:72px; border-radius:999px; overflow:hidden; border:1px solid rgba(0,0,0,.08);">
      <?php if (!empty($emp['foto_path'])): ?>
        <img src="<?= htmlspecialchars($emp['foto_path']) ?>" alt="foto" style="width:100%; height:100%; object-fit:cover;">
      <?php else: ?>
        <div style="width:100%; height:100%; display:grid; place-items:center; background:#f5f5f5;">👤</div>
      <?php endif; ?>
    </div>

    <div>
      <h3 style="margin:0;"><?= htmlspecialchars($emp['nombre'] . ' ' . $emp['apellidos']) ?></h3>
      <div class="meta"><?= htmlspecialchars($emp['departamento'] . ' — ' . $emp['puesto']) ?></div>
      <div class="meta"><?= htmlspecialchars($emp['email']) ?> · <?= htmlspecialchars($emp['telefono'] ?? '-') ?></div>
    </div>

    <?php if (in_array($_user['rol'] ?? '', ['super_admin','rrhh'], true)): ?>
      <div style="margin-left:auto; display:flex; gap:.6rem;">
        <a class="btn btn-secundario" href="<?= $_base_url ?>/?r=empleados/editarForm&id=<?= (int)$emp['id'] ?>">Editar</a>
      </div>
    <?php endif; ?>
  </div>
</div>

<div class="grid-2" style="margin-top:1rem;">
  <div class="card">
    <h3>Información</h3>
    <div class="meta">Fecha ingreso: <?= htmlspecialchars($emp['fecha_ingreso']) ?></div>
    <div class="meta">Salario base: <?= htmlspecialchars($emp['salario_base']) ?></div>
    <div class="meta">Estado: <?= htmlspecialchars($emp['estado']) ?></div>
    <div class="meta">Dirección: <?= htmlspecialchars($emp['direccion'] ?? '-') ?></div>
  </div>

  <div class="card">
    <h3>Emergencia</h3>
    <div class="meta"><?= htmlspecialchars($emp['contacto_emergencia_nombre'] ?? '-') ?></div>
    <div class="meta"><?= htmlspecialchars($emp['contacto_emergencia_telefono'] ?? '-') ?></div>
  </div>
</div>

<div class="grid-2" style="margin-top:1rem;">
  <div class="card">
    <h3>Últimas asistencias</h3>
    <div class="table-wrap">
      <table style="min-width:520px;">
        <thead><tr><th>Fecha</th><th>Entrada</th><th>Salida</th><th>Min</th></tr></thead>
        <tbody>
          <?php foreach ($asist as $a): ?>
            <tr>
              <td><?= htmlspecialchars($a['fecha']) ?></td>
              <td><?= htmlspecialchars($a['hora_entrada'] ?? '-') ?></td>
              <td><?= htmlspecialchars($a['hora_salida'] ?? '-') ?></td>
              <td><?= (int)($a['minutos_trabajados'] ?? 0) ?></td>
            </tr>
          <?php endforeach; ?>
          <?php if (!$asist): ?><tr><td colspan="4">Sin registros</td></tr><?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>

  <div class="card">
    <h3>Nóminas</h3>
    <div class="table-wrap">
      <table style="min-width:520px;">
        <thead><tr><th>Periodo</th><th>Total</th><th>Estado</th><th></th></tr></thead>
        <tbody>
          <?php foreach ($nominas as $n): ?>
            <tr>
              <td><?= (int)$n['periodo_mes'] ?>/<?= (int)$n['periodo_anio'] ?></td>
              <td><?= htmlspecialchars($n['total']) ?></td>
              <td><?= htmlspecialchars($n['estado']) ?></td>
              <td><a class="btn btn-secundario" href="<?= $_base_url ?>/?r=nominas/ver&id=<?= (int)$n['id'] ?>">Ver</a>
</td>
            </tr>
          <?php endforeach; ?>
          <?php if (!$nominas): ?><tr><td colspan="4">Sin nóminas</td></tr><?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>

<div class="card" style="margin-top:1rem;">
  <h3>Documentos</h3>
  <p class="muted">Subida de documentos se implementa en fase 2/3 (endpoint + validación MIME).</p>
  <ul>
    <?php foreach ($docs as $d): ?>
      <li><?= htmlspecialchars($d['nombre']) ?> — <?= htmlspecialchars($d['created_at']) ?></li>
    <?php endforeach; ?>
    <?php if (!$docs): ?><li>Sin documentos</li><?php endif; ?>
  </ul>
</div>
