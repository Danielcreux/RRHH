<?php
// Header común. Incluye CSS/JS base desde /public/assets
?>
<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?= htmlspecialchars($_app_name) ?></title>

  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&family=Poppins:wght@600;700&display=swap" rel="stylesheet">

  <link rel="stylesheet" href="<?= $_base_url ?>/assets/css/app.css">
</head>
<body>
<header class="topbar">
  <div class="topbar-left">
    <button class="btn btn-secundario" type="button" data-open-sidebar style="padding:.5rem .7rem;">☰</button>
    <a class="brand" href="<?= $_base_url ?>/?r=dashboard/index">
        <span class="brand-dot"></span>
        <span class="brand-text"><?= htmlspecialchars($_app_name) ?></span>
    </a>
  </div>

  <div class="topbar-right">
    <?php if ($_user): ?>
      <div class="user-pill">
        <div class="user-name"><?= htmlspecialchars($_user['nombre'] ?: 'Usuario') ?></div>
        <div class="user-role"><?= htmlspecialchars($_user['rol']) ?></div>
      </div>
      <button class="btn btn-secundario" type="button" id="btnTema" title="Cambiar tema">🌓</button>
      <a class="btn btn-primario" href="<?= $_base_url ?>/?r=auth/logout">Salir</a>
    <?php endif; ?>
  </div>
</header>
<script>window.__BASE_URL__ = "<?= htmlspecialchars($_base_url) ?>";</script>

<div class="layout">
