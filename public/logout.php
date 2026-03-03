<?php
// public/logout.php
require_once '../components/auth.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    session_unset();
    session_destroy();
}
header('Location: login.php');
exit;
