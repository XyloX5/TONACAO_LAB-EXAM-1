

<?php
require_once '../components/auth.php';
require_once '../components/pdo.php';
require_once '../components/layout.php';

requireAdmin();

$pdo = getPDO();
$id  = (int)($_GET['id'] ?? 0);

if ($id <= 0) {
    header('Location: admin_users.php');
    exit;
}

$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$id]);
$user = $stmt->fetch();

if (!$user) {
    setFlash('error', 'User not found.');
    header('Location: admin_users.php');
    exit;
}

$errors   = [];
$success  = false;
$passMsg  = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {

    if ($_POST['action'] === 'update_profile') {
        $data = array_map('trim', [
            'username'       => $_POST['username'] ?? '',
            'email'          => $_POST['email'] ?? '',
            'firstname'      => $_POST['firstname'] ?? '',
            'lastname'       => $_POST['lastname'] ?? '',
            'role'           => $_POST['role'] ?? 'user',
        ]);

        if (empty($data['username'])) $errors[] = 'Username is required.';
        if (empty($data['email']))     $errors[] = 'Email is required.';
        elseif (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) $errors[] = 'Invalid email.';
        if (!in_array($data['role'], ['admin','user'])) $data['role'] = 'user';

        if (empty($errors)) {
<<<<<<< HEAD
=======

>>>>>>> 9b05468 (changes)
            $check = $pdo->prepare("SELECT COUNT(*) FROM users WHERE (username=? OR email=?) AND id != ?");
            $check->execute([$data['username'], $data['email'], $id]);
            if ($check->fetchColumn() > 0) {
                $errors[] = 'Username or email already used by another account.';
            } else {
                $upd = $pdo->prepare("UPDATE users SET username=?,email=?,role=?,firstname=?,lastname=? WHERE id=?");
                $upd->execute([$data['username'],$data['email'],$data['role'],$data['firstname'],$data['lastname'],$id]);
                setFlash('success', "User updated successfully.");
                header("Location: user_edit.php?id={$id}");
                exit;
            }
        }
        $user = array_merge($user, $data);
    }

    elseif ($_POST['action'] === 'reset_password') {
        $newPass  = $_POST['new_password'] ?? '';
        $newPass2 = $_POST['new_password2'] ?? '';
        $passErrors = [];
        if (empty($newPass))           $passErrors[] = 'New password is required.';
        elseif (strlen($newPass) < 8)  $passErrors[] = 'Password must be at least 8 characters.';
        if ($newPass !== $newPass2)    $passErrors[] = 'Passwords do not match.';

        if (empty($passErrors)) {
            $hash = password_hash($newPass, PASSWORD_BCRYPT);
            $pdo->prepare("UPDATE users SET password_hash=? WHERE id=?")->execute([$hash, $id]);
            setFlash('success', "Password reset successfully for {$user['username']}.");
            header("Location: user_edit.php?id={$id}");
            exit;
        } else {
            $passMsg = implode(' ', $passErrors);
        }
    }
}

pageHeader('Edit User', 'Edit User — Admin');
navBar();
?>
<div class="container">
  <div class="page-header">
    <div>
      <div class="page-title">Edit User</div>
      <div class="page-sub">Editing account for <strong><?= h($user['username']) ?></strong></div>
    </div>
    <a href="admin_users.php" class="btn btn-ghost">← Back to Users</a>
  </div>

  <?php flashMessage(); ?>
  <?php foreach ($errors as $e): ?>
  <div class="alert alert-error">✕ <?= h($e) ?></div>
  <?php endforeach; ?>

  <div style="display:grid;grid-template-columns:2fr 1fr;gap:1.5rem;align-items:start">

<<<<<<< HEAD
=======

