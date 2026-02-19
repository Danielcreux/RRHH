<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?= htmlspecialchars($_app_name) ?> - Recuperar</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&family=Poppins:wght@600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="<?= $_base_url ?>/assets/css/app.css">

</head>
<body class="auth-body">
  <div class="auth-card">
    <h1>Recuperar contraseña</h1>

    <?php if (!empty($error)): ?>
      <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <?php if (!empty($ok)): ?>
      <div class="alert alert-ok"><?= htmlspecialchars($ok) ?></div>
    <?php endif; ?>

    <form method="post" action="<?= $_base_url ?>/?r=auth/forgot">
      <input type="hidden" name="csrf" value="<?= htmlspecialchars($csrf) ?>">
      <label>Email</label>
      <input class="input" type="email" name="email" required>
      <button class="btn btn-primario" style="width:100%; margin-top:1rem;" type="submit">Enviar enlace</button>
    </form>

    <?php if (!empty($demo_link)): ?>
      <div class="card" style="margin-top:1rem;">
        <strong>Modo demo:</strong><br>
        <a href="<?= htmlspecialchars($demo_link) ?>"><?= htmlspecialchars($demo_link) ?></a>
      </div>
    <?php endif; ?>

    <div class="auth-links">
      <a href="<?= $_base_url ?>/?r=auth/loginForm">Volver</a>
    </div>
  </div>
</body>
</html>
