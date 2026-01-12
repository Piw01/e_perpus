<?php
require_once ROOT_PATH . 'src/models/ReservasiModel.php';
require_once ROOT_PATH . 'src/config/Database.php';

class ReservasiController {
    private $model;

    public function __construct() {
        if (session_status() == PHP_SESSION_NONE) session_start();
        $db = (new Database())->getConnection();
        $this->model = new ReservasiModel($db);
    }

    // MEMBER: Buat reservasi
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
        $bukuModel = new BukuModel((new Database())->getConnection());
        $buku = $bukuModel->readById($id_buku);
        
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

    // ADMIN: Lihat semua reservasi
    public function index() {
        $stmt = $this->model->readAll();
        $data_reservasi = $stmt->fetchAll(PDO::FETCH_ASSOC);
        require_once ROOT_PATH . 'src/views/reservasi/index.php';
    }

    // ADMIN: Update status reservasi
    public function updateStatus() {
        $id = $_POST['id_reservasi'] ?? null;
        $status = $_POST['status'] ?? null;

        if ($id && $status && $this->model->updateStatus($id, $status)) {
            $_SESSION['success_message'] = "Status reservasi diperbarui.";
        } else {
            $_SESSION['error_message'] = "Gagal update status.";
        }
        header("Location: index.php?page=reservasi/index");
        exit();
    }
}
?>