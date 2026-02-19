<?php
final class Auth {
  public static function user(): ?array {
    return $_SESSION['user'] ?? null;
  }

  public static function check(): bool {
    return isset($_SESSION['user']);
  }

  public static function id(): ?int {
    return isset($_SESSION['user']['id']) ? (int)$_SESSION['user']['id'] : null;
  }

  public static function rol(): ?string {
    return $_SESSION['user']['rol'] ?? null;
  }

  public static function employeeId(): ?int {
    return isset($_SESSION['user']['employee_id']) ? (int)$_SESSION['user']['employee_id'] : null;
  }

  public static function requireLogin(): void {
    if (!self::check()) {
      Response::redirect('?r=auth/loginForm');
    }
  }
}
