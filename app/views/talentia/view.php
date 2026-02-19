<div class="page-head">
  <h1><?= htmlspecialchars($row['nombre']) ?></h1>
  <p class="muted">Detalle de candidato</p>
</div>

<div class="card" style="display:flex; gap:.6rem; flex-wrap:wrap;">
  <a class="btn btn-secundario" href="<?= $_base_url ?>/?r=talentia/index">← Volver</a>
</div>

<div class="card" style="margin-top:1rem;">
  <div class="grid" style="grid-template-columns:repeat(auto-fit,minmax(240px,1fr)); gap:12px;">
    <div><div class="meta">Email</div><div><strong><?= htmlspecialchars($row['email'] ?? '-') ?></strong></div></div>
    <div><div class="meta">Teléfono</div><div><strong><?= htmlspecialchars($row['telefono'] ?? '-') ?></strong></div></div>
    <div><div class="meta">Experiencia</div><div><strong><?= htmlspecialchars(($row['experiencia_anios'] ?? '-') . ' años') ?></strong></div></div>
    <div><div class="meta">Puesto</div><div><strong><?= htmlspecialchars($row['puesto_actual'] ?? '-') ?></strong></div></div>
    <div><div class="meta">Nivel inglés</div><div><strong><?= htmlspecialchars($row['nivel_ingles'] ?? '-') ?></strong></div></div>
    <div><div class="meta">Registro</div><div><strong><?= htmlspecialchars($row['fecha_registro'] ?? '-') ?></strong></div></div>
  </div>

  <hr style="margin:14px 0; opacity:.2;">

  <div><div class="meta">Skills</div><div><?= htmlspecialchars($row['skills'] ?? '-') ?></div></div>
  <div style="margin-top:10px;"><div class="meta">Idiomas</div><div><?= htmlspecialchars($row['idiomas'] ?? '-') ?></div></div>

  <div style="margin-top:14px;">
    <div class="meta">Texto CV</div>
    <pre style="white-space:pre-wrap; margin:0; opacity:.9;"><?= htmlspecialchars($row['cv_text'] ?? '') ?></pre>
  </div>
</div>
