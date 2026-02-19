<div class="page-head">
  <h1>Edición manual fichaje</h1>
  <p class="muted"><?= htmlspecialchars($row['apellidos'] . ', ' . $row['nombre']) ?> · <?= htmlspecialchars($row['fecha']) ?></p>
</div>

<div class="card">
  <form method="post" action="<?= $_base_url ?>/?r=asistencia/edit">
    <input type="hidden" name="csrf" value="<?= htmlspecialchars($csrf) ?>">
    <input type="hidden" name="id" value="<?= (int)$row['id'] ?>">

    <div class="form-grid">
      <div>
        <label>Hora entrada (YYYY-MM-DD HH:MM:SS)</label>
        <input class="input" name="hora_entrada" value="<?= htmlspecialchars($row['hora_entrada'] ?? '') ?>">
      </div>
      <div>
        <label>Hora salida (YYYY-MM-DD HH:MM:SS)</label>
        <input class="input" name="hora_salida" value="<?= htmlspecialchars($row['hora_salida'] ?? '') ?>">
      </div>

      <div>
        <label>Estado</label>
        <select class="input" name="estado">
          <?php foreach (['a_tiempo','tardanza','ausente','justificado'] as $e): ?>
            <option value="<?= $e ?><?= (($row['estado'] ?? '') === $e) ? 'selected' : '' ?>><?= $e ?></option>
          <?php endforeach; ?>
        </select>
      </div>

      <div>
        <label>Motivo</label>
        <input class="input" name="motivo" placeholder="Justificación">
      </div>
    </div>

    <div style="margin-top:1rem; display:flex; gap:.6rem;">
      <button class="btn btn-primario" type="submit">Guardar</button>
      <a class="btn btn-secundario" href="<?= $_base_url ?>/?r=asistencia/daily">Cancelar</a>

    </div>
  </form>
</div>
