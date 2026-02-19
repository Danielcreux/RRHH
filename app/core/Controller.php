<?php
abstract class Controller {
  protected array $cfg;

  public function __construct() {
    $this->cfg = require __DIR__ . '/../config/config.php';
  }

  protected function view(string $view, array $data = []): void {
    $viewFile = __DIR__ . '/../views/' . $view . '.php';
    if (!file_exists($viewFile)) {
      http_response_code(500);
      echo "Vista no encontrada: " . htmlspecialchars($view);
      exit;
    }

    // Variables comunes
    $data['_app_name'] = $this->cfg['app_name'] ?? 'App';
    $data['_base_url'] = $this->cfg['base_url'] ?? '';
    $data['_user'] = Auth::user();

    extract($data, EXTR_SKIP);

    require __DIR__ . '/../views/layout/header.php';
    require __DIR__ . '/../views/layout/sidebar.php';
    require $viewFile;
    require __DIR__ . '/../views/layout/footer.php';
  }

  protected function viewPlain(string $view, array $data = []): void {
    $viewFile = __DIR__ . '/../views/' . $view . '.php';
    if (!file_exists($viewFile)) {
      http_response_code(500);
      echo "Vista no encontrada: " . htmlspecialchars($view);
      exit;
    }

    $data['_app_name'] = $this->cfg['app_name'] ?? 'App';
    $data['_base_url'] = $this->cfg['base_url'] ?? '';
    $data['_user'] = Auth::user();
    extract($data, EXTR_SKIP);
    require $viewFile;
  }

 protected function redirect(string $url): void {
    // Si la URL no empieza con http y comienza con /, agregamos la base
    if (strpos($url, 'http') !== 0 && strpos($url, '/') === 0) {
        $url = $this->cfg['base_url'] . $url;
    }
    Response::redirect($url);
}
  protected function json(array $data, int $status = 200): void {
    Response::json($data, $status);
  }
}
