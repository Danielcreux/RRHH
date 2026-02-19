<?php
class LeaveRequest extends Model {

  public function countPending(): int {
    $st = $this->pdo->query("SELECT COUNT(*) c FROM leave_requests WHERE estado='pending'");
    return (int)($st->fetch()['c'] ?? 0);
  }

  public function listForEmployee(int $employeeId): array {
    $st = $this->pdo->prepare("SELECT * FROM leave_requests WHERE employee_id=:e ORDER BY created_at DESC");
    $st->execute(['e' => $employeeId]);
    return $st->fetchAll();
  }

  public function listPending(): array {
    $sql = "SELECT lr.*, e.nombre, e.apellidos
            FROM leave_requests lr
            INNER JOIN employees e ON e.id = lr.employee_id
            WHERE lr.estado='pending'
            ORDER BY lr.created_at ASC";
    return $this->pdo->query($sql)->fetchAll();
  }

  public function create(array $data): int {
    $sql = "INSERT INTO leave_requests (employee_id, tipo, fecha_inicio, fecha_fin, motivo)
            VALUES (:employee_id, :tipo, :fecha_inicio, :fecha_fin, :motivo)";
    $this->pdo->prepare($sql)->execute($data);
    return (int)$this->pdo->lastInsertId();
  }

  public function approve(int $id, int $userId, ?string $comentario): void {
    $sql = "UPDATE leave_requests
            SET estado='approved', aprobado_por_user_id=:u, comentario_aprobacion=:c, resuelto_en=NOW()
            WHERE id=:id AND estado='pending'";
    $this->pdo->prepare($sql)->execute(['u' => $userId, 'c' => $comentario, 'id' => $id]);
  }

  public function reject(int $id, int $userId, ?string $comentario): void {
    $sql = "UPDATE leave_requests
            SET estado='rejected', aprobado_por_user_id=:u, comentario_aprobacion=:c, resuelto_en=NOW()
            WHERE id=:id AND estado='pending'";
    $this->pdo->prepare($sql)->execute(['u' => $userId, 'c' => $comentario, 'id' => $id]);
  }

  public function calendarApproved(string $from, string $to): array {
    $sql = "SELECT lr.*, e.nombre, e.apellidos
            FROM leave_requests lr
            INNER JOIN employees e ON e.id = lr.employee_id
            WHERE lr.estado='approved' AND lr.fecha_inicio <= :to AND lr.fecha_fin >= :from
            ORDER BY lr.fecha_inicio ASC";
    $st = $this->pdo->prepare($sql);
    $st->execute(['from' => $from, 'to' => $to]);
    return $st->fetchAll();
  }
}