>>>>>>> 9b05468 (changes)
    <div class="card">
      <div class="card-title">Account Information</div>
      <form method="post" action="">
        <input type="hidden" name="action" value="update_profile">
        <div class="section-label">Account Details</div>
        <div class="form-grid">
          <div class="form-group">
            <label>Username <span style="color:var(--danger)">*</span></label>
            <input type="text" name="username" value="<?= h($user['username']) ?>" required>
          </div>
          <div class="form-group">
            <label>Email Address <span style="color:var(--danger)">*</span></label>
            <input type="email" name="email" value="<?= h($user['email']) ?>" required>
          </div>
          <div class="form-group">
            <label>Role</label>
            <select name="role">
              <option value="user"  <?= $user['role']==='user'?'selected':'' ?>>User</option>
              <option value="admin" <?= $user['role']==='admin'?'selected':'' ?>>Admin</option>
            </select>
          </div>
        </div>
        <hr>
        <div class="section-label" style="margin-top:1.5rem">Personal Information</div>
        <div class="form-grid">
          <div class="form-group">
            <label>First Name</label>
            <input type="text" name="firstname" value="<?= h($user['firstname'] ?? '') ?>">
          </div>
          <div class="form-group">
            <label>Last Name</label>
            <input type="text" name="lastname" value="<?= h($user['lastname'] ?? '') ?>">
          </div>
        <hr>
        <div style="display:flex;gap:.75rem;justify-content:flex-end;margin-top:1.5rem">
          <button type="submit" class="btn btn-primary">Save Changes</button>
        </div>
      </form>
    </div>

<<<<<<< HEAD
    <div style="display:flex;flex-direction:column;gap:1rem">
=======

    <div style="display:flex;flex-direction:column;gap:1rem">

>>>>>>> 9b05468 (changes)
      <div class="card">
        <div class="card-title">User Info</div>
        <div style="display:flex;flex-direction:column;gap:.6rem;font-size:.875rem">
          <div style="display:flex;justify-content:space-between">
            <span style="color:var(--muted)">ID</span>
            <span>#<?= h($user['id']) ?></span>
          </div>
          <div style="display:flex;justify-content:space-between">
            <span style="color:var(--muted)">Role</span>
            <span class="badge badge-<?= h($user['role']) ?>"><?= h($user['role']) ?></span>
          </div>
          <div style="display:flex;justify-content:space-between">
            <span style="color:var(--muted)">Joined</span>
            <span><?= h(date('M d, Y', strtotime($user['created_at']))) ?></span>
          </div>
        </div>
      </div>

<<<<<<< HEAD
=======

>>>>>>> 9b05468 (changes)
      <div class="card">
        <div class="card-title">Reset Password</div>
        <?php if ($passMsg): ?>
        <div class="alert alert-error" style="margin-bottom:1rem">✕ <?= h($passMsg) ?></div>
        <?php endif; ?>
        <form method="post" action="">
          <input type="hidden" name="action" value="reset_password">
          <div class="form-group" style="margin-bottom:.85rem">
            <label>New Password</label>
            <input type="password" name="new_password" placeholder="Min. 8 characters" required>
          </div>
          <div class="form-group" style="margin-bottom:1rem">
            <label>Confirm New Password</label>
            <input type="password" name="new_password2" placeholder="Repeat password" required>
          </div>
          <button type="submit" class="btn btn-warning" style="width:100%;justify-content:center">Reset Password</button>
        </form>
      </div>

<<<<<<< HEAD
=======

>>>>>>> 9b05468 (changes)
      <?php if ($user['id'] != currentUserId()): ?>
      <div class="card" style="border-color:#ef444444">
        <div class="card-title" style="color:var(--danger)">Danger Zone</div>
        <p style="font-size:.85rem;color:var(--muted);margin-bottom:1rem">Permanently delete this user account. This action cannot be undone.</p>
        <a href="user_delete.php?id=<?= $user['id'] ?>"
           class="btn btn-danger" style="width:100%;justify-content:center"
           onclick="return confirm('Delete <?= h(addslashes($user['username'])) ?>? This cannot be undone.')">Delete User</a>
      </div>
      <?php endif; ?>
    </div>

  </div>
</div>
<?php pageFooter(); ?>
