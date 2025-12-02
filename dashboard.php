<?php
// TENTUKAN NAMA FOLDER PROYEK KAMU DI SINI
// Pastikan ini sama dengan nama folder di dalam htdocs kamu
define('CAN_ACCESS', true); // Izinkan akses ke config
require_once 'config.php';

session_start();
if (!isset($_SESSION['user_id'])) {
    header("location: " . BASE_URL . "index.php");
    exit();
}

require_once 'app/rentController.php';
 $rentController = new rentController();

// Ambil data user dari session
 $username = $_SESSION['username'];
 $role = $_SESSION['role'];
 $user_id = $_SESSION['user_id'];
 $full_name = $_SESSION['full_name'];
 $email = $_SESSION['email'];

// Ambil data berdasarkan role
if ($role == 'admin') {
    $apartments = $rentController->getAllApartments();
    $transactions = $rentController->getAllTransactions();
    $users = $rentController->getAllUsers();
} else {
    $myTransactions = $rentController->getMyTransactions($user_id);
    $availableApartments = $rentController->getAvailableApartments();
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard | Sewa Apartemen.id</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Google Fonts: Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="assets/css/dashboard.css">
</head>
<body>
    <!-- Top Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light bg-light fixed-top">
        <div class="container-fluid">
            <a class="navbar-brand fw-bold text-dark" href="<?php echo BASE_URL; ?>dashboard.php">Sewa Apartemen.id</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNavDropdown">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fas fa-user-circle me-2"></i>
                            <span class="ms-2 d-none d-md-inline-block"><?php echo htmlspecialchars($full_name); ?></span>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><h6 class="dropdown-header">Akun Saya</h6></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="#"><i class="fas fa-user me-2"></i>Profil Saya</a></li>
                            <li><a class="dropdown-item" href="#"><i class="fas fa-cog me-2"></i>Pengaturan</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="<?php echo BASE_URL; ?>auth/logout.php"><i class="fas fa-sign-out-alt me-2"></i>Logout</a></li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Sidebar -->
    <nav class="sidebar">
        <ul class="nav flex-column">
            <li class="nav-item">
                <a class="nav-link active" href="#"><i class="fas fa-tachometer-alt me-2"></i> Dashboard</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#browse-apartments"><i class="fas fa-search me-2"></i> Cari Apartemen</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#my-transactions"><i class="fas fa-history me-2"></i> Transaksi Saya</a>
            </li>
        </ul>
    </nav>

    <!-- Main Content -->
    <main class="main-content">
        <h1>Dashboard</h1>
        <p class="text-muted">Selamat datang kembali, <strong><?php echo htmlspecialchars($full_name); ?></strong>! (Role: <span class="badge bg-primary"><?php echo htmlspecialchars($role); ?></span>)</p>

        <?php
        if (isset($_SESSION['success'])) {
            echo '<div class="alert alert-success alert-dismissible fade show" role="alert">
                    ' . $_SESSION['success'] . '
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                  </div>';
            unset($_SESSION['success']);
        }
        if (isset($_SESSION['error'])) {
            echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                    ' . $_SESSION['error'] . '
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                  </div>';
            unset($_SESSION['error']);
        }
        ?>

        <?php if ($role == 'user'): ?>
        <!-- ==================== USER VIEW ==================== -->
        <section id="user-profile" class="mb-5">
            <div class="card">
                <div class="card-header">
                    <h4><i class="fas fa-user me-2"></i> Profil Saya</h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <p><strong>Username:</strong> <?php echo htmlspecialchars($username); ?></p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>Nama Lengkap:</strong> <?php echo htmlspecialchars($full_name); ?></p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <p><strong>Email:</strong> <?php echo htmlspecialchars($email); ?></p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>Role:</strong> <span class="badge bg-primary"><?php echo ucfirst($role); ?></span></p>
                        </div>
                    </div>
                    <!-- <div class="text-center mt-3">
                        <a href="#" class="btn btn-primary"><i class="fas fa-edit me-2"></i> Edit Profil</a>
                    </div> -->
                </div>
            </div>
        </section>

        <section id="browse-apartments" class="mb-5">
            <h2><i class="fas fa-search me-2"></i> Cari Apartemen Tersedia</h2>
            <div class="row">
                <?php if (empty($availableApartments)): ?>
                    <div class="col-12">
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle me-2"></i>
                            Saat ini, belum ada apartemen yang tersedia untuk disewa. Silakan kembali lagi nanti.
                        </div>
                    </div>
                <?php else: ?>
                    <?php foreach ($availableApartments as $apartment): ?>
                        <div class="col-md-6 mb-4">
                            <div class="card h-100">
                                <!-- <img src="https://picsum.photos/seed/<?php echo $apartment['id']; ?>/400/250.jpg" class="card-img-top" alt="<?php echo htmlspecialchars($apartment['name']); ?>"> -->
                                <div class="card-body d-flex flex-column">
                                    <h5 class="card-title"><?php echo htmlspecialchars($apartment['name']); ?></h5>
                                    <p class="card-text"><?php echo htmlspecialchars(substr($apartment['description'], 0, 80)) . '...'; ?></p>
                                    <div class="mt-auto">
                                        <p class="fw-bold text-primary">Rp. <?php echo number_format($apartment['price_per_month'], 0, ',', '.'); ?> /bulan</p>
                                        <a href="<?php echo BASE_URL; ?>rent-apartment.php?id=<?php echo $apartment['id']; ?>" class="btn btn-primary w-100">Sewa Sekarang</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </section>

        <section id="my-transactions" class="mb-5">
            <h2><i class="fas fa-history me-2"></i> Riwayat Transaksi Saya</h2>
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead>
                        <tr>
                            <th>Apartemen</th>
                            <th>Tanggal Mulai</th>
                            <th>Tanggal Selesai</th>
                            <th>Total Harga</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($myTransactions)): ?>
                            <tr>
                                <td colspan="6" class="text-center">Anda belum memiliki transaksi.</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($myTransactions as $transaction): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($transaction['apartment_name']); ?></td>
                                    <td><?php echo date('d M Y', strtotime($transaction['start_date'])); ?></td>
                                    <td><?php echo date('d M Y', strtotime($transaction['end_date'])); ?></td>
                                    <td>Rp. <?php echo number_format($transaction['total_price'], 0, ',', '.'); ?></td>
                                    <td><span class="badge bg-<?php echo $transaction['status'] == 'completed' ? 'success' : 'warning'; ?>"><?php echo ucfirst($transaction['status']); ?></span></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </section>
        <!-- ==================== END USER VIEW ==================== -->

        <?php elseif ($role == 'admin'): ?>
        <!-- ================ ADMIN VIEW ================ -->
        <section id="admin-stats" class="mb-5">
            <div class="row">
                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="card card-stat border-left-primary">
                        <div class="card-body">
                            <h5 class="card-title text-uppercase text-muted mb-0">Total Apartemen</h5>
                            <h2 class="mt-0 mb-0"><?php echo count($apartments); ?></h2>
                            <p class="card-text">Total unit apartemen</p>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="card card-stat border-left-success">
                        <div class="card-body">
                            <h5 class="card-title text-uppercase text-muted mb-0">Total Pengguna</h5>
                            <h2 class="mt-0 mb-0"><?php echo count($users); ?></h2>
                            <p class="card-text">Total pengguna terdaftar</p>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="card card-stat border-left-info">
                        <div class="card-body">
                            <h5 class="card-title text-uppercase text-muted mb-0">Total Transaksi</h5>
                            <h2 class="mt-0 mb-0"><?php echo count($transactions); ?></h2>
                            <p class="card-text">Total transaksi yang terjadi</p>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="card card-stat border-left-warning">
                        <div class="card-body">
                            <h5 class="card-title text-uppercase text-muted mb-0">Estimasi Pendapatan</h5>
                            <h2 class="mt-0 mb-0">Rp. <?php
                                $totalRevenue = 0;
                                foreach ($transactions as $transaction) {
                                    if ($transaction['status'] == 'completed') {
                                        $totalRevenue += $transaction['total_price'];
                                    }
                                }
                                echo number_format($totalRevenue, 0, ',', '.');
                            ?></h2>
                            <p class="card-text">Berdasarkan transaksi yang selesai</p>
                        </div>
                    </div>
                </div>
            </section>

        <section id="admin-apartments" class="mb-5">
            <h2><i class="fas fa-building me-2"></i> Manajemen Apartemen</h2>
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span>Daftar Apartemen</span>
                    <a href="<?php echo BASE_URL; ?>add-apartment.php" class="btn btn-primary btn-sm"><i class="fas fa-plus me-1"></i> Tambah Baru</a>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead>
                                <tr>
                                    <th scope="col">ID</th>
                                    <th scope="col">Nama</th>
                                    <th scope="col">Alamat</th>
                                    <th scope="col">Harga/Bulan</th>
                                    <th scope="col">Status</th>
                                    <th scope="col" class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($apartments as $apartment): ?>
                                    <tr>
                                        <th scope="row"><?php echo $apartment['id']; ?></th>
                                        <td><?php echo htmlspecialchars($apartment['name']); ?></td>
                                        <td><?php echo htmlspecialchars($apartment['address']); ?></td>
                                        <td>Rp. <?php echo number_format($apartment['price_per_month'], 0, ',', '.'); ?></td>
                                        <td><span class="badge bg-<?php echo $apartment['status'] == 'available' ? 'success' : 'danger'; ?>"><?php echo ucfirst($apartment['status']); ?></span></td>
                                        <td class="table-actions">
                                            <a href="<?php echo BASE_URL; ?>view-apartment.php?id=<?php echo $apartment['id']; ?>" class="btn btn-sm btn-info btn-action"><i class="fas fa-eye"></i></a>
                                            <a href="<?php echo BASE_URL; ?>edit-apartment.php?id=<?php echo $apartment['id']; ?>" class="btn btn-sm btn-warning btn-action"><i class="fas fa-edit"></i></a>
                                            <button class="btn btn-sm btn-danger btn-action" onclick="confirmDeleteApartment(<?php echo $apartment['id']; ?>)"><i class="fas fa-trash"></i></button>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </section>

        <section id="admin-users" class="mb-5">
            <h2><i class="fas fa-users me-2"></i> Manajemen Pengguna</h2>
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead>
                                <tr>
                                    <th scope="col">Username</th>
                                    <th>Nama Lengkap</th>
                                    <th>Email</th>
                                    <th scope="col">Role</th>
                                    <th scope="col" class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($users as $user): ?>
                                    <tr>
                                        <th scope="row"><?php echo htmlspecialchars($user['username']); ?></th>
                                        <td><?php echo htmlspecialchars($user['full_name']); ?></td>
                                        <td><?php echo htmlspecialchars($user['email']); ?></td>
                                        <td><span class="badge bg-<?php echo $user['role'] == 'admin' ? 'danger' : 'primary'; ?>"><?php echo ucfirst($user['role']); ?></span></td>
                                        <td class="table-actions">
                                            <a href="<?php echo BASE_URL; ?>view-user.php?id=<?php echo $user['id']; ?>" class="btn btn-sm btn-primary btn-action"><i class="fas fa-eye"></i></a>
                                            <a href="<?php echo BASE_URL; ?>edit-user.php?id=<?php echo $user['id']; ?>" class="btn btn-sm btn-warning btn-action"><i class="fas fa-edit"></i></a>
                                            <button class="btn btn-sm btn-danger btn-action" onclick="confirmDeleteUser(<?php echo $user['id']; ?>)"><i class="fas fa-trash"></i></button>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </section>

        <section id="admin-transactions" class="mb-5">
            <h2><i class="fas fa-exchange-alt me-2"></i> Manajemen Transaksi</h2>
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead>
                                <tr>
                                    <th scope="col">User</th>
                                    <th scope="col">Apartemen</th>
                                    <th scope="col">Tanggal Mulai</th>
                                    <th scope="col">Tanggal Selesai</th>
                                    <th scope="col">Total Harga</th>
                                    <th scope="col">Status</th>
                                    <th scope="col" class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($transactions as $transaction): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($transaction['username']); ?></td>
                                        <td><?php echo htmlspecialchars($transaction['apartment_name']); ?></td>
                                        <td><?php echo date('d M Y', strtotime($transaction['start_date'])); ?></td>
                                        <td><?php echo date('d M Y', strtotime($transaction['end_date'])); ?></td>
                                        <td>Rp. <?php echo number_format($transaction['total_price'], 0, ',', '.'); ?></td>
                                        <td><span class="badge bg-<?php echo $transaction['status'] == 'completed' ? 'success' : ($transaction['status'] == 'confirmed' ? 'info' : 'warning'); ?>"><?php echo ucfirst($transaction['status']); ?></span></td>
                                        <td class="table-actions">
                                            <a href="<?php echo BASE_URL; ?>view-transaction.php?id=<?php echo $transaction['id']; ?>" class="btn btn-sm btn-primary btn-action"><i class="fas fa-eye"></i></a>
                                            <a href="<?php echo BASE_URL; ?>edit-transaction.php?id=<?php echo $transaction['id']; ?>" class="btn btn-sm btn-warning btn-action"><i class="fas fa-edit"></i></a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </section>
        <!-- ================ END ADMIN VIEW ================ -->
        <?php endif; ?>

    </main>

    <!-- Bootstrap 5 JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        const BASE_URL_JS = '<?php echo BASE_URL; ?>'; // Buat base URL untuk JavaScript

        function confirmDeleteApartment(id) {
            if (confirm('Apakah Anda yakin ingin menghapus apartemen ini?')) {
                window.location.href = BASE_URL_JS + 'auth/delete-apartment.php?id=' + id;
            }
        }

        function confirmDeleteUser(id) {
            if (confirm('Apakah Anda yakin ingin menghapus pengguna ini? Tindakan ini akan menghapus semua data transaksi yang terkait. Ini tidak bisa dibatalkan!')) {
                if (confirm('YA, HAPUS SEMUA!')) {
                    window.location.href = BASE_URL_JS + 'auth/delete-user.php?id=' + id;
                }
            }
        }
    </script>
</body>
</html>