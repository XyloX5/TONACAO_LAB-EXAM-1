<?php
require_once '../components/auth.php';
require_once '../components/pdo.php';
require_once '../components/layout.php';

requireLogin();

$pdo = getPDO();
$id  = currentUserId();

$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$id]);
$user = $stmt->fetch();

if (!$user) {
    session_destroy();
    header('Location: login.php');
    exit;
}

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $current  = $_POST['current_password'] ?? '';
    $new      = $_POST['new_password'] ?? '';
    $confirm  = $_POST['confirm_password'] ?? '';

    if (empty($current))              $errors[] = 'Current password is required.';
    if (empty($new))                  $errors[] = 'New password is required.';
    elseif (strlen($new) < 8)         $errors[] = 'New password must be at least 8 characters.';
    if ($new !== $confirm)            $errors[] = 'New password and confirmation do not match.';

    if (empty($errors)) {
        if (!password_verify($current, $user['password_hash'])) {
            $errors[] = 'Current password is incorrect.';
        } else {
            $hash = password_hash($new, PASSWORD_BCRYPT);
            $pdo->prepare("UPDATE users SET password_hash=? WHERE id=?")->execute([$hash, $id]);
            setFlash('success', 'Password changed successfully. Please log in again.');
            session_unset();
            session_destroy();
            header('Location: login.php');
            exit;
        }
    }
}

pageHeader('Change Password');
navBar();
?>
<div class="container" style="max-width:540px">
  <?php flashMessage(); ?>

  <div class="page-header">
    <div>
      <div class="page-title">Change Password</div>
      <div class="page-sub">Update your account password</div>
    </div>
    <a href="profile.php" class="btn btn-ghost">← Back to Profile</a>
  </div>

  <?php foreach ($errors as $e): ?>
  <div class="alert alert-error">✕ <?= h($e) ?></div>
  <?php endforeach; ?>

  <div class="card">
    <div style="display:flex;align-items:center;gap:1rem;margin-bottom:1.75rem;padding-bottom:1.25rem;border-bottom:1px solid var(--border)">
      <div style="width:44px;height:44px;border-radius:50%;background:linear-gradient(135deg,var(--accent),var(--accent2));display:flex;align-items:center;justify-content:center;font-size:1.25rem">🔑</div>
      <div>
        <div style="font-weight:500"><?= h(($user['firstname'] ?? '') . ' ' . ($user['lastname'] ?? '')) ?></div>
        <div style="font-size:.82rem;color:var(--muted)"><?= h($user['email']) ?></div>
      </div>
    </div>

    <form method="post" action="">
      <div class="form-group" style="margin-bottom:1.1rem">
        <label for="current_password">Current Password</label>
        <input type="password" id="current_password" name="current_password" placeholder="Enter your current password" required autocomplete="current-password">
      </div>
      <hr>
      <div class="form-group" style="margin-bottom:1.1rem;margin-top:1.1rem">
        <label for="new_password">New Password</label>
        <input type="password" id="new_password" name="new_password" placeholder="Min. 8 characters" required autocomplete="new-password">
      </div>
      <div class="form-group" style="margin-bottom:1.5rem">
        <label for="confirm_password">Confirm New Password</label>
        <input type="password" id="confirm_password" name="confirm_password" placeholder="Repeat new password" required autocomplete="new-password">
      </div>
      <div class="alert alert-info" style="margin-bottom:1.5rem">
        ℹ You will be logged out after changing your password for security.
      </div>
      <button type="submit" class="btn btn-primary" style="width:100%;justify-content:center">Change Password</button>
    </form>
  </div>
</div>
<?php pageFooter(); ?>
