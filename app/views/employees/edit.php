<div class="page-head">
  <h1>Editar empleado</h1>
  <p class="muted"><?= htmlspecialchars($emp['apellidos'] . ', ' . $emp['nombre']) ?></p>
</div>

<div class="card">
  <form method="post" action="<?= $_base_url ?>/?r=empleados/editar" enctype="multipart/form-data">
    <input type="hidden" name="csrf" value="<?= htmlspecialchars($csrf) ?>">
    <input type="hidden" name="id" value="<?= (int)$emp['id'] ?>">

    <div class="form-grid">
      <div>
        <label>Nombre</label>
        <input class="input" name="nombre" value="<?= htmlspecialchars($emp['nombre']) ?>" required>
      </div>
      <div>
        <label>Apellidos</label>
        <input class="input" name="apellidos" value="<?= htmlspecialchars($emp['apellidos']) ?>" required>
      </div>

      <div>
        <label>Email</label>
        <input class="input" type="email" name="email" value="<?= htmlspecialchars($emp['email']) ?>" required>
      </div>
      <div>
        <label>Teléfono</label>
        <input class="input" name="telefono" value="<?= htmlspecialchars($emp['telefono'] ?? '') ?>">
      </div>

      <div>
        <label>Dirección</label>
        <input class="input" name="direccion" value="<?= htmlspecialchars($emp['direccion'] ?? '') ?>">
      </div>
      <div>
        <label>Fecha nacimiento</label>
        <input class="input" type="date" name="fecha_nacimiento" value="<?= htmlspecialchars($emp['fecha_nacimiento'] ?? '') ?>">
      </div>

      <div>
        <label>Departamento</label>
        <select class="input" name="department_id" required>
          <?php foreach ($deps as $d): ?>
            <option value="<?= (int)$d['id'] ?>" <?= ((int)$emp['department_id'] === (int)$d['id']) ? 'selected' : '' ?>>
              <?= htmlspecialchars($d['nombre']) ?>
            </option>
          <?php endforeach; ?>
        </select>
      </div>

      <div>
        <label>Puesto</label>
        <select class="input" name="position_id" required>
          <?php foreach ($pos as $p): ?>
            <option value="<?= (int)$p['id'] ?>" <?= ((int)$emp['position_id'] === (int)$p['id']) ? 'selected' : '' ?>>
              <?= htmlspecialchars($p['departamento_nombre'] . ' — ' . $p['nombre']) ?>
            </option>
          <?php endforeach; ?>
        </select>
      </div>

      <div>
        <label>Fecha ingreso</label>
        <input class="input" type="date" name="fecha_ingreso" value="<?= htmlspecialchars($emp['fecha_ingreso']) ?>" required>
      </div>

      <div>
        <label>Salario base</label>
        <input class="input" type="number" step="0.01" name="salario_base" value="<?= htmlspecialchars($emp['salario_base']) ?>" required>
      </div>

      <div>
        <label>Contacto emergencia</label>
        <input class="input" name="emerg_nombre" value="<?= htmlspecialchars($emp['contacto_emergencia_nombre'] ?? '') ?>">
      </div>

      <div>
        <label>Tel. emergencia</label>
        <input class="input" name="emerg_tel" value="<?= htmlspecialchars($emp['contacto_emergencia_telefono'] ?? '') ?>">
      </div>

      <div>
        <label>Foto (opcional)</label>
        <input class="input" type="file" name="foto" accept="image/*">
        <?php if (!empty($emp['foto_path'])): ?>
          <small class="muted">Actual: <?= htmlspecialchars($emp['foto_path']) ?></small>
        <?php endif; ?>
      </div>

      <div>
        <label>Estado</label>
        <select class="input" name="estado">
          <?php foreach (['activo','baja','suspendido'] as $e): ?>
            <option value="<?= $e ?>" <?= (($emp['estado'] ?? '') === $e) ? 'selected' : '' ?>><?= $e ?></option>
          <?php endforeach; ?>
        </select>
      </div>
    </div>

    <div style="margin-top:1rem; display:flex; gap:.6rem;">
      <button class="btn btn-primario" type="submit">Guardar cambios</button>
      <a class="btn btn-secundario" href="<?= $_base_url ?>/?r=empleados/ver&id=<?= (int)$emp['id'] ?>">Cancelar</a>
    </div>
  </form>
</div>
