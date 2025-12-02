<?php
session_start();

// Cek apakah user sudah login
function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

// Redirect jika belum login
function requireLogin() {
    if (!isLoggedIn()) {
        header("Location: index.php");
        exit;
    }
}

// Logout
function logout() {
    session_unset();
    session_destroy();
    header("Location: index.php");
    exit;
}
?>