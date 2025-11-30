<?php 

include(ROOT_PATH . 'src/views/layouts/header.php');
?>

<div class="container mt-4">
    
    <?php if (isset($_SESSION['success_message'])): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i> <?= htmlspecialchars($_SESSION['success_message']); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php unset($_SESSION['success_message']); ?>
    <?php endif; ?>

    <?php if (isset($_SESSION['error_message'])): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-circle me-2"></i> <?= htmlspecialchars($_SESSION['error_message']); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php unset($_SESSION['error_message']); ?>
    <?php endif; ?>
    
    <div class="alert alert-info">
        <h4><i class="fas fa-chart-line me-2"></i> Selamat Datang di Dashboard!</h4>
        <p>Anda login sebagai: <strong><?= htmlspecialchars($_SESSION['nama_lengkap'] ?? $_SESSION['username']) ?></strong></p>
    </div>

    <div class="row mt-4">
        
        <div class="col-md-4 mb-3">
            <div style="background-color: rgba(55, 118, 190, 1);" class="card text-white shadow">
                <div style="background-color: rgba(0, 44, 156, 1);" class="card-header"><i class="fas fa-book me-2"></i> Total Buku</div>
                <div class="card-body">
                    <h1 class="card-title display-4"><?= $total_buku ?></h1> 
                    <p class="card-text">Judul Buku yang tersedia</p>
                </div>
            </div>
        </div>
        
        <div class="col-md-4 mb-3">
            <div style="background-color: rgba(227, 217, 28, 1);" class="card text-white shadow">
                <div style="background-color: rgba(189, 157, 0, 1);" class="card-header"><i class="fas fa-users me-2"></i> Total Siswa</div>
                <div class="card-body">
                    <h1 class="card-title display-4"><?= $total_siswa ?></h1>
                    <p class="card-text">Anggota perpustakaan aktif</p>
                </div>
            </div>
        </div>
        
        <div class="col-md-4 mb-3">
            <div style="background-color: rgba(76, 209, 107, 1);" class="card text-white shadow">
                <div style="background-color: rgba(56, 178, 77, 1);" class="card-header"><i class="fas fa-hand-holding me-2"></i> Buku Dipinjam</div>
                <div class="card-body">
                    <h1 class="card-title display-4"><?= $buku_dipinjam ?></h1>
                    <p class="card-text">Buku yang belum dikembalikan</p>
                </div>
            </div>
        </div>
    </div>
    
<div class="row mt-4">
    <div class="col-md-12">
        <h3><i class="fas fa-arrow-circle-right me-2"></i> Akses Cepat Data</h3>
    </div>
</div>

<div class="row mt-2">
    
    <div class="col-md-6 mb-4">
        <a href="index.php?page=buku/index" class="text-decoration-none">
            <div class="card card-shortcut border-0 shadow-lg" style="background-color: #f0f8ff;">
                <div class="card-body d-flex align-items-center">
                    <i class="fas fa-book fa-3x text-primary me-4"></i>
                    <div>
                        <h4 class="card-title mb-0 text-dark">Data Buku</h4>
                        <p class="card-text text-secondary">Kelola daftar buku perpustakaan.</p>
                    </div>
                    <i class="fas fa-chevron-right ms-auto text-primary"></i>
                </div>
            </div>
        </a>
    </div>

    <div class="col-md-6 mb-4">
        <a href="index.php?page=siswa/index" class="text-decoration-none">
            <div class="card card-shortcut border-0 shadow-lg" style="background-color: #fff8f0;">
                <div class="card-body d-flex align-items-center">
                    <i class="fas fa-user-graduate fa-3x text-warning me-4"></i>
                    <div>
                        <h4 class="card-title mb-0 text-dark">Data Siswa</h4>
                        <p class="card-text text-secondary">Kelola data anggota dan siswa.</p>
                    </div>
                    <i class="fas fa-chevron-right ms-auto text-warning"></i>
                </div>
            </div>
        </a>
    </div>

    </div>
</div>
    
<hr>
    
<?php include(ROOT_PATH . 'src/views/layouts/footer.php'); ?>