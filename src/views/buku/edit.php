<?php 
// Ambil data POST dari session jika ada error
$data = $_SESSION['post_data'] ?? $buku;
unset($_SESSION['post_data']);

include(ROOT_PATH . 'src/views/layouts/header.php'); 
?>

<div class="container mt-4 mb-5">
    <div class="row">
        <div class="col-lg-10 offset-lg-1">
            <div class="card shadow-lg">
                <div class="card-header bg-warning text-dark">
                    <h4 class="mb-0"><i class="fas fa-book-reader me-2"></i> Edit Data Buku: <?= htmlspecialchars($data['judul']) ?></h4>
                </div>
                <div class="card-body">
                    
                    <!-- Pesan error/sukses dari session -->
                    <?php if (isset($_SESSION['error_message'])): ?>
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <?= $_SESSION['error_message']; unset($_SESSION['error_message']); ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    <?php endif; ?>

                    <form action="index.php?page=buku/update" method="POST" enctype="multipart/form-data" id="bukuEditForm">
                        
                        <!-- ID Buku (Hidden) -->
                        <input type="hidden" name="id_buku" value="<?= htmlspecialchars($data['id_buku']) ?>">
                        <!-- Nama File Sampul Lama (Hidden) -->
                        <input type="hidden" name="foto_sampul_lama" value="<?= htmlspecialchars($data['foto_sampul'] ?? '') ?>">

                        <div class="row">
                            <!-- Kolom Kiri -->
                            <div class="col-md-6">
                                <!-- ISBN -->
                                <div class="mb-3">
                                    <label for="isbn" class="form-label">ISBN:</label>
                                    <input type="text" class="form-control" id="isbn" name="isbn" required 
                                           maxlength="20" placeholder="Masukkan ISBN buku" 
                                           value="<?= htmlspecialchars($data['isbn'] ?? '') ?>">
                                    <div class="invalid-feedback">ISBN wajib diisi.</div>
                                </div>

                                <!-- Judul -->
                                <div class="mb-3">
                                    <label for="judul" class="form-label">Judul Buku:</label>
                                    <input type="text" class="form-control" id="judul" name="judul" required 
                                           maxlength="255" placeholder="Masukkan judul buku" 
                                           value="<?= htmlspecialchars($data['judul'] ?? '') ?>">
                                    <div class="invalid-feedback">Judul buku wajib diisi.</div>
                                </div>

                                <!-- Penulis -->
                                <div class="mb-3">
                                    <label for="id_penulis" class="form-label">Penulis:</label>
                                    <select class="form-select" id="id_penulis" name="id_penulis" required>
                                        <option value="">-- Pilih Penulis --</option>
                                        <?php foreach ($penulis as $p): ?>
                                            <option value="<?= $p['id_penulis'] ?>" 
                                                <?= ($data['id_penulis'] ?? '') == $p['id_penulis'] ? 'selected' : '' ?>>
                                                <?= htmlspecialchars($p['nama_penulis']) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                    <div class="invalid-feedback">Penulis wajib dipilih.</div>
                                </div>
                                
                                <!-- Penerbit -->
                                <div class="mb-3">
                                    <label for="id_penerbit" class="form-label">Penerbit:</label>
                                    <select class="form-select" id="id_penerbit" name="id_penerbit" required>
                                        <option value="">-- Pilih Penerbit --</option>
                                        <?php foreach ($penerbit as $p): ?>
                                            <option value="<?= $p['id_penerbit'] ?>" 
                                                <?= ($data['id_penerbit'] ?? '') == $p['id_penerbit'] ? 'selected' : '' ?>>
                                                <?= htmlspecialchars($p['nama_penerbit']) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                    <div class="invalid-feedback">Penerbit wajib dipilih.</div>
                                </div>
                                
                                <!-- Kategori -->
                                <div class="mb-3">
                                    <label for="id_kategori" class="form-label">Kategori:</label>
                                    <select class="form-select" id="id_kategori" name="id_kategori" required>
                                        <option value="">-- Pilih Kategori --</option>
                                        <?php foreach ($kategori as $k): ?>
                                            <option value="<?= $k['id_kategori'] ?>"
                                                <?= ($data['id_kategori'] ?? '') == $k['id_kategori'] ? 'selected' : '' ?>>
                                                <?= htmlspecialchars($k['nama_kategori']) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                    <div class="invalid-feedback">Kategori wajib dipilih.</div>
                                </div>
                            </div>
                            
                            <!-- Kolom Kanan -->
                            <div class="col-md-6">
                                <!-- Tahun Terbit -->
                                <div class="mb-3">
                                    <label for="tahun_terbit" class="form-label">Tahun Terbit:</label>
                                    <input type="number" class="form-control" id="tahun_terbit" name="tahun_terbit" required 
                                           min="1900" max="<?= date('Y') ?>" placeholder="Contoh: 2023"
                                           value="<?= htmlspecialchars($data['tahun_terbit'] ?? '') ?>">
                                    <div class="invalid-feedback">Tahun terbit wajib diisi (4 digit).</div>
                                </div>
                                
                                <!-- Jumlah -->
                                <div class="mb-3">
                                    <label for="jumlah" class="form-label">Jumlah Stok:</label>
                                    <input type="number" class="form-control" id="jumlah" name="jumlah" required 
                                           min="1" placeholder="Jumlah buku yang tersedia"
                                           value="<?= htmlspecialchars($data['jumlah'] ?? 1) ?>">
                                    <div class="invalid-feedback">Jumlah wajib diisi (minimal 1).</div>
                                </div>
                                
                                <!-- Sampul Buku -->
                                <div class="mb-3">
                                    <label for="foto_sampul" class="form-label">Ganti Sampul Buku (Opsional):</label>
                                    <input type="file" class="form-control" id="foto_sampul" name="foto_sampul" accept="image/*">
                                    <div class="form-text">Kosongkan jika tidak ingin mengganti. Maksimal 5MB.</div>
                                    <div class="invalid-feedback">Pilih file gambar yang valid.</div>
                                    
                                    <?php if (!empty($data['foto_sampul'])): ?>
                                        <p class="mt-2">Sampul saat ini:</p>
                                        <img src="assets/img/sampul_buku/<?= htmlspecialchars($data['foto_sampul']) ?>" 
                                             alt="Sampul Buku" style="max-width: 100px; height: auto; border: 1px solid #ccc; border-radius: 5px;">
                                    <?php endif; ?>
                                </div>

                                <!-- Sinopsis -->
                                <div class="mb-3">
                                    <label for="sinopsis" class="form-label">Sinopsis:</label>
                                    <textarea class="form-control" id="sinopsis" name="sinopsis" rows="5" 
                                              placeholder="Tulis sinopsis singkat buku ini..."><?= htmlspecialchars($data['sinopsis'] ?? '') ?></textarea>
                                    <div class="invalid-feedback">Sinopsis wajib diisi.</div>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between mt-4">
                            <a href="index.php?page=buku/index" class="btn btn-secondary">
                                <i class="fas fa-arrow-left me-1"></i> Kembali
                            </a>
                            <button type="submit" class="btn btn-warning text-dark">
                                <i class="fas fa-edit me-1"></i> Perbarui Buku
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.getElementById('bukuEditForm').addEventListener('submit', function(event) {
        let isValid = true;
        
        // Fungsi untuk validasi field
        function validateField(id, isSelect = false, minVal = null) {
            const field = document.getElementById(id);
            const value = field.value.trim();
            field.classList.remove('is-invalid');

            if (value === '' || (isSelect && value === '')) {
                field.classList.add('is-invalid');
                isValid = false;
            } else if (minVal !== null && parseFloat(value) < minVal) {
                field.classList.add('is-invalid');
                isValid = false;
            }
        }
        
        // Validasi field
        validateField('isbn');
        validateField('judul');
        validateField('id_penulis', true);
        validateField('id_penerbit', true);
        validateField('id_kategori', true);
        validateField('tahun_terbit', false, 1900);
        validateField('jumlah', false, 1);
        validateField('sinopsis');

        // Validasi file
        const foto_sampul = document.getElementById('foto_sampul');
        if (foto_sampul.files.length > 0) {
            const file = foto_sampul.files[0];
            const maxSize = 5 * 1024 * 1024; // 5MB
            const allowedExt = ['jpg', 'jpeg', 'png', 'gif'];
            const fileExt = file.name.split('.').pop().toLowerCase();
            
            if (file.size > maxSize || !allowedExt.includes(fileExt)) {
                foto_sampul.classList.add('is-invalid');
                isValid = false;
                // Menambahkan pesan error yang lebih spesifik jika diperlukan
            }
        }

        if (!isValid) {
            event.preventDefault();
            event.stopPropagation();
        }
    });
</script>

<?php 
include(ROOT_PATH . 'src/views/layouts/footer.php'); 
?>