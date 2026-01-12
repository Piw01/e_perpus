<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Anggota | E-Perpus</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-success">
    <div class="container">
        <a class="navbar-brand" href="index.php?page=anggota/dashboard">
            <i class="fas fa-user-circle me-2"></i> Dashboard Anggota
        </a>
        <div class="d-flex">
            <a href="index.php?page=katalog/index" class="btn btn-sm btn-outline-light me-2">Katalog</a>
            <a href="index.php?page=auth/logout" class="btn btn-sm btn-danger">Logout</a>
        </div>
    </div>
</nav>

<div class="container my-5">
    <div class="row mb-4">
        <div class="col-md-8">
            <h2>Selamat Datang, <?= htmlspecialchars($anggota['nama_lengkap']) ?>!</h2>
            <p class="text-muted">Nomor Anggota: <strong><?= htmlspecialchars($anggota['no_anggota']) ?></strong></p>
        </div>
        <div class="col-md-4 text-end">
            <div class="card bg-light">
                <div class="card-body">
                    <small>Masa Aktif Sampai:</small><br>
                    <strong><?= date('d-m-Y', strtotime($anggota['tanggal_expired'])) ?></strong>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-4 mb-3">
            <div class="card text-white bg-primary">
                <div class="card-body text-center">
                    <h1><?= $anggota['total_pinjam'] ?></h1>
                    <p class="mb-0">Total Peminjaman</p>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-3">
            <div class="card text-white bg-warning">
                <div class="card-body text-center">
                    <h1>Rp <?= number_format($anggota['denda_aktif']) ?></h1>
                    <p class="mb-0">Denda Aktif</p>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-3">
            <div class="card text-white bg-success">
                <div class="card-body text-center">
                    <h1><?= ucfirst($anggota['status_anggota']) ?></h1>
                    <p class="mb-0">Status Keanggotaan</p>
                </div>
            </div>
        </div>
    </div>

    <div class="card mt-4">
        <div class="card-header bg-success text-white">
            <h5 class="mb-0"><i class="fas fa-info-circle"></i> Informasi Profil</h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <table class="table table-borderless">
                        <tr><td width="150"><strong>Email</strong></td><td>: <?= htmlspecialchars($anggota['email']) ?></td></tr>
                        <tr><td><strong>No. HP</strong></td><td>: <?= htmlspecialchars($anggota['no_hp']) ?></td></tr>
                        <tr><td><strong>Pekerjaan</strong></td><td>: <?= htmlspecialchars($anggota['pekerjaan']) ?></td></tr>
                        <tr><td><strong>Instansi</strong></td><td>: <?= htmlspecialchars($anggota['instansi'] ?? '-') ?></td></tr>
                    </table>
                </div>
                <div class="col-md-6">
                    <table class="table table-borderless">
                        <tr><td width="150"><strong>Alamat</strong></td><td>: <?= htmlspecialchars($anggota['alamat']) ?></td></tr>
                        <tr><td><strong>Kota</strong></td><td>: <?= htmlspecialchars($anggota['kota']) ?></td></tr>
                        <tr><td><strong>Terdaftar Sejak</strong></td><td>: <?= date('d-m-Y', strtotime($anggota['tanggal_daftar'])) ?></td></tr>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="text-center mt-4">
        <a href="index.php?page=katalog/index" class="btn btn-lg btn-primary">
            <i class="fas fa-search"></i> Cari Buku di Katalog
        </a>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>