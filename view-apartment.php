<?php
// TENTUKAN NAMA FOLDER PROYEK KAMU DI SINI
define('CAN_ACCESS', true); // Izinkan akses ke config
require_once 'config.php';

session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("location: " . BASE_URL . "index.php");
    exit();
}

require_once 'app/rentController.php';
 $rentController = new rentController();

 $apartment = null;
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
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Apartemen | Sewa Apartemen.id</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="assets/css/dashboard.css">
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
            <a href="<?php echo BASE_URL; ?>dashboard.php" class="btn btn-secondary mb-4"><i class="fas fa-arrow-left me-2"></i>Kembali ke Dashboard</a>

            <div class="card">
                <div class="card-header">
                    <h3><i class="fas fa-building me-2"></i>Detail Apartemen</h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-5">
                            <img src="https://picsum.photos/seed/<?php echo $apartment['id']; ?>/600/400.jpg" class="img-fluid rounded shadow-sm" alt="<?php echo htmlspecialchars($apartment['name']); ?>">
                        </div>
                        <div class="col-md-7">
                            <h2><?php echo htmlspecialchars($apartment['name']); ?></h2>
                            <p class="text-muted fs-5">Rp. <?php echo number_format($apartment['price_per_month'], 0, ',', '.'); ?> /bulan</p>
                            <p><strong>Status:</strong> <span class="badge bg-<?php echo $apartment['status'] == 'available' ? 'success' : 'danger'; ?> fs-6"><?php echo ucfirst($apartment['status']); ?></span></p>
                            <hr>
                            <h5>Deskripsi</h5>
                            <p><?php echo nl2br(htmlspecialchars($apartment['description'])); ?></p>
                            <hr>
                            <h5>Alamat</h5>
                            <p><i class="fas fa-map-marker-alt me-2"></i> <?php echo htmlspecialchars($apartment['address']); ?></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>