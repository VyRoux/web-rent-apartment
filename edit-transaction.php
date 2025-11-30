<?php
define('CAN_ACCESS', true); // Izinkan akses ke config
require_once 'config.php';

session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("location: index.php");
    exit();
}

require_once 'app/rentController.php';
 $rentController = new rentController();

 $transaction = null;
 $error = '';
 $success = '';

// Ambil ID dari URL
if (isset($_GET['id'])) {
    $transactionId = $_GET['id'];
    $transaction = $rentController->getTransactionDetails($transactionId);

    if (!$transaction) {
        $_SESSION['error'] = "Transaksi tidak ditemukan.";
        header("location: dashboard.php");
        exit();
    }
} else {
    header("location: dashboard.php");
    exit();
}

// Proses update status
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $status = $_POST['status'];

    if ($rentController->updateTransactionStatus($transactionId, $status)) {
        $_SESSION['success'] = "Status transaksi berhasil diperbarui.";
        header("location: dashboard.php");
        exit();
    } else {
        $error = "Gagal memperbarui status transaksi.";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ubah Status Transaksi | Sewa Apartemen.id</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="assets/css/dashboard.css">
</head>
<body>
    <!-- Navbar dan Sidebar bisa kamu include atau salin dari dashboard.php -->
    <nav class="navbar navbar-expand-lg navbar-light bg-light fixed-top">
        <div class="container-fluid">
            <a class="navbar-brand fw-bold text-dark" href="dashboard.php">Sewa Apartemen.id</a>
            <div class="collapse navbar-collapse">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" role="button" data-bs-toggle="dropdown">
                            <i class="fas fa-user-circle me-2"></i>
                            <span><?php echo htmlspecialchars($_SESSION['full_name']); ?></span>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><a class="dropdown-item" href="auth/logout.php"><i class="fas fa-sign-out-alt me-2"></i>Logout</a></li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    <main class="main-content" style="margin-top: 80px;">
        <div class="container-fluid">
            <h2 class="mb-4">Ubah Status Transaksi</h2>
            <a href="dashboard.php" class="btn btn-secondary mb-3"><i class="fas fa-arrow-left me-2"></i>Kembali ke Dashboard</a>

            <?php if ($error): ?>
                <div class="alert alert-danger"><?php echo $error; ?></div>
            <?php endif; ?>

            <div class="card">
                <div class="card-body">
                    <form action="edit-transaction.php?id=<?php echo $transactionId; ?>" method="POST">
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">User</label>
                                <p class="form-control-plaintext"><?php echo htmlspecialchars($transaction['username']); ?></p>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Apartemen</label>
                                <p class="form-control-plaintext"><?php echo htmlspecialchars($transaction['apartment_name']); ?></p>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">Tanggal Mulai</label>
                                <p class="form-control-plaintext"><?php echo date('d M Y', strtotime($transaction['start_date'])); ?></p>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Tanggal Selesai</label>
                                <p class="form-control-plaintext"><?php echo date('d M Y', strtotime($transaction['end_date'])); ?></p>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">Total Harga</label>
                                <p class="form-control-plaintext">Rp. <?php echo number_format($transaction['total_price'], 0, ',', '.'); ?></p>
                            </div>
                            <div class="col-md-6">
                                <label for="status" class="form-label">Status</label>
                                <select class="form-select" id="status" name="status">
                                    <option value="pending" <?php echo ($transaction['status'] == 'pending') ? 'selected' : ''; ?>>Menunggu Konfirmasi</option>
                                    <option value="confirmed" <?php echo ($transaction['status'] == 'confirmed') ? 'selected' : ''; ?>>Dikonfirmasi</option>
                                    <option value="completed" <?php echo ($transaction['status'] == 'completed') ? 'selected' : ''; ?>>Selesai</option>
                                    <option value="cancelled" <?php echo ($transaction['status'] == 'cancelled') ? 'selected' : ''; ?>>Dibatalkan</option>
                                </select>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary"><i class="fas fa-save me-2"></i>Simpan Perubahan</button>
                    </form>
                </div>
            </div>
        </div>
    </main>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>