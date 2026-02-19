<div class="page-head">
  <h1>Empleados</h1>
  <p class="muted">Listado y gestión</p>
</div>

<div class="card">
  <form method="get" action="<?= $_base_url ?>/index.php" class="form-inline">
    <input type="hidden" name="r" value="empleados/listar">

    <input class="input" name="q" placeholder="Buscar por nombre/email" value="<?= htmlspecialchars($filters['q'] ?? '') ?>" style="max-width:320px;">

    <select name="department_id" class="input" style="max-width:220px;">
      <option value="">Departamento</option>
      <?php foreach ($deps as $d): ?>
        <option value="<?= (int)$d['id'] ?>" <?= ((int)($filters['department_id'] ?? 0) === (int)$d['id']) ? 'selected' : '' ?>>
          <?= htmlspecialchars($d['nombre']) ?>
        </option>
      <?php endforeach; ?>
    </select>

    <select name="estado" class="input" style="max-width:180px;">
      <option value="">Estado</option>
      <?php foreach (['activo','baja','suspendido'] as $e): ?>
        <option value="<?= $e ?>" <?= (($filters['estado'] ?? '') === $e) ? 'selected' : '' ?>><?= $e ?></option>
      <?php endforeach; ?>
    </select>

    <button class="btn btn-secundario" type="submit">Filtrar</button>
    <a class="btn btn-primario" href="<?= $_base_url ?>/?r=empleados/crearForm">+ Nuevo</a>
  </form>
</div>

<div class="table-wrap" style="margin-top:1rem;">
  <table>
    <thead>
      <tr>
        <th>#</th>
        <th>Empleado</th>
        <th>Email</th>
        <th>Departamento</th>
        <th>Puesto</th>
        <th>Estado</th>
        <th style="width:220px;">Acciones</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($rows as $r): ?>
        <tr>
          <td><?= (int)$r['id'] ?></td>
          <td><?= htmlspecialchars($r['apellidos'] . ', ' . $r['nombre']) ?></td>
          <td><?= htmlspecialchars($r['email']) ?></td>
          <td><?= htmlspecialchars($r['departamento']) ?></td>
          <td><?= htmlspecialchars($r['puesto']) ?></td>
          <td><?= htmlspecialchars($r['estado']) ?></td>
          <td>
            <a class="btn btn-secundario" href="<?= $_base_url ?>/?r=empleados/ver&id=<?= (int)$r['id'] ?>">Ver</a>
<a class="btn btn-secundario" href="<?= $_base_url ?>/?r=empleados/editarForm&id=<?= (int)$r['id'] ?>">Editar</a>


            <?php if (($_user['rol'] ?? '') === 'super_admin'): ?>
              <form method="post" action="<?= $_base_url ?>/?r=empleados/eliminar" style="display:inline;" data-confirm="¿Eliminar empleado? Esta acción no se puede deshacer.">
                  <input type="hidden" name="csrf" value="<?= htmlspecialchars($csrf) ?>">
                  <input type="hidden" name="id" value="<?= (int)$r['id'] ?>">
                  <button class="btn btn-peligro" type="submit">Eliminar</button>
            </form>
            <?php endif; ?>
          </td>
        </tr>
      <?php endforeach; ?>
      <?php if (!$rows): ?>
        <tr><td colspan="7">Sin resultados</td></tr>
      <?php endif; ?>
    </tbody>
  </table>
</div>
