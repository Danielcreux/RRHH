<?php
class ActivityLog extends Model {

  public function log(?int $userId, string $accion, ?string $entidad = null, ?int $entidadId = null, ?string $detalle = null): void {
    $sql = "INSERT INTO activity_logs (user_id,accion,entidad,entidad_id,detalle,ip,user_agent)
            VALUES (:u,:a,:e,:eid,:d,:ip,:ua)";
    $this->pdo->prepare($sql)->execute([
      'u'=>$userId,
      'a'=>$accion,
      'e'=>$entidad,
      'eid'=>$entidadId,
      'd'=>$detalle,
      'ip'=>$_SERVER['REMOTE_ADDR'] ?? null,
      'ua'=>substr($_SERVER['HTTP_USER_AGENT'] ?? '', 0, 255),
    ]);
  }

  public function last(int $limit = 50): array {
    $sql = "SELECT l.*, u.email
            FROM activity_logs l
            LEFT JOIN users u ON u.id = l.user_id
            ORDER BY l.created_at DESC
            LIMIT {$limit}";
    return $this->pdo->query($sql)->fetchAll();
  }
}
