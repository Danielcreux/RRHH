<?php
$rol = $_user['rol'] ?? null;

$items = [];

if ($rol === 'super_admin') {
  $items = [
    ['label'=>'Dashboard','route'=>'dashboard/index','icon'=>'📊'],
    ['label'=>'Empleados','route'=>'empleados/listar','icon'=>'👥'],
    ['label'=>'Asistencia','route'=>'asistencia/daily','icon'=>'⏱️'],
    ['label'=>'Fichar','route'=>'asistencia/clock','icon'=>'✅'],
    ['label'=>'Nóminas','route'=>'nominas/listar','icon'=>'💶'],
    ['label'=>'Solicitudes','route'=>'solicitudes/pending','icon'=>'📩'],
    ['label'=>'Calendario ausencias','route'=>'solicitudes/calendar','icon'=>'📅'],
    ['label'=>'TalentIA','route'=>'talentia/index','icon'=>'🧠'],
    ['label'=>'Usuarios','route'=>'config/users','icon'=>'🔐'],
    ['label'=>'Ajustes','route'=>'config/settings','icon'=>'⚙️'],
  ];
} elseif ($rol === 'rrhh') {
  $items = [
    ['label'=>'Dashboard','route'=>'dashboard/index','icon'=>'📊'],
    ['label'=>'Empleados','route'=>'empleados/listar','icon'=>'👥'],
    ['label'=>'Asistencia','route'=>'asistencia/daily','icon'=>'⏱️'],
    ['label'=>'Nóminas','route'=>'nominas/listar','icon'=>'💶'],
    ['label'=>'Solicitudes','route'=>'solicitudes/pending','icon'=>'📩'],
    ['label'=>'Calendario ausencias','route'=>'solicitudes/calendar','icon'=>'📅'],
    ['label'=>'TalentIA','route'=>'talentia/index','icon'=>'🧠'],
  ];
} elseif ($rol === 'empleado') {
  $eid = $_user['employee_id'] ?? null;
  $items = [
    ['label'=>'Dashboard','route'=>'dashboard/index','icon'=>'📊'],
    ['label'=>'Mi perfil','route'=>'empleados/ver&id=' . (int)$eid,'icon'=>'🧾'],
    ['label'=>'Fichar','route'=>'asistencia/clock','icon'=>'✅'],
    ['label'=>'Mis solicitudes','route'=>'solicitudes/my','icon'=>'📩'],
    ['label'=>'TalentIA','route'=>'talentia/index','icon'=>'🧠'],
  ];
}
?>
<aside class="sidebar">
  <div class="sidebar-head">
    <button class="btn btn-secundario" type="button" data-toggle-sidebar>⇔</button>
    <div class="sidebar-title">Menú</div>
  </div>

  <nav class="nav">
    <?php foreach ($items as $it): ?>
      <a class="nav-item" href="<?= $_base_url ?>/?r=<?= htmlspecialchars($it['route']) ?>">
        <span class="icon"><?= $it['icon'] ?></span>
        <span class="label"><?= htmlspecialchars($it['label']) ?></span>
      </a>
    <?php endforeach; ?>
  </nav>
</aside>

<main class="main">
  <div class="container">
