<?php
// Ini buat ngambil data dari database, biar tabelnya ada isinya
require_once __DIR__ . '/../app/rentController.php';
    $rentController = new rentController();

// Ambil data berdasarkan role
if ($_SESSION['role'] == 'admin') {
    $apartments = $rentController->getAllApartments();
    $transactions = $rentController->getAllTransactions();
    $users = $rentController->getAllUsers();
} else {
    $myTransactions = $rentController->getMyTransactions($_SESSION['user_id']);
    $availableApartments = $rentController->getAvailableApartments();
}
?>

<!-- ... (kode navbar dan sidebar tetap sama) ... -->

<main class="main-content">
    <h1>Dashboard</h1>
    <p class="text-muted">Selamat datang kembali, <strong><?php echo htmlspecialchars($_SESSION['full_name']); ?></strong>! (Role: <span class="badge bg-primary"><?php echo htmlspecialchars($_SESSION['role']); ?></span>)</p>

    <?php if ($_SESSION['role'] == 'admin'): ?>
        <!-- ================ TAMPILAN ADMIN ================ -->
        
        <!-- Statistik -->
        <div class="row mb-4">
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card card-stat border-left-primary">
                    <div class="card-body">
                        <h5 class="card-title text-uppercase text-muted mb-2">Apartemen</h5>
                        <h3 class="mb-0"><?php echo count($apartments); ?></h3>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card card-stat border-left-success">
                    <div class="card-body">
                        <h5 class="card-title text-uppercase text-muted mb-2">Pengguna</h5>
                        <h3 class="mb-0"><?php echo count($users); ?></h3>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card card-stat border-left-info">
                    <div class="card-body">
                        <h5 class="card-title text-uppercase text-muted mb-2">Transaksi</h5>
                        <h3 class="mb-0"><?php echo count($transactions); ?></h3>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card card-stat border-left-warning">
                    <div class="card-body">
                        <h5 class="card-title text-uppercase text-muted mb-2">Est. Pendapatan</h5>
                        <h3 class="mb-0">Rp. <?php 
                            // Hitung total pendapatan dari transaksi yang 'completed'
                            $totalRevenue = 0;
                            foreach ($transactions as $t) {
                                if ($t['status'] == 'completed') {
                                    $totalRevenue += $t['total_price'];
                                }
                            }
                            echo number_format($totalRevenue, 0, ',', '.'); 
                        ?></h3>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tabel Apartemen -->
        <section id="apartments-section" class="mb-5">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">Manajemen Apartemen</h4>
                    <a href="add-apartment.php" class="btn btn-primary btn-sm"><i class="fas fa-plus me-1"></i> Tambah Baru</a>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="table-light">
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
                                    <td class="text-center">
                                        <a href="view-apartment.php?id=<?php echo $apartment['id']; ?>" class="btn btn-sm btn-info btn-action" title="Lihat Detail"><i class="fas fa-eye"></i></a>
                                        <a href="edit-apartment.php?id=<?php echo $apartment['id']; ?>" class="btn btn-sm btn-warning btn-action" title="Edit"><i class="fas fa-edit"></i></a>
                                        <button class="btn btn-sm btn-danger btn-action" onclick="confirmDeleteApartment(<?php echo $apartment['id']; ?>)" title="Hapus"><i class="fas fa-trash"></i></button>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </section>

        <!-- Tabel Users -->
        <section id="users-section" class="mb-5">
            <div class="card">
                <div class="card-header">
                    <h4 class="mb-0">Manajemen Pengguna</h4>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th scope="col">Username</th>
                                    <th scope="col">Nama Lengkap</th>
                                    <th scope="col">Email</th>
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
                                    <td class="text-center">
                                        <a href="view-user.php?id=<?php echo $user['id']; ?>" class="btn btn-sm btn-primary btn-action" title="Lihat Detail"><i class="fas fa-eye"></i></a>
                                        <a href="edit-user.php?id=<?php echo $user['id']; ?>" class="btn btn-sm btn-warning btn-action" title="Edit"><i class="fas fa-edit"></i></a>
                                        <button class="btn btn-sm btn-danger btn-action" onclick="confirmDeleteUser(<?php echo $user['id']; ?>)" title="Hapus"><i class="fas fa-key"></i></button>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </section>

        <!-- Tabel Transaksi -->
        <section id="transactions-section" class="mb-5">
            <div class="card">
                <div class="card-header">
                    <h4 class="mb-0">Riwayat Transaksi</h4>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="table-light">
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
                                    <th scope="row"><?php echo htmlspecialchars($transaction['username']); ?></th>
                                    <td><?php echo htmlspecialchars($transaction['apartment_name']); ?></td>
                                    <td><?php echo date('d M Y', strtotime($transaction['start_date'])); ?></td>
                                    <td><?php echo date('d M Y', strtotime($transaction['end_date'])); ?></td>
                                    <td>Rp. <?php echo number_format($transaction['total_price'], 0, ',', '.'); ?></td>
                                    <td><span class="badge bg-<?php echo $transaction['status'] == 'completed' ? 'success' : ($transaction['status'] == 'confirmed' ? 'info' : 'warning'); ?>"><?php echo ucfirst($transaction['status']); ?></span></td>
                                    <td class="text-center">
                                        <a href="view-transaction.php?id=<?php echo $transaction['id']; ?>" class="btn btn-sm btn-primary btn-action" title="Lihat Detail"><i class="fas fa-eye"></i></a>
                                        <a href="edit-transaction.php?id=<?php echo $transaction['id']; ?>" class="btn btn-sm btn-warning btn-action" title="Ubah Status"><i class="fas fa-edit"></i></a>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </section>

    <?php else: ?>
        <!-- ================ TAMPILAN USER ================ -->
        <!-- ... (tampilan user yang sudah kamu buat sebelumnya) ... -->
    <?php endif; ?>
</main>

<!-- ... (Script JS tetap sama) ... -->

<script>
// Fungsi untuk konfirmasi hapus (sederhana)
function confirmDeleteApartment(id) {
    if (confirm('Apakah Anda yakin ingin menghapus apartemen ini? Tindakan tidak dapat dibatalkan.')) {
        window.location.href = 'auth/delete-apartment.php?id=' + id;
    }
}

function confirmDeleteUser(id) {
    if (confirm('Apakah Anda yakin ingin menghapus pengguna ini? Ini akan menghapus semua data terkait.')) {
        window.location.href = 'auth/delete-user.php?id=' + id;
    }
}
</script>