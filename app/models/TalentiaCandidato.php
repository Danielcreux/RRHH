<?php
final class TalentiaCandidato extends Model {

  public function all(): array {
    $sql = "SELECT *
            FROM talentia_candidatos
            ORDER BY fecha_registro DESC";
    return $this->pdo->query($sql)->fetchAll();
  }

  public function find(int $id): ?array {
    $st = $this->pdo->prepare("SELECT * FROM talentia_candidatos WHERE id=:id LIMIT 1");
    $st->execute(['id'=>$id]);
    $r = $st->fetch();
    return $r ?: null;
  }

  public function create(array $d): int {
    $sql = "INSERT INTO talentia_candidatos
      (nombre,email,telefono,edad,experiencia_anios,skills,idiomas,nivel_ingles,puesto_actual,
       archivo_pdf,cv_text,estado,empleado_id,fecha_registro,creado_por_user_id)
      VALUES
      (:nombre,:email,:telefono,:edad,:exp,:skills,:idiomas,:nivel,:puesto,
       :pdf,:cv_text,:estado,:empleado_id,NOW(),:creado_por_user_id)";

    $this->pdo->prepare($sql)->execute([
      'nombre' => $d['nombre'],
      'email' => $d['email'] ?? null,
      'telefono' => $d['telefono'] ?? null,
      'edad' => $d['edad'] ?? null,
      'exp' => $d['experiencia_anios'] ?? null,
      'skills' => $d['skills'] ?? null,
      'idiomas' => $d['idiomas'] ?? null,
      'nivel' => $d['nivel_ingles'] ?? null,
      'puesto' => $d['puesto_actual'] ?? null,
      'pdf' => $d['archivo_pdf'] ?? null,
      'cv_text' => $d['cv_text'] ?? null,
      'estado' => $d['estado'] ?? 'nuevo',
      'empleado_id' => $d['empleado_id'] ?? null,
      'creado_por_user_id' => $d['creado_por_user_id'] ?? null,
    ]);

    return (int)$this->pdo->lastInsertId();
  }

  public function updateEstado(int $id, string $estado): void {
    $st = $this->pdo->prepare("UPDATE talentia_candidatos SET estado=:e WHERE id=:id");
    $st->execute(['e'=>$estado,'id'=>$id]);
  }

  public function delete(int $id): void {
    $this->pdo->prepare("DELETE FROM talentia_candidatos WHERE id=:id")->execute(['id'=>$id]);
  }

  /**
   * Para el prompt (equivalente a tu cv_storage.json).
   */
  public function allAsJson(): string {
    $sql = "SELECT nombre, edad, experiencia_anios, skills, idiomas, nivel_ingles, puesto_actual
            FROM talentia_candidatos
            ORDER BY fecha_registro DESC";
    $rows = $this->pdo->query($sql)->fetchAll();

    foreach ($rows as &$r) {
      $r['skills'] = $r['skills'] ? array_values(array_filter(array_map('trim', explode(',', $r['skills'])))) : [];
      $r['idiomas'] = $r['idiomas'] ? array_values(array_filter(array_map('trim', explode(',', $r['idiomas'])))) : [];
      $r['edad'] = $r['edad'] !== null ? (int)$r['edad'] : null;
      $r['experiencia_anios'] = $r['experiencia_anios'] !== null ? (int)$r['experiencia_anios'] : null;
    }

    return json_encode($rows, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
  }
}
