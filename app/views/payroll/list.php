<div class="page-head">
  <h1>Nóminas</h1>
  <p class="muted">Listado por período</p>
</div>

<div class="card">
  <form method="get" action="<?= $_base_url ?>/index.php" class="form-inline">

    <input type="hidden" name="r" value="nominas/listar">
    <input class="input" name="anio" placeholder="Año" value="<?= htmlspecialchars($filters['anio'] ?? '') ?>" style="max-width:120px;">
    <input class="input" name="mes" placeholder="Mes" value="<?= htmlspecialchars($filters['mes'] ?? '') ?>" style="max-width:120px;">
    <button class="btn btn-secundario" type="submit">Filtrar</button>
    <a class="btn btn-primario" href="<?= $_base_url ?>/?r=nominas/crearForm">+ Generar</a>
  </form>
</div>

<div class="table-wrap" style="margin-top:1rem;">
  <table>
    <thead>
      <tr><th>Periodo</th><th>Empleado</th><th>Total</th><th>Estado</th><th></th></tr>
    </thead>
    <tbody>
      <?php foreach ($rows as $r): ?>
        <tr>
          <td><?= (int)$r['periodo_mes'] ?>/<?= (int)$r['periodo_anio'] ?></td>
          <td><?= htmlspecialchars($r['apellidos'] . ', ' . $r['nombre']) ?></td>
          <td><?= htmlspecialchars($r['total']) ?></td>
          <td><?= htmlspecialchars($r['estado']) ?></td>
          <td><a class="btn btn-secundario" href="<?= $_base_url ?>/?r=nominas/ver&id=<?= (int)$r['id'] ?>">Ver</a></td>
        </tr>
      <?php endforeach; ?>
      <?php if (!$rows): ?><tr><td colspan="5">Sin resultados</td></tr><?php endif; ?>
    </tbody>
  </table>
</div>
