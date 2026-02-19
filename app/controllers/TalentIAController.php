<?php
final class TalentIAController extends Controller {

  public function index(): void {
    RoleMiddleware::require(['super_admin','rrhh']); // recomendado
    $rows = (new TalentiaCandidato())->all();

    $this->view('talentia/index', [
      'rows' => $rows,
      'csrf' => Csrf::token(),
    ]);
  }

  public function createForm(): void {
    RoleMiddleware::require(['super_admin','rrhh']);
    $this->view('talentia/create', ['csrf'=>Csrf::token()]);
  }

  public function create(): void {
    RoleMiddleware::require(['super_admin','rrhh']);
    Csrf::check($_POST['csrf'] ?? '');

    $nombre = trim($_POST['nombre'] ?? '');
    if ($nombre === '') {
      $this->view('talentia/create', ['csrf'=>Csrf::token(), 'error'=>'Nombre requerido']);
      return;
    }

    $id = (new TalentiaCandidato())->create([
      'nombre' => $nombre,
      'email' => trim($_POST['email'] ?? '') ?: null,
      'telefono' => trim($_POST['telefono'] ?? '') ?: null,
      'edad' => ($_POST['edad'] ?? '') !== '' ? (int)$_POST['edad'] : null,
      'experiencia_anios' => ($_POST['experiencia_anios'] ?? '') !== '' ? (int)$_POST['experiencia_anios'] : null,
      'skills' => trim($_POST['skills'] ?? '') ?: null,
      'idiomas' => trim($_POST['idiomas'] ?? '') ?: null,
      'nivel_ingles' => trim($_POST['nivel_ingles'] ?? '') ?: null,
      'puesto_actual' => trim($_POST['puesto_actual'] ?? '') ?: null,
      'archivo_pdf' => null,
      'cv_text' => trim($_POST['cv_text'] ?? '') ?: null,
      'creado_por_user_id' => Auth::id(),
    ]);

    $this->redirect('?r=talentia/view&id=' . $id);
  }

 public function ver(): void {
  RoleMiddleware::require(['super_admin','rrhh']);
  $id = (int)($_GET['id'] ?? 0);

  $row = (new TalentiaCandidato())->find($id);
  if (!$row) { http_response_code(404); echo "No encontrado"; return; }

  $this->view('talentia/view', [
    'row' => $row,
    'csrf' => Csrf::token(),
  ]);
}


  public function delete(): void {
    RoleMiddleware::require(['super_admin','rrhh']);
    Csrf::check($_POST['csrf'] ?? '');
    $id = (int)($_POST['id'] ?? 0);

    (new TalentiaCandidato())->delete($id);
    $this->redirect('?r=talentia/index');
  }
}
