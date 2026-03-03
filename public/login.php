<?php
// public/login.php
require_once '../components/auth.php';
require_once '../components/pdo.php';
require_once '../components/layout.php';

if (isLoggedIn()) {
    header('Location: ' . (isAdmin() ? 'admin_users.php' : 'profile.php'));
    exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $identifier = trim($_POST['identifier'] ?? '');
    $password   = $_POST['password'] ?? '';

    if (empty($identifier) || empty($password)) {
        $error = 'Please enter your username/email and password.';
    } else {
        try {
            $pdo  = getPDO();
            $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ? OR email = ? LIMIT 1");
            $stmt->execute([$identifier, $identifier]);
            $user = $stmt->fetch();

            if (!$user) {
                $error = 'No account found with that username or email.';
            } elseif (!password_verify($password, $user['password_hash'])) {
                $error = 'Incorrect password. Run the SQL fix below first, then use <strong>password</strong> as the password.';
            } else {
                session_regenerate_id(true);
                $_SESSION['user_id']   = $user['id'];
                $_SESSION['role']      = $user['role'];
                $_SESSION['firstname'] = $user['firstname'];
                $_SESSION['lastname']  = $user['lastname'];
                $_SESSION['username']  = $user['username'];
                header('Location: ' . ($user['role'] === 'admin' ? 'admin_users.php' : 'profile.php'));
                exit;
            }
        } catch (Exception $e) {
            $error = 'Database error: ' . $e->getMessage();
        }
    }
}

pageHeader('Sign In');
?>
<style>
body{display:flex;align-items:center;justify-content:center;min-height:100vh;padding:1rem}
.login-wrap{width:100%;max-width:440px}
.login-brand{text-align:center;margin-bottom:2rem}
.login-brand h1{font-family:'Syne',sans-serif;font-size:2rem;font-weight:800;letter-spacing:-1px}
.login-brand h1 span{color:var(--accent)}
.login-brand p{color:var(--muted);font-size:.9rem;margin-top:.35rem}
.login-card{background:var(--surface);border:1px solid var(--border);border-radius:14px;padding:2.25rem}
.login-card h2{font-family:'Syne',sans-serif;font-size:1.2rem;font-weight:700;margin-bottom:1.75rem}
.form-group{margin-bottom:1.1rem}
.btn-full{width:100%;justify-content:center;padding:.75rem;font-size:.95rem;margin-top:.5rem}
.demo-info{margin-top:1rem;padding:1rem 1.25rem;background:var(--surface2);border-radius:8px;border:1px solid var(--border)}
.demo-info p{font-size:.75rem;color:var(--muted);margin-bottom:.6rem;font-weight:600;text-transform:uppercase;letter-spacing:.08em}
.demo-row{display:flex;justify-content:space-between;align-items:center;font-size:.83rem;padding:.3rem 0;border-bottom:1px solid var(--border)}
.demo-row:last-child{border:none;padding-bottom:0}
.demo-label{color:var(--muted)}
.demo-val{font-family:monospace;background:var(--bg);padding:.15rem .5rem;border-radius:4px;font-size:.8rem}
.fix-box{margin-top:1rem;padding:1rem 1.25rem;background:#f59e0b12;border:1px solid #f59e0b55;border-radius:8px}
.fix-box p{font-size:.78rem;color:#fbbf24;font-weight:700;margin-bottom:.5rem}
.fix-box code{display:block;font-size:.72rem;background:#0008;padding:.65rem .85rem;border-radius:5px;color:#e8eaf0;word-break:break-all;line-height:1.6;margin-bottom:.4rem;cursor:pointer;border:1px solid #f59e0b33}
.fix-box small{font-size:.73rem;color:var(--muted)}
</style>

<div class="login-wrap">
  <div class="login-brand">
    <h1>User<span>Student Management</span></h1>
    <p>PHP + MySQL Student Management System</p>
  </div>

  <div class="login-card">
    <h2>Sign In to your account</h2>
    <?php if ($error): ?>
    <div class="alert alert-error">✕ <?= $error ?></div>
    <?php endif; ?>
    <form method="post" action="">
      <div class="form-group">
        <label for="identifier">Username or Email</label>
        <input type="text" id="identifier" name="identifier"
               value="<?= h($_POST['identifier'] ?? '') ?>"
               placeholder="e.g. mcruz_admin"
               autofocus autocomplete="username">
      </div>
      <div class="form-group">
        <label for="password">Password</label>
        <input type="password" id="password" name="password"
               placeholder="Enter password"
               autocomplete="current-password">
      </div>
      <button type="submit" class="btn btn-primary btn-full">Sign In →</button>
    </form>
  </div>

<?php pageFooter(); ?>
