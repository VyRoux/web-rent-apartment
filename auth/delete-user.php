<?php
define('CAN_ACCESS', true);
require_once '../config.php'; // Naik satu level untuk akses config

session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("location: " . BASE_URL . "index.php"); // Pake BASE_URL
    exit();
}

require_once '../app/rentController.php';
 $rentController = new rentController();

if (isset($_GET['id'])) {
    $userId = $_GET['id'];
    
    // Mencegah admin menghapus dirinya sendiri
    if ($userId == $_SESSION['user_id']) {
        $_SESSION['error'] = "Tidak dapat menghapus akun Anda sendiri.";
    } else {
        if ($rentController->deleteUserAndTransactions($userId)) {
            $_SESSION['success'] = "Pengguna dan semua transaksi terkait berhasil dihapus.";
        } else {
            $_SESSION['error'] = "Gagal menghapus pengguna.";
        }
    }
}

header("location: ../dashboard.php");
exit();