<?php
/**
 * ReservasiController - FIXED VERSION
 * Menambahkan fitur approval yang otomatis membuat peminjaman
 */
require_once ROOT_PATH . 'src/models/ReservasiModel.php';
require_once ROOT_PATH . 'src/models/PeminjamanModel.php';
require_once ROOT_PATH . 'src/models/BukuModel.php';
require_once ROOT_PATH . 'src/config/Database.php';

class ReservasiController {
    private $model;
    private $peminjamanModel;
    private $bukuModel;

    public function __construct() {
        if (session_status() == PHP_SESSION_NONE) session_start();
        $db = (new Database())->getConnection();
        $this->model = new ReservasiModel($db);
        $this->peminjamanModel = new PeminjamanModel($db);
        $this->bukuModel = new BukuModel($db);
    }

    // ========================================
    // MEMBER: Buat reservasi
    // ========================================
    public function create() {
        if (!isset($_SESSION['anggota_id'])) {
            $_SESSION['error_message'] = "Harap login sebagai anggota.";
            header("Location: index.php?page=auth/loginAnggota");
            exit();
        }

        $id_buku = $_POST['id_buku'] ?? null;
        if (!$id_buku) {
            $_SESSION['error_message'] = "Buku tidak valid.";
            header("Location: index.php?page=katalog/index");
            exit();
        }

        // Cek apakah buku tersedia
        $buku = $this->bukuModel->readById($id_buku);
        
        if (!$buku || $buku['jumlah_tersedia'] <= 0) {
            $_SESSION['error_message'] = "Buku tidak tersedia untuk reservasi.";
            header("Location: index.php?page=katalog/detail&id=$id_buku");
            exit();
        }

        $kode = $this->model->generateKodeReservasi();
        $expired = date('Y-m-d H:i:s', strtotime('+3 days'));

        $data = [
            'kode_reservasi' => $kode,
            'id_anggota' => $_SESSION['anggota_id'],
            'id_buku' => $id_buku,
            'tanggal_expired' => $expired,
            'status' => 'pending'
        ];

        if ($this->model->create($data)) {
            $_SESSION['success_message'] = "Reservasi berhasil! Kode: $kode. Ambil dalam 3 hari.";
            header("Location: index.php?page=anggota/dashboard");
            exit();
        } else {
            $_SESSION['error_message'] = "Reservasi gagal.";
            header("Location: index.php?page=katalog/detail&id=$id_buku");
            exit();
        }
    }

    // ========================================
    // ADMIN: Lihat semua reservasi
    // ========================================
    public function index() {
        $stmt = $this->model->readAll();
        $data_reservasi = $stmt->fetchAll(PDO::FETCH_ASSOC);
        require_once ROOT_PATH . 'src/views/reservasi/index.php';
    }

    // ========================================
    // ADMIN: Update status reservasi (FIXED)
    // ========================================
    public function updateStatus() {
        $id = $_POST['id_reservasi'] ?? null;
        $status = $_POST['status'] ?? null;

        if (!$id || !$status) {
            $_SESSION['error_message'] = "Data tidak lengkap.";
            header("Location: index.php?page=reservasi/index");
            exit();
        }

        // ✅ FITUR BARU: Jika status = "diambil", otomatis buat peminjaman
        if ($status == 'diambil') {
            try {
                // 1. Ambil data reservasi
                $reservasi = $this->model->readById($id);
                if (!$reservasi) {
                    throw new Exception("Data reservasi tidak ditemukan");
                }

                // 2. Cek stok buku masih ada
                $buku = $this->bukuModel->readById($reservasi['id_buku']);
                if (!$buku || $buku['jumlah_tersedia'] <= 0) {
                    throw new Exception("Buku sudah tidak tersedia");
                }

                // 3. Generate kode peminjaman
                $kode_peminjaman = $this->peminjamanModel->generateKodePeminjaman();
                $tgl_pinjam = date('Y-m-d');
                $durasi = 7; // Default 7 hari
                $tgl_harus_kembali = date('Y-m-d', strtotime("+{$durasi} days"));

                // 4. Buat data peminjaman
                $data_peminjaman = [
                    'kode_peminjaman' => $kode_peminjaman,
                    'id_anggota' => $reservasi['id_anggota'],
                    'id_admin' => $_SESSION['user_id'],
                    'tanggal_pinjam' => $tgl_pinjam,
                    'tanggal_harus_kembali' => $tgl_harus_kembali,
                    'durasi_hari' => $durasi,
                    'total_buku' => 1,
                    'status_pinjam' => 'dipinjam'
                ];

                $id_peminjaman = $this->peminjamanModel->create($data_peminjaman);

                if ($id_peminjaman) {
                    // 5. Insert detail peminjaman
                    $this->peminjamanModel->addDetail($id_peminjaman, $reservasi['id_buku']);

                    // 6. Kurangi stok buku
                    $this->bukuModel->kurangiStok($reservasi['id_buku'], 1);

                    // 7. Update status reservasi
                    if ($this->model->updateStatus($id, $status)) {
                        $_SESSION['success_message'] = "Reservasi disetujui dan peminjaman dibuat! Kode: $kode_peminjaman";
                    } else {
                        throw new Exception("Gagal update status reservasi");
                    }
                } else {
                    throw new Exception("Gagal membuat peminjaman");
                }

            } catch (Exception $e) {
                $_SESSION['error_message'] = "Gagal approval: " . $e->getMessage();
            }
        } 
        // Jika status batal, cukup update status
        else if ($status == 'batal') {
            if ($this->model->updateStatus($id, $status)) {
                $_SESSION['success_message'] = "Reservasi dibatalkan.";
            } else {
                $_SESSION['error_message'] = "Gagal membatalkan reservasi.";
            }
        }

        header("Location: index.php?page=reservasi/index");
        exit();
    }
}
?>