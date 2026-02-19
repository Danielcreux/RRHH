<?php
// database/seed.php
declare(strict_types=1);

$cfg = [
  'db_host' => '127.0.0.1',
  'db_name' => 'rrhh',
  'db_user' => 'danielcreux',
  'db_pass' => 'danielcreux',
];

$dsn = "mysql:host={$cfg['db_host']};dbname={$cfg['db_name']};charset=utf8mb4";
$pdo = new PDO($dsn, $cfg['db_user'], $cfg['db_pass'], [
  PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
  PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
  PDO::ATTR_EMULATE_PREPARES => false,
]);

function upsertAdmin(PDO $pdo): int {
  // Garantiza admin: danielcreux@empresa.com / danielcreux
  $email = 'danielcreux@empresa.com';
  $passPlain = 'danielcreux';
  $hash = password_hash($passPlain, PASSWORD_DEFAULT);

  // employee_id = 1 (Daniel Creux) según seed_base.sql
  $employeeId = 1;

  $st = $pdo->prepare("SELECT id FROM users WHERE email=:email LIMIT 1");
  $st->execute(['email'=>$email]);
  $row = $st->fetch();

  if ($row) {
    $pdo->prepare("UPDATE users SET password_hash=:h, rol='super_admin', activo=1, employee_id=:eid WHERE id=:id")
        ->execute(['h'=>$hash,'eid'=>$employeeId,'id'=>$row['id']]);
    return (int)$row['id'];
  }

  $pdo->prepare("INSERT INTO users (employee_id,email,password_hash,rol,activo) VALUES (:eid,:email,:h,'super_admin',1)")
      ->execute(['eid'=>$employeeId,'email'=>$email,'h'=>$hash]);

  return (int)$pdo->lastInsertId();
}

$pdo->beginTransaction();
try {
  $adminId = upsertAdmin($pdo);

  // Corrige payroll generado_por_user_id/aprobado_por_user_id si hace falta
  $pdo->prepare("UPDATE payroll SET generado_por_user_id=:u WHERE generado_por_user_id IS NULL OR generado_por_user_id=0 OR generado_por_user_id=1")
      ->execute(['u'=>$adminId]);

  // Asigna aprobador en solicitudes ya resueltas (approved/rejected)
  $pdo->prepare("UPDATE leave_requests
                 SET aprobado_por_user_id=:u, comentario_aprobacion=COALESCE(comentario_aprobacion,'Seed admin'), resuelto_en=COALESCE(resuelto_en, NOW())
                 WHERE estado IN ('approved','rejected') AND aprobado_por_user_id IS NULL")
      ->execute(['u'=>$adminId]);

  $pdo->commit();
  echo "OK: Admin portal creado/actualizado. Email: danielcreux@empresa.com Pass: danielcreux\n";
} catch (Throwable $e) {
  $pdo->rollBack();
  fwrite(STDERR, "ERROR: " . $e->getMessage() . "\n");
  exit(1);
}
