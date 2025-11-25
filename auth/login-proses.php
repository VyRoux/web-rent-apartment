<?php
    // emulai session unruk menyimpan info sementara dari pengguna
    session_start();

    // require file class Database dan mendapatkan instance database
    require_once '../app/database.php';
    $db = Database::getInstance();
    $connection = $db->getConnection();

    // proses login request method POST
    if($_SERVER['REQUEST_METHOD'] === 'POST'){
        $username = $_POST['username'];
        $password = $_POST['password'];

        // Mencari user di database berdasarkan username
        $check_query = "SELECT * FROM users WHERE username = ?";
        $stmt = $connection->prepare($check_query);
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();

        // jika user ditemukan, verifikasi passord
        if($result->num_rows > 0){
            $user = $result->fetch_assoc();

            // Verifikasi password dengan menggunakan password_verify
            if(password_verify($password, $user['password'])){
                // menyetel session pengguna jika login berhasil
                $_SESSION['success'] = "Login berhasil.";
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['role'] = $user['role'];
                header("location: ../dashboard.php");
                exit();
            } else {
                // jika password salah
                $_SESSION['error'] = "Password yang dimasukan salah, coba lagi.";
                header("location: ../index.php");
                exit();
            }
        } else {
            // jika usrname tidak ditemukan dalam database
            $_SESSION['error'] = "Username tidak ditemukan";
            header("location: ../index.php");
            exit();
        }
    }
?>