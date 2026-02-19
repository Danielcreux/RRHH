<div class="page-head">
  <h1>TalentIA</h1>
  <p class="muted">Gestión inteligente de candidatos</p>
</div>

<?php if (!empty($error)): ?>
  <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
<?php endif; ?>

<div class="card" id="buscar">
  <h2 style="margin-top:0;">Buscar candidato</h2>
  <form method="post" action="<?= $_base_url ?>/?r=talentia/buscar">
    <input type="hidden" name="csrf" value="<?= htmlspecialchars($csrf) ?>">
    <textarea class="input" name="consulta" rows="5" required placeholder="Ej: Busco backend PHP senior con Docker y MySQL"></textarea>
    <button class="btn btn-primario" type="submit">Buscar</button>
  </form>
</div>

<div class="card" id="subir" style="margin-top:1rem;">
  <h2 style="margin-top:0;">Subir CV</h2>
<form method="post" action="<?= $_base_url ?>/?r=talentia/subir" enctype="multipart/form-data">
  <input type="hidden" name="csrf" value="<?= htmlspecialchars($csrf) ?>">
  <input type="file" name="cv_file" accept="application/pdf" required>
  <button class="btn btn-primario" type="submit">Procesar CV</button>
</form>


</div>

<div class="card" style="margin-top:1rem;">
  <h2 style="margin-top:0;">Candidatos registrados</h2>

  <?php if (empty($rows)): ?>
    <p class="muted" style="font-style:italic;">No hay CV cargados</p>
  <?php else: ?>
    <div class="cv-grid" style="display:grid; grid-template-columns:repeat(auto-fill,minmax(260px,1fr)); gap:16px; margin-top:14px;">
      <?php foreach ($rows as $cv): ?>
        <div class="cv-card" style="border:1px solid rgba(255,255,255,.08); border-radius:12px; padding:16px;">
          <h3 style="margin:0 0 10px 0; color:var(--primary);">
            <?= htmlspecialchars($cv['nombre']) ?>
          </h3>

          <p style="margin:.25rem 0;"><strong>Puesto:</strong> <?= htmlspecialchars($cv['puesto_actual'] ?? '-') ?></p>
          <p style="margin:.25rem 0;"><strong>Experiencia:</strong> <?= htmlspecialchars($cv['experiencia_anios'] ?? '-') ?> años</p>

          <?php if (!empty($cv['skills'])): ?>
            <div style="margin-top:10px; display:flex; flex-wrap:wrap; gap:6px;">
              <?php foreach (array_slice(array_map('trim', explode(',', $cv['skills'])), 0, 12) as $skill): ?>
                <?php if ($skill !== ''): ?>
                  <span style="background:var(--primary); padding:6px 10px; border-radius:999px; font-size:12px;">
                    <?= htmlspecialchars($skill) ?>
                  </span>
                <?php endif; ?>
              <?php endforeach; ?>
            </div>
          <?php endif; ?>

          <div style="display:flex; gap:8px; margin-top:12px; flex-wrap:wrap;">
            <?php if (!empty($cv['archivo_pdf'])): ?>
              <a class="btn btn-secundario" href="<?= $_base_url ?><?= htmlspecialchars($cv['archivo_pdf']) ?>" target="_blank" rel="noopener">
                Ver PDF
              </a>
            <?php endif; ?>

            <form method="post" action="<?= $_base_url ?>/?r=talentia/eliminar" onsubmit="return confirm('¿Eliminar candidato?');">
              <input type="hidden" name="csrf" value="<?= htmlspecialchars($csrf) ?>">
              <input type="hidden" name="id" value="<?= (int)$cv['id'] ?>">
              <button class="btn btn-peligro" type="submit">Eliminar</button>
            </form>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  <?php endif; ?>
</div>
