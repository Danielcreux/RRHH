<?php
class RequestsController extends Controller {

  public function my(): void {
    RoleMiddleware::require(['empleado','super_admin','rrhh']);
    $employeeId = Auth::employeeId();
    if (!$employeeId) { http_response_code(400); echo "Sin empleado asociado"; exit; }

    $rows = (new LeaveRequest())->listForEmployee($employeeId);
    $this->view('requests/my_requests', ['rows'=>$rows,'csrf'=>Csrf::token()]);
  }

  public function createForm(): void {
    RoleMiddleware::require(['empleado','super_admin','rrhh']);
    $this->view('requests/create', ['csrf'=>Csrf::token()]);
  }

  public function create(): void {
    RoleMiddleware::require(['empleado','super_admin','rrhh']);
    Csrf::check($_POST['csrf'] ?? '');

    $employeeId = Auth::employeeId();
    if (!$employeeId) { http_response_code(400); echo "Sin empleado asociado"; exit; }

    $tipo = $_POST['tipo'] ?? 'vacaciones';
    $fi = $_POST['fecha_inicio'] ?? '';
    $ff = $_POST['fecha_fin'] ?? '';
    $motivo = trim($_POST['motivo'] ?? '');

    if (!Validator::date($fi) || !Validator::date($ff) || $fi === '' || $ff === '') {
    $this->view('requests/create', ['error'=>'Fechas inválidas','csrf'=>Csrf::token()]);
    return;
}

    $id = (new LeaveRequest())->create([
      'employee_id'=>$employeeId,
      'tipo'=>$tipo,
      'fecha_inicio'=>$fi,
      'fecha_fin'=>$ff,
      'motivo'=>$motivo,
    ]);

    (new ActivityLog())->log(Auth::id(), 'leave_create', 'leave_requests', $id, $tipo);
    $this->redirect('?r=solicitudes/my');
  }

  public function pending(): void {
    RoleMiddleware::require(['super_admin','rrhh']);
    $rows = (new LeaveRequest())->listPending();
    $this->view('requests/pending', ['rows'=>$rows,'csrf'=>Csrf::token()]);
  }

  public function ajaxApprove(): void {
    RoleMiddleware::require(['super_admin','rrhh']);
    $payload = json_decode(file_get_contents('php://input'), true) ?: [];
    Csrf::check($payload['csrf'] ?? '');

    $id = (int)($payload['id'] ?? 0);
    $accion = $payload['accion'] ?? 'approve';
    $coment = trim($payload['comentario'] ?? '');

    if ($id <= 0) $this->json(['ok'=>false,'error'=>'ID inválido'], 400);

    $lm = new LeaveRequest();
    if ($accion === 'approve') {
      $lm->approve($id, Auth::id(), $coment);
      (new ActivityLog())->log(Auth::id(), 'leave_approve', 'leave_requests', $id, $coment);
      $this->json(['ok'=>true,'estado'=>'approved']);
    }

    if ($accion === 'reject') {
      $lm->reject($id, Auth::id(), $coment);
      (new ActivityLog())->log(Auth::id(), 'leave_reject', 'leave_requests', $id, $coment);
      $this->json(['ok'=>true,'estado'=>'rejected']);
    }

    $this->json(['ok'=>false,'error'=>'Acción inválida'], 400);
  }

  public function calendar(): void {
    RoleMiddleware::require(['super_admin','rrhh']);
    $from = $_GET['from'] ?? (new DateTime('first day of this month'))->format('Y-m-d');
    $to = $_GET['to'] ?? (new DateTime('last day of this month'))->format('Y-m-d');

    $rows = (new LeaveRequest())->calendarApproved($from, $to);
    $this->view('requests/calendar', ['from'=>$from,'to'=>$to,'rows'=>$rows,'csrf'=>Csrf::token()]);
  }
}
