<div class="page-head">
  <h1>Dashboard</h1>
  <p class="muted">Centro de herramientas</p>
</div>

<div class="cards-grid">
  <div class="card">
    <h3>Empleados</h3>
    <div class="meta">Activos en plantilla</div>
    <div class="stat"><?= (int)$stats['empleados_activos'] ?></div>
    <?php if (in_array($rol, ['super_admin','rrhh'], true)): ?>
      <a class="btn btn-secundario" href="<?= $_base_url ?>/?r=empleados/listar" style="margin-top:.8rem;">Acceder</a>
    <?php else: ?>
      <a class="btn btn-secundario" href="<?= $_base_url ?>/?r=empleados/ver&id=<?= (int)($_user['employee_id'] ?? 0) ?>" style="margin-top:.8rem;">Ver mi perfil</a>
    <?php endif; ?>
  </div>

  <div class="card">
    <h3>Asistencia</h3>
    <div class="meta">Fichajes incompletos hoy</div>
    <div class="stat"><?= (int)$stats['fichajes_incompletos_hoy'] ?></div>
    <a class="btn btn-secundario" href="<?= $_base_url ?>/?r=asistencia/clock" style="margin-top:.8rem;">Fichar</a>
  </div>

  <div class="card">
    <h3>Nóminas</h3>
    <div class="meta">En borrador</div>
    <div class="stat"><?= (int)$stats['nominas_borrador'] ?></div>
    <a class="btn btn-secundario" href="<?= $_base_url ?>/?r=nominas/listar" style="margin-top:.8rem;">Acceder</a>
  </div>

  <div class="card">
    <h3>Solicitudes</h3>
    <div class="meta">Pendientes</div>
    <div class="stat"><?= (int)$stats['solicitudes_pendientes'] ?></div>
    <?php if (in_array($rol, ['super_admin','rrhh'], true)): ?>
      <a class="btn btn-secundario" href="<?= $_base_url ?>/?r=solicitudes/pending" style="margin-top:.8rem;">Bandeja</a>
    <?php else: ?>
      <a class="btn btn-secundario" href="<?= $_base_url ?>/?r=solicitudes/my" style="margin-top:.8rem;">Mis solicitudes</a>
    <?php endif; ?>
  </div>

  <div class="card">
    <h3>TalentIA</h3>
    <div class="meta">Selección de personal</div>
    <div class="stat">Integrado</div>
    <a class="btn btn-secundario" href="<?= $_base_url ?>/?r=talentia/index" style="margin-top:.8rem;">Acceder</a>
  </div>
</div>
