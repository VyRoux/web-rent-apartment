<?php
session_start();
require_once '../app/database.php';
 $db = Database::getInstance();
 $connection = $db->getConnection();

// Cek apakah yang akses adalah admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    $_SESSION['error'] = "Akses ditolak!";
    header("location: ../index.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Ambil data dari form
    $name = trim($_POST['name']);
    $description = trim($_POST['description']);
    $address = trim($_POST['address']);
    $price_per_month = trim($_POST['price_per_month']);
    $image_url = trim($_POST['image_url']);
    $status = $_POST['status'];

    // Validasi sederhana
    if (empty($name) || empty($address) || empty($price_per_month)) {
        $_SESSION['error'] = "Nama, alamat, dan harga wajib diisi!";
        header("location: ../add-apartment.php");
        exit();
    }

    // Siapkan query untuk menambah apartemen
    $query = "INSERT INTO apartments (name, description, address, price_per_month, image_url, status) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $connection->prepare($query);

    // Bind parameter dan eksekusi
    $stmt->bind_param("ssssss", $name, $description, $address, $price_per_month, $image_url, $status);

    if ($stmt->execute()) {
        $_SESSION['success'] = "Apartemen baru berhasil ditambahkan!";
    } else {
        $_SESSION['error'] = "Gagal menambah apartemen. Coba lagi.";
    }

    $stmt->close();
    header("location: ../add-apartment.php");
    exit();
} else {
    header("location: ../add-apartment.php");
    exit();
}
?>