<?php
define('CAN_ACCESS', true);
require_once '../config.php'; // Naik satu level untuk akses config
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("location: " . BASE_URL . "index.php");
    exit();
}

require_once '../app/rentController.php';
 $rentController = new rentController();

if (isset($_GET['id'])) {
    $apartmentId = $_GET['id'];
    
    // Panggil method untuk menghapus apartemen dan transaksinya
    if ($rentController->deleteApartmentAndTransactions($apartmentId)) {
        $_SESSION['success'] = "Apartemen dan semua data transaksi terkait berhasil dihapus.";
    } else {
        $_SESSION['error'] = "Gagal menghapus apartemen. Mungkin apartemen sedang disewa atau terjadi kesalahan.";
    }
}

header("location: " . BASE_URL . "dashboard.php");
exit();