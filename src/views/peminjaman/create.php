<?php include(ROOT_PATH . 'src/views/layouts/header.php'); ?>

<div class="container mt-4">
    <div class="card shadow">
        <div class="card-header bg-primary text-white">
            <h4 class="mb-0"><i class="fas fa-plus-circle"></i> Buat Peminjaman Baru</h4>
        </div>
        <div class="card-body">
            <?php if (isset($_SESSION['error_message'])): ?>
                <div class="alert alert-danger alert-dismissible fade show">
                    <?= $_SESSION['error_message']; unset($_SESSION['error_message']); ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <form method="POST" action="index.php?page=peminjaman/store" id="formPeminjaman">
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Pilih Anggota <span class="text-danger">*</span></label>
                            <select class="form-select" name="id_anggota" required>
                                <option value="">-- Pilih Anggota --</option>
                                <?php foreach ($anggota_list as $ang): ?>
                                    <option value="<?= $ang['id_anggota'] ?>">
                                        <?= htmlspecialchars($ang['no_anggota']) ?> - <?= htmlspecialchars($ang['nama_lengkap']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Durasi Peminjaman (hari)</label>
                            <input type="number" class="form-control" name="durasi_hari" value="7" min="1" max="30">
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Pilih Buku (Maksimal 3) <span class="text-danger">*</span></label>
                            <div style="max-height: 250px; overflow-y: auto; border: 1px solid #dee2e6; padding: 10px; border-radius: 5px;">
                                <?php foreach ($buku_tersedia as $buku): ?>
                                    <div class="form-check">
                                        <input class="form-check-input buku-checkbox" type="checkbox" 
                                               name="id_buku[]" value="<?= $buku['id_buku'] ?>" 
                                               id="buku<?= $buku['id_buku'] ?>">
                                        <label class="form-check-label" for="buku<?= $buku['id_buku'] ?>">
                                            <strong><?= htmlspecialchars($buku['judul']) ?></strong><br>
                                            <small class="text-muted">Tersedia: <?= $buku['jumlah_tersedia'] ?> eksemplar</small>
                                        </label>
                                    </div>
                                    <hr class="my-2">
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                </div>

                <hr>
                <div class="d-flex justify-content-between">
                    <a href="index.php?page=peminjaman/index" class="btn btn-secondary">Batal</a>
                    <button type="submit" class="btn btn-primary btn-lg">
                        <i class="fas fa-save"></i> Proses Peminjaman
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
// Limit checkbox maksimal 3
document.addEventListener('DOMContentLoaded', function() {
    const checkboxes = document.querySelectorAll('.buku-checkbox');
    const maxChecked = 3;
    
    checkboxes.forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            const checkedCount = document.querySelectorAll('.buku-checkbox:checked').length;
            if (checkedCount >= maxChecked) {
                checkboxes.forEach(cb => {
                    if (!cb.checked) cb.disabled = true;
                });
            } else {
                checkboxes.forEach(cb => cb.disabled = false);
            }
        });
    });
});
</script>

<?php include(ROOT_PATH . 'src/views/layouts/footer.php'); ?>