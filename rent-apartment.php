<?php
define('CAN_ACCESS', true);
require_once 'config.php';

session_start();
if (!isset($_SESSION['user_id'])) {
    header("location: " . BASE_URL . "index.php");
    exit();
}

require_once 'app/rentController.php';
 $rentController = new rentController();

 $apartment = null;
 $error = '';

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

// Proses pengajuan sewa
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $apartmentId = $_POST['apartment_id'];
    $startDate = $_POST['start_date'];
    $endDate = $_POST['end_date'];

    // --- LOGIKA PERHITUNGAN YANG BENAR ---
    $start = new DateTime($startDate);
    $end = new DateTime($endDate);
    $interval = $start->diff($end);
    $months = ($interval->y * 12) + $interval->m;
    if ($interval->d > 0) {
        $months++;
    }
    if ($months <= 0) {
        $months = 1;
    }
    // --- AKHIR LOGIKA PERHITUNGAN ---

    $totalPrice = $apartment['price_per_month'] * $months;

    if ($rentController->createTransaction($_SESSION['user_id'], $apartmentId, $startDate, $endDate, $totalPrice)) {
        $_SESSION['success'] = "Pengajuan sewa berhasil! Menunggu konfirmasi admin.";
        header("location: " . BASE_URL . "dashboard.php");
        exit();
    } else {
        $error = "Gagal mengajukan sewa.";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sewa Apartemen | Sewa Apartemen.id</title>
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
        <div class="container">
            <h2 class="mb-4">Ajukan Sewa</h2>
            <a href="<?php echo BASE_URL; ?>dashboard.php" class="btn btn-secondary mb-3"><i class="fas fa-arrow-left me-2"></i>Kembali ke Dashboard</a>

            <?php if ($error): ?>
                <div class="alert alert-danger"><?php echo $error; ?></div>
            <?php endif; ?>

            <?php if ($apartment): // Tampilkan form HANYA jika data apartemen ditemukan ?>
            <div class="card">
                <div class="card-body">
                    <form action="rent-apartment.php?id=<?php echo $apartmentId; ?>" method="POST">
                        <!-- INI ADALAH BAGIAN KRUSIAL YANG HILANG -->
                        <input type="hidden" name="apartment_id" value="<?php echo $apartment['id']; ?>">
                        
                        <div class="row">
                            <div class="col-md-6">
                                <h4><?php echo htmlspecialchars($apartment['name']); ?></h4>
                                <p class="text-muted">Rp. <?php echo number_format($apartment['price_per_month'], 0, ',', '.'); ?> /bulan</p>
                                <p><strong>Alamat:</strong> <?php echo htmlspecialchars($apartment['address']); ?></p>
                            </div>
                            <div class="col-md-6">
                                <img src="
                                <?php
                                if (!empty($apartment['image'])) {
                                    if (filter_var($apartment['image'], FILTER_VALIDATE_URL)) {
                                        echo htmlspecialchars($apartment['image']);
                                    } else {
                                        echo BASE_URL . 'uploads/apartments/' . htmlspecialchars($apartment['image']);
                                    }
                                } else {
                                    echo 'https://picsum.photos/seed/' . $apartment['id'] . '/400/250.jpg';
                                }
                                ?>
                                " class="img-fluid rounded" alt="<?php echo htmlspecialchars($apartment['name']); ?>">
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="start_date" class="form-label">Tanggal Mulai</label>
                                    <input type="date" class="form-control" id="start_date" name="start_date" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="end_date" class="form-label">Tanggal Selesai</label>
                                    <input type="date" class="form-control" id="end_date" name="end_date" required>
                                </div>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary w-100"><i class="fas fa-paper-plane me-2"></i>Ajukan Sewa Sekarang</button>
                    </form>
                </div>
            </div>
            <?php endif; // Akhir dari if ($apartment) ?>
        </div>
    </main>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>