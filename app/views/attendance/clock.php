<div class="page-head">
  <h1>Fichaje</h1>
  <p class="muted">Entrada / Salida sin recargar</p>
</div>

<div class="card">
  <input type="hidden" id="csrf" value="<?= htmlspecialchars($csrf) ?>">
  <button class="btn btn-primario" id="btnFichar">Fichar entrada/salida</button>
  <div id="estadoFichaje" class="card" style="margin-top:1rem; display:none;"></div>
</div>

<script src="<?= $_base_url ?>/assets/js/attendance.js"></script>

