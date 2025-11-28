<?php
include(ROOT_PATH . 'src/views/layouts/header.php');
?>
<div class="container-fluid mt-5">
    <div class="row mb-4">
        <div class="col-md-8">
            <h2><i class="fas fa-list"></i> Data Kategori</h2>
        </div>
        <div class="col-md-4 text-end">
            <a href="index.php?page=kategori/create" class="btn btn-primary">
                <i class="fas fa-plus"></i> Tambah Kategori
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
                        <th>Nama Kategori</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($data_kategori)): ?>
                        <?php foreach ($data_kategori as $kat): ?>
                            <tr>
                                <td><?= htmlspecialchars($kat['id_kategori']) ?></td>
                                <td><?= htmlspecialchars($kat['nama_kategori']) ?></td>
                                <td>
                                    <a href="index.php?page=kategori/edit&id=<?= $kat['id_kategori'] ?>" 
                                       class="btn btn-sm btn-warning me-1">
                                        <i class="fas fa-edit"></i> Edit
                                    </a>
                                    <a href="index.php?page=kategori/delete&id=<?= $kat['id_kategori'] ?>" 
                                       class="btn btn-sm btn-danger"
                                       onclick="return confirm('Yakin ingin menghapus?')">
                                        <i class="fas fa-trash"></i> Hapus
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="3" class="text-center text-muted">Belum ada data kategori</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?php include(ROOT_PATH . 'src/views/layouts/footer.php'); ?>
