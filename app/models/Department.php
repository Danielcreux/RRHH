<?php
class Department extends Model {
  public function allActive(): array {
    $st = $this->pdo->query("SELECT * FROM departments WHERE activo=1 ORDER BY nombre ASC");
    return $st->fetchAll();
  }
}
