<?php
// app/views/talentia/editor.php
// Requisitos: $data (array), $csrf (string), $_base_url (string)

$pdfPath = trim((string)($data['archivo_pdf'] ?? ''));
error_log("editor.php: data[archivo_pdf] = " . ($data['archivo_pdf'] ?? 'NULL'));
error_log("editor.php: pdfPath = " . ($pdfPath ?? 'NULL'));
// Normaliza: si viene sin "/" inicial, se lo ponemos
if ($pdfPath !== '' && $pdfPath[0] !== '/') {
  $pdfPath = '/' . $pdfPath;
}

// Si por error te guardaron "RRHH/public/..." o "public/uploads/..." lo recortamos:
$pdfPath = preg_replace('#^/RRHH/public#', '', $pdfPath);
$pdfPath = preg_replace('#^/public#', '', $pdfPath);

// Construye URL final (ej: /RRHH/public + /uploads/talentia/x.pdf)
$pdfUrl = ($pdfPath !== '') ? ($_base_url . $pdfPath) : '';

// Validación básica: solo PDF en uploads/talentia
$isPdfOk = $pdfPath !== ''
  && (strpos($pdfPath, '/uploads/talentia/') === 0)
  && (strtolower(substr($pdfPath, -4)) === '.pdf');
?>

<div class="page-head">
  <h1>Revisión de CV</h1>
  <p class="muted">Vista previa + edición antes de guardar</p>
</div>

<div class="card" style="display:flex; gap:16px; flex-wrap:wrap;">
  <a class="btn btn-secundario" href="<?= $_base_url ?>/?r=talentia/index">← Volver</a>

  <?php if ($isPdfOk): ?>
    <a class="btn btn-secundario" href="<?= htmlspecialchars($pdfUrl, ENT_QUOTES, 'UTF-8') ?>" target="_blank" rel="noopener">
      Abrir PDF en pestaña
    </a>
  <?php endif; ?>
</div>

<div class="card" style="margin-top:1rem;">
  <div style="display:flex; gap:16px; flex-wrap:wrap;">
    <div style="flex:1; min-width:320px;">
      <h3 style="margin-top:0;">Vista previa del CV</h3>

      <?php if (!$isPdfOk): ?>
        <div class="alert alert-danger">
          <strong>No hay PDF válido para mostrar.</strong><br>
          <span class="muted">Valor recibido en <code>archivo_pdf</code>:</span>
          <div style="margin-top:6px;"><code><?= htmlspecialchars($pdfPath ?: '(vacío)', ENT_QUOTES, 'UTF-8') ?></code></div>
          <div class="muted" style="margin-top:8px;">
            Debe ser algo como: <code>/uploads/talentia/archivo.pdf</code>
          </div>
        </div>
      <?php else: ?>
        <!--
          Alternativa MEJOR que iframe: <object>.
          Si el navegador no puede embebir PDF, mostrará el fallback con enlace.
        -->
        <div style="border:1px solid rgba(0,0,0,.08); border-radius:12px; overflow:hidden; height:70vh; background:#fff;">
          <object
            data="<?= htmlspecialchars($pdfUrl, ENT_QUOTES, 'UTF-8') ?>#toolbar=0"
            type="application/pdf"
            width="100%"
            height="100%"
          >
            <div style="padding:12px;">
              <strong>No se pudo mostrar el PDF embebido.</strong>
              <div class="muted" style="margin-top:6px;">
                Abre el archivo aquí:
                <a href="<?= htmlspecialchars($pdfUrl, ENT_QUOTES, 'UTF-8') ?>" target="_blank" rel="noopener">
                  <?= htmlspecialchars($pdfUrl, ENT_QUOTES, 'UTF-8') ?>
                </a>
              </div>
            </div>
          </object>
        </div>

        <!-- Debug visible (quítalo cuando ya funcione) -->
        <p class="muted" style="margin-top:.6rem;">
          PDF URL: <code><?= htmlspecialchars($pdfUrl, ENT_QUOTES, 'UTF-8') ?></code>
        </p>
      <?php endif; ?>
    </div>

    <div style="flex:1; min-width:320px;">
      <h3 style="margin-top:0;">Editar información</h3>

      <form method="post" action="<?= $_base_url ?>/?r=talentia/guardar">
        <input type="hidden" name="csrf" value="<?= htmlspecialchars($csrf, ENT_QUOTES, 'UTF-8') ?>">
        <input type="hidden" name="archivo_pdf" value="<?= htmlspecialchars($pdfPath, ENT_QUOTES, 'UTF-8') ?>">

        <!-- IMPORTANTE: cv_text NO va en value="" -->
        <textarea name="cv_text" style="display:none;"><?= htmlspecialchars($data['cv_text'] ?? '', ENT_QUOTES, 'UTF-8') ?></textarea>

        <label class="meta">Nombre</label>
        <input class="input" name="nombre" value="<?= htmlspecialchars($data['nombre'] ?? '', ENT_QUOTES, 'UTF-8') ?>">

        <label class="meta">Email</label>
        <input class="input" name="email" value="<?= htmlspecialchars($data['email'] ?? '', ENT_QUOTES, 'UTF-8') ?>">

        <label class="meta">Teléfono</label>
        <input class="input" name="telefono" value="<?= htmlspecialchars($data['telefono'] ?? '', ENT_QUOTES, 'UTF-8') ?>">

        <label class="meta">Edad</label>
        <input class="input" type="number" name="edad" value="<?= htmlspecialchars((string)($data['edad'] ?? ''), ENT_QUOTES, 'UTF-8') ?>">

        <label class="meta">Años de experiencia</label>
        <input class="input" type="number" name="experiencia_anios" value="<?= htmlspecialchars((string)($data['experiencia_anios'] ?? ''), ENT_QUOTES, 'UTF-8') ?>">

        <label class="meta">Skills (coma)</label>
        <input class="input" name="skills" value="<?= htmlspecialchars($data['skills'] ?? '', ENT_QUOTES, 'UTF-8') ?>">

        <label class="meta">Puesto actual</label>
        <input class="input" name="puesto_actual" value="<?= htmlspecialchars($data['puesto_actual'] ?? '', ENT_QUOTES, 'UTF-8') ?>">

        <label class="meta">Idiomas</label>
        <input class="input" name="idiomas" value="<?= htmlspecialchars($data['idiomas'] ?? '', ENT_QUOTES, 'UTF-8') ?>">

        <label class="meta">Nivel inglés</label>
        <input class="input" name="nivel_ingles" value="<?= htmlspecialchars($data['nivel_ingles'] ?? '', ENT_QUOTES, 'UTF-8') ?>">

        <div style="margin-top:12px; display:flex; gap:10px; flex-wrap:wrap;">
          <a class="btn btn-secundario" href="<?= $_base_url ?>/?r=talentia/index">Cancelar</a>
          <button class="btn btn-primario" type="submit">Guardar CV</button>
        </div>
      </form>
    </div>
  </div>
</div>
