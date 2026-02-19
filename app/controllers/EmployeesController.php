<?php
class EmployeesController extends Controller {

  public function listar(): void {
    RoleMiddleware::require(['super_admin','rrhh']);

    $filters = [
      'q' => trim($_GET['q'] ?? ''),
      'department_id' => (int)($_GET['department_id'] ?? 0),
      'estado' => trim($_GET['estado'] ?? ''),
    ];
    if ($filters['department_id'] === 0) unset($filters['department_id']);
    if ($filters['estado'] === '') unset($filters['estado']);

    $deps = (new Department())->allActive();
    $rows = (new Employee())->list($filters);

    $this->view('employees/list', [
      'deps'=>$deps,
      'rows'=>$rows,
      'filters'=>$filters,
      'csrf'=>Csrf::token()
    ]);
  }

  public function ver(): void {
    RoleMiddleware::require(['super_admin','rrhh','empleado']);

    $id = (int)($_GET['id'] ?? 0);
    if ($id <= 0) { http_response_code(400); echo "ID inválido"; exit; }

    // Empleado: solo puede ver su propio perfil
    if (Auth::rol() === 'empleado' && Auth::employeeId() !== $id) {
      http_response_code(403); echo "403 - No autorizado"; exit;
    }

    $emp = (new Employee())->find($id);
    if (!$emp) { http_response_code(404); echo "No existe"; exit; }

    $asist = (new Attendance())->listByEmployee($id, 45);
    $nom = (new Payroll())->listByEmployee($id);
    $docs = (new Document())->listByEmployee($id);

    $this->view('employees/view', [
      'emp'=>$emp,
      'asist'=>$asist,
      'nominas'=>$nom,
      'docs'=>$docs,
      'csrf'=>Csrf::token()
    ]);
  }

  public function crearForm(): void {
    RoleMiddleware::require(['super_admin','rrhh']);
    $deps = (new Department())->allActive();
    $pos = (new Position())->allActive();
    $this->view('employees/create', ['deps'=>$deps,'pos'=>$pos,'csrf'=>Csrf::token()]);
  }

  public function crear(): void {
    RoleMiddleware::require(['super_admin','rrhh']);
    Csrf::check($_POST['csrf'] ?? '');

    $data = $this->collectEmployeePost();
    $data['foto_path'] = $this->handlePhoto($_FILES['foto'] ?? null);

    if (!Validator::required($data['nombre']) || !Validator::required($data['apellidos']) || !Validator::email($data['email'])) {
    $this->view('employees/create', ['error'=>'Revisa nombre/apellidos/email', 'deps'=>(new Department())->allActive(), 'pos'=>(new Position())->allActive(), 'csrf'=>Csrf::token()]);
    return;
}

    $id = (new Employee())->create($data);
    (new ActivityLog())->log(Auth::id(), 'employee_create', 'employees', $id, 'Alta empleado');

    $this->redirect('?r=empleados/listar');
  }

  public function editarForm(): void {
    RoleMiddleware::require(['super_admin','rrhh']);
    $id = (int)($_GET['id'] ?? 0);
    $emp = (new Employee())->find($id);
    if (!$emp) { http_response_code(404); echo "No existe"; exit; }

    $deps = (new Department())->allActive();
    $pos = (new Position())->allActive();
    $this->view('employees/edit', ['emp'=>$emp,'deps'=>$deps,'pos'=>$pos,'csrf'=>Csrf::token()]);
  }

  public function editar(): void {
    RoleMiddleware::require(['super_admin','rrhh']);
    Csrf::check($_POST['csrf'] ?? '');

    $id = (int)($_POST['id'] ?? 0);
    if ($id <= 0) { http_response_code(400); echo "ID inválido"; exit; }

    $empActual = (new Employee())->find($id);
    if (!$empActual) { http_response_code(404); echo "No existe"; exit; }

    $data = $this->collectEmployeePost();
    $fotoNueva = $this->handlePhoto($_FILES['foto'] ?? null);
    $data['foto_path'] = $fotoNueva ?: ($empActual['foto_path'] ?? null);

    (new Employee())->update($id, $data);
    (new ActivityLog())->log(Auth::id(), 'employee_update', 'employees', $id, 'Edición empleado');

    $this->redirect('?r=empleados/ver&id=' . $id);
  }

  public function eliminar(): void {
    RoleMiddleware::require(['super_admin']);
    Csrf::check($_POST['csrf'] ?? '');

    $id = (int)($_POST['id'] ?? 0);
    if ($id <= 0) { http_response_code(400); echo "ID inválido"; exit; }

    (new Employee())->delete($id);
    (new ActivityLog())->log(Auth::id(), 'employee_delete', 'employees', $id, 'Borrado empleado');

    $this->redirect('?r=empleados/listar');
  }

  private function collectEmployeePost(): array {
    return [
      'department_id' => (int)($_POST['department_id'] ?? 0),
      'position_id' => (int)($_POST['position_id'] ?? 0),
      'nombre' => trim($_POST['nombre'] ?? ''),
      'apellidos' => trim($_POST['apellidos'] ?? ''),
      'email' => trim($_POST['email'] ?? ''),
      'telefono' => trim($_POST['telefono'] ?? ''),
      'direccion' => trim($_POST['direccion'] ?? ''),
      'fecha_nacimiento' => ($_POST['fecha_nacimiento'] ?? '') ?: null,
      'fecha_ingreso' => ($_POST['fecha_ingreso'] ?? ''),
      'salario_base' => (float)($_POST['salario_base'] ?? 0),
      'estado' => $_POST['estado'] ?? 'activo',
      'contacto_emergencia_nombre' => trim($_POST['emerg_nombre'] ?? ''),
      'contacto_emergencia_telefono' => trim($_POST['emerg_tel'] ?? ''),
    ];
  }

  private function handlePhoto(?array $file): ?string {
    if (!$file || ($file['error'] ?? UPLOAD_ERR_NO_FILE) === UPLOAD_ERR_NO_FILE) return null;
    if (($file['error'] ?? UPLOAD_ERR_OK) !== UPLOAD_ERR_OK) return null;

    $max = 2 * 1024 * 1024;
    if (($file['size'] ?? 0) > $max) return null;

    $finfo = new finfo(FILEINFO_MIME_TYPE);
    $mime = $finfo->file($file['tmp_name']);
    $allowed = ['image/jpeg'=>'jpg','image/png'=>'png','image/webp'=>'webp'];
    if (!isset($allowed[$mime])) return null;

    $dir = __DIR__ . '/../../public/uploads/employees/';
    if (!is_dir($dir)) mkdir($dir, 0775, true);

    $name = bin2hex(random_bytes(16)) . '.' . $allowed[$mime];
    $dest = $dir . $name;

    if (!move_uploaded_file($file['tmp_name'], $dest)) return null;
    return '/uploads/employees/' . $name;
  }
}
