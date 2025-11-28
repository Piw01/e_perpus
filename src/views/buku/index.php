<?php 
// src/views/buku/index.php
// Variabel $data_buku (array of assoc) harus diset oleh BukuController::index()
?>

<?php include(ROOT_PATH . 'src/views/layouts/header.php'); ?>

<div class="container-fluid mt-4">
    <div class="card shadow-sm">
        <div class="card-header bg-success text-white">
            <h4 class="mb-0">
                <i class="fas fa-book me-2"></i> Data Master Buku
            </h4>
        </div>
        <div class="card-body">
            
            <!-- Pesan success/error dari session -->
            <?php if (isset($_SESSION['success_message'])): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <?= $_SESSION['success_message']; unset($_SESSION['success_message']); ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>
            
            <?php if (isset($_SESSION['error_message'])): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <?= $_SESSION['error_message']; unset($_SESSION['error_message']); ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>

            <a href="index.php?page=buku/create" class="btn btn-primary mb-3">
                <i class="fas fa-plus me-1"></i> Tambah Buku Baru
            </a>
            
            <div class="table-responsive">
                <table class="table table-bordered table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th scope="col" style="width: 5%;">No</th>
                            <th scope="col">Sampul</th>
                            <th scope="col">Judul & ISBN</th>
                            <th scope="col">Penulis</th>
                            <th scope="col">Penerbit</th>
                            <th scope="col">Kategori</th>
                            <th scope="col">Tahun</th>
                            <th scope="col">Stok</th>
                            <th scope="col" style="width: 15%;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $no = 1;
                        if (!empty($data_buku)):
                            foreach ($data_buku as $row): 
                        ?>
                                <tr>
                                    <th scope="row"><?= $no++ ?></th>
                                    <td>
                                        <?php if (!empty($row['foto_sampul'])): ?>
                                            <img src="assets/img/sampul_buku/<?= htmlspecialchars($row['foto_sampul']) ?>" 
                                                 alt="Sampul" style="width: 50px; height: auto; border-radius: 3px;">
                                        <?php else: ?>
                                            <i class="fas fa-image fa-2x text-muted"></i>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <strong><?= htmlspecialchars($row['judul']) ?></strong><br>
                                        <small class="text-muted">ISBN: <?= htmlspecialchars($row['isbn']) ?></small>
                                    </td>
                                    <td><?= htmlspecialchars($row['nama_penulis']) ?></td>
                                    <td><?= htmlspecialchars($row['nama_penerbit']) ?></td>
                                    <td><?= htmlspecialchars($row['nama_kategori']) ?></td>
                                    <td><?= htmlspecialchars($row['tahun_terbit']) ?></td>
                                    <td><span class="badge bg-info text-dark"><?= htmlspecialchars($row['jumlah']) ?></span></td>
                                    <td>
                                        <a href="index.php?page=buku/edit&id=<?= $row['id_buku'] ?>" class="btn btn-warning btn-sm me-1" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal" 
                                                data-bs-target="#deleteModal" 
                                                data-id="<?= $row['id_buku'] ?>" 
                                                data-judul="<?= htmlspecialchars($row['judul']) ?>"
                                                title="Hapus">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                        <?php 
                            endforeach;
                        else:
                        ?>
                            <tr>
                                <td colspan="9" class="text-center">Tidak ada data Buku yang ditemukan.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
            
        </div>
    </div>
</div>

<!-- Modal Konfirmasi Hapus -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel">Konfirmasi Hapus Data</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Apakah Anda yakin ingin menghapus data Buku: <strong><span id="bookTitle"></span></strong>? 
                Aksi ini tidak dapat dibatalkan.
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <a id="deleteButton" href="#" class="btn btn-danger">Hapus Permanen</a>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const deleteModal = document.getElementById('deleteModal');
    if (deleteModal) {
        deleteModal.addEventListener('show.bs.modal', function (event) {
            // Button that triggered the modal
            const button = event.relatedTarget;
            // Extract info from data-bs-* attributes
            const id = button.getAttribute('data-id');
            const title = button.getAttribute('data-judul');
            
            // Update the modal's content.
            const bookTitle = deleteModal.querySelector('#bookTitle');
            const deleteButton = deleteModal.querySelector('#deleteButton');
            
            bookTitle.textContent = title;
            deleteButton.href = 'index.php?page=buku/delete&id=' + id;
        });
    }
});
</script>

<?php include(ROOT_PATH . 'src/views/layouts/footer.php'); ?>