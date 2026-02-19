<?php
class AuthController extends Controller {

  public function loginForm(): void {
     if (Auth::check()) {
        $this->redirect('?r=dashboard/index'); 
    }
    $this->viewPlain('auth/login', ['csrf' => Csrf::token()]);
}

  public function login(): void {
  Csrf::check($_POST['csrf'] ?? '');

  $email = trim($_POST['email'] ?? '');
  $pass  = $_POST['password'] ?? '';

  if (!Validator::email($email) || $pass === '') {
    $this->viewPlain('auth/login', ['error'=>'Credenciales inválidas', 'csrf'=>Csrf::token()]);
     return;
  }

  $um = new User();
  $u = $um->findWithEmployeeByEmail($email);

  if (!$u || (int)$u['activo'] !== 1 || !password_verify($pass, $u['password_hash'])) {
     $this->viewPlain('auth/login', ['error'=>'Email o contraseña incorrectos', 'csrf'=>Csrf::token()]);
      return;
  }

  session_regenerate_id(true);
  $_SESSION['user'] = [
    'id' => (int)$u['id'],
    'rol' => $u['rol'],
    'employee_id' => $u['employee_id'] ? (int)$u['employee_id'] : null,
    'nombre' => trim(($u['nombre'] ?? '') . ' ' . ($u['apellidos'] ?? '')),
  ];

  $um->updateLastLogin((int)$u['id']);
  (new ActivityLog())->log(Auth::id(), 'login', 'users', (int)$u['id'], 'Inicio de sesión');

  $this->redirect('?r=dashboard/index');
}


  public function logout(): void {
    (new ActivityLog())->log(Auth::id(), 'logout', 'users', Auth::id(), 'Cierre de sesión');
    session_unset();
    session_destroy();
    $this->redirect('?r=auth/loginForm');
  }

  public function forgotForm(): void {
    $this->viewPlain('auth/forgot', ['csrf'=>Csrf::token()]);
  }

  public function forgot(): void {
    Csrf::check($_POST['csrf'] ?? '');

    $email = trim($_POST['email'] ?? '');
    if (!Validator::email($email)) {
        $this->viewPlain('auth/forgot', ['error' => 'Email inválido', 'csrf' => Csrf::token()]);
        return;
    }

    $um = new User();
    $u = $um->findByEmail($email);
    $msgOk = "Si el email existe, se enviará un enlace de recuperación.";

    if (!$u) {
    $this->viewPlain('auth/forgot', ['ok'=>$msgOk, 'csrf'=>Csrf::token()]);
    return;
}

    $token = bin2hex(random_bytes(24));
    $hash = password_hash($token, PASSWORD_DEFAULT);
    $expires = (new DateTime('+30 minutes'))->format('Y-m-d H:i:s');

    $um->setResetToken((int)$u['id'], $hash, $expires);

    // En producción: enviar email (PHPMailer)
    // Aquí: mostramos el enlace (modo demo)
    $link = "/?r=auth/resetForm&token=" . urlencode($token);

    (new ActivityLog())->log(null, 'forgot_password', 'users', (int)$u['id'], 'Generado token reset');

    $this->viewPlain('auth/forgot', [
        'ok' => $msgOk,
        'demo_link' => $link,
        'csrf' => Csrf::token()
    ]);
}
  public function resetForm(): void {
    $token = $_GET['token'] ?? '';
    if ($token === '') {
       $this->viewPlain('auth/reset_password', ['error'=>'Token faltante', 'csrf'=>Csrf::token(), 'token'=>'']);
        return;
    }
    $this->viewPlain('auth/reset_password', ['csrf'=>Csrf::token(), 'token'=>$token]);
  }

  public function reset(): void {
    Csrf::check($_POST['csrf'] ?? '');
    $token = $_POST['token'] ?? '';
    $pass1 = $_POST['password'] ?? '';
    $pass2 = $_POST['password2'] ?? '';

    if ($token === '' || $pass1 === '' || $pass1 !== $pass2) {
       $this->viewPlain('auth/reset_password', ['error'=>'Datos inválidos', 'csrf'=>Csrf::token(), 'token'=>$token]);
        return;
    }

    $um = new User();
    $u = $um->findByResetToken($token);
    if (!$u) {
       $this->viewPlain('auth/reset_password', ['error'=>'Token inválido o caducado', 'csrf'=>Csrf::token(), 'token'=>$token]);
        return;
    }

    $hash = password_hash($pass1, PASSWORD_DEFAULT);
    $um->updatePassword((int)$u['id'], $hash);

    (new ActivityLog())->log(null, 'reset_password', 'users', (int)$u['id'], 'Cambio de contraseña');

    $this->viewPlain('auth/login', ['ok'=>'Contraseña actualizada. Ya puedes iniciar sesión.', 'csrf'=>Csrf::token()]);
  }
}
