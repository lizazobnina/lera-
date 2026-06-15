<?php
if (session_status() === PHP_SESSION_NONE) { session_start(); }

function is_admin() {
    return !empty($_SESSION['admin_id']);
}

function require_admin() {
    if (!is_admin()) {
        header('Location: login.php');
        exit;
    }
}

function is_client() {
    return !empty($_SESSION['client_user_id']);
}

function require_client() {
    if (!is_client()) {
        header('Location: client_login.php');
        exit;
    }
}
?>
