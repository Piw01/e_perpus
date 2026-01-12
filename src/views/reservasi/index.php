<?php include(ROOT_PATH . 'src/views/layouts/header.php'); ?>

<div class="container-fluid mt-4">
    <div class="card shadow">
        <div class="card-header bg-info text-white">
            <h4 class="mb-0"><i class="fas fa-bookmark"></i> Data Reservasi Buku</h4>
        </div>
        <div class="card-body">
            <?php if (isset($_SESSION['success_message'])): ?>
                <div class="alert alert-success alert-dismissible fade show">
                    <?= $_SESSION['success_message']; unset($_SESSION['success_message']); ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead class="table-dark">
                        <tr>
                            <th>No</th>
                            <th>Kode</th>
                            <th>Anggota</th>
                            <th>Buku</th>
                            <th>Tgl Reservasi</th>
                            <th>Batas Ambil</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($data_reservasi)): ?>
                            <?php $no = 1; foreach ($data_reservasi as $rsv): ?>
                                <tr>
                                    <td><?= $no++ ?></td>
                                    <td><strong><?= htmlspecialchars($rsv['kode_reservasi']) ?></strong></td>
                                    <td>
                                        <?= htmlspecialchars($rsv['nama_lengkap']) ?><br>
                                        <small class="text-muted"><?= htmlspecialchars($rsv['no_hp']) ?></small>
                                    </td>
                                    <td><?= htmlspecialchars($rsv['judul']) ?></td>
                                    <td><?= date('d-m-Y H:i', strtotime($rsv['tanggal_reservasi'])) ?></td>
                                    <td><?= date('d-m-Y H:i', strtotime($rsv['tanggal_expired'])) ?></td>
                                    <td>
                                        <?php if ($rsv['status'] == 'pending'): ?>
                                            <span class="badge bg-warning">Pending</span>
                                        <?php elseif ($rsv['status'] == 'diambil'): ?>
                                            <span class="badge bg-success">Diambil</span>
                                        <?php elseif ($rsv['status'] == 'expired'): ?>
                                            <span class="badge bg-danger">Expired</span>
                                        <?php else: ?>
                                            <span class="badge bg-secondary">Batal</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php if ($rsv['status'] == 'pending'): ?>
                                            <form method="POST" action="index.php?page=reservasi/updateStatus" class="d-inline">
                                                <input type="hidden" name="id_reservasi" value="<?= $rsv['id_reservasi'] ?>">
                                                <button type="submit" name="status" value="diambil" class="btn btn-sm btn-success">
                                                    <i class="fas fa-check"></i> Diambil
                                                </button>
                                                <button type="submit" name="status" value="batal" class="btn btn-sm btn-danger">
                                                    <i class="fas fa-times"></i> Batal
                                                </button>
                                            </form>
                                        <?php else: ?>
                                            <span class="text-muted">-</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr><td colspan="8" class="text-center">Tidak ada data reservasi.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php include(ROOT_PATH . 'src/views/layouts/footer.php'); ?>