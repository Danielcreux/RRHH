<div class="page-head">
  <h1>Nuevo candidato</h1>
  <p class="muted">Alta manual (luego añadimos subir PDF)</p>
</div>

<?php if (!empty($error)): ?>
  <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
<?php endif; ?>

<div class="card">
  <form method="post" action="<?= $_base_url ?>/?r=talentia/create" class="form">
    <input type="hidden" name="csrf" value="<?= htmlspecialchars($csrf) ?>">

    <div class="grid" style="grid-template-columns:repeat(auto-fit,minmax(220px,1fr)); gap:12px;">
      <div class="form-group">
        <label>Nombre *</label>
        <input class="input" name="nombre" required>
      </div>

      <div class="form-group">
        <label>Email</label>
        <input class="input" name="email">
      </div>

      <div class="form-group">
        <label>Teléfono</label>
        <input class="input" name="telefono">
      </div>

      <div class="form-group">
        <label>Experiencia (años)</label>
        <input class="input" name="experiencia_anios" type="number" min="0">
      </div>

      <div class="form-group">
        <label>Edad</label>
        <input class="input" name="edad" type="number" min="0">
      </div>

      <div class="form-group">
        <label>Puesto actual</label>
        <input class="input" name="puesto_actual">
      </div>

      <div class="form-group">
        <label>Nivel inglés</label>
        <input class="input" name="nivel_ingles" placeholder="A1-A2-B1-B2-C1-C2">
      </div>

      <div class="form-group">
        <label>Skills (separadas por coma)</label>
        <input class="input" name="skills">
      </div>

      <div class="form-group">
        <label>Idiomas (separados por coma)</label>
        <input class="input" name="idiomas">
      </div>
    </div>

    <div class="form-group" style="margin-top:12px;">
      <label>Texto CV (opcional)</label>
      <textarea class="input" name="cv_text" rows="6"></textarea>
    </div>

    <div style="display:flex; gap:.6rem; margin-top:12px;">
      <a class="btn btn-secundario" href="<?= $_base_url ?>/?r=talentia/index">Cancelar</a>
      <button class="btn btn-primario" type="submit">Guardar</button>
    </div>
  </form>
</div>
