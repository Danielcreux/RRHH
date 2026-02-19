<div class="page-head">
  <h1>Calendario de ausencias</h1>
  <p class="muted">Aprobadas entre <?= htmlspecialchars($from) ?> y <?= htmlspecialchars($to) ?></p>
</div>

<div class="card">
  <form method="get" action="<?= $_base_url ?>/index.php" class="form-inline">
    <input type="hidden" name="r" value="solicitudes/calendar">
    <input class="input" type="date" name="from" value="<?= htmlspecialchars($from) ?>" style="max-width:200px;">
    <input class="input" type="date" name="to" value="<?= htmlspecialchars($to) ?>" style="max-width:200px;">
    <button class="btn btn-secundario" type="submit">Actualizar</button>
  </form>
</div>

<div class="table-wrap" style="margin-top:1rem;">
  <table>
    <thead><tr><th>Empleado</th><th>Tipo</th><th>Inicio</th><th>Fin</th></tr></thead>
    <tbody>
      <?php foreach ($rows as $r): ?>
        <tr>
          <td><?= htmlspecialchars($r['apellidos'] . ', ' . $r['nombre']) ?></td>
          <td><?= htmlspecialchars($r['tipo']) ?></td>
          <td><?= htmlspecialchars($r['fecha_inicio']) ?></td>
          <td><?= htmlspecialchars($r['fecha_fin']) ?></td>
        </tr>
      <?php endforeach; ?>
      <?php if (!$rows): ?><tr><td colspan="4">Sin ausencias en rango</td></tr><?php endif; ?>
    </tbody>
  </table>
</div>
