<?php
require_once ROOT_PATH . 'src/models/PeminjamanModel.php';
require_once ROOT_PATH . 'src/models/BukuModel.php';
require_once ROOT_PATH . 'src/models/AnggotaModel.php';
require_once ROOT_PATH . 'src/config/Database.php';

class PeminjamanController {
    private $model;
    private $bukuModel;
    private $anggotaModel;

    public function __construct() {
        if (session_status() == PHP_SESSION_NONE) session_start();
        $db = (new Database())->getConnection();
        $this->model = new PeminjamanModel($db);
        $this->bukuModel = new BukuModel($db);
        $this->anggotaModel = new AnggotaModel($db);
    }

    // ADMIN: List peminjaman
    public function index() {
        $status = $_GET['status'] ?? '';
        if ($status) {
            $stmt = $this->model->filterByStatus($status);
        } else {
            $stmt = $this->model->readAll();
        }
        $data_peminjaman = $stmt->fetchAll(PDO::FETCH_ASSOC);
        require_once ROOT_PATH . 'src/views/peminjaman/index.php';
    }

    // ADMIN: Form peminjaman baru
    public function create() {
        $anggota_list = $this->anggotaModel->readAll()->fetchAll(PDO::FETCH_ASSOC);
        $buku_tersedia = $this->bukuModel->getBukuTersedia()->fetchAll(PDO::FETCH_ASSOC);
        require_once ROOT_PATH . 'src/views/peminjaman/create.php';
    }

    // ADMIN: Proses peminjaman
    public function store() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: index.php?page=peminjaman/create");
            exit();
        }

        $id_anggota = $_POST['id_anggota'];
        $buku_dipinjam = $_POST['id_buku'] ?? []; // Array buku
        $durasi = $_POST['durasi_hari'] ?? 7;

        if (empty($buku_dipinjam)) {
            $_SESSION['error_message'] = "Pilih minimal 1 buku.";
            header("Location: index.php?page=peminjaman/create");
            exit();
        }

        // Cek limit buku
        $setting = $this->model->getSetting();
        if (count($buku_dipinjam) > $setting['max_buku_pinjam']) {
            $_SESSION['error_message'] = "Maksimal {$setting['max_buku_pinjam']} buku per peminjaman.";
            header("Location: index.php?page=peminjaman/create");
            exit();
        }

        // Generate kode
        $kode = $this->model->generateKodePeminjaman();
        $tgl_pinjam = date('Y-m-d');
        $tgl_harus_kembali = date('Y-m-d', strtotime("+{$durasi} days"));

        $data_peminjaman = [
            'kode_peminjaman' => $kode,
            'id_anggota' => $id_anggota,
            'id_admin' => $_SESSION['user_id'],
            'tanggal_pinjam' => $tgl_pinjam,
            'tanggal_harus_kembali' => $tgl_harus_kembali,
            'durasi_hari' => $durasi,
            'total_buku' => count($buku_dipinjam),
            'status_pinjam' => 'dipinjam'
        ];

        $id_peminjaman = $this->model->create($data_peminjaman);

        if ($id_peminjaman) {
            // Insert detail & update stok
            foreach ($buku_dipinjam as $id_buku) {
                $this->model->addDetail($id_peminjaman, $id_buku);
                $this->bukuModel->kurangiStok($id_buku, 1);
            }

            $_SESSION['success_message'] = "Peminjaman berhasil! Kode: $kode";
            header("Location: index.php?page=peminjaman/index");
            exit();
        } else {
            $_SESSION['error_message'] = "Gagal membuat peminjaman.";
            header("Location: index.php?page=peminjaman/create");
            exit();
        }
    }

    // ADMIN: Detail peminjaman
    public function detail() {
        $id = $_GET['id'] ?? null;
        if (!$id) {
            header("Location: index.php?page=peminjaman/index");
            exit();
        }

        $peminjaman = $this->model->readById($id);
        $detail_buku = $this->model->getDetailBuku($id);

        require_once ROOT_PATH . 'src/views/peminjaman/detail.php';
    }
}
?>