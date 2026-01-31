<?php include(ROOT_PATH . 'src/views/layouts/header.php'); ?>

<div class="container-fluid mt-4">
    <div class="card shadow">
        <div class="card-header bg-success text-white">
            <h4 class="mb-0"><i class="fas fa-undo-alt"></i> Data Pengembalian & Denda</h4>
        </div>
        <div class="card-body">
            <?php if (isset($_SESSION['success_message'])): ?>
                <div class="alert alert-success alert-dismissible fade show">
                    <?= $_SESSION['success_message']; unset($_SESSION['success_message']); ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <a href="index.php?page=pengembalian/create" class="btn btn-primary mb-3">
                <i class="fas fa-plus"></i> Proses Pengembalian
            </a>

            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead class="table-dark">
                        <tr>
                            <th>No</th>
                            <th>Kode</th>
                            <th>Anggota</th>
                            <th>Kode Peminjaman</th>
                            <th>Tgl Kembali</th>
                            <th>Terlambat</th>
                            <th>Total Denda</th>
                            <th>Status Bayar</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($data_pengembalian)): ?>
                            <?php $no = 1; foreach ($data_pengembalian as $kmb): ?>
                                <tr>
                                    <td><?= $no++ ?></td>
                                    <td><strong><?= htmlspecialchars($kmb['kode_pengembalian']) ?></strong></td>
                                    <td>
                                        <?= htmlspecialchars($kmb['no_anggota']) ?><br>
                                        <small><?= htmlspecialchars($kmb['nama_lengkap']) ?></small>
                                    </td>
                                    <td><?= htmlspecialchars($kmb['kode_peminjaman']) ?></td>
                                    <td><?= date('d-m-Y', strtotime($kmb['tanggal_kembali'])) ?></td>
                                    <td>
                                        <?php if ($kmb['keterlambatan_hari'] > 0): ?>
                                            <span class="badge bg-warning"><?= $kmb['keterlambatan_hari'] ?> hari</span>
                                        <?php else: ?>
                                            <span class="badge bg-success">Tepat Waktu</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <strong>Rp <?= number_format($kmb['total_denda']) ?></strong><br>
                                        <small class="text-muted">
                                            <?php if ($kmb['denda_keterlambatan'] > 0): ?>
                                                ⏱ Telat: Rp <?= number_format($kmb['denda_keterlambatan']) ?><br>
                                            <?php endif; ?>
                                            <?php if ($kmb['denda_kerusakan'] > 0): ?>
                                                📕 Rusak: Rp <?= number_format($kmb['denda_kerusakan']) ?><br>
                                            <?php endif; ?>
                                            <?php if ($kmb['denda_kehilangan'] > 0): ?>
                                                ❌ Hilang: Rp <?= number_format($kmb['denda_kehilangan']) ?>
                                            <?php endif; ?>
                                        </small>
                                    </td>                                    <td>
                                        <?php if ($kmb['status_bayar'] == 'lunas'): ?>
                                            <span class="badge bg-success">Lunas</span>
                                        <?php elseif ($kmb['status_bayar'] == 'dicicil'): ?>
                                            <span class="badge bg-warning">Dicicil</span>
                                        <?php else: ?>
                                            <span class="badge bg-danger">Belum Lunas</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php if ($kmb['status_bayar'] != 'lunas' && $kmb['total_denda'] > 0): ?>
                                            <button class="btn btn-sm btn-warning" data-bs-toggle="modal" 
                                                    data-bs-target="#bayarDendaModal<?= $kmb['id_pengembalian'] ?>">
                                                <i class="fas fa-money-bill"></i> Bayar
                                            </button>

                                            <!-- Modal Bayar Denda -->
                                            <div class="modal fade" id="bayarDendaModal<?= $kmb['id_pengembalian'] ?>">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title">Bayar Denda</h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                        </div>
                                                        <form method="POST" action="index.php?page=pengembalian/bayarDenda">
                                                            <div class="modal-body">
                                                                <input type="hidden" name="id_pengembalian" value="<?= $kmb['id_pengembalian'] ?>">
                                                                <p><strong>Sisa Denda:</strong> Rp <?= number_format($kmb['sisa_denda']) ?></p>
                                                                <p style="font-size:14px; color:#666; margin-bottom:10px;">
                                                                    ⏱ Telat: Rp <?= number_format($kmb['denda_keterlambatan']) ?><br>
                                                                    📕 Rusak: Rp <?= number_format($kmb['denda_kerusakan']) ?><br>
                                                                    ❌ Hilang: Rp <?= number_format($kmb['denda_kehilangan']) ?>
                                                                </p> 
                                                                <div class="mb-3">
                                                                    <label class="form-label">Jumlah Dibayar</label>
                                                                    <input type="number" class="form-control" name="jumlah_bayar" 
                                                                           max="<?= $kmb['sisa_denda'] ?>" required>
                                                                </div>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                                                <button type="submit" class="btn btn-primary">Proses Pembayaran</button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr><td colspan="9" class="text-center">Tidak ada data pengembalian.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php include(ROOT_PATH . 'src/views/layouts/footer.php'); ?>