<?php
final class Response {

  private static function baseUrl(): string {
    $cfg = require __DIR__ . '/../config/config.php';
    return rtrim($cfg['base_url'] ?? '', '/'); // /RRHH/public
  }

  public static function json(array $data, int $status = 200): void {
    http_response_code($status);
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode($data, JSON_UNESCAPED_UNICODE);
    exit;
  }

  public static function redirect(string $url): void {
    $base = self::baseUrl();

    // "/?r=..." => "?r=..."
    if (str_starts_with($url, '/?')) $url = substr($url, 1);

    // "?r=..." => "/RRHH/public/index.php?r=..."
    if ($base !== '' && str_starts_with($url, '?')) {
      $url = $base . '/index.php' . $url;
    }

    // "/algo" => "/RRHH/public/algo"
    if ($base !== '' && str_starts_with($url, '/') && !str_starts_with($url, $base . '/')) {
      $url = $base . $url;
    }

    header("Location: {$url}");
    exit;
  }
}
