<?php
include(ROOT_PATH . 'src/views/layouts/header.php');
?>
<div class="container-fluid mt-5">
    <div class="row">
        <div class="col-md-6 offset-md-3">
            <h2><i class="fas fa-edit"></i> Edit Penerbit</h2>
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
                    <form action="index.php?page=penerbit/update" method="POST">
                        <div class="mb-3">
                            <label for="id_penerbit" class="form-label">ID Penerbit</label>
                            <input type="text" class="form-control" id="id_penerbit" name="id_penerbit" 
                                   value="<?= htmlspecialchars($data_penerbit['id_penerbit']) ?>" readonly>
                        </div>
                        <div class="mb-3">
                            <label for="nama_penerbit" class="form-label">Nama Penerbit <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="nama_penerbit" name="nama_penerbit" 
                                   value="<?= htmlspecialchars($data_penerbit['nama_penerbit']) ?>" 
                                   maxlength="50" required>
                        </div>
                        <div class="mb-3">
                            <label for="kota" class="form-label">Kota <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="kota" name="kota" 
                                   value="<?= htmlspecialchars($data_penerbit['kota']) ?>" 
                                   maxlength="30" required>
                        </div>
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Simpan Perubahan
                            </button>
                            <a href="index.php?page=penerbit/index" class="btn btn-secondary">
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