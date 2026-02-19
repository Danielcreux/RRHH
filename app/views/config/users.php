<div class="page-head">
  <h1>Usuarios del sistema</h1>
  <p class="muted">Solo Super Admin</p>
</div>

<div class="table-wrap">
  <table>
    <thead><tr><th>ID</th><th>Email</th><th>Rol</th><th>Activo</th><th>Último login</th><th>Empleado</th></tr></thead>
    <tbody>
      <?php foreach ($users as $u): ?>
        <tr>
          <td><?= (int)$u['id'] ?></td>
          <td><?= htmlspecialchars($u['email']) ?></td>
          <td><?= htmlspecialchars($u['rol']) ?></td>
          <td><?= ((int)$u['activo'] === 1) ? 'sí' : 'no' ?></td>
          <td><?= htmlspecialchars($u['last_login_at'] ?? '-') ?></td>
          <td><?= htmlspecialchars(trim(($u['nombre'] ?? '') . ' ' . ($u['apellidos'] ?? '')) ?: '-') ?></td>
        </tr>
      <?php endforeach; ?>
      <?php if (!$users): ?><tr><td colspan="6">Sin usuarios</td></tr><?php endif; ?>
    </tbody>
  </table>
</div>
