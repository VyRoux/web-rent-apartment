<?php
define('CAN_ACCESS', true); // Izinkan akses ke config
require_once 'config.php';

session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("location: index.php");
    exit();
}

// Ambil pesan error atau sukses dari session
 $error = $_SESSION['error'] ?? '';
 $success = $_SESSION['success'] ?? '';
unset($_SESSION['error'], $_SESSION['success']);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Apartemen | Admin</title>
    <!-- Gunakan CSS yang sama dengan dashboard -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="assets/css/dashboard.css">
</head>
<body>
    <!-- Navbar dan Sidebar bisa disalin dari dashboard.php -->
    <!-- ... -->

    <main class="main-content">
        <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
            <h1 class="h2">Tambah Apartemen Baru</h1>
            <a href="dashboard.php" class="btn btn-secondary"><i class="fas fa-arrow-left me-2"></i>Kembali</a>
        </div>

        <div class="card">
            <div class="card-body">
                <!-- Tampilkan pesan error atau sukses -->
                <?php if ($error): ?>
                    <div class="alert alert-danger" role="alert">
                        <i class="fas fa-exclamation-triangle-fill me-2"></i><?php echo $error; ?>
                    </div>
                <?php endif; ?>

                <?php if ($success): ?>
                    <div class="alert alert-success" role="alert">
                        <i class="fas fa-check-circle-fill me-2"></i><?php echo $success; ?>
                    </div>
                <?php endif; ?>

                <form action="auth/add-apartment-proses.php" method="POST">
                    <div class="mb-3">
                        <label for="name" class="form-label">Nama Apartemen</label>
                        <input type="text" class="form-control" id="name" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label for="description" class="form-label">Deskripsi</label>
                        <textarea class="form-control" id="description" name="description" rows="3"></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="address" class="form-label">Alamat Lengkap</label>
                        <input type="text" class="form-control" id="address" name="address" required>
                    </div>
                    <div class="mb-3">
                        <label for="price_per_month" class="form-label">Harga per Bulan</label>
                        <div class="input-group">
                            <span class="input-group-text">Rp.</span>
                            <input type="number" class="form-control" id="price_per_month" name="price_per_month" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="image_url" class="form-label">URL Gambar (Opsional)</label>
                        <input type="text" class="form-control" id="image_url" name="image_url" placeholder="https://example.com/gambar.jpg">
                    </div>
                    <div class="mb-3">
                        <label for="status" class="form-label">Status</label>
                        <select class="form-select" id="status" name="status">
                            <option value="available">Tersedia</option>
                            <option value="rented">Disewa</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary"><i class="fas fa-plus me-2"></i>Tambah Apartemen</button>
                </form>
            </div>
        </div>
    </main>
    <!-- JS Bootstrap -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>