<?php
final class Csrf {
  public static function token(): string {
    if (session_status() !== PHP_SESSION_ACTIVE) session_start();
    if (empty($_SESSION['_csrf'])) {
      $_SESSION['_csrf'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['_csrf'];
  }

  public static function check(string $token): void {
    if (session_status() !== PHP_SESSION_ACTIVE) session_start();
    $ok = isset($_SESSION['_csrf']) && hash_equals($_SESSION['_csrf'], $token);
    if (!$ok) {
      http_response_code(419);
      echo "Token CSRF inválido.";
      exit;
    }
  }
}
