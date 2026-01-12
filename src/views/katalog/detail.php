<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($buku['judul']) ?> | E-Perpus Subang</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body>
<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
    <div class="container">
        <a class="navbar-brand" href="index.php?page=katalog/index">
            <i class="fas fa-book-reader me-2"></i><strong>E-Perpus Subang</strong>
        </a>
        <div class="ms-auto">
            <a href="index.php?page=katalog/index" class="btn btn-sm btn-outline-light">
                <i class="fas fa-arrow-left"></i> Kembali ke Katalog
            </a>
        </div>
    </div>
</nav>

<div class="container my-5">
    <div class="row">
        <div class="col-md-4">
            <img src="assets/img/sampul_buku/<?= htmlspecialchars($buku['foto_sampul']) ?>" 
                 class="img-fluid rounded shadow" alt="Sampul Buku">
        </div>
        <div class="col-md-8">
            <h2 class="mb-3"><?= htmlspecialchars($buku['judul']) ?></h2>
            
            <table class="table table-borderless">
                <tr>
                    <td width="150"><strong>ISBN</strong></td>
                    <td>: <?= htmlspecialchars($buku['isbn']) ?></td>
                </tr>
                <tr>
                    <td><strong>Penulis</strong></td>
                    <td>: <?= htmlspecialchars($buku['nama_penulis']) ?></td>
                </tr>
                <tr>
                    <td><strong>Penerbit</strong></td>
                    <td>: <?= htmlspecialchars($buku['nama_penerbit']) ?> (<?= htmlspecialchars($buku['kota']) ?>)</td>
                </tr>
                <tr>
                    <td><strong>Kategori</strong></td>
                    <td>: <?= htmlspecialchars($buku['nama_kategori']) ?></td>
                </tr>
                <tr>
                    <td><strong>Tahun Terbit</strong></td>
                    <td>: <?= htmlspecialchars($buku['tahun_terbit']) ?></td>
                </tr>
                <tr>
                    <td><strong>Halaman</strong></td>
                    <td>: <?= htmlspecialchars($buku['halaman'] ?? '-') ?> halaman</td>
                </tr>
                <tr>
                    <td><strong>Bahasa</strong></td>
                    <td>: <?= htmlspecialchars($buku['bahasa']) ?></td>
                </tr>
                <tr>
                    <td><strong>Lokasi Rak</strong></td>
                    <td>: <?= htmlspecialchars($buku['lokasi_rak']) ?></td>
                </tr>
                <tr>
                    <td><strong>Ketersediaan</strong></td>
                    <td>: 
                        <?php if ($buku['jumlah_tersedia'] > 0): ?>
                            <span class="badge bg-success">Tersedia (<?= $buku['jumlah_tersedia'] ?> eksemplar)</span>
                        <?php else: ?>
                            <span class="badge bg-danger">Tidak Tersedia</span>
                        <?php endif; ?>
                    </td>
                </tr>
            </table>

            <div class="alert alert-light border">
                <h5>Sinopsis:</h5>
                <p><?= nl2br(htmlspecialchars($buku['sinopsis'])) ?></p>
            </div>

            <?php if ($buku['jumlah_tersedia'] > 0): ?>
                <?php if (isset($_SESSION['anggota_id'])): ?>
                    <form method="POST" action="index.php?page=reservasi/create">
                        <input type="hidden" name="id_buku" value="<?= $buku['id_buku'] ?>">
                        <button type="submit" class="btn btn-success btn-lg">
                            <i class="fas fa-bookmark"></i> Reservasi Buku Ini
                        </button>
                    </form>
                <?php else: ?>
                    <a href="index.php?page=auth/loginAnggota" class="btn btn-primary btn-lg">
                        <i class="fas fa-sign-in-alt"></i> Login untuk Reservasi
                    </a>
                <?php endif; ?>
            <?php else: ?>
                <button class="btn btn-secondary btn-lg" disabled>
                    <i class="fas fa-times-circle"></i> Buku Tidak Tersedia
                </button>
            <?php endif; ?>
        </div>
    </div>
</div>

<footer class="bg-dark text-white text-center py-4 mt-5">
    <p class="mb-0">E-Perpus Subang &copy; 2026</p>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>