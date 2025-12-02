<h2 class="mt-4">Apartemen Tersedia</h2>
<div class="row">
    <?php if (empty($availableApartments)): ?>
        <p>Belum ada apartemen tersedia saat ini.</p>
    <?php else: ?>
        <?php foreach ($availableApartments as $apartment): ?>
            <div class="col-md-4 mb-4">
                <div class="card h-100">
                    <img src="https://picsum.photos/seed/<?php echo $apartment['id']; ?>/400/250.jpg" class="card-img-top" alt="<?php echo htmlspecialchars($apartment['name']); ?>">
                    <div class="card-body d-flex flex-column">
                        <h5 class="card-title"><?php echo htmlspecialchars($apartment['name']); ?></h5>
                        <p class="card-text"><?php echo htmlspecialchars(substr($apartment['description'], 0, 80)) . '...'; ?></p>
                        <div class="mt-auto">
                            <p class="fw-bold text-primary">Rp. <?php echo number_format($apartment['price_per_month'], 0, ',', '.'); ?> /bulan</p>
                            <a href="rent-apartment.php?id=<?php echo $apartment['id']; ?>" class="btn btn-primary">Sewa Sekarang</a>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>

<h2 class="mt-5">Riwayat Transaksi Saya</h2>
<div class="table-responsive">
    <table class="table table-striped table-hover align-middle">
        <thead>
            <tr>
                <th>Apartemen</th>
                <th>Mulai</th>
                <th>Selesai</th>
                <th>Total Harga</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($myTransactions)): ?>
                <tr><td colspan="6" class="text-center">Anda belum memiliki transaksi.</td></tr>
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