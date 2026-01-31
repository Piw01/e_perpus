<?php include(ROOT_PATH . 'src/views/layouts/header.php'); ?>

<div class="container-fluid mt-4">
    <div class="card shadow">
        <div class="card-header bg-primary text-white">
            <h4 class="mb-0"><i class="fas fa-handshake"></i> Data Peminjaman Buku</h4>
        </div>
        <div class="card-body">
            <?php if (isset($_SESSION['success_message'])): ?>
                <div class="alert alert-success alert-dismissible fade show">
                    <?= $_SESSION['success_message']; unset($_SESSION['success_message']); ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <div class="d-flex justify-content-between mb-3">
                <div>
                    <a href="index.php?page=peminjaman/create" class="btn btn-success">
                        <i class="fas fa-plus"></i> Peminjaman Baru
                    </a>
                </div>
                <div class="btn-group">
                    <a href="index.php?page=peminjaman/index" class="btn btn-outline-secondary <?= empty($_GET['status']) ? 'active' : '' ?>">
                        Semua
                    </a>
                    <a href="index.php?page=peminjaman/index&status=dipinjam" class="btn btn-outline-primary <?= ($_GET['status'] ?? '') == 'dipinjam' ? 'active' : '' ?>">
                        Dipinjam
                    </a>
                    <a href="index.php?page=peminjaman/index&status=dikembalikan" class="btn btn-outline-success <?= ($_GET['status'] ?? '') == 'dikembalikan' ? 'active' : '' ?>">
                        Dikembalikan
                    </a>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead class="table-dark">
                        <tr>
                            <th>No</th>
                            <th>Kode</th>
                            <th>Anggota</th>
                            <th>Tgl Pinjam</th>
                            <th>Harus Kembali</th>
                            <th>Total Buku</th>
                            <th>Status</th>
                            <th>Admin</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($data_peminjaman)): ?>
                            <?php $no = 1; foreach ($data_peminjaman as $pjm): ?>
                                <tr>
                                    <td><?= $no++ ?></td>
                                    <td><strong><?= htmlspecialchars($pjm['kode_peminjaman']) ?></strong></td>
                                    <td>
                                        <?= htmlspecialchars($pjm['no_anggota']) ?><br>
                                        <small><?= htmlspecialchars($pjm['nama_lengkap']) ?></small>
                                    </td>
                                    <td><?= date('d-m-Y', strtotime($pjm['tanggal_pinjam'])) ?></td>
                                    <td><?= date('d-m-Y', strtotime($pjm['tanggal_harus_kembali'])) ?></td>
                                    <td><span class="badge bg-info"><?= $pjm['total_buku'] ?></span></td>
                                    <td>
                                        <?php if ($pjm['status_pinjam'] == 'dipinjam'): ?>
                                            <span class="badge bg-warning">Dipinjam</span>
                                        <?php elseif ($pjm['status_pinjam'] == 'dikembalikan'): ?>
                                            <span class="badge bg-success">Dikembalikan</span>
                                        <?php else: ?>
                                            <span class="badge bg-danger">Terlambat</span>
                                        <?php endif; ?>
                                    </td>
                                    <td><small><?= htmlspecialchars($pjm['admin_nama']) ?></small></td>
                                    <td>
                                        <a href="index.php?page=pengembalian/index&kode=<?= $pjm['kode_peminjaman'] ?>" 
                                           class="btn btn-sm btn-info">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr><td colspan="9" class="text-center">Tidak ada data peminjaman.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php include(ROOT_PATH . 'src/views/layouts/footer.php'); ?>