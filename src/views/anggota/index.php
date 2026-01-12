<?php include(ROOT_PATH . 'src/views/layouts/header.php'); ?>

<div class="container-fluid mt-5">
    <div class="card shadow">
        <div class="card-header bg-warning text-dark">
            <h4 class="mb-0"><i class="fas fa-users"></i> Data Anggota Perpustakaan</h4>
        </div>
        <div class="card-body">
            <?php if (isset($_SESSION['success_message'])): ?>
                <div class="alert alert-success alert-dismissible fade show">
                    <?= $_SESSION['success_message']; unset($_SESSION['success_message']); ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <?php if (isset($_SESSION['error_message'])): ?>
                <div class="alert alert-danger alert-dismissible fade show">
                    <?= $_SESSION['error_message']; unset($_SESSION['error_message']); ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <div class="alert alert-info">
                <i class="fas fa-info-circle"></i> Total anggota terdaftar: <strong><?= count($data_anggota) ?></strong>
            </div>

            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead class="table-dark">
                        <tr>
                            <th>No</th>
                            <th>No. Anggota</th>
                            <th>Nama Lengkap</th>
                            <th>Email</th>
                            <th>No. HP</th>
                            <th>Status</th>
                            <th>Terdaftar</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($data_anggota)): ?>
                            <?php $no = 1; foreach ($data_anggota as $ang): ?>
                                <tr>
                                    <td><?= $no++ ?></td>
                                    <td><strong><?= htmlspecialchars($ang['no_anggota']) ?></strong></td>
                                    <td><?= htmlspecialchars($ang['nama_lengkap']) ?></td>
                                    <td><?= htmlspecialchars($ang['email']) ?></td>
                                    <td><?= htmlspecialchars($ang['no_hp']) ?></td>
                                    <td>
                                        <?php if ($ang['status_anggota'] == 'aktif'): ?>
                                            <span class="badge bg-success">Aktif</span>
                                        <?php elseif ($ang['status_anggota'] == 'nonaktif'): ?>
                                            <span class="badge bg-secondary">Non-aktif</span>
                                        <?php else: ?>
                                            <span class="badge bg-danger">Suspended</span>
                                        <?php endif; ?>
                                    </td>
                                    <td><?= date('d-m-Y', strtotime($ang['tanggal_daftar'])) ?></td>
                                    <td>
                                        <?php if ($_SESSION['level'] != 3): // Operator tidak bisa hapus ?>
                                            <button type="button" class="btn btn-danger btn-sm" 
                                                    onclick="if(confirm('Yakin hapus anggota ini?')) location.href='index.php?page=anggota/delete&id=<?= $ang['id_anggota'] ?>'">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="8" class="text-center text-muted">
                                    <i class="fas fa-inbox"></i> Belum ada anggota terdaftar.
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php include(ROOT_PATH . 'src/views/layouts/footer.php'); ?>