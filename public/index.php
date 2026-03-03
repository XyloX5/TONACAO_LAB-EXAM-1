<?php
// public/index.php — Entry point redirect
require_once '../components/auth.php';

if (isLoggedIn()) {
    if (isAdmin()) {
        header('Location: admin_users.php');
    } else {
        header('Location: profile.php');
    }
} else {
    header('Location: login.php');
}
exit;
