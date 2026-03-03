<?php
function pageHeader(string $title, string $pageTitle = ''): void {
    if (!$pageTitle) $pageTitle = $title;
    echo <<<HTML
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>{$pageTitle} — Student Management</title>
<link href="https://fonts.googleapis.com/css2?family=Syne:wght@400;600;700;800&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">
<style>
:root {
  /* alternate palette provided by user */
  --bg: #355872;          
  --surface: #7AAACE;    
  --surface2: #7AAACE;    
  --border: #000000;     
  --accent: #000000;      
  --accent2: #355872;     
  --success: #08b447;    
  --danger: #ef4444;     
  --warning: #f59e0b;    
  --text: #fdfdfd;       
  --muted: #000000;      
  --radius: 10px;
}
*,*::before,*::after{box-sizing:border-box;margin:0;padding:0}
body{background:var(--bg);color:var(--text);font-family:'DM Sans',sans-serif;min-height:100vh;font-size:15px}
a{color:var(--accent);text-decoration:none}
a:hover{opacity:.85}

/* NAV */
.nav{background:var(--surface);border-bottom:1px solid var(--border);padding:0 2rem;display:flex;align-items:center;gap:2rem;height:60px;position:sticky;top:0;z-index:100}
.nav-brand{font-family:'Syne',sans-serif;font-weight:800;font-size:1.2rem;color:var(--text);letter-spacing:-.5px}
.nav-brand span{color:var(--accent)}
.nav-links{display:flex;gap:.25rem;margin-left:auto}
.nav-link{padding:.4rem .9rem;border-radius:6px;color:var(--muted);font-size:.875rem;font-weight:500;transition:.15s}
.nav-link:hover,.nav-link.active{background:var(--surface2);color:var(--text)}
.nav-user{display:flex;align-items:center;gap:.75rem;padding-left:1rem;border-left:1px solid var(--border)}
.nav-avatar{width:32px;height:32px;border-radius:50%;background:linear-gradient(135deg,var(--accent),var(--accent2));display:flex;align-items:center;justify-content:center;font-family:'Syne',sans-serif;font-weight:700;font-size:.8rem;color:#fff}
.nav-name{font-size:.85rem;font-weight:500}
.nav-role{font-size:.72rem;color:var(--muted)}
.btn-logout{background:transparent;border:1px solid var(--border);color:var(--muted);padding:.35rem .8rem;border-radius:6px;font-size:.8rem;cursor:pointer;font-family:'DM Sans',sans-serif;transition:.15s}
.btn-logout:hover{border-color:var(--danger);color:var(--danger)}

/* LAYOUT */
.container{max-width:1200px;margin:0 auto;padding:2rem 1.5rem}
.page-title{font-family:'Syne',sans-serif;font-weight:700;font-size:1.6rem;margin-bottom:.25rem}
.page-sub{color:var(--muted);font-size:.9rem;margin-bottom:2rem}

/* CARD */
.card{background:var(--surface);border:1px solid var(--border);border-radius:var(--radius);padding:1.75rem}
.card-title{font-family:'Syne',sans-serif;font-weight:700;font-size:1.1rem;margin-bottom:1.25rem}

/* BUTTONS */
.btn{display:inline-flex;align-items:center;gap:.4rem;padding:.55rem 1.2rem;border-radius:7px;font-size:.875rem;font-weight:500;cursor:pointer;border:none;font-family:'DM Sans',sans-serif;transition:.15s;text-decoration:none}
.btn-primary{background:var(--accent);color:#fff}
.btn-primary:hover{background:#4a6df0;opacity:1}
.btn-success{background:var(--success);color:#fff}
.btn-success:hover{background:#16a34a;opacity:1}
.btn-danger{background:var(--danger);color:#fff}
.btn-danger:hover{background:#dc2626;opacity:1}
.btn-warning{background:var(--warning);color:#000}
.btn-warning:hover{background:#d97706;opacity:1}
.btn-ghost{background:var(--surface2);color:var(--text);border:1px solid var(--border)}
.btn-ghost:hover{background:var(--border);opacity:1}
.btn-sm{padding:.35rem .8rem;font-size:.8rem}

/* TABLE */
.table-wrap{overflow-x:auto;border-radius:var(--radius);border:1px solid var(--border)}
table{width:100%;border-collapse:collapse}
thead tr{background:var(--surface2)}
th{padding:.85rem 1rem;text-align:left;font-family:'Syne',sans-serif;font-size:.78rem;font-weight:600;letter-spacing:.05em;color:var(--muted);text-transform:uppercase;white-space:nowrap}
td{padding:.8rem 1rem;border-top:1px solid var(--border);font-size:.875rem;vertical-align:middle}
tr:hover td{background:var(--surface2)}
.actions{display:flex;gap:.4rem;flex-wrap:wrap}

/* BADGES */
.badge{display:inline-block;padding:.2rem .65rem;border-radius:20px;font-size:.72rem;font-weight:600;letter-spacing:.04em;text-transform:uppercase}
.badge-admin{background:#5b7fff22;color:var(--accent);border:1px solid #5b7fff44}
.badge-user{background:#22c55e22;color:var(--success);border:1px solid #22c55e44}

/* FORMS */
.form-grid{display:grid;grid-template-columns:1fr 1fr;gap:1.25rem}
.form-group{display:flex;flex-direction:column;gap:.4rem}
.form-group.full{grid-column:1/-1}
label{font-size:.82rem;font-weight:500;color:var(--muted)}
input,select,textarea{background:var(--surface2);border:1px solid var(--border);color:var(--text);border-radius:7px;padding:.65rem .9rem;font-size:.9rem;font-family:'DM Sans',sans-serif;width:100%;transition:.15s;outline:none}
input:focus,select:focus,textarea:focus{border-color:var(--accent);box-shadow:0 0 0 3px #5b7fff22}
input[readonly]{opacity:.6;cursor:not-allowed}
select option{background:var(--surface2)}

/* ALERTS */
.alert{padding:.85rem 1.1rem;border-radius:8px;font-size:.875rem;margin-bottom:1.25rem;display:flex;align-items:center;gap:.6rem}
.alert-success{background:#22c55e18;border:1px solid #22c55e44;color:#4ade80}
.alert-error{background:#ef444418;border:1px solid #ef444444;color:#f87171}
.alert-info{background:#5b7fff18;border:1px solid #5b7fff44;color:#93b4ff}
.alert-warning{background:#f59e0b18;border:1px solid #f59e0b44;color:#fbbf24}

/* HEADER ROW */
.page-header{display:flex;align-items:flex-start;justify-content:space-between;margin-bottom:2rem;flex-wrap:wrap;gap:1rem}

/* STATS ROW */
.stats-row{display:grid;grid-template-columns:repeat(auto-fit,minmax(200px,1fr));gap:1rem;margin-bottom:2rem}
.stat-card{background:var(--surface);border:1px solid var(--border);border-radius:var(--radius);padding:1.25rem 1.5rem}
.stat-value{font-family:'Syne',sans-serif;font-size:2rem;font-weight:800;color:var(--text)}
.stat-label{font-size:.8rem;color:var(--muted);margin-top:.2rem}

/* DIVIDER */
hr{border:none;border-top:1px solid var(--border);margin:1.5rem 0}

/* PROFILE */
.profile-header{display:flex;align-items:center;gap:1.5rem;margin-bottom:2rem}
.profile-avatar{width:72px;height:72px;border-radius:50%;background:linear-gradient(135deg,var(--accent),var(--accent2));display:flex;align-items:center;justify-content:center;font-family:'Syne',sans-serif;font-weight:800;font-size:1.75rem;color:#fff;flex-shrink:0}
.profile-info h2{font-family:'Syne',sans-serif;font-size:1.4rem;font-weight:700}
.profile-info p{color:var(--muted);font-size:.875rem}

/* SECTION DIVIDER */
.section-label{font-family:'Syne',sans-serif;font-size:.78rem;font-weight:700;text-transform:uppercase;letter-spacing:.1em;color:var(--muted);margin-bottom:1rem;padding-bottom:.5rem;border-bottom:1px solid var(--border)}

/* CONFIRM MODAL */
.modal-overlay{position:fixed;inset:0;background:#000000bb;z-index:200;display:flex;align-items:center;justify-content:center;backdrop-filter:blur(4px)}
.modal-box{background:var(--surface);border:1px solid var(--border);border-radius:var(--radius);padding:2rem;width:100%;max-width:420px}
.modal-title{font-family:'Syne',sans-serif;font-weight:700;font-size:1.1rem;margin-bottom:.75rem}
.modal-msg{color:var(--muted);font-size:.9rem;margin-bottom:1.5rem}
.modal-actions{display:flex;justify-content:flex-end;gap:.75rem}

@media(max-width:640px){
  .form-grid{grid-template-columns:1fr}
  .nav{padding:0 1rem}
  .container{padding:1.25rem 1rem}
}
</style>
</head>
<body>
HTML;
}

function navBar(): void {
    if (!isLoggedIn()) return;
    $initials = strtoupper(
        substr($_SESSION['firstname'] ?? 'U', 0, 1) .
        substr($_SESSION['lastname'] ?? '', 0, 1)
    );
    $name = h(($_SESSION['firstname'] ?? '') . ' ' . ($_SESSION['lastname'] ?? ''));
    $role = h($_SESSION['role'] ?? 'user');
    $isAdmin = isAdmin();

    $adminActive = (strpos($_SERVER['SCRIPT_NAME'], 'admin') !== false) ? 'active' : '';
    $profileActive = (strpos($_SERVER['SCRIPT_NAME'], 'profile') !== false || strpos($_SERVER['SCRIPT_NAME'], 'change_password') !== false) ? 'active' : '';

    echo <<<HTML
<nav class="nav">
  <span class="nav-brand">Student<span>Mgmt</span></span>
  <div class="nav-links">
HTML;
    if ($isAdmin) {
        echo "<a href='admin_users.php' class='nav-link {$adminActive}'>👥 All Users</a>";
        echo "<a href='create-student.php' class='nav-link'>+ New User</a>";
    }
    echo "<a href='profile.php' class='nav-link {$profileActive}'>My Profile</a>";
    echo "<a href='change_password.php' class='nav-link'>🔑 Password</a>";
    echo <<<HTML
  </div>
  <div class="nav-user">
    <div class="nav-avatar">{$initials}</div>
    <div>
      <div class="nav-name">{$name}</div>
      <div class="nav-role">{$role}</div>
    </div>
    <form method="post" action="logout.php" style="margin:0">
      <button type="submit" class="btn-logout">Sign out</button>
    </form>
  </div>
</nav>
HTML;
}

function flashMessage(): void {
    $flash = getFlash();
    if (!$flash) return;
    $type = $flash['type'] === 'success' ? 'alert-success' : ($flash['type'] === 'error' ? 'alert-error' : 'alert-info');
    $icon = $flash['type'] === 'success' ? '✓' : ($flash['type'] === 'error' ? '✕' : 'ℹ');
    echo "<div class='alert {$type}'><span>{$icon}</span> " . h($flash['message']) . "</div>";
}

function pageFooter(): void {
    echo "</body></html>";
}
