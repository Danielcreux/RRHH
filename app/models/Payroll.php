<?php
class Payroll extends Model {

  public function countDraft(): int {
    $st = $this->pdo->query("SELECT COUNT(*) c FROM payroll WHERE estado='draft'");
    return (int)($st->fetch()['c'] ?? 0);
  }

  public function list(array $filters = []): array {
    $where = [];
    $params = [];

    if (!empty($filters['employee_id'])) {
      $where[] = "p.employee_id = :eid";
      $params['eid'] = (int)$filters['employee_id'];
    }
    if (!empty($filters['anio'])) {
      $where[] = "p.periodo_anio = :a";
      $params['a'] = (int)$filters['anio'];
    }
    if (!empty($filters['mes'])) {
      $where[] = "p.periodo_mes = :m";
      $params['m'] = (int)$filters['mes'];
    }

    $sql = "SELECT p.*, e.nombre, e.apellidos
            FROM payroll p
            INNER JOIN employees e ON e.id = p.employee_id";
    if ($where) $sql .= " WHERE " . implode(" AND ", $where);
    $sql .= " ORDER BY p.periodo_anio DESC, p.periodo_mes DESC, p.id DESC";

    $st = $this->pdo->prepare($sql);
    $st->execute($params);
    return $st->fetchAll();
  }

  public function find(int $id): ?array {
    $sql = "SELECT p.*, e.nombre, e.apellidos, e.email
            FROM payroll p
            INNER JOIN employees e ON e.id = p.employee_id
            WHERE p.id=:id LIMIT 1";
    $st = $this->pdo->prepare($sql);
    $st->execute(['id'=>$id]);
    $row = $st->fetch();
    return $row ?: null;
  }

  public function create(array $data): int {
    $sql = "INSERT INTO payroll
      (employee_id, periodo_mes, periodo_anio, salario_base, bonos, deducciones, total, estado, generado_por_user_id)
      VALUES
      (:employee_id,:periodo_mes,:periodo_anio,:salario_base,:bonos,:deducciones,:total,:estado,:generado_por_user_id)";
    $this->pdo->prepare($sql)->execute($data);
    return (int)$this->pdo->lastInsertId();
  }

  public function setStatusApproved(int $id, int $userId): void {
    $sql = "UPDATE payroll SET estado='approved', aprobado_por_user_id=:u, aprobado_en=NOW() WHERE id=:id AND estado='draft'";
    $this->pdo->prepare($sql)->execute(['u'=>$userId,'id'=>$id]);
  }

  public function setStatusPaid(int $id): void {
    $sql = "UPDATE payroll SET estado='paid', pagado_en=NOW() WHERE id=:id AND estado='approved'";
    $this->pdo->prepare($sql)->execute(['id'=>$id]);
  }

  public function listByEmployee(int $employeeId): array {
    $st = $this->pdo->prepare("SELECT * FROM payroll WHERE employee_id=:e ORDER BY periodo_anio DESC, periodo_mes DESC");
    $st->execute(['e'=>$employeeId]);
    return $st->fetchAll();
  }
}
