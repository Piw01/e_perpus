<?php
include(ROOT_PATH . 'src/views/layouts/header.php');
?>
<div class="container-fluid mt-5">
    <div class="row mb-4">
        <div class="col-md-8">
            <h2><i class="fas fa-building"></i> Data Penerbit</h2>
        </div>
        <div class="col-md-4 text-end">
            <a href="index.php?page=penerbit/create" class="btn btn-primary">
                <i class="fas fa-plus"></i> Tambah Penerbit
            </a>
        </div>
    </div>

    <?php if (isset($_SESSION['success_message'])): ?>
        <div class="alert alert-success alert-dismissible fade show">
            <?= htmlspecialchars($_SESSION['success_message']); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php unset($_SESSION['success_message']); ?>
    <?php endif; ?>

    <?php if (isset($_SESSION['error_message'])): ?>
        <div class="alert alert-danger alert-dismissible fade show">
            <?= htmlspecialchars($_SESSION['error_message']); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php unset($_SESSION['error_message']); ?>
    <?php endif; ?>

    <div class="card shadow-sm">
        <div class="card-body">
            <table class="table table-striped table-hover">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>Nama Penerbit</th>
                        <th>Kota</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($data_penerbit)): ?>
                        <?php foreach ($data_penerbit as $penerbit): ?>
                            <tr>
                                <td><?= htmlspecialchars($penerbit['id_penerbit']) ?></td>
                                <td><?= htmlspecialchars($penerbit['nama_penerbit']) ?></td>
                                <td><?= htmlspecialchars($penerbit['kota']) ?></td>
                                <td>
                                    <a href="index.php?page=penerbit/edit&id=<?= $penerbit['id_penerbit'] ?>" 
                                       class="btn btn-sm btn-warning me-1">
                                        <i class="fas fa-edit"></i> Edit
                                    </a>
                                    <a href="index.php?page=penerbit/delete&id=<?= $penerbit['id_penerbit'] ?>" 
                                       class="btn btn-sm btn-danger"
                                       onclick="return confirm('Yakin ingin menghapus?')">
                                        <i class="fas fa-trash"></i> Hapus
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="4" class="text-center text-muted">Belum ada data penerbit</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?php include(ROOT_PATH . 'src/views/layouts/footer.php'); ?>
