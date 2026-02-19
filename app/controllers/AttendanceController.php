<?php
class AttendanceController extends Controller {

  public function clock(): void {
    RoleMiddleware::require(['super_admin','rrhh','empleado']);
    $this->view('attendance/clock', ['csrf'=>Csrf::token()]);
  }

  public function ajaxClock(): void {
    RoleMiddleware::require(['super_admin','rrhh','empleado']);

    $payload = json_decode(file_get_contents('php://input'), true) ?: [];
    Csrf::check($payload['csrf'] ?? '');

    $employeeId = Auth::employeeId();
    if (!$employeeId) $this->json(['ok'=>false,'error'=>'Usuario sin empleado asociado'], 400);

    $hoy = (new DateTime())->format('Y-m-d');
    $ahora = (new DateTime())->format('Y-m-d H:i:s');

    $am = new Attendance();
    $row = $am->findByEmployeeAndDate($employeeId, $hoy);

    if (!$row) {
      $am->insertEntry($employeeId, $hoy, $ahora, $_SERVER['REMOTE_ADDR'] ?? null);
      (new ActivityLog())->log(Auth::id(), 'attendance_clock_in', 'attendance', null, 'Entrada registrada');
      $this->json(['ok'=>true,'mensaje'=>'Entrada registrada','fecha'=>$hoy,'entrada'=>$ahora,'salida'=>null,'minutos'=>0]);
    }

    if ($row['hora_entrada'] && !$row['hora_salida']) {
      $entrada = new DateTime($row['hora_entrada']);
      $salida = new DateTime($ahora);
      $mins = max(0, (int)floor(($salida->getTimestamp() - $entrada->getTimestamp()) / 60));

      $am->setExit((int)$row['id'], $ahora, $mins);
      (new ActivityLog())->log(Auth::id(), 'attendance_clock_out', 'attendance', (int)$row['id'], 'Salida registrada');

      $this->json(['ok'=>true,'mensaje'=>'Salida registrada','fecha'=>$hoy,'entrada'=>$row['hora_entrada'],'salida'=>$ahora,'minutos'=>$mins]);
    }

    $this->json(['ok'=>false,'error'=>'Ya existe entrada y salida para hoy'], 409);
  }

  public function daily(): void {
    RoleMiddleware::require(['super_admin','rrhh']);

    $fecha = $_GET['fecha'] ?? (new DateTime())->format('Y-m-d');
    if (!Validator::date($fecha)) $fecha = (new DateTime())->format('Y-m-d');

    $filters = [
      'employee_id' => (int)($_GET['employee_id'] ?? 0),
      'department_id' => (int)($_GET['department_id'] ?? 0),
    ];
    if ($filters['employee_id'] === 0) unset($filters['employee_id']);
    if ($filters['department_id'] === 0) unset($filters['department_id']);

    $deps = (new Department())->allActive();
    $rows = (new Attendance())->getDayBoard($fecha, $filters);

    $this->view('attendance/daily', [
      'fecha'=>$fecha,
      'deps'=>$deps,
      'rows'=>$rows,
      'filters'=>$filters,
      'csrf'=>Csrf::token()
    ]);
  }

  public function editForm(): void {
    RoleMiddleware::requireAdmin();
    $id = (int)($_GET['id'] ?? 0);
    if ($id <= 0) { http_response_code(400); echo "ID inválido"; exit; }

    $pdo = Database::pdo();
    $st = $pdo->prepare("SELECT a.*, e.nombre, e.apellidos FROM attendance a INNER JOIN employees e ON e.id=a.employee_id WHERE a.id=:id");
    $st->execute(['id'=>$id]);
    $row = $st->fetch();
    if (!$row) { http_response_code(404); echo "No existe"; exit; }

    $this->view('attendance/edit', ['row'=>$row,'csrf'=>Csrf::token()]);
  }

  public function edit(): void {
    RoleMiddleware::requireAdmin();
    Csrf::check($_POST['csrf'] ?? '');

    $id = (int)($_POST['id'] ?? 0);
    $he = ($_POST['hora_entrada'] ?? '') ?: null;
    $hs = ($_POST['hora_salida'] ?? '') ?: null;
    $estado = $_POST['estado'] ?? 'justificado';
    $motivo = trim($_POST['motivo'] ?? '');

    $mins = 0;
    if ($he && $hs) {
      $d1 = new DateTime($he);
      $d2 = new DateTime($hs);
      $mins = max(0, (int)floor(($d2->getTimestamp() - $d1->getTimestamp())/60));
    }

    (new Attendance())->manualEdit($id, [
      'hora_entrada'=>$he,
      'hora_salida'=>$hs,
      'minutos_trabajados'=>$mins,
      'estado'=>$estado,
      'editado_por_user_id'=>Auth::id(),
      'motivo_edicion'=>$motivo,
    ]);

    (new ActivityLog())->log(Auth::id(), 'attendance_manual_edit', 'attendance', $id, $motivo ?: 'Edición manual');
    $this->redirect('?r=asistencia/daily');
  }

  public function monthly(): void {
    RoleMiddleware::require(['super_admin','rrhh']);
    // Vista simple: el calendario lo montas en JS luego.
    $this->view('attendance/monthly', ['csrf'=>Csrf::token()]);
  }

  public function report(): void {
    RoleMiddleware::require(['super_admin','rrhh']);
    $this->view('attendance/report', ['csrf'=>Csrf::token()]);
  }
}
