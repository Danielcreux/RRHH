<div class="page-head">
  <h1>Generar nómina</h1>
  <p class="muted">Draft inicial</p>
</div>

<?php if (!empty($error)): ?>
  <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
<?php endif; ?>

<div class="card">
  <form method="post" action="<?= $_base_url ?>/?r=nominas/crear">
    <input type="hidden" name="csrf" value="<?= htmlspecialchars($csrf) ?>">

    <div class="form-grid">
      <div>
        <label>Empleado</label>
        <select class="input" name="employee_id" required>
          <option value="">Selecciona</option>
          <?php foreach ($emps as $e): ?>
            <option value="<?= (int)$e['id'] ?>"><?= htmlspecialchars($e['apellidos'] . ', ' . $e['nombre']) ?></option>
          <?php endforeach; ?>
        </select>
      </div>

      <div>
        <label>Periodo (mes/año)</label>
        <div style="display:flex; gap:.6rem;">
          <input class="input" type="number" name="periodo_mes" min="1" max="12" placeholder="Mes" required>
          <input class="input" type="number" name="periodo_anio" min="2000" placeholder="Año" required>
        </div>
      </div>

      <div>
        <label>Salario base</label>
        <input class="input" type="number" step="0.01" name="salario_base" required>
      </div>

      <div>
        <label>Bonos</label>
        <input class="input" type="number" step="0.01" name="bonos" value="0">
      </div>

      <div>
        <label>Deducciones</label>
        <input class="input" type="number" step="0.01" name="deducciones" value="0">
      </div>
    </div>

    <div style="margin-top:1rem;">
      <button class="btn btn-primario" type="submit">Crear nómina</button>
      <a class="btn btn-secundario" href="<?= $_base_url ?>/?r=nominas/listar">Cancelar</a>
    </div>
  </form>
</div>
