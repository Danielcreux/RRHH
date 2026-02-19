<?php
class PayrollController extends Controller {

  public function listar(): void {
    RoleMiddleware::require(['super_admin','rrhh']);

    $filters = [
      'employee_id' => (int)($_GET['employee_id'] ?? 0),
      'anio' => (int)($_GET['anio'] ?? 0),
      'mes' => (int)($_GET['mes'] ?? 0),
    ];
    if ($filters['employee_id'] === 0) unset($filters['employee_id']);
    if ($filters['anio'] === 0) unset($filters['anio']);
    if ($filters['mes'] === 0) unset($filters['mes']);

    $rows = (new Payroll())->list($filters);
    $this->view('payroll/list', ['rows'=>$rows,'filters'=>$filters,'csrf'=>Csrf::token()]);
  }

  public function ver(): void {
    RoleMiddleware::require(['super_admin','rrhh','empleado']);
    $id = (int)($_GET['id'] ?? 0);
    if ($id <= 0) { http_response_code(400); echo "ID inválido"; exit; }

    $row = (new Payroll())->find($id);
    if (!$row) { http_response_code(404); echo "No existe"; exit; }

    // Empleado: solo su nómina
    if (Auth::rol() === 'empleado' && Auth::employeeId() !== (int)$row['employee_id']) {
      http_response_code(403); echo "403 - No autorizado"; exit;
    }

    $this->view('payroll/view', ['row'=>$row,'csrf'=>Csrf::token()]);
  }

  public function crearForm(): void {
    RoleMiddleware::require(['super_admin','rrhh']);
    $emps = (new Employee())->list([]);
    $this->view('payroll/create', ['emps'=>$emps,'csrf'=>Csrf::token()]);
  }

  public function crear(): void {
    RoleMiddleware::require(['super_admin','rrhh']);
    Csrf::check($_POST['csrf'] ?? '');

    $employeeId = (int)($_POST['employee_id'] ?? 0);
    $mes = (int)($_POST['periodo_mes'] ?? 0);
    $anio = (int)($_POST['periodo_anio'] ?? 0);

    $salario = (float)($_POST['salario_base'] ?? 0);
    $bonos = (float)($_POST['bonos'] ?? 0);
    $ded = (float)($_POST['deducciones'] ?? 0);
    $total = $salario + $bonos - $ded;

    if ($employeeId <= 0 || $mes < 1 || $mes > 12 || $anio < 2000) {
    $this->view('payroll/create', ['error'=>'Datos inválidos','emps'=>(new Employee())->list([]),'csrf'=>Csrf::token()]);
    return;
}

    $id = (new Payroll())->create([
      'employee_id'=>$employeeId,
      'periodo_mes'=>$mes,
      'periodo_anio'=>$anio,
      'salario_base'=>$salario,
      'bonos'=>$bonos,
      'deducciones'=>$ded,
      'total'=>$total,
      'estado'=>'draft',
      'generado_por_user_id'=>Auth::id(),
    ]);

    (new ActivityLog())->log(Auth::id(), 'payroll_create', 'payroll', $id, "Nómina {$mes}/{$anio}");
    $this->redirect('?r=nominas/ver&id=' . $id);
  }

  public function approve(): void {
    RoleMiddleware::require(['super_admin','rrhh']);
    Csrf::check($_POST['csrf'] ?? '');

    $id = (int)($_POST['id'] ?? 0);
    $accion = $_POST['accion'] ?? 'approve';

    $pm = new Payroll();
    if ($accion === 'approve') {
      $pm->setStatusApproved($id, Auth::id());
      (new ActivityLog())->log(Auth::id(), 'payroll_approve', 'payroll', $id, 'Aprobada');
    } elseif ($accion === 'paid') {
      $pm->setStatusPaid($id);
      (new ActivityLog())->log(Auth::id(), 'payroll_paid', 'payroll', $id, 'Pagada');
    }

    $this->redirect('?r=nominas/ver&id=' . $id);
  }
}
