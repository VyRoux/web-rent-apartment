<?php
// File: register-proses.php - Proses registrasi ke database

session_start();

// Validasi method POST
if ($_SERVER["REQUEST_METHOD"] != "POST") {
    header('Location: register.php');
    exit;
}

// Ambil data dari form
$name = trim($_POST["name"] ?? "");
$email = trim($_POST["email"] ?? "");
$phone = trim($_POST["phone"] ?? "");
$password = $_POST["password"] ?? "";
$confirm_password = $_POST["confirm_password"] ?? "";

// Array untuk menyimpan error
$errors = [];

// Validasi nama
if (empty($name)) {
    $errors["name"] = "Nama tidak boleh kosong.";
}

// Validasi email
if (empty($email)) {
    $errors["email"] = "Email tidak boleh kosong.";
} elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors["email"] = "Format email tidak valid.";
}

// Validasi phone
if (empty($phone)) {
    $errors["phone"] = "Nomor telepon tidak boleh kosong.";
}

// Validasi password
if (empty($password)) {
    $errors["password"] = "Password tidak boleh kosong.";
} elseif (strlen($password) < 6) {
    $errors["password"] = "Password minimal 6 karakter.";
}

// Validasi confirm password
if (empty($confirm_password)) {
    $errors["confirm_password"] = "Konfirmasi password tidak boleh kosong.";
} elseif ($password !== $confirm_password) {
    $errors["confirm_password"] = "Password tidak cocok.";
}

// Jika ada error, kembali ke halaman register dengan pesan error
if (!empty($errors)) {
    $_SESSION["register_errors"] = $errors;
    $_SESSION["register_data"] = compact("name", "email", "phone");
    header('Location: register.php');
    exit;
}

// Koneksi database
require_once __DIR__ . '/app/database.php';
$db = Database::getInstance()->getConnection();

// Cek email apakah sudah terdaftar
$stmt = mysqli_prepare($db, "SELECT id FROM users WHERE email = ? LIMIT 1");
mysqli_stmt_bind_param($stmt, 's', $email);
mysqli_stmt_execute($stmt);
mysqli_stmt_store_result($stmt);

if (mysqli_stmt_num_rows($stmt) > 0) {
    mysqli_stmt_close($stmt);
    $_SESSION["register_errors"] = ["email" => "Email sudah terdaftar!"];
    $_SESSION["register_data"] = compact("name", "email", "phone");
    header('Location: register.php');
    exit;
}

mysqli_stmt_close($stmt);

// Hash password
$hashed_password = password_hash($password, PASSWORD_DEFAULT);

// Simpan ke database
$stmt = mysqli_prepare($db, "INSERT INTO users (username, email, password, full_name) VALUES (?, ?, ?, ?)");
mysqli_stmt_bind_param($stmt, 'ssss', $name, $email, $hashed_password, $phone);

if (mysqli_stmt_execute($stmt)) {
    mysqli_stmt_close($stmt);
    $_SESSION["register_success"] = "Registrasi berhasil! Silakan login.";
    header('Location: index.php');
    exit;
} else {
    mysqli_stmt_close($stmt);
    $_SESSION["register_errors"] = ["general" => "Terjadi kesalahan saat menyimpan data."];
    $_SESSION["register_data"] = compact("name", "email", "phone");
    header('Location: register.php');
    exit;
}
?>
