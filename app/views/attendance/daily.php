<div class="page-head">
  <h1>Asistencia diaria</h1>
  <p class="muted">Fecha: <?= htmlspecialchars($fecha) ?></p>
</div>

<div class="card">
  <form method="get" action="<?= $_base_url ?>/index.php" class="form-inline">
    <input type="hidden" name="r" value="asistencia/daily">
    <input class="input" type="date" name="fecha" value="<?= htmlspecialchars($fecha) ?>" style="max-width:200px;">
    <select name="department_id" class="input" style="max-width:220px;">
      <option value="">Departamento</option>
      <?php foreach ($deps as $d): ?>
        <option value="<?= (int)$d['id'] ?>" <?= ((int)($filters['department_id'] ?? 0) === (int)$d['id']) ? 'selected' : '' ?>>
          <?= htmlspecialchars($d['nombre']) ?>
        </option>
      <?php endforeach; ?>
    </select>
    <button class="btn btn-secundario" type="submit">Filtrar</button>
  </form>
</div>

<div class="table-wrap" style="margin-top:1rem;">
  <table>
    <thead>
      <tr>
        <th>Empleado</th>
        <th>Departamento</th>
        <th>Entrada</th>
        <th>Salida</th>
        <th>Minutos</th>
        <th>Estado</th>
        <?php if (($_user['rol'] ?? '') === 'super_admin'): ?><th>Acción</th><?php endif; ?>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($rows as $r): ?>
        <tr>
          <td><?= htmlspecialchars($r['apellidos'] . ', ' . $r['nombre']) ?></td>
          <td><?= htmlspecialchars($r['departamento']) ?></td>
          <td><?= htmlspecialchars($r['hora_entrada'] ?? '-') ?></td>
          <td><?= htmlspecialchars($r['hora_salida'] ?? '-') ?></td>
          <td><?= (int)($r['minutos_trabajados'] ?? 0) ?></td>
          <td><?= htmlspecialchars($r['estado']) ?></td>
          <?php if (($_user['rol'] ?? '') === 'super_admin'): ?>
            <td><a class="btn btn-secundario" href="<?= $_base_url ?>/?r=asistencia/editForm&id=<?= (int)$r['id'] ?>"
>Editar</a></td>
          <?php endif; ?>
        </tr>
      <?php endforeach; ?>
      <?php if (!$rows): ?><tr><td colspan="7">Sin registros</td></tr><?php endif; ?>
    </tbody>
  </table>
</div>
