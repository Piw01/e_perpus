<?php
// src/controllers/AdminController.php

require_once ROOT_PATH . 'src/models/AdminModel.php';
require_once ROOT_PATH . 'src/config/Database.php';

class AdminController {
    private $model;
    
    public function __construct() {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        // --- PROTEKSI HALAMAN (HANYA SUPER ADMIN / LEVEL 1) ---
        // Jika level tidak ada atau bukan 1, tendang ke dashboard
        if (!isset($_SESSION['level']) || $_SESSION['level'] != 1) {
            $_SESSION['error_message'] = "Akses Ditolak. Halaman ini hanya untuk Super Admin.";
            header("Location: index.php?page=dashboard/index");
            exit();
        }
        // -----------------------------------------------------

        $db_instance = new Database();
        $db = $db_instance->getConnection();
        $this->model = new AdminModel($db);
    }

    public function index() {
        $stmt = $this->model->readAll();
        require_once ROOT_PATH . 'src/views/admin/index.php';
    }

    public function create() {
        require_once ROOT_PATH . 'src/views/admin/create.php';
    }

    public function store() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: index.php?page=admin/create");
            exit();
        }
        
        $nama_lengkap = trim($_POST['nama_lengkap']);
        $username = trim($_POST['username']);
        $password = $_POST['password'];
        $level = $_POST['level'] ?? 2; // Default level 2 jika tidak dipilih

        if (empty($nama_lengkap) || empty($username) || empty($password)) {
            $_SESSION['error_message'] = "Semua field wajib diisi.";
            header("Location: index.php?page=admin/create");
            exit();
        }
        
        if ($this->model->usernameExists($username)) {
            $_SESSION['error_message'] = "Username '{$username}' sudah terdaftar.";
            header("Location: index.php?page=admin/create");
            exit();
        }

        $password_hashed = password_hash($password, PASSWORD_DEFAULT);

        $data = [
            'nama_lengkap' => $nama_lengkap,
            'username' => $username,
            'password_hashed' => $password_hashed,
            'level' => $level // Simpan Level
        ];

        if ($this->model->create($data)) {
            $_SESSION['success_message'] = "Admin baru berhasil ditambahkan!";
            header("Location: index.php?page=admin/index");
            exit();
        } else {
            $_SESSION['error_message'] = "Gagal menambahkan Admin.";
            header("Location: index.php?page=admin/create");
            exit();
        }
    }

    public function edit() {
        $id_admin = $_GET['id'] ?? null;
        if (!$id_admin) {
            header("Location: index.php?page=admin/index");
            exit();
        }

        $admin = $this->model->readById($id_admin);
        if (!$admin) {
            header("Location: index.php?page=admin/index");
            exit();
        }
        require_once ROOT_PATH . 'src/views/admin/edit.php';
    }

    public function update() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: index.php?page=admin/index");
            exit();
        }

        $id_admin = $_POST['id_admin'] ?? null;
        $nama_lengkap = trim($_POST['nama_lengkap']);
        $username = trim($_POST['username']);
        $password = $_POST['password'] ?? ''; 
        $level = $_POST['level'] ?? 2; // Ambil Level

        if (!$id_admin || empty($nama_lengkap) || empty($username)) {
            $_SESSION['error_message'] = "Data tidak lengkap.";
            header("Location: index.php?page=admin/edit&id=" . $id_admin);
            exit();
        }
        
        if ($this->model->usernameExists($username, $id_admin)) {
            $_SESSION['error_message'] = "Username '{$username}' sudah dipakai.";
            header("Location: index.php?page=admin/edit&id=" . $id_admin);
            exit();
        }

        $data = [
            'nama_lengkap' => $nama_lengkap,
            'username' => $username,
            'level' => $level, // Update Level
            'password_hashed' => null
        ];

        if (!empty($password)) {
            $data['password_hashed'] = password_hash($password, PASSWORD_DEFAULT);
        }

        if ($this->model->update($id_admin, $data)) {
            $_SESSION['success_message'] = "Data Admin berhasil diperbarui!";
            header("Location: index.php?page=admin/index");
            exit();
        } else {
            $_SESSION['error_message'] = "Gagal memperbarui data.";
            header("Location: index.php?page=admin/edit&id=" . $id_admin);
            exit();
        }
    }

    public function delete() {
        $id_admin = $_GET['id'] ?? null;
        if (!$id_admin) {
            header("Location: index.php?page=admin/index");
            exit();
        }
        
        if (isset($_SESSION['user_id']) && $_SESSION['user_id'] == $id_admin) {
            $_SESSION['error_message'] = "Tidak bisa menghapus akun sendiri.";
            header("Location: index.php?page=admin/index");
            exit();
        }

        if ($this->model->delete($id_admin)) {
            $_SESSION['success_message'] = "Data Admin berhasil dihapus.";
            header("Location: index.php?page=admin/index");
            exit();
        } else {
            $_SESSION['error_message'] = "Gagal menghapus data Admin.";
            header("Location: index.php?page=admin/index");
            exit();
        }
    }
}
?>