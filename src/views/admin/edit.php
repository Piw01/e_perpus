<?php include(ROOT_PATH . 'src/views/layouts/header.php'); ?>

<div class="container mt-5">
    <div class="row">
        <div class="col-md-8 offset-md-2">
            <div class="card shadow-lg">
                <div class="card-header bg-warning text-dark">
                    <h4 class="mb-0"><i class="fas fa-user-edit me-2"></i> Edit Akun Admin</h4>
                </div>
                <div class="card-body">
                    <form action="index.php?page=admin/update" method="POST" id="adminEditForm">
                        <input type="hidden" name="id_admin" value="<?= htmlspecialchars($admin['id_admin']) ?>">
                        
                        <div class="mb-3">
                            <label for="nama_lengkap" class="form-label">Nama Lengkap:</label>
                            <input type="text" class="form-control" id="nama_lengkap" name="nama_lengkap" required value="<?= htmlspecialchars($admin['nama_lengkap']) ?>">
                        </div>
                        <div class="mb-3">
                            <label for="username" class="form-label">Username:</label>
                            <input type="text" class="form-control" id="username" name="username" required value="<?= htmlspecialchars($admin['username']) ?>">
                        </div>
                        
                        <!-- INPUT LEVEL -->
                        <div class="mb-3">
                            <label for="level" class="form-label">Level Akses:</label>
                            <select class="form-select" name="level" id="level">
                                <option value="1" <?= ($admin['level'] == 1) ? 'selected' : '' ?>>Super Admin</option>
                                <option value="2" <?= ($admin['level'] == 2) ? 'selected' : '' ?>>Admin</option>
                                <option value="3" <?= ($admin['level'] == 3) ? 'selected' : '' ?>>Operator</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label">Password Baru (Opsional):</label>
                            <input type="password" class="form-control" id="password" name="password" placeholder="Kosongkan jika tidak ingin ganti">
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="index.php?page=admin/index" class="btn btn-secondary">Kembali</a>
                            <button type="submit" class="btn btn-warning text-dark">Perbarui Admin</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<?php include(ROOT_PATH . 'src/views/layouts/footer.php'); ?>