<?php
// public/profile.php
require_once '../components/auth.php';
require_once '../components/pdo.php';
require_once '../components/layout.php';

requireLogin();

$pdo = getPDO();
// SECURITY: Always load by session user_id — never trust URL parameter
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
    $data = array_map('trim', [
        'firstname'      => $_POST['firstname'] ?? '',
        'lastname'       => $_POST['lastname'] ?? '',
    ]);

    if (empty($errors)) {
        $upd = $pdo->prepare("UPDATE users SET firstname=?,lastname=? WHERE id=?");
        $upd->execute([$data['firstname'],$data['lastname'],$id]);

        // Update session
        $_SESSION['firstname'] = $data['firstname'];
        $_SESSION['lastname']  = $data['lastname'];

        setFlash('success', 'Profile updated successfully.');
        header('Location: profile.php');
        exit;
    }
    $user = array_merge($user, $data);
}

pageHeader('My Profile', 'My Profile');
navBar();
?>
<div class="container">
  <?php if (isset($_GET['error']) && $_GET['error'] === 'access_denied'): ?>
  <div class="alert alert-error">✕ Access denied. You do not have permission to access that page.</div>
  <?php endif; ?>

  <?php flashMessage(); ?>

  <div class="page-header">
    <div>
      <div class="page-title">My Profile</div>
      <div class="page-sub">View and update your personal information</div>
    </div>
    <a href="change_password.php" class="btn btn-ghost">🔑 Change Password</a>
  </div>

  <!-- Profile Header -->
  <div class="card" style="margin-bottom:1.5rem">
    <div class="profile-header">
      <div class="profile-avatar">
        <?= strtoupper(substr($user['firstname'] ?? 'U', 0, 1) . substr($user['lastname'] ?? '', 0, 1)) ?>
      </div>
      <div class="profile-info">
        <h2><?= h(($user['firstname'] ?? '') . ' ' . ($user['lastname'] ?? '')) ?></h2>
        <p><?= h($user['email']) ?> &nbsp;·&nbsp; <span class="badge badge-<?= h($user['role']) ?>"><?= h($user['role']) ?></span></p>
      </div>
    </div>

    <div class="section-label">Account (Read-only)</div>
    <div class="form-grid" style="margin-bottom:1.5rem">
      <div class="form-group">
        <label>Username</label>
        <input type="text" value="<?= h($user['username']) ?>" readonly>
      </div>
      <div class="form-group">
        <label>Email Address</label>
        <input type="text" value="<?= h($user['email']) ?>" readonly>
      </div>
      <div class="form-group">
        <label>Role</label>
        <input type="text" value="<?= h($user['role']) ?>" readonly>
      </div>
      <div class="form-group">
        <label>Member Since</label>
        <input type="text" value="<?= h(date('F d, Y', strtotime($user['created_at']))) ?>" readonly>
      </div>
    </div>

    <hr>
    <div class="section-label" style="margin-top:1.5rem">Edit Personal Info</div>
    <?php foreach ($errors as $e): ?>
    <div class="alert alert-error">✕ <?= h($e) ?></div>
    <?php endforeach; ?>
    <form method="post" action="">
      <div class="form-grid">
        <div class="form-group">
          <label for="firstname">First Name</label>
          <input type="text" id="firstname" name="firstname" value="<?= h($user['firstname'] ?? '') ?>" placeholder="Your first name">
        </div>
        <div class="form-group">
          <label for="lastname">Last Name</label>
          <input type="text" id="lastname" name="lastname" value="<?= h($user['lastname'] ?? '') ?>" placeholder="Your last name">
        </div>
      <div style="display:flex;gap:.75rem;justify-content:flex-end;margin-top:1.5rem">
        <button type="submit" class="btn btn-primary">Save Changes</button>
      </div>
    </form>
  </div>
</div>
<?php pageFooter(); ?>
