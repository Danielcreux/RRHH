<?php
final class Router {

  public static function dispatch(): void {
    $cfg = require __DIR__ . '/../config/config.php';
    date_default_timezone_set($cfg['timezone'] ?? 'Europe/Madrid');

    $route = $_GET['r'] ?? 'auth/loginForm';
    $route = trim($route, '/');

    // Convención: modulo/accion  ->  ModuloController::accion()
    [$mod, $accion] = array_pad(explode('/', $route, 2), 2, null);
    $mod = $mod ?: 'dashboard';
    $accion = $accion ?: 'index';

    $controllerClass = self::mapController($mod);
    if (!class_exists($controllerClass)) {
      http_response_code(404);
      echo "Controlador no encontrado.";
      exit;
    }

    $controller = new $controllerClass();

    if (!method_exists($controller, $accion)) {
      http_response_code(404);
      echo "Acción no encontrada.";
      exit;
    }

    // Seguridad base: todas menos auth requieren login
    if ($controllerClass !== 'AuthController') {
      Auth::requireLogin();
    }

    $controller->$accion();
  }

  private static function mapController(string $mod): string {
    $map = [
      'auth' => 'AuthController',
      'dashboard' => 'DashboardController',
      'empleados' => 'EmployeesController',
      'attendance' => 'AttendanceController',
      'asistencia' => 'AttendanceController',
      'payroll' => 'PayrollController',
      'nominas' => 'PayrollController',
      'requests' => 'RequestsController',
      'solicitudes' => 'RequestsController',
      'talentia' => 'TalentIAController',
      'config' => 'ConfigController',
    ];
    return $map[$mod] ?? (ucfirst($mod) . 'Controller');
  }
}