<?php
session_start();

require_once __DIR__ . '/app/database.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: index.php');
    exit;
}

$email = trim($_POST['email'] ?? '');
$password = $_POST['password'] ?? '';

if ($email === '' || $password === '') {
    $_SESSION['login_error'] = 'Email dan password wajib diisi.';
    header('Location: index.php');
    exit;
}

$db = Database::getInstance()->getConnection();

$stmt = mysqli_prepare($db, "SELECT id, username, password FROM users WHERE email = ? LIMIT 1");
if ($stmt) {
    mysqli_stmt_bind_param($stmt, 's', $email);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_store_result($stmt);

    if (mysqli_stmt_num_rows($stmt) === 1) {
        mysqli_stmt_bind_result($stmt, $id, $username, $hashed_password);
        mysqli_stmt_fetch($stmt);

        if ($hashed_password !== null && password_verify($password, $hashed_password)) {
            // Login berhasil
            $_SESSION['user'] = [
                'id' => $id,
                'username' => $username,
                'email' => $email
            ];
            $_SESSION['login_success'] = 'Login berhasil!';
            header('Location: ade.php');
            exit;
        }
    }

    mysqli_stmt_close($stmt);
}

// Jika sampai sini, login gagal
$_SESSION['login_error'] = 'Email atau password salah!';
header('Location: index.php');
exit;
?>