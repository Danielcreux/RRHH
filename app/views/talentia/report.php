<div class="page-head">
  <h1>Informe Ejecutivo de Selección</h1>
  <p class="muted"><?= date("d/m/Y H:i") ?></p>
</div>

<div class="card">
  <h3 style="margin-top:0;">Perfil solicitado</h3>
  <p><?= htmlspecialchars($consulta ?? '') ?></p>
</div>

<div class="card" style="margin-top:1rem;">
  <h3 style="margin-top:0;">Resultado</h3>
  <div style="white-space:pre-wrap; line-height:1.7;">
    <?= htmlspecialchars($respuesta ?? '') ?>
  </div>

  <div style="margin-top:14px;">
    <a class="btn btn-secundario" href="<?= $_base_url ?>/?r=talentia/index">← Volver a TalentIA</a>
  </div>
</div>
