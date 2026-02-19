<?php
class ConfigController extends Controller {

  public function settings(): void {
    RoleMiddleware::requireAdmin();
    $this->view('config/settings', ['csrf'=>Csrf::token()]);
  }

  public function users(): void {
    RoleMiddleware::requireAdmin();
    $users = (new User())->listAll();
    $this->view('config/users', ['users'=>$users,'csrf'=>Csrf::token()]);
  }
}
