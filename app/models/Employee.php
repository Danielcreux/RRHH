<?php
class Employee extends Model {

  public function countActive(): int {
    $st = $this->pdo->query("SELECT COUNT(*) c FROM employees WHERE estado='activo'");
    return (int)($st->fetch()['c'] ?? 0);
  }

  public function list(array $filters = []): array {
    $where = [];
    $params = [];

    if (!empty($filters['q'])) {
      $where[] = "(e.nombre LIKE :q OR e.apellidos LIKE :q OR e.email LIKE :q)";
      $params['q'] = '%' . $filters['q'] . '%';
    }
    if (!empty($filters['department_id'])) {
      $where[] = "e.department_id = :dep";
      $params['dep'] = (int)$filters['department_id'];
    }
    if (!empty($filters['estado'])) {
      $where[] = "e.estado = :estado";
      $params['estado'] = $filters['estado'];
    }

    $sql = "SELECT e.*, d.nombre AS departamento, p.nombre AS puesto
            FROM employees e
            INNER JOIN departments d ON d.id = e.department_id
            INNER JOIN positions p ON p.id = e.position_id";

    if ($where) $sql .= " WHERE " . implode(" AND ", $where);
    $sql .= " ORDER BY e.id DESC";

    $st = $this->pdo->prepare($sql);
    $st->execute($params);
    return $st->fetchAll();
  }

  public function find(int $id): ?array {
    $sql = "SELECT e.*, d.nombre AS departamento, p.nombre AS puesto
            FROM employees e
            INNER JOIN departments d ON d.id = e.department_id
            INNER JOIN positions p ON p.id = e.position_id
            WHERE e.id=:id LIMIT 1";
    $st = $this->pdo->prepare($sql);
    $st->execute(['id'=>$id]);
    $row = $st->fetch();
    return $row ?: null;
  }

  public function create(array $data): int {
    $sql = "INSERT INTO employees
      (department_id, position_id, nombre, apellidos, email, telefono, direccion, fecha_nacimiento, fecha_ingreso,
       salario_base, estado, contacto_emergencia_nombre, contacto_emergencia_telefono, foto_path)
      VALUES
      (:department_id,:position_id,:nombre,:apellidos,:email,:telefono,:direccion,:fecha_nacimiento,:fecha_ingreso,
       :salario_base,:estado,:contacto_emergencia_nombre,:contacto_emergencia_telefono,:foto_path)";
    $this->pdo->prepare($sql)->execute($data);
    return (int)$this->pdo->lastInsertId();
  }

  public function update(int $id, array $data): void {
    $data['id'] = $id;
    $sql = "UPDATE employees SET
      department_id=:department_id,
      position_id=:position_id,
      nombre=:nombre,
      apellidos=:apellidos,
      email=:email,
      telefono=:telefono,
      direccion=:direccion,
      fecha_nacimiento=:fecha_nacimiento,
      fecha_ingreso=:fecha_ingreso,
      salario_base=:salario_base,
      estado=:estado,
      contacto_emergencia_nombre=:contacto_emergencia_nombre,
      contacto_emergencia_telefono=:contacto_emergencia_telefono,
      foto_path=:foto_path
      WHERE id=:id";
    $this->pdo->prepare($sql)->execute($data);
  }

  public function delete(int $id): void {
    $this->pdo->prepare("DELETE FROM employees WHERE id=:id")->execute(['id'=>$id]);
  }
}
