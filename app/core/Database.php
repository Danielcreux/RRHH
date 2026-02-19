<?php
final class Database {
  private static ?PDO $pdo = null;

  public static function pdo(): PDO {
    if (self::$pdo) return self::$pdo;

    $cfg = require __DIR__ . '/../config/config.php';
    $dsn = "mysql:host={$cfg['db_host']};dbname={$cfg['db_name']};charset=utf8mb4";

    self::$pdo = new PDO($dsn, $cfg['db_user'], $cfg['db_pass'], [
      PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
      PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
      PDO::ATTR_EMULATE_PREPARES => false,
    ]);

    return self::$pdo;
  }
}
