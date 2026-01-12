<?php include(ROOT_PATH . 'src/views/layouts/header.php'); ?>

<div class="container mt-4">
    <div class="card shadow">
        <div class="card-header bg-success text-white">
            <h4 class="mb-0"><i class="fas fa-undo"></i> Proses Pengembalian Buku</h4>
        </div>
        <div class="card-body">
            <?php if (isset($_SESSION['error_message'])): ?>
                <div class="alert alert-danger alert-dismissible fade show">
                    <?= $_SESSION['error_message']; unset($_SESSION['error_message']); ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <form method="POST" action="index.php?page=pengembalian/store">
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Pilih Peminjaman Aktif <span class="text-danger">*</span></label>
                            <select class="form-select" name="id_peminjaman" id="peminjamanSelect" required>
                                <option value="">-- Pilih Peminjaman --</option>
                                <?php foreach ($peminjaman_aktif as $pjm): ?>
                                    <option value="<?= $pjm['id_peminjaman'] ?>" 
                                            data-kode="<?= htmlspecialchars($pjm['kode_peminjaman']) ?>"
                                            data-anggota="<?= htmlspecialchars($pjm['nama_lengkap']) ?>"
                                            data-tgl-pinjam="<?= $pjm['tanggal_pinjam'] ?>"
                                            data-harus-kembali="<?= $pjm['tanggal_harus_kembali'] ?>">
                                        <?= htmlspecialchars($pjm['kode_peminjaman']) ?> - <?= htmlspecialchars($pjm['nama_lengkap']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div id="detailPeminjaman" class="alert alert-info d-none">
                            <h6>Detail Peminjaman:</h6>
                            <table class="table table-sm table-borderless mb-0">
                                <tr><td width="150">Kode</td><td>: <span id="showKode">-</span></td></tr>
                                <tr><td>Anggota</td><td>: <span id="showAnggota">-</span></td></tr>
                                <tr><td>Tgl Pinjam</td><td>: <span id="showTglPinjam">-</span></td></tr>
                                <tr><td>Harus Kembali</td><td>: <span id="showHarusKembali">-</span></td></tr>
                            </table>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Tanggal Kembali <span class="text-danger">*</span></label>
                            <input type="date" class="form-control" name="tanggal_kembali" 
                                   value="<?= date('Y-m-d') ?>" required>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="alert alert-warning">
                            <h6><i class="fas fa-exclamation-triangle"></i> Kondisi Buku Saat Dikembalikan</h6>
                            <p class="small mb-0">Pilih kondisi untuk setiap buku yang dikembalikan</p>
                        </div>

                        <div id="kondisiBukuContainer">
                            <p class="text-muted">Pilih peminjaman terlebih dahulu...</p>
                        </div>
                    </div>
                </div>

                <hr>
                <div class="d-flex justify-content-between">
                    <a href="index.php?page=pengembalian/index" class="btn btn-secondary">Batal</a>
                    <button type="submit" class="btn btn-success btn-lg">
                        <i class="fas fa-check"></i> Proses Pengembalian
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.getElementById('peminjamanSelect').addEventListener('change', function() {
    const selected = this.options[this.selectedIndex];
    const detailDiv = document.getElementById('detailPeminjaman');
    
    if (this.value) {
        document.getElementById('showKode').textContent = selected.dataset.kode;
        document.getElementById('showAnggota').textContent = selected.dataset.anggota;
        document.getElementById('showTglPinjam').textContent = selected.dataset.tglPinjam;
        document.getElementById('showHarusKembali').textContent = selected.dataset.harusKembali;
        detailDiv.classList.remove('d-none');
        
        // Load buku (hardcoded untuk demo, seharusnya AJAX)
        document.getElementById('kondisiBukuContainer').innerHTML = `
            <div class="mb-2">
                <label class="form-label"><strong>Buku 1</strong></label>
                <select class="form-select" name="kondisi_buku[1]" required>
                    <option value="baik">Baik</option>
                    <option value="rusak ringan">Rusak Ringan</option>
                    <option value="rusak berat">Rusak Berat</option>
                    <option value="hilang">Hilang</option>
                </select>
            </div>
        `;
    } else {
        detailDiv.classList.add('d-none');
        document.getElementById('kondisiBukuContainer').innerHTML = '<p class="text-muted">Pilih peminjaman terlebih dahulu...</p>';
    }
});
</script>

<?php include(ROOT_PATH . 'src/views/layouts/footer.php'); ?>