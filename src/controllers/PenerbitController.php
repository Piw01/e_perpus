<?php
// src/controllers/PenerbitController.php

// Pastikan file-file yang dibutuhkan di-load
require_once ROOT_PATH . 'src/models/PenerbitModel.php';
require_once ROOT_PATH . 'src/config/Database.php';

class PenerbitController {
    private $penerbitModel;

    public function __construct() {
        // Mulai session jika belum dimulai (PENTING untuk pesan flash dan level)
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        
        $database = new Database();
        $db = $database->getConnection();
        $this->penerbitModel = new PenerbitModel($db);
    }
    
    /**
     * Helper untuk membatasi akses CUD
     */
    private function checkAccess() {
        // Hanya izinkan Level < 3 (misal: Admin/Super Admin)
        if (!isset($_SESSION['level']) || $_SESSION['level'] == 3) {
            $_SESSION['error_message'] = "Akses ditolak! Anda tidak memiliki izin untuk melakukan aksi ini.";
            header("Location: index.php?page=penerbit/index");
            exit();
        }
    }

    public function index() {
        $stmt = $this->penerbitModel->readAll();
        $data_penerbit = $stmt->fetchAll(PDO::FETCH_ASSOC);
        require_once ROOT_PATH . 'src/views/penerbit/index.php';
    }

    public function create() {
        $this->checkAccess(); // Tambahkan Pengecekan Akses
        require_once ROOT_PATH . 'src/views/penerbit/create.php';
    }

    public function store() {
        $this->checkAccess(); // Tambahkan Pengecekan Akses
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nama_penerbit = trim($_POST['nama_penerbit']);
            $kota = trim($_POST['kota']);

            if ($this->penerbitModel->namaPenerbitExists($nama_penerbit)) {
                $_SESSION['error_message'] = "Penerbit sudah ada!";
                header("Location: index.php?page=penerbit/create");
                exit();
            }

            $data = [
                'nama_penerbit' => $nama_penerbit,
                'kota' => $kota
            ];

            if ($this->penerbitModel->create($data)) {
                $_SESSION['success_message'] = "Penerbit berhasil ditambahkan!";
                header("Location: index.php?page=penerbit/index");
                exit();
            } else {
                $_SESSION['error_message'] = "Gagal menyimpan penerbit!";
                header("Location: index.php?page=penerbit/create");
                exit();
            }
        }
    }

    public function edit() {
        $this->checkAccess(); // Tambahkan Pengecekan Akses
        
        $id_penerbit = $_GET['id'] ?? null;
        $penerbit = $this->penerbitModel->readById($id_penerbit);

        if (!$penerbit) {
            $_SESSION['error_message'] = "Penerbit tidak ditemukan!";
            header("Location: index.php?page=penerbit/index");
            exit();
        }
        require_once ROOT_PATH . 'src/views/penerbit/edit.php';
    }

    public function update() {
        $this->checkAccess(); // Tambahkan Pengecekan Akses
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id_penerbit = (int)$_POST['id_penerbit'];
            $nama_penerbit = trim($_POST['nama_penerbit']);
            $kota = trim($_POST['kota']);
            $data = [
                'nama_penerbit' => $nama_penerbit,
                'kota' => $kota
            ];

            if ($this->penerbitModel->update($id_penerbit, $data)) {
                $_SESSION['success_message'] = "Penerbit berhasil diperbarui!";
                header("Location: index.php?page=penerbit/index");
                exit();
            } else {
                $_SESSION['error_message'] = "Gagal memperbarui penerbit!";
                header("Location: index.php?page=penerbit/edit&id=$id_penerbit");
                exit();
            }
        }
    }

    public function delete() {
        $this->checkAccess(); // Tambahkan Pengecekan Akses
        
        $id_penerbit = $_GET['id'] ?? null;
        if (!$id_penerbit) {
            header("Location: index.php?page=penerbit/index");
            exit();
        }

        if ($this->penerbitModel->delete($id_penerbit)) {
            $_SESSION['success_message'] = "Penerbit berhasil dihapus!";
            header("Location: index.php?page=penerbit/index");
            exit();
        } else {
            $_SESSION['error_message'] = "Gagal menghapus penerbit. Mungkin data ini digunakan di tabel lain.";
            header("Location: index.php?page=penerbit/index");
            exit();
        }
    }
}