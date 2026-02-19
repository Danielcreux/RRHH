<?php

declare(strict_types=1);

$cfg = require __DIR__ . '/../app/config/config.php';

session_name($cfg['session_name'] ?? 'HRSESSID');
session_set_cookie_params([
  'lifetime' => 0,
  'path' => '/',
  'secure' => (bool)($cfg['session_secure'] ?? false),
  'httponly' => true,
  'samesite' => $cfg['session_samesite'] ?? 'Lax',
]);
session_start();

require_once __DIR__ . '/../app/core/Database.php';
require_once __DIR__ . '/../app/core/Response.php';
require_once __DIR__ . '/../app/core/Csrf.php';
require_once __DIR__ . '/../app/core/Validator.php';
require_once __DIR__ . '/../app/core/Auth.php';
require_once __DIR__ . '/../app/core/Model.php';
require_once __DIR__ . '/../app/core/Controller.php';
require_once __DIR__ . '/../app/core/Router.php';
require_once __DIR__ . '/../app/middleware/RoleMiddleware.php';

spl_autoload_register(function(string $class){
  $paths = [
    __DIR__ . '/../app/controllers/' . $class . '.php',
    __DIR__ . '/../app/models/' . $class . '.php',
    __DIR__ . '/../app/core/' . $class . '.php',
    __DIR__ . '/../app/middleware/' . $class . '.php',
  ];
  foreach ($paths as $p) {
    if (file_exists($p)) { require_once $p; return; }
  }
});

Router::dispatch();
