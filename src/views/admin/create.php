<?php include(ROOT_PATH . 'src/views/layouts/header.php'); ?>

<div class="container mt-5">
    <div class="row">
        <div class="col-md-8 offset-md-2">
            <div class="card shadow-lg">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0"><i class="fas fa-user-plus me-2"></i> Tambah Akun Admin Baru</h4>
                </div>
                <div class="card-body">
                    <form action="index.php?page=admin/store" method="POST" id="adminForm">
                        <div class="mb-3">
                            <label for="nama_lengkap" class="form-label">Nama Lengkap:</label>
                            <input type="text" class="form-control" id="nama_lengkap" name="nama_lengkap" required maxlength="100">
                        </div>
                        <div class="mb-3">
                            <label for="username" class="form-label">Username:</label>
                            <input type="text" class="form-control" id="username" name="username" required maxlength="100">
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label">Password:</label>
                            <input type="password" class="form-control" id="password" name="password" required>
                        </div>
                        
                        <!-- INPUT LEVEL BARU -->
                        <div class="mb-3">
                            <label for="level" class="form-label">Level Akses:</label>
                            <select class="form-select" name="level" id="level">
                                <option value="1">Super Admin (Full Akses)</option>
                                <option value="2" selected>Admin (Data Master & Transaksi)</option>
                                <option value="3">Operator (Hanya Transaksi)</option>
                            </select>
                        </div>

                        <div class="d-flex justify-content-between mt-4">
                            <a href="index.php?page=admin/index" class="btn btn-secondary">Batal</a>
                            <button type="submit" class="btn btn-primary">Simpan Admin</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<?php include(ROOT_PATH . 'src/views/layouts/footer.php'); ?>