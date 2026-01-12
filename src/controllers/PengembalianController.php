<?php
require_once ROOT_PATH . 'src/models/PengembalianModel.php';
require_once ROOT_PATH . 'src/models/PeminjamanModel.php';
require_once ROOT_PATH . 'src/models/BukuModel.php';
require_once ROOT_PATH . 'src/config/Database.php';

class PengembalianController {
    private $model;
    private $peminjamanModel;
    private $bukuModel;

    public function __construct() {
        if (session_status() == PHP_SESSION_NONE) session_start();
        $db = (new Database())->getConnection();
        $this->model = new PengembalianModel($db);
        $this->peminjamanModel = new PeminjamanModel($db);
        $this->bukuModel = new BukuModel($db);
    }

    // ADMIN: List pengembalian
    public function index() {
        $stmt = $this->model->readAll();
        $data_pengembalian = $stmt->fetchAll(PDO::FETCH_ASSOC);
        require_once ROOT_PATH . 'src/views/pengembalian/index.php';
    }

    // ADMIN: Form pengembalian (pilih peminjaman aktif)
    public function create() {
        $peminjaman_aktif = $this->peminjamanModel->getPeminjamanAktif()->fetchAll(PDO::FETCH_ASSOC);
        require_once ROOT_PATH . 'src/views/pengembalian/create.php';
    }

    // ADMIN: Proses pengembalian
    public function store() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: index.php?page=pengembalian/create");
            exit();
        }

        $id_peminjaman = $_POST['id_peminjaman'];
        $tanggal_kembali = $_POST['tanggal_kembali'] ?? date('Y-m-d');
        $kondisi_buku = $_POST['kondisi_buku'] ?? []; // Array kondisi per buku

        // Ambil data peminjaman
        $peminjaman = $this->peminjamanModel->readById($id_peminjaman);
        if (!$peminjaman) {
            $_SESSION['error_message'] = "Data peminjaman tidak ditemukan.";
            header("Location: index.php?page=pengembalian/create");
            exit();
        }

        // Hitung denda
        $setting = $this->peminjamanModel->getSetting();
        $tgl_harus_kembali = new DateTime($peminjaman['tanggal_harus_kembali']);
        $tgl_dikembalikan = new DateTime($tanggal_kembali);
        $keterlambatan = max(0, $tgl_dikembalikan->diff($tgl_harus_kembali)->days);
        
        $denda_keterlambatan = ($keterlambatan > 0 && $tgl_dikembalikan > $tgl_harus_kembali) 
            ? $keterlambatan * $setting['denda_per_hari'] 
            : 0;

        // Hitung denda kerusakan/kehilangan
        $denda_kerusakan = 0;
        $denda_kehilangan = 0;

        foreach ($kondisi_buku as $id_buku => $kondisi) {
            if ($kondisi == 'rusak ringan') {
                $denda_kerusakan += $setting['denda_rusak_ringan'];
            } elseif ($kondisi == 'rusak berat') {
                $denda_kerusakan += $setting['denda_rusak_berat'];
            } elseif ($kondisi == 'hilang') {
                $denda_kehilangan += $setting['denda_hilang'];
            }
        }

        $total_denda = $denda_keterlambatan + $denda_kerusakan + $denda_kehilangan;

        // Generate kode pengembalian
        $kode = $this->model->generateKodePengembalian();

        $data = [
            'kode_pengembalian' => $kode,
            'id_peminjaman' => $id_peminjaman,
            'id_admin' => $_SESSION['user_id'],
            'tanggal_kembali' => $tanggal_kembali,
            'keterlambatan_hari' => $keterlambatan,
            'denda_keterlambatan' => $denda_keterlambatan,
            'denda_kerusakan' => $denda_kerusakan,
            'denda_kehilangan' => $denda_kehilangan,
            'total_denda' => $total_denda,
            'status_bayar' => ($total_denda > 0) ? 'belum_lunas' : 'lunas',
            'jumlah_dibayar' => 0,
            'sisa_denda' => $total_denda
        ];

        if ($this->model->create($data)) {
            // Update status peminjaman
            $this->peminjamanModel->updateStatus($id_peminjaman, 'dikembalikan', $tanggal_kembali);

            // Kembalikan stok buku
            $detail_buku = $this->peminjamanModel->getDetailBuku($id_peminjaman);
            foreach ($detail_buku as $buku) {
                // Kecuali yang hilang
                if (!isset($kondisi_buku[$buku['id_buku']]) || $kondisi_buku[$buku['id_buku']] != 'hilang') {
                    $this->bukuModel->tambahStok($buku['id_buku'], 1);
                }
            }

            $_SESSION['success_message'] = "Pengembalian berhasil! Total denda: Rp " . number_format($total_denda);
            header("Location: index.php?page=pengembalian/index");
            exit();
        } else {
            $_SESSION['error_message'] = "Gagal memproses pengembalian.";
            header("Location: index.php?page=pengembalian/create");
            exit();
        }
    }

    // ADMIN: Bayar denda
    public function bayarDenda() {
        $id = $_POST['id_pengembalian'];
        $jumlah = $_POST['jumlah_bayar'];

        if ($this->model->bayarDenda($id, $jumlah)) {
            $_SESSION['success_message'] = "Pembayaran denda berhasil dicatat.";
        } else {
            $_SESSION['error_message'] = "Gagal mencatat pembayaran.";
        }
        header("Location: index.php?page=pengembalian/index");
        exit();
    }
}
?>