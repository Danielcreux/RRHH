<?php
class Attendance extends Model {

  public function countPendingToday(): int {
    $hoy = (new DateTime())->format('Y-m-d');
    $st = $this->pdo->prepare("SELECT COUNT(*) c FROM attendance WHERE fecha=:f AND (hora_entrada IS NULL OR hora_salida IS NULL)");
    $st->execute(['f'=>$hoy]);
    return (int)($st->fetch()['c'] ?? 0);
  }

  public function getDayBoard(string $fecha, array $filters = []): array {
    $where = ["a.fecha = :f"];
    $params = ['f'=>$fecha];

    if (!empty($filters['employee_id'])) {
      $where[] = "e.id = :eid";
      $params['eid'] = (int)$filters['employee_id'];
    }
    if (!empty($filters['department_id'])) {
      $where[] = "e.department_id = :dep";
      $params['dep'] = (int)$filters['department_id'];
    }

    $sql = "SELECT a.*, e.nombre, e.apellidos, e.foto_path,
                   d.nombre AS departamento
            FROM attendance a
            INNER JOIN employees e ON e.id = a.employee_id
            INNER JOIN departments d ON d.id = e.department_id
            WHERE " . implode(" AND ", $where) . "
            ORDER BY e.apellidos ASC";

    $st = $this->pdo->prepare($sql);
    $st->execute($params);
    return $st->fetchAll();
  }

  public function findByEmployeeAndDate(int $employeeId, string $fecha): ?array {
    $st = $this->pdo->prepare("SELECT * FROM attendance WHERE employee_id=:e AND fecha=:f LIMIT 1");
    $st->execute(['e'=>$employeeId,'f'=>$fecha]);
    $row = $st->fetch();
    return $row ?: null;
  }

  public function insertEntry(int $employeeId, string $fecha, string $horaEntrada, ?string $ip): void {
    $sql = "INSERT INTO attendance (employee_id, fecha, hora_entrada, origen_ip, estado)
            VALUES (:e,:f,:h,:ip,'a_tiempo')";
    $this->pdo->prepare($sql)->execute(['e'=>$employeeId,'f'=>$fecha,'h'=>$horaEntrada,'ip'=>$ip]);
  }

  public function setExit(int $id, string $horaSalida, int $minutos): void {
    $sql = "UPDATE attendance SET hora_salida=:hs, minutos_trabajados=:m WHERE id=:id";
    $this->pdo->prepare($sql)->execute(['hs'=>$horaSalida,'m'=>$minutos,'id'=>$id]);
  }

  public function listByEmployee(int $employeeId, int $limit = 60): array {
    $st = $this->pdo->prepare("SELECT * FROM attendance WHERE employee_id=:e ORDER BY fecha DESC LIMIT {$limit}");
    $st->execute(['e'=>$employeeId]);
    return $st->fetchAll();
  }

  public function manualEdit(int $attendanceId, array $data): void {
    $data['id'] = $attendanceId;
    $sql = "UPDATE attendance SET
              hora_entrada=:hora_entrada,
              hora_salida=:hora_salida,
              minutos_trabajados=:minutos_trabajados,
              estado=:estado,
              editado_manual=1,
              editado_por_user_id=:editado_por_user_id,
              motivo_edicion=:motivo_edicion
            WHERE id=:id";
    $this->pdo->prepare($sql)->execute($data);
  }
}
