<?php
class Document extends Model {

    /**
     * Lista los documentos de un empleado
     */
    public function listByEmployee(int $employeeId): array {
        $st = $this->pdo->prepare("SELECT * FROM documents WHERE employee_id = :e ORDER BY created_at DESC");
        $st->execute(['e' => $employeeId]);
        return $st->fetchAll();
    }

    /**
     * Sube un nuevo documento (asociado a un empleado)
     */
    public function upload(int $employeeId, string $nombre, string $path, string $mime, int $tamano, int $subidoPor): int {
        $sql = "INSERT INTO documents
                (employee_id, nombre, path, mime, tamano_bytes, subido_por_user_id)
                VALUES
                (:e, :n, :p, :m, :t, :u)";
        $this->pdo->prepare($sql)->execute([
            'e' => $employeeId,
            'n' => $nombre,
            'p' => $path,
            'm' => $mime,
            't' => $tamano,
            'u' => $subidoPor
        ]);
        return (int)$this->pdo->lastInsertId();
    }

    /**
     * Elimina un documento por su ID (opcional)
     */
    public function delete(int $id): void {
        $this->pdo->prepare("DELETE FROM documents WHERE id = :id")->execute(['id' => $id]);
    }
}