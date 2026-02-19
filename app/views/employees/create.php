<div class="page-head">
  <h1>Nuevo empleado</h1>
  <p class="muted">Alta de empleado</p>
</div>

<?php if (!empty($error)): ?>
  <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
<?php endif; ?>

<div class="card">
  <form method="post" action="<?= $_base_url ?>/?r=empleados/crear" enctype="multipart/form-data">
    <input type="hidden" name="csrf" value="<?= htmlspecialchars($csrf) ?>">

    <div class="form-grid">
      <div>
        <label>Nombre</label>
        <input class="input" name="nombre" required>
      </div>
      <div>
        <label>Apellidos</label>
        <input class="input" name="apellidos" required>
      </div>

      <div>
        <label>Email</label>
        <input class="input" type="email" name="email" required>
      </div>
      <div>
        <label>Teléfono</label>
        <input class="input" name="telefono">
      </div>

      <div>
        <label>Dirección</label>
        <input class="input" name="direccion">
      </div>
      <div>
        <label>Fecha nacimiento</label>
        <input class="input" type="date" name="fecha_nacimiento">
      </div>

      <div>
        <label>Departamento</label>
        <select class="input" name="department_id" required>
          <option value="">Selecciona</option>
          <?php foreach ($deps as $d): ?>
            <option value="<?= (int)$d['id'] ?>"><?= htmlspecialchars($d['nombre']) ?></option>
          <?php endforeach; ?>
        </select>
      </div>

      <div>
        <label>Puesto</label>
        <select class="input" name="position_id" required>
          <option value="">Selecciona</option>
          <?php foreach ($pos as $p): ?>
            <option value="<?= (int)$p['id'] ?>"><?= htmlspecialchars($p['departamento_nombre'] . ' — ' . $p['nombre']) ?></option>
          <?php endforeach; ?>
        </select>
      </div>

      <div>
        <label>Fecha ingreso</label>
        <input class="input" type="date" name="fecha_ingreso" required>
      </div>

      <div>
        <label>Salario base</label>
        <input class="input" type="number" step="0.01" name="salario_base" required>
      </div>

      <div>
        <label>Contacto emergencia</label>
        <input class="input" name="emerg_nombre">
      </div>

      <div>
        <label>Tel. emergencia</label>
        <input class="input" name="emerg_tel">
      </div>

      <div>
        <label>Foto</label>
        <input class="input" type="file" name="foto" accept="image/*">
      </div>

      <div>
        <label>Estado</label>
        <select class="input" name="estado">
          <option value="activo">activo</option>
          <option value="baja">baja</option>
          <option value="suspendido">suspendido</option>
        </select>
      </div>
    </div>

    <div style="margin-top:1rem; display:flex; gap:.6rem;">
      <button class="btn btn-primario" type="submit">Guardar</button>
      <a class="btn btn-secundario" href="<?= $_base_url ?>/?r=empleados/listar">Cancelar</a>
    </div>
  </form>
</div>
