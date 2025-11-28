<?php include(ROOT_PATH . 'src/views/layouts/header.php'); ?>

<div class="container-fluid mt-4">
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white">
            <h4 class="mb-0"><i class="fas fa-user-lock me-2"></i> Management Akun Admin</h4>
        </div>
        <div class="card-body">
            
            <a href="index.php?page=admin/create" class="btn btn-success mb-3">
                <i class="fas fa-plus me-1"></i> Tambah Admin Baru
            </a>
            
            <div class="table-responsive">
                <table class="table table-bordered table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th scope="col" style="width: 5%;">No</th>
                            <th scope="col">Nama Lengkap</th>
                            <th scope="col">Username</th>
                            <th scope="col">Level</th> <!-- KOLOM BARU -->
                            <th scope="col" style="width: 15%;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $no = 1;
                        if ($stmt->rowCount() > 0):
                            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)): 
                                // Logic Label Level
                                $levelLabel = "Unknown";
                                $badgeClass = "secondary";
                                if ($row['level'] == 1) { $levelLabel = "Super Admin"; $badgeClass = "warning text-dark"; }
                                elseif ($row['level'] == 2) { $levelLabel = "Admin"; $badgeClass = "info text-dark"; }
                                elseif ($row['level'] == 3) { $levelLabel = "Operator"; $badgeClass = "secondary"; }
                        ?>
                                <tr>
                                    <th scope="row"><?= $no++ ?></th>
                                    <td><?= htmlspecialchars($row['nama_lengkap']) ?></td>
                                    <td><?= htmlspecialchars($row['username']) ?></td>
                                    <td><span class="badge bg-<?= $badgeClass ?>"><?= $levelLabel ?></span></td>
                                    <td>
                                        <a href="index.php?page=admin/edit&id=<?= $row['id_admin'] ?>" class="btn btn-warning btn-sm me-1"><i class="fas fa-edit"></i></a>
                                        <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#deleteModal" data-id="<?= $row['id_admin'] ?>" data-nama="<?= htmlspecialchars($row['nama_lengkap']) ?>"><i class="fas fa-trash"></i></button>
                                    </td>
                                </tr>
                        <?php endwhile; else: ?>
                            <tr><td colspan="5" class="text-center">Tidak ada data Admin.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Modal Hapus sama seperti sebelumnya -->
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header"><h5 class="modal-title">Konfirmasi Hapus Data</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
            <div class="modal-body">Yakin hapus: <strong><span id="adminName"></span></strong>?</div>
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
            const button = event.relatedTarget;
            const id = button.getAttribute('data-id');
            const name = button.getAttribute('data-nama');
            deleteModal.querySelector('#adminName').textContent = name;
            deleteModal.querySelector('#deleteButton').href = 'index.php?page=admin/delete&id=' + id;
        });
    }
});
</script>
<?php include(ROOT_PATH . 'src/views/layouts/footer.php'); ?>