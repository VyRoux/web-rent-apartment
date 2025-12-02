<?php
define('CAN_ACCESS', true);
require_once 'config.php'; // <-- INI YANG DIPERBAIKI

session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("location: " . BASE_URL . "index.php");
    exit();
}

require_once 'app/rentController.php';
 $rentController = new rentController();

 $apartment = null;
 $error = '';
 $success = '';

// Ambil ID dari URL
if (isset($_GET['id'])) {
    $apartmentId = $_GET['id'];
    $apartment = $rentController->getApartmentDetails($apartmentId);

    if (!$apartment) {
        $_SESSION['error'] = "Apartemen tidak ditemukan.";
        header("location: " . BASE_URL . "dashboard.php");
        exit();
    }
} else {
    header("location: " . BASE_URL . "dashboard.php");
    exit();
}

// Proses update data
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $description = $_POST['description'];
    $address = $_POST['address'];
    $price_per_month = $_POST['price_per_month'];
    $status = $_POST['status'];
    
    // Ambil data gambar dari form
    $imageFile = $_FILES['image_file'] ?? null;
    $imageUrl = $_POST['image_url'] ?? '';

    if (empty($name) || empty($address) || empty($price_per_month)) {
        $error = "Nama, Alamat, dan Harga wajib diisi.";
    } else {
        // Panggil fungsi dengan semua argumen yang diperlukan
        if ($rentController->updateApartment($apartmentId, $name, $description, $address, $price_per_month, $status, $imageFile, $imageUrl)) {
            $_SESSION['success'] = "Data apartemen berhasil diperbarui.";
            header("location: " . BASE_URL . "dashboard.php");
            exit();
        } else {
            $error = "Gagal memperbarui data apartemen.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Apartemen | Sewa Apartemen.id</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>assets/css/dashboard.css">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light fixed-top">
        <div class="container-fluid">
            <a class="navbar-brand fw-bold text-dark" href="<?php echo BASE_URL; ?>dashboard.php">Sewa Apartemen.id</a>
            <div class="collapse navbar-collapse">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" role="button" data-bs-toggle="dropdown">
                            <i class="fas fa-user-circle me-2"></i>
                            <span><?php echo htmlspecialchars($_SESSION['full_name']); ?></span>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><a class="dropdown-item" href="<?php echo BASE_URL; ?>auth/logout.php"><i class="fas fa-sign-out-alt me-2"></i>Logout</a></li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    <main class="main-content" style="margin-top: 80px;">
        <div class="container-fluid">
            <h2 class="mb-4">Edit Apartemen</h2>
            <a href="<?php echo BASE_URL; ?>dashboard.php" class="btn btn-secondary mb-3"><i class="fas fa-arrow-left me-2"></i>Kembali ke Dashboard</a>

            <?php if ($error): ?>
                <div class="alert alert-danger"><?php echo $error; ?></div>
            <?php endif; ?>

            <?php if ($apartment): // Tampilkan form HANYA jika data apartemen ditemukan ?>
            <div class="card">
                <div class="card-body">
                    <!-- TAMBAHKAN enctype untuk upload file -->
                    <form action="edit-apartment.php?id=<?php echo $apartmentId; ?>" method="POST" enctype="multipart/form-data">
                        <div class="mb-3">
                            <label for="name" class="form-label">Nama Apartemen</label>
                            <input type="text" class="form-control" id="name" name="name" value="<?php echo htmlspecialchars($apartment['name']); ?>" required>
                        </div>
                        <div class="mb-3">
                            <label for="description" class="form-label">Deskripsi</label>
                            <textarea class="form-control" id="description" name="description" rows="4"><?php echo htmlspecialchars($apartment['description']); ?></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="address" class="form-label">Alamat</label>
                            <input type="text" class="form-control" id="address" name="address" value="<?php echo htmlspecialchars($apartment['address']); ?>" required>
                        </div>
                        <div class="mb-3">
                            <label for="price_per_month" class="form-label">Harga per Bulan</label>
                            <input type="number" class="form-control" id="price_per_month" name="price_per_month" value="<?php echo htmlspecialchars($apartment['price_per_month']); ?>" required>
                        </div>
                        <div class="mb-3">
                            <label for="status" class="form-label">Status</label>
                            <select class="form-select" id="status" name="status">
                                <option value="available" <?php echo ($apartment['status'] == 'available') ? 'selected' : ''; ?>>Tersedia</option>
                                <option value="rented" <?php echo ($apartment['status'] == 'rented') ? 'selected' : ''; ?>>Disewa</option>
                            </select>
                        </div>
                        
                        <hr>
                        <h5>Gambar Apartemen</h5>
                        <div class="mb-3">
                            <label for="image_file" class="form-label">Upload Gambar Baru (Opsional)</label>
                            <input type="file" class="form-control" id="image_file" name="image_file" accept="image/*">
                            <div class="form-text">Kosongkan jika tidak ingin mengubah gambar.</div>
                        </div>
                        <div class="mb-3">
                            <label for="image_url" class="form-label">Atau Gunakan URL Gambar (Opsional)</label>
                            <input type="url" class="form-control" id="image_url" name="image_url" placeholder="https://example.com/gambar.jpg">
                        </div>

                        <button type="submit" class="btn btn-primary"><i class="fas fa-save me-2"></i>Simpan Perubahan</button>
                    </form>
                </div>
            </div>
            <?php endif; // Akhir dari if ($apartment) ?>
        </div>
    </main>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>