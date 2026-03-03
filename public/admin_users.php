<?php
// public/admin_users.php
require_once '../components/auth.php';
require_once '../components/pdo.php';
require_once '../components/layout.php';

requireAdmin(); // Only admin allowed

$pdo = getPDO();

// Stats
$total  = $pdo->query("SELECT COUNT(*) FROM users")->fetchColumn();
$admins = $pdo->query("SELECT COUNT(*) FROM users WHERE role='admin'")->fetchColumn();
$users  = $pdo->query("SELECT COUNT(*) FROM users WHERE role='user'")->fetchColumn();

// Fetch all users with search
$search = trim($_GET['search'] ?? '');
if ($search !== '') {
    $stmt = $pdo->prepare("SELECT * FROM users WHERE username LIKE ? OR email LIKE ? OR firstname LIKE ? OR lastname LIKE ? ORDER BY created_at DESC");
    $like = "%{$search}%";
    $stmt->execute([$like, $like, $like, $like]);
} else {
    $stmt = $pdo->query("SELECT * FROM users ORDER BY created_at DESC");
}
$allUsers = $stmt->fetchAll();

pageHeader('All Users', 'All Users — Admin');
navBar();
?>
<div class="container">
  <div class="page-header">
    <div>
      <div class="page-title">Student Management</div>
      <div class="page-sub">Manage all registered users in the system</div>
    </div>
    <a href="create-student.php" class="btn btn-primary">+ Add New Student</a>
  </div>

  <?php flashMessage(); ?>
  <?php if (isset($_GET['error']) && $_GET['error'] === 'access_denied'): ?>
  <div class="alert alert-error">✕ Access denied. You do not have permission to view that page.</div>
  <?php endif; ?>

  <!-- Stats -->
  <div class="stats-row">
    <div class="stat-card">
      <div class="stat-value"><?= $total ?></div>
      <div class="stat-label">Total Users</div>
    </div>
    <div class="stat-card">
      <div class="stat-value" style="color:var(--accent)"><?= $admins ?></div>
      <div class="stat-label">Administrators</div>
    </div>
    <div class="stat-card">
      <div class="stat-value" style="color:var(--success)"><?= $users ?></div>
      <div class="stat-label">Regular Users</div>
    </div>
  </div>

  <!-- Search -->
  <div class="card" style="margin-bottom:1.5rem;padding:1rem 1.25rem">
    <form method="get" action="" style="display:flex;gap:.75rem;align-items:center">
      <input type="text" name="search" value="<?= h($search) ?>"
             placeholder="Search by name, username, or email…" style="max-width:380px">
      <button type="submit" class="btn btn-ghost">Search</button>
      <?php if ($search): ?>
      <a href="admin_users.php" class="btn btn-ghost">✕ Clear</a>
      <?php endif; ?>
    </form>
  </div>

  <!-- Table -->
  <div class="table-wrap">
    <table>
      <thead>
        <tr>
          <th>#</th>
          <th>User</th>
          <th>Username</th>
          <th>Email</th>
          <th>Role</th>
          <th>Created</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php if (empty($allUsers)): ?>
        <tr><td colspan="10" style="text-align:center;color:var(--muted);padding:2rem">No users found.</td></tr>
        <?php else: ?>
        <?php foreach ($allUsers as $u): ?>
        <tr>
          <td style="color:var(--muted);font-size:.8rem"><?= h($u['id']) ?></td>
          <td>
            <div style="display:flex;align-items:center;gap:.65rem">
              <div style="width:32px;height:32px;border-radius:50%;background:linear-gradient(135deg,var(--accent),var(--accent2));display:flex;align-items:center;justify-content:center;font-family:'Syne',sans-serif;font-weight:700;font-size:.75rem;color:#fff;flex-shrink:0">
                <?= strtoupper(substr($u['firstname'] ?? 'U', 0, 1) . substr($u['lastname'] ?? '', 0, 1)) ?>
              </div>
              <div>
                <div style="font-weight:500"><?= h(($u['firstname'] ?? '') . ' ' . ($u['lastname'] ?? '')) ?></div>
              </div>
            </div>
          </td>
          <td><code style="font-size:.82rem;color:var(--muted)"><?= h($u['username']) ?></code></td>
          <td style="font-size:.85rem"><?= h($u['email']) ?></td>
          <td><span class="badge badge-<?= h($u['role']) ?>"><?= h($u['role']) ?></span></td>
          <td style="font-size:.78rem;color:var(--muted);white-space:nowrap">
            <?= h(date('M d, Y', strtotime($u['created_at']))) ?>
          </td>
          <td>
            <div class="actions">
              <a href="user_edit.php?id=<?= $u['id'] ?>" class="btn btn-ghost btn-sm">Edit</a>
              <?php if ($u['id'] != currentUserId()): ?>
              <a href="user_delete.php?id=<?= $u['id'] ?>" class="btn btn-danger btn-sm"
                 onclick="return confirm('Delete <?= h(addslashes($u['username'])) ?>? This cannot be undone.')">Delete</a>
              <?php else: ?>
              <span class="btn btn-ghost btn-sm" style="opacity:.4;cursor:not-allowed" title="Cannot delete yourself">Delete</span>
              <?php endif; ?>
            </div>
          </td>
        </tr>
        <?php endforeach; ?>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</div>
<?php pageFooter(); ?>
