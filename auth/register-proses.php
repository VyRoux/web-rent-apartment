<?php
    // Memulai session untuk menyimpan info sementara dari pengguna
    define('CAN_ACCESS', true); // Izinkan akses ke config
    require_once '../config.php';

    session_start();

    // Require file class Database dan mendapatkan instance koneksi database (Meren?)
    require_once '../app/database.php';
    $db = Database::getInstance();
    $connection = $db->getConnection();

    // Proses pendaftaran request method POST
    if($_SERVER['REQUEST_METHOD'] === 'POST'){

        // *** Perbaikan: Simpan data & errors ke array ***
        $errors = [];
        $data = $_POST;

        // Validasi input dari form register
        if(empty($data['username']) || empty($data['full_name']) || empty($data['email']) || empty($data['password']) || empty($data['password_confirm'])){
            $errors['general'] = "Username, password, dan email wajib diisi.";
        }

        // *** Tambahan: cek password confirm ***
        if($data['password'] !== $data['password_confirm']){
            $errors['password_confirm'] = "Passwordnya ngga cocok, coba lagi.";
        }

        // Jika ada error validasi, kembalikan ke form
        if(!empty($errors)){
            $_SESSION['register_errors'] = $errors;
            $_SESSION['register_data'] = $data;
            header("location: ../register.php");
            exit();
        }

        // cek apakah username atau email sudah terdaftar di database?
        $check_query = "SELECT * FROM users WHERE username = ? OR email = ?";
        $stmt = $connection->prepare($check_query);
        $stmt->bind_param("ss", $data['username'], $data['email']);
        $stmt->execute();
        $result = $stmt->get_result();

        if($result->num_rows > 0){
            $errors['general'] = "Username atau email sudah terdaftar. Silakan gunakan yang lain.";
            $_SESSION['register_errors'] = $errors;
            $_SESSION['register_data'] = $data;
            header("location: ../register.php");
            exit();
        }

        // Melakukan Hash kepada password demi keamanan pengguna (password hashing, reset password tidak akan menjamin password asli)
        $hashed_password = password_hash($data['password'], PASSWORD_BCRYPT);

        // Melakukan insert data user baru ke database dengan SQL
        $insert_query = "INSERT INTO users (username, full_name, email, password) VALUES (?, ?, ?, ?)";
        $stmt = $connection->prepare($insert_query);
        $stmt->bind_param("ssss", $data['username'], $data['full_name'], $data['email'], $hashed_password);

        if($stmt->execute()){
            // Jika berhasil, maka akan mengalihkan ke halaman login untuk login
            $_SESSION['success'] = "Akun berhasil dibuat! silakan Login";
            header("location: ../index.php");
            exit();
        } else {
            // Jika gagal, maka akan mengembalikan ke halaman register beserta pesan error
            $errors['general'] = "Terjadi kesaalaan saat membuat akun. Silakan coba lagi, atau hubungi Admin.";
            $_SESSION['register_errors'] = $errors;
            $_SESSION['register_data'] = $data;
            header("location: ../register.php");
            exit();
        }

        // Menutup statement yang telah dibuat
        $stmt->close();

    } else {
        // Jika bukan request method POST, maka akan mengembalikan ke halaman register
        header("location: ../register.php");
        exit();
    }
?>