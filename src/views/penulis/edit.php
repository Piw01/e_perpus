<?php
include(ROOT_PATH . 'src/views/layouts/header.php');
?>
<div class="container-fluid mt-5">
    <div class="row">
        <div class="col-md-6 offset-md-3">
            <h2><i class="fas fa-edit"></i> Edit Penulis</h2>
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
                    <form action="index.php?page=penulis/update" method="POST">
                        <div class="mb-3">
                            <label for="id_penulis" class="form-label">ID Penulis</label>
                            <input type="text" class="form-control" id="id_penulis" name="id_penulis" 
                                   value="<?= htmlspecialchars($data_penulis['id_penulis']) ?>" readonly>
                        </div>
                        <div class="mb-3">
                            <label for="nama_penulis" class="form-label">Nama Penulis <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="nama_penulis" name="nama_penulis" 
                                   value="<?= htmlspecialchars($data_penulis['nama_penulis']) ?>" 
                                   maxlength="100" required>
                        </div>
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Simpan Perubahan
                            </button>
                            <a href="index.php?page=penulis/index" class="btn btn-secondary">
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
