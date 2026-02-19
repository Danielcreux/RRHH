<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?= htmlspecialchars($_app_name) ?> - Login</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&family=Poppins:wght@600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="<?= $_base_url ?>/assets/css/app.css">
</head>
<body class="auth-body">
  <div class="auth-card">
    <div class="auth-brand">
      <span class="brand-dot"></span>
      <h1><?= htmlspecialchars($_app_name) ?></h1>
      <p>Acceso al portal</p>
    </div>

    <?php if (!empty($error)): ?>
      <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <?php if (!empty($ok)): ?>
      <div class="alert alert-ok"><?= htmlspecialchars($ok) ?></div>
    <?php endif; ?>

    <form method="post" action="<?= $_base_url ?>/?r=auth/login">
      <input type="hidden" name="csrf" value="<?= htmlspecialchars($csrf) ?>">
      <label>Email</label>
      <input class="input" type="email" name="email" required>

      <label style="margin-top:.8rem;">Contraseña</label>
      <input class="input" type="password" name="password" required>

      <button class="btn btn-primario" style="width:100%; margin-top:1rem;" type="submit">Entrar</button>
    </form>

    <div class="auth-links">
      <a href="<?= $_base_url ?>/?r=auth/forgotForm">Olvidé mi contraseña</a>

    </div>

    <div class="auth-demo">
      <small>Demo: admin@empresa.com / Admin123*</small>
    </div>
  </div>
</body>
</html>
