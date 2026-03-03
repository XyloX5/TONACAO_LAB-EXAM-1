<?php
require_once '../components/auth.php';
require_once '../components/pdo.php';
require_once '../components/layout.php';

requireAdmin();

$errors = [];
$data = ['username'=>'','email'=>'','firstname'=>'','lastname'=>'','role'=>'user'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = array_map('trim', [
        'username'       => $_POST['username'] ?? '',
        'email'          => $_POST['email'] ?? '',
        'firstname'      => $_POST['firstname'] ?? '',
        'lastname'       => $_POST['lastname'] ?? '',
        'role'           => $_POST['role'] ?? 'user',
    ]);
    $password  = $_POST['password'] ?? '';
    $password2 = $_POST['password2'] ?? '';

    if (empty($data['username']))   $errors[] = 'Username is required.';
    if (empty($data['email']))       $errors[] = 'Email is required.';
    elseif (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) $errors[] = 'Invalid email address.';
    if (empty($password))            $errors[] = 'Password is required.';
    elseif (strlen($password) < 8)   $errors[] = 'Password must be at least 8 characters.';
    if ($password !== $password2)    $errors[] = 'Passwords do not match.';
    if (!in_array($data['role'], ['admin','user'])) $data['role'] = 'user';

    if (empty($errors)) {
        $pdo = getPDO();
        $check = $pdo->prepare("SELECT COUNT(*) FROM users WHERE username=? OR email=?");
        $check->execute([$data['username'], $data['email']]);
        if ($check->fetchColumn() > 0) {
            $errors[] = 'Username or email already exists.';
        } else {
            $hash = password_hash($password, PASSWORD_BCRYPT);
            $stmt = $pdo->prepare("INSERT INTO users (username,email,password_hash,role,firstname,lastname) VALUES (?,?,?,?,?,?)");
            $stmt->execute([$data['username'],$data['email'],$hash,$data['role'],$data['firstname'],$data['lastname']]);
            setFlash('success', "User '{$data['username']}' created successfully.");
            header('Location: admin_users.php');
            exit;
        }
    }
}

pageHeader('Create User', 'Add New User');
navBar();
?>
<div class="container">
  <div class="page-header">
    <div>
      <div class="page-title">Add New Student</div>
      <div class="page-sub">Create a new user account</div>
    </div>
    <a href="admin_users.php" class="btn btn-ghost">← Back to Users</a>
  </div>

  <?php foreach ($errors as $e): ?>
  <div class="alert alert-error">✕ <?= h($e) ?></div>
  <?php endforeach; ?>

  <div class="card">
    <form method="post" action="">
      <div class="section-label">Account Details</div>
      <div class="form-grid">
        <div class="form-group">
          <label for="username">Username <span style="color:var(--danger)">*</span></label>
          <input type="text" id="username" name="username" value="<?= h($data['username']) ?>" placeholder="e.g. jdelacruz" required>
        </div>
        <div class="form-group">
          <label for="email">Email Address <span style="color:var(--danger)">*</span></label>
          <input type="email" id="email" name="email" value="<?= h($data['email']) ?>" placeholder="user@example.com" required>
        </div>
        <div class="form-group">
          <label for="password">Password <span style="color:var(--danger)">*</span></label>
          <input type="password" id="password" name="password" placeholder="Min. 8 characters" required>
        </div>
        <div class="form-group">
          <label for="password2">Confirm Password <span style="color:var(--danger)">*</span></label>
          <input type="password" id="password2" name="password2" placeholder="Repeat password" required>
        </div>
        <div class="form-group">
          <label for="role">Role</label>
          <select id="role" name="role">
            <option value="user" <?= $data['role']==='user'?'selected':'' ?>>User</option>
            <option value="admin" <?= $data['role']==='admin'?'selected':'' ?>>Admin</option>
          </select>
        </div>
      </div>

      <hr>
      <div class="section-label" style="margin-top:1.5rem">Personal Information</div>
      <div class="form-grid">
        <div class="form-group">
          <label for="firstname">First Name</label>
          <input type="text" id="firstname" name="firstname" value="<?= h($data['firstname']) ?>" placeholder="First name">
        </div>
        <div class="form-group">
          <label for="lastname">Last Name</label>
          <input type="text" id="lastname" name="lastname" value="<?= h($data['lastname']) ?>" placeholder="Last name">
        </div>

      <hr>
      <div style="display:flex;gap:.75rem;justify-content:flex-end;margin-top:1.5rem">
        <a href="admin_users.php" class="btn btn-ghost">Cancel</a>
        <button type="submit" class="btn btn-primary">Create User</button>
      </div>
    </form>
  </div>
</div>
<?php pageFooter(); ?>
