<?php
include(ROOT_PATH . 'src/views/layouts/header.php');
?>
<div class="container-fluid mt-5">
    <div class="row">
        <div class="col-md-6 offset-md-3">
            <h2><i class="fas fa-edit"></i> Edit Kategori</h2>
            <hr>

            <?php if (isset($_SESSION['error_message'])): ?>
                <div class="alert alert-danger alert-dismissible fade show">
                    <?= htmlspecialchars($_SESSION['error_message']); ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
                <?php unset($_SESSION['error_message']); ?>
            <?php endif; ?>

            <div class="card shadow-sm">
                <div class="card-body">
                    <form action="index.php?page=kategori/update" method="POST">
                        <div class="mb-3">
                            <label for="id_kategori" class="form-label">ID Kategori</label>
                            <input type="text" class="form-control" id="id_kategori" name="id_kategori" 
                                   value="<?= htmlspecialchars($data_kategori['id_kategori']) ?>" readonly>
                        </div>
                        <div class="mb-3">
                            <label for="nama_kategori" class="form-label">Nama Kategori <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="nama_kategori" name="nama_kategori" 
                                   value="<?= htmlspecialchars($data_kategori['nama_kategori']) ?>" required>
                        </div>
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Simpan Perubahan
                            </button>
                            <a href="index.php?page=kategori/index" class="btn btn-secondary">
                                <i class="fas fa-arrow-left"></i> Batal
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<?php include(ROOT_PATH . 'src/views/layouts/footer.php'); ?>