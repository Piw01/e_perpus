<?php
include(ROOT_PATH . 'src/views/layouts/header.php');
?>

<div class="container-fluid mt-5">
    <div class="row">
        <div class="col-md-8 offset-md-2">
            <h2><i class="fas fa-plus-circle"></i> Tambah Siswa Baru</h2>
            <hr>

            <!-- Alert Error -->
            <?php if (isset($_SESSION['error_message'])): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-circle"></i> <?= htmlspecialchars($_SESSION['error_message']); ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
                <?php unset($_SESSION['error_message']); ?>
            <?php endif; ?>

            <div class="card shadow-sm">
                <div class="card-body">
                    <form action="index.php?page=siswa/store" method="POST" novalidate>
                        <!-- NISN -->
                        <div class="mb-3">
                            <label for="nisn" class="form-label">NISN <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="nisn" name="nisn" 
                                   placeholder="Masukkan NISN (20 digit)" 
                                   maxlength="20" required>
                            <small class="text-muted">NISN harus unik dan tidak boleh kosong</small>
                        </div>

                        <!-- Nama Siswa -->
                        <div class="mb-3">
                            <label for="nama_siswa" class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="nama_siswa" name="nama_siswa" 
                                   placeholder="Masukkan nama lengkap siswa" 
                                   maxlength="100" required>
                        </div>

                        <!-- Jenis Kelamin -->
                        <div class="mb-3">
                            <label for="jenis_kelamin" class="form-label">Jenis Kelamin <span class="text-danger">*</span></label>
                            <select class="form-select" id="jenis_kelamin" name="jenis_kelamin" required>
                                <option value="">-- Pilih --</option>
                                <option value="L">Laki-laki</option>
                                <option value="P">Perempuan</option>
                            </select>
                        </div>

                        <!-- Tempat Lahir -->
                        <div class="mb-3">
                            <label for="tempat_lahir" class="form-label">Tempat Lahir <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="tempat_lahir" name="tempat_lahir" 
                                   placeholder="Contoh: Bandung" 
                                   maxlength="30" required>
                        </div>

                        <!-- Tanggal Lahir -->
                        <div class="mb-3">
                            <label for="tgl_lahir" class="form-label">Tanggal Lahir <span class="text-danger">*</span></label>
                            <input type="date" class="form-control" id="tgl_lahir" name="tgl_lahir" required>
                        </div>

                        <!-- Alamat -->
                        <div class="mb-3">
                            <label for="alamat" class="form-label">Alamat <span class="text-danger">*</span></label>
                            <textarea class="form-control" id="alamat" name="alamat" 
                                      rows="3" placeholder="Masukkan alamat lengkap" 
                                      maxlength="255" required></textarea>
                        </div>

                        <!-- No. HP -->
                        <div class="mb-3">
                            <label for="no_hp" class="form-label">No. HP <span class="text-danger">*</span></label>
                            <input type="tel" class="form-control" id="no_hp" name="no_hp" 
                                   placeholder="Contoh: 08123456789" 
                                   maxlength="13" required>
                        </div>

                        <!-- Tombol Submit -->
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Simpan Data
                            </button>
                            <a href="index.php?page=siswa/index" class="btn btn-secondary">
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