<?php
final class RoleMiddleware {
  public static function require(array $rolesPermitidos): void {
    Auth::requireLogin();
    $rol = Auth::rol();
    if (!$rol || !in_array($rol, $rolesPermitidos, true)) {
      http_response_code(403);
      echo "403 - No autorizado";
      exit;
    }
  }

  public static function requireAdmin(): void {
    self::require(['super_admin']);
  }
}
