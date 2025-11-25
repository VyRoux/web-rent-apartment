<?php
    // Memulai session untuk menyimpan info sementara
    session_start();

    // Require file class Database dan mendapatkan instanse koneksi database (Meren?)
    require_once '../app/database.php';
    $db = Database::getInstance();
    $connection = $db->getConnection();

    // Proses pendaftara request method POST
    if($_SERVER['REQUEST_METHOD'] === 'POST'){

        // Validasi inpu dari form register
        $username = ($_POST['username']);
        $full_name = ($_POST['full_name']);
        $email = ($_POST['email']);
        $password = ($_POST['password']);
        $password_confirm = ($_POST['password_confirm']);

        // Cek apakah semua formulier/field sudah terisi
        if(empty($username) || empty($full_name) || empty($email) || empty($password) || empty($password_confirm)){
            $_SESSION['error'] = "Username, password, dan email wajib diisi.";
            // Mengarahkan kembali ke halaman register
            header("location: ../register.php");
            // Menghentikan perintah atau script
            exit();
        }

        // cek apakah usernme atau email sudah terdaftar di database?
        $check_query = "SELECT * FROM users WHERE username = ? OR email = ?";
        $stmt = $connection->prepare($check_query);
        $stmt->bind_param("ss", $username, $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if($result->num_rows > 0){
            $_SESSION['error'] = "Username atau email sudah terdaftar. Silakan gunakan yang lain. Jika belum menggunakan, maka hubungi Admin.";
            header("location: ../register.php");
            exit();
        }

        // Melakukan Hash kepada password demi keamanan pengguna (password hashing, reset password tidak akan menjamin password asli)
        $hashed_password = password_hash($password, PASSWORD_BCRYPT);

        // Melakukan insert data user baru ke database dengan SQL
        $insert_query = "INSERT INTO users (username, full_name, email, password) VALUES (?, ?, ?, ?)";
        $stmt = $connection->prepare($insert_query);
        $stmt->bind_param("ssss", $username, $full_name, $email, $hashed_password);

        if($stmt->execute()){
            // Jika berhasil, maka akan mengalihkan ke kalaman login untuk login
            $_SESSION['success'] = "Akun berhasil dibuat! silakan Login";
            header("location: ../index.php");
            exit();
        } else {
            // Jika gagal, maka akan emnganbalikan ke halaman register beserta pesan error
            $_SESSION['error'] = "Terjadi kesaalaan saat membuat akun. Silakan coba lagi, atau hubungi Admin.";
            header("location: ../register.php");
            exit();
        }

        // Menutup statement yang telah dibuat
        $stmt->close();

    } else {
        // Jika bukan request method POST, makaa akan emngembalikan ke halam register
        header("location: ../register.php");
        exit();
    }
?>