<?php
class User extends Model {

  public function findByEmail(string $email): ?array {
    $st = $this->pdo->prepare("SELECT * FROM users WHERE email = :email LIMIT 1");
    $st->execute(['email' => $email]);
    $u = $st->fetch();
    return $u ?: null;
  }

  public function findWithEmployeeByEmail(string $email): ?array {
    $sql = "SELECT u.id,u.email,u.password_hash,u.rol,u.activo,u.employee_id,
                   e.nombre,e.apellidos
            FROM users u
            LEFT JOIN employees e ON e.id = u.employee_id
            WHERE u.email = :email
            LIMIT 1";
    $st = $this->pdo->prepare($sql);
    $st->execute(['email' => $email]);
    $u = $st->fetch();
    return $u ?: null;
  }

  public function updateLastLogin(int $id): void {
    $this->pdo->prepare("UPDATE users SET last_login_at = NOW() WHERE id = :id")->execute(['id'=>$id]);
  }

  public function listAll(): array {
    $sql = "SELECT u.id,u.email,u.rol,u.activo,u.last_login_at,
                   e.nombre,e.apellidos
            FROM users u
            LEFT JOIN employees e ON e.id = u.employee_id
            ORDER BY u.id DESC";
    return $this->pdo->query($sql)->fetchAll();
  }

  public function create(array $data): int {
    $sql = "INSERT INTO users (employee_id,email,password_hash,rol,activo)
            VALUES (:employee_id,:email,:password_hash,:rol,:activo)";
    $this->pdo->prepare($sql)->execute($data);
    return (int)$this->pdo->lastInsertId();
  }

  public function setResetToken(int $userId, string $hash, string $expiresAt): void {
    $sql = "UPDATE users
            SET reset_token_hash=:h, reset_token_expires_at=:e
            WHERE id=:id";
    $this->pdo->prepare($sql)->execute(['h'=>$hash,'e'=>$expiresAt,'id'=>$userId]);
  }

  public function findByResetToken(string $token): ?array {
    $now = (new DateTime())->format('Y-m-d H:i:s');
    $st = $this->pdo->prepare("SELECT * FROM users WHERE reset_token_hash IS NOT NULL AND reset_token_expires_at > :now");
    $st->execute(['now'=>$now]);
    while ($u = $st->fetch()) {
      if (password_verify($token, $u['reset_token_hash'])) {
        return $u;
      }
    }
    return null;
  }

  public function updatePassword(int $id, string $hash): void {
    $sql = "UPDATE users
            SET password_hash=:h, reset_token_hash=NULL, reset_token_expires_at=NULL
            WHERE id=:id";
    $this->pdo->prepare($sql)->execute(['h'=>$hash,'id'=>$id]);
  }
}
