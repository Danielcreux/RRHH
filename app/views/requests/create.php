<div class="page-head">
  <h1>Nueva solicitud</h1>
  <p class="muted">Vacaciones / Permisos / Día personal</p>
</div>

<?php if (!empty($error)): ?>
  <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
<?php endif; ?>

<div class="card">
  <form method="post" action="<?= $_base_url ?>/?r=solicitudes/create">
    <input type="hidden" name="csrf" value="<?= htmlspecialchars($csrf) ?>">

    <div class="form-grid">
      <div>
        <label>Tipo</label>
        <select class="input" name="tipo">
          <option value="vacaciones">vacaciones</option>
          <option value="permiso">permiso</option>
          <option value="dia_personal">día personal</option>
        </select>
      </div>

      <div>
        <label>Motivo</label>
        <input class="input" name="motivo" placeholder="Opcional">
      </div>

      <div>
        <label>Fecha inicio</label>
        <input class="input" type="date" name="fecha_inicio" required>
      </div>

      <div>
        <label>Fecha fin</label>
        <input class="input" type="date" name="fecha_fin" required>
      </div>
    </div>

    <div style="margin-top:1rem;">
      <button class="btn btn-primario" type="submit">Enviar</button>
      <a class="btn btn-secundario" href="<?= $_base_url ?>/?r=solicitudes/my">Cancelar</a>

    </div>
  </form>
</div>
