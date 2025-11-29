<?php
// src/controllers/PenulisController.php

// Pastikan file-file yang dibutuhkan di-load
require_once ROOT_PATH . 'src/models/PenulisModel.php';
require_once ROOT_PATH . 'src/config/Database.php';

class PenulisController {
    private $penulisModel;

    public function __construct() {
        // Mulai session jika belum dimulai (PENTING untuk pesan flash dan level)
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        
        $database = new Database();
        $db = $database->getConnection();
        $this->penulisModel = new PenulisModel($db);
    }
    
    /**
     * Helper untuk membatasi akses CUD
     */
    private function checkAccess() {
        // Hanya izinkan Level < 3 (misal: Admin/Super Admin)
        if (!isset($_SESSION['level']) || $_SESSION['level'] == 3) {
            $_SESSION['error_message'] = "Akses ditolak! Anda tidak memiliki izin untuk melakukan aksi ini.";
            header("Location: index.php?page=penulis/index");
            exit();
        }
    }

    public function index() {
        $stmt = $this->penulisModel->readAll();
        $data_penulis = $stmt->fetchAll(PDO::FETCH_ASSOC);
        require_once ROOT_PATH . 'src/views/penulis/index.php';
    }

    public function create() {
        $this->checkAccess(); // Tambahkan Pengecekan Akses
        require_once ROOT_PATH . 'src/views/penulis/create.php';
    }

    public function store() {
        $this->checkAccess(); // Tambahkan Pengecekan Akses
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nama_penulis = trim($_POST['nama_penulis']);

            if ($this->penulisModel->namaPenulisExists($nama_penulis)) {
                $_SESSION['error_message'] = "Penulis sudah ada!";
                header("Location: index.php?page=penulis/create");
                exit();
            }

            $data = ['nama_penulis' => $nama_penulis];

            if ($this->penulisModel->create($data)) {
                $_SESSION['success_message'] = "Penulis berhasil ditambahkan!";
                header("Location: index.php?page=penulis/index");
                exit();
            } else {
                $_SESSION['error_message'] = "Gagal menyimpan penulis!";
                header("Location: index.php?page=penulis/create");
                exit();
            }
        }
    }

    public function edit() {
        $this->checkAccess(); // Tambahkan Pengecekan Akses
        
        $id_penulis = $_GET['id'] ?? null;
        $penulis = $this->penulisModel->readById($id_penulis);

        if (!$penulis) {
            $_SESSION['error_message'] = "Penulis tidak ditemukan!";
            header("Location: index.php?page=penulis/index");
            exit();
        }
        require_once ROOT_PATH . 'src/views/penulis/edit.php';
    }

    public function update() {
        $this->checkAccess(); // Tambahkan Pengecekan Akses
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id_penulis = (int)$_POST['id_penulis'];
            $nama_penulis = trim($_POST['nama_penulis']);
            $data = ['nama_penulis' => $nama_penulis];

            if ($this->penulisModel->update($id_penulis, $data)) {
                $_SESSION['success_message'] = "Penulis berhasil diperbarui!";
                header("Location: index.php?page=penulis/index");
                exit();
            } else {
                $_SESSION['error_message'] = "Gagal memperbarui penulis!";
                header("Location: index.php?page=penulis/edit&id=$id_penulis");
                exit();
            }
        }
    }

    public function delete() {
        $this->checkAccess(); // Tambahkan Pengecekan Akses
        
        $id_penulis = $_GET['id'] ?? null;
        if (!$id_penulis) {
            header("Location: index.php?page=penulis/index");
            exit();
        }

        if ($this->penulisModel->delete($id_penulis)) {
            $_SESSION['success_message'] = "Penulis berhasil dihapus!";
            header("Location: index.php?page=penulis/index");
            exit();
        } else {
            $_SESSION['error_message'] = "Gagal menghapus penulis. Mungkin data ini digunakan di tabel lain.";
            header("Location: index.php?page=penulis/index");
            exit();
        }
    }
}