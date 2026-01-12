<?php
include(ROOT_PATH . 'src/views/layouts/header.php');
?>

<div class="container-fluid mt-5">
    <div class="row mb-4">
        <div class="col-md-8">
            <h2><i class="fas fa-users"></i> Data Siswa</h2>
        </div>
        <div class="col-md-4 text-end">
            <a href="index.php?page=siswa/create" class="btn btn-primary">
                <i class="fas fa-plus"></i> Tambah Siswa Baru
            </a>
        </div>
    </div>

    <!-- Alert Messages -->
    <?php if (isset($_SESSION['success_message'])): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle"></i> <?= htmlspecialchars($_SESSION['success_message']); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php unset($_SESSION['success_message']); ?>
    <?php endif; ?>

    <?php if (isset($_SESSION['error_message'])): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-circle"></i> <?= htmlspecialchars($_SESSION['error_message']); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php unset($_SESSION['error_message']); ?>
    <?php endif; ?>

    <!-- Tabel Data Siswa -->
    <div class="card shadow-sm">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead class="table-dark">
                        <tr>
                            <th>NISN</th>
                            <th>Nama Siswa</th>
                            <th>Jenis Kelamin</th>
                            <th>Tempat Lahir</th>
                            <th>Tanggal Lahir</th>
                            <th>No. HP</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($data_siswa)): ?>
                            <?php foreach ($data_siswa as $siswa): ?>
                                <tr>
                                    <td><strong><?= htmlspecialchars($siswa['nisn']) ?></strong></td>
                                    <td><?= htmlspecialchars($siswa['nama_siswa']) ?></td>
                                    <td>
                                        <?php 
                                            echo ($siswa['jenis_kelamin'] == 'L') ? 'Laki-laki' : 'Perempuan';
                                        ?>
                                    </td>
                                    <td><?= htmlspecialchars($siswa['tempat_lahir']) ?></td>
                                    <td><?= date('d-m-Y', strtotime($siswa['tgl_lahir'])) ?></td>
                                    <td><?= htmlspecialchars($siswa['no_hp']) ?></td>
                                    <td>
                                        <a href="index.php?page=siswa/edit&id=<?= urlencode($siswa['nisn']) ?>" 
                                           class="btn btn-sm btn-warning me-1">
                                            <i class="fas fa-edit"></i> Edit
                                        </a>
                                        <a href="index.php?page=siswa/delete&id=<?= urlencode($siswa['nisn']) ?>" 
                                           class="btn btn-sm btn-danger"
                                           onclick="return confirm('Yakin ingin menghapus data siswa ini?')">
                                            <i class="fas fa-trash"></i> Hapus
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="7" class="text-center text-muted">
                                    <i class="fas fa-inbox"></i> Belum ada data siswa yang tersedia.
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