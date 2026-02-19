<?php
class Position extends Model {
  public function allActive(): array {
    $sql = "SELECT p.*, d.nombre AS departamento_nombre
            FROM positions p
            INNER JOIN departments d ON d.id = p.department_id
            WHERE p.activo=1
            ORDER BY d.nombre ASC, p.nombre ASC";
    return $this->pdo->query($sql)->fetchAll();
  }

  public function byDepartment(int $deptId): array {
    $st = $this->pdo->prepare("SELECT * FROM positions WHERE activo=1 AND department_id=:id ORDER BY nombre ASC");
    $st->execute(['id'=>$deptId]);
    return $st->fetchAll();
  }
}
