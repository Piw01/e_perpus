<?php include(ROOT_PATH . 'src/views/layouts/header.php'); ?>

<div class="container mt-4">
    
    <?php if (isset($_SESSION['success_message'])): ?>
        <div class="alert alert-success alert-dismissible fade show">
            <i class="fas fa-check-circle me-2"></i> <?= htmlspecialchars($_SESSION['success_message']); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php unset($_SESSION['success_message']); ?>
    <?php endif; ?>

    <?php if (isset($_SESSION['error_message'])): ?>
        <div class="alert alert-danger alert-dismissible fade show">
            <i class="fas fa-exclamation-circle me-2"></i> <?= htmlspecialchars($_SESSION['error_message']); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php unset($_SESSION['error_message']); ?>
    <?php endif; ?>
    
    <div class="alert alert-info">
        <h4><i class="fas fa-chart-line me-2"></i> Selamat Datang di Dashboard!</h4>
        <p class="mb-0">Anda login sebagai: <strong><?= htmlspecialchars($_SESSION['nama_lengkap'] ?? $_SESSION['username']) ?></strong></p>
    </div>

    <!-- Statistik Cards -->
    <div class="row mt-4">
        <div class="col-md-3 mb-3">
            <div class="card text-white shadow" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                <div class="card-body text-center">
                    <i class="fas fa-book fa-2x mb-2"></i>
                    <h1 class="display-4"><?= $total_buku ?></h1> 
                    <p class="mb-0">Total Buku</p>
                </div>
            </div>
        </div>
        
        <div class="col-md-3 mb-3">
            <div class="card text-white shadow" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);">
                <div class="card-body text-center">
                    <i class="fas fa-users fa-2x mb-2"></i>
                    <h1 class="display-4"><?= $total_anggota ?></h1>
                    <p class="mb-0">Anggota Aktif</p>
                </div>
            </div>
        </div>
        
        <div class="col-md-3 mb-3">
            <div class="card text-white shadow" style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);">
                <div class="card-body text-center">
                    <i class="fas fa-handshake fa-2x mb-2"></i>
                    <h1 class="display-4"><?= $buku_dipinjam ?></h1>
                    <p class="mb-0">Sedang Dipinjam</p>
                </div>
            </div>
        </div>

        <div class="col-md-3 mb-3">
            <div class="card text-white shadow" style="background: linear-gradient(135deg, #fa709a 0%, #fee140 100%);">
                <div class="card-body text-center">
                    <i class="fas fa-exclamation-triangle fa-2x mb-2"></i>
                    <h1 class="display-4"><?= $total_terlambat ?></h1>
                    <p class="mb-0">Terlambat</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistik Tambahan -->
    <div class="row mt-3">
        <div class="col-md-6 mb-3">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="fas fa-chart-bar"></i> Statistik Bulan Ini</h5>
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-between mb-3">
                        <div>
                            <h6 class="text-muted">Peminjaman</h6>
                            <h3 class="text-primary"><?= $peminjaman_bulan_ini ?></h3>
                        </div>
                        <div>
                            <h6 class="text-muted">Pengembalian</h6>
                            <h3 class="text-success"><?= $pengembalian_bulan_ini ?></h3>
                        </div>
                    </div>
                    <hr>
                    <div>
                        <h6 class="text-muted">Total Denda Belum Lunas</h6>
                        <h3 class="text-danger">Rp <?= number_format($denda_belum_lunas) ?></h3>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6 mb-3">
            <div class="card shadow">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0"><i class="fas fa-star"></i> Buku Terpopuler</h5>
                </div>
                <div class="card-body">
                    <ul class="list-group list-group-flush">
                        <?php if (!empty($buku_populer)): ?>
                            <?php foreach ($buku_populer as $buku): ?>
                                <li class="list-group-item d-flex justify-content-between">
                                    <span><?= htmlspecialchars($buku['judul']) ?></span>
                                    <span class="badge bg-primary rounded-pill"><?= $buku['total_dipinjam'] ?> kali</span>
                                </li>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <li class="list-group-item text-muted">Belum ada data</li>
                        <?php endif; ?>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Akses Cepat -->
    <div class="row mt-4">
        <div class="col-md-12">
            <h3><i class="fas fa-arrow-circle-right me-2"></i> Akses Cepat</h3>
        </div>
    </div>

    <div class="row mt-2">
        <div class="col-md-4 mb-4">
            <a href="index.php?page=buku/index" class="text-decoration-none">
                <div class="card card-shortcut border-0 shadow-lg" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                    <div class="card-body d-flex align-items-center text-white">
                        <i class="fas fa-book fa-3x me-4"></i>
                        <div>
                            <h4 class="mb-0">Data Buku</h4>
                            <p class="mb-0 small">Kelola koleksi buku</p>
                        </div>
                        <i class="fas fa-chevron-right ms-auto"></i>
                    </div>
                </div>
            </a>
        </div>

        <div class="col-md-4 mb-4">
            <a href="index.php?page=anggota/index" class="text-decoration-none">
                <div class="card card-shortcut border-0 shadow-lg" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);">
                    <div class="card-body d-flex align-items-center text-white">
                        <i class="fas fa-users fa-3x me-4"></i>
                        <div>
                            <h4 class="mb-0">Data Anggota</h4>
                            <p class="mb-0 small">Kelola anggota perpustakaan</p>
                        </div>
                        <i class="fas fa-chevron-right ms-auto"></i>
                    </div>
                </div>
            </a>
        </div>

        <div class="col-md-4 mb-4">
            <a href="index.php?page=peminjaman/index" class="text-decoration-none">
                <div class="card card-shortcut border-0 shadow-lg" style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);">
                    <div class="card-body d-flex align-items-center text-white">
                        <i class="fas fa-handshake fa-3x me-4"></i>
                        <div>
                            <h4 class="mb-0">Peminjaman</h4>
                            <p class="mb-0 small">Transaksi peminjaman buku</p>
                        </div>
                        <i class="fas fa-chevron-right ms-auto"></i>
                    </div>
                </div>
            </a>
        </div>
    </div>
</div>
    
<hr>
    
<?php include(ROOT_PATH . 'src/views/layouts/footer.php'); ?>