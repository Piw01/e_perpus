<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Katalog Buku | E-Perpus Subang</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body>
<!-- Navbar Public -->
<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
    <div class="container">
        <a class="navbar-brand" href="index.php?page=katalog/index">
            <i class="fas fa-book-reader me-2"></i><strong>E-Perpus Subang</strong>
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item"><a class="nav-link active" href="index.php?page=katalog/index">Katalog</a></li>
                <?php if (isset($_SESSION['anggota_id'])): ?>
                    <li class="nav-item"><a class="nav-link" href="index.php?page=anggota/dashboard">Dashboard</a></li>
                    <li class="nav-item"><a class="btn btn-sm btn-outline-light ms-2" href="index.php?page=auth/logout">Logout</a></li>
                <?php else: ?>
                    <li class="nav-item"><a class="nav-link" href="index.php?page=anggota/register">Daftar</a></li>
                    <li class="nav-item"><a class="nav-link" href="index.php?page=auth/loginAnggota">Login Anggota</a></li>
                    <li class="nav-item"><a class="btn btn-sm btn-warning ms-2" href="index.php?page=auth/login">Login Admin</a></li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>

<!-- Hero Section -->
<div class="bg-light py-5">
    <div class="container">
        <h1 class="display-5 text-center mb-4"><i class="fas fa-search me-2"></i> Cari Buku Favorit Anda</h1>
        <form method="GET" action="index.php">
            <input type="hidden" name="page" value="katalog/index">
            <div class="row g-2">
                <div class="col-md-8">
                    <input type="text" class="form-control form-control-lg" name="search" 
                           placeholder="Cari berdasarkan judul, penulis, atau ISBN..." 
                           value="<?= htmlspecialchars($_GET['search'] ?? '') ?>">
                </div>
                <div class="col-md-2">
                    <select class="form-select form-select-lg" name="kategori">
                        <option value="">Semua Kategori</option>
                        <?php foreach ($kategori_list as $kat): ?>
                            <option value="<?= $kat['id_kategori'] ?>" <?= (($_GET['kategori'] ?? '') == $kat['id_kategori']) ? 'selected' : '' ?>>
                                <?= htmlspecialchars($kat['nama_kategori']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary btn-lg w-100">
                        <i class="fas fa-search"></i> Cari
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Daftar Buku -->
<div class="container my-5">
    <?php if (isset($_SESSION['success_message'])): ?>
        <div class="alert alert-success alert-dismissible fade show">
            <?= $_SESSION['success_message']; unset($_SESSION['success_message']); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>
    
    <?php if (isset($_SESSION['error_message'])): ?>
        <div class="alert alert-danger alert-dismissible fade show">
            <?= $_SESSION['error_message']; unset($_SESSION['error_message']); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <h3 class="mb-4">Daftar Buku Tersedia (<?= count($data_buku) ?>)</h3>
    
    <?php if (!empty($data_buku)): ?>
        <div class="row row-cols-1 row-cols-md-3 row-cols-lg-4 g-4">
            <?php foreach ($data_buku as $buku): ?>
                <div class="col">
                    <div class="card h-100 shadow-sm">
                        <img src="assets/img/sampul_buku/<?= htmlspecialchars($buku['foto_sampul']) ?>" 
                             class="card-img-top" alt="Sampul" style="height: 250px; object-fit: cover;">
                        <div class="card-body">
                            <h6 class="card-title"><?= htmlspecialchars($buku['judul']) ?></h6>
                            <p class="card-text text-muted small mb-1">
                                <i class="fas fa-user"></i> <?= htmlspecialchars($buku['nama_penulis']) ?>
                            </p>
                            <p class="card-text text-muted small mb-2">
                                <i class="fas fa-tag"></i> <?= htmlspecialchars($buku['nama_kategori']) ?>
                            </p>
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="badge bg-<?= $buku['jumlah_tersedia'] > 0 ? 'success' : 'danger' ?>">
                                    <?= $buku['jumlah_tersedia'] > 0 ? "Tersedia ({$buku['jumlah_tersedia']})" : 'Habis' ?>
                                </span>
                                <a href="index.php?page=katalog/detail&id=<?= $buku['id_buku'] ?>" 
                                   class="btn btn-sm btn-primary">Detail</a>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <div class="alert alert-info text-center">
            <i class="fas fa-info-circle"></i> Tidak ada buku yang ditemukan.
        </div>
    <?php endif; ?>
</div>

<!-- Footer -->
<footer class="bg-dark text-white text-center py-4 mt-5">
    <p class="mb-0">E-Perpus Subang &copy; 2026 | <a href="https://eperpussbg.my.id" class="text-white">eperpussbg.my.id</a></p>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>