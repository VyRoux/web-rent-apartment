<?php
define('CAN_ACCESS', true);
require_once 'config.php';

session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("location: " . BASE_URL . "index.php");
    exit();
}

require_once 'app/rentController.php';
 $rentController = new rentController();

 $transaction = null;
if (isset($_GET['id'])) {
    $transactionId = $_GET['id'];
    $transaction = $rentController->getTransactionDetails($transactionId);

    if (!$transaction) {
        $_SESSION['error'] = "Transaksi tidak ditemukan.";
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
    <title>Detail Transaksi | Sewa Apartemen.id</title>
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
            <a href="<?php echo BASE_URL; ?>dashboard.php" class="btn btn-secondary mb-4"><i class="fas fa-arrow-left me-2"></i>Kembali ke Dashboard</a>

            <div class="card">
                <div class="card-header">
                    <h3><i class="fas fa-exchange-alt me-2"></i>Detail Transaksi</h3>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <p><strong>ID Transaksi:</strong> #<?php echo $transaction['id']; ?></p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>Tanggal Transaksi:</strong> <?php echo date('d F Y, H:i', strtotime($transaction['transaction_date'])); ?></p>
                        </div>
                    </div>
                    <hr>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <p><strong>User:</strong> <?php echo htmlspecialchars($transaction['username']); ?></p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>Apartemen:</strong> <?php echo htmlspecialchars($transaction['apartment_name']); ?></p>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <p><strong>Tanggal Mulai:</strong> <?php echo date('d F Y', strtotime($transaction['start_date'])); ?></p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>Tanggal Selesai:</strong> <?php echo date('d F Y', strtotime($transaction['end_date'])); ?></p>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <p><strong>Total Harga:</strong> Rp. <?php echo number_format($transaction['total_price'], 0, ',', '.'); ?></p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>Status:</strong> <span class="badge bg-<?php echo $transaction['status'] == 'completed' ? 'success' : ($transaction['status'] == 'confirmed' ? 'info' : 'warning'); ?> fs-6"><?php echo ucfirst($transaction['status']); ?></span></p>
                        </div>
                    </div>
                    <hr>
                    <a href="<?php echo BASE_URL; ?>edit-transaction.php?id=<?php echo $transaction['id']; ?>" class="btn btn-warning"><i class="fas fa-edit me-2"></i>Ubah Status</a>
                </div>
            </div>
        </div>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>