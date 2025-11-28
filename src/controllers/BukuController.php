<?php
// src/controllers/BukuController.php

require_once ROOT_PATH . 'src/models/BukuModel.php';
require_once ROOT_PATH . 'src/config/Database.php';

class BukuController {
    private $bukuModel;
    private $upload_dir = 'assets/img/sampul_buku/';
    private $user_level;

    public function __construct() {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        
        // Ambil level pengguna
        $this->user_level = $_SESSION['level'] ?? 0;

        $database = new Database();
        $db = $database->getConnection();
        $this->bukuModel = new BukuModel($db);
    }

    public function index() {
        $stmt = $this->bukuModel->readAll();
        $data_buku = $stmt->fetchAll(PDO::FETCH_ASSOC);
        require_once ROOT_PATH . 'src/views/buku/index.php';
    }

    public function create() {
        // CEK LEVEL: Operator (3) tidak boleh akses
        if ($this->user_level == 3) {
            $_SESSION['error_message'] = "Operator tidak diizinkan menambah data buku.";
            header("Location: index.php?page=buku/index");
            exit();
        }

        $kategori = $this->bukuModel->getKategori();
        $penulis = $this->bukuModel->getPenulis();
        $penerbit = $this->bukuModel->getPenerbit();
        require_once ROOT_PATH . 'src/views/buku/create.php';
    }

    public function store() {
        // CEK LEVEL
        if ($this->user_level == 3) {
            header("Location: index.php?page=buku/index");
            exit();
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: index.php?page=buku/create");
            exit();
        }

        $isbn = trim($_POST['isbn'] ?? '');
        $judul = trim($_POST['judul'] ?? '');
        $id_penulis = $_POST['id_penulis'] ?? null;
        $id_penerbit = $_POST['id_penerbit'] ?? null;
        $id_kategori = $_POST['id_kategori'] ?? null;
        $tahun_terbit = $_POST['tahun_terbit'] ?? null;
        $sinopsis = trim($_POST['sinopsis'] ?? '');
        $jumlah = $_POST['jumlah'] ?? 0;

        if (empty($isbn) || empty($judul) || empty($id_penulis) || empty($id_penerbit) || empty($id_kategori) || empty($tahun_terbit)) {
            $_SESSION['error_message'] = "Semua field wajib diisi.";
            header("Location: index.php?page=buku/create");
            exit();
        }

        if ($this->bukuModel->isbnExists($isbn)) {
            $_SESSION['error_message'] = "ISBN sudah terdaftar.";
            header("Location: index.php?page=buku/create");
            exit();
        }

        $foto_sampul = '';
        if (isset($_FILES['foto_sampul']) && $_FILES['foto_sampul']['error'] == 0) {
            $upload_result = $this->handleFileUpload($_FILES['foto_sampul']);
            if (is_string($upload_result)) {
                $foto_sampul = $upload_result;
            } else {
                $_SESSION['error_message'] = $upload_result['error'];
                header("Location: index.php?page=buku/create");
                exit();
            }
        }
        
        $data = [
            'isbn' => $isbn, 'judul' => $judul, 'id_penulis' => $id_penulis,
            'id_penerbit' => $id_penerbit, 'id_kategori' => $id_kategori,
            'tahun_terbit' => $tahun_terbit, 'sinopsis' => $sinopsis,
            'jumlah' => $jumlah, 'foto_sampul' => $foto_sampul
        ];

        if ($this->bukuModel->create($data)) {
            $_SESSION['success_message'] = "Buku berhasil ditambahkan!";
            header("Location: index.php?page=buku/index");
            exit();
        } else {
            $_SESSION['error_message'] = "Gagal menambah buku.";
            header("Location: index.php?page=buku/create");
            exit();
        }
    }

    public function edit() {
        // CEK LEVEL
        if ($this->user_level == 3) {
            $_SESSION['error_message'] = "Operator tidak diizinkan mengedit data buku.";
            header("Location: index.php?page=buku/index");
            exit();
        }

        $id_buku = $_GET['id'] ?? null;
        if (!$id_buku) { header("Location: index.php?page=buku/index"); exit(); }

        $buku = $this->bukuModel->readById($id_buku);
        if (!$buku) { header("Location: index.php?page=buku/index"); exit(); }

        $kategori = $this->bukuModel->getKategori();
        $penulis = $this->bukuModel->getPenulis();
        $penerbit = $this->bukuModel->getPenerbit();
        
        require_once ROOT_PATH . 'src/views/buku/edit.php';
    }

    public function update() {
        // CEK LEVEL
        if ($this->user_level == 3) { header("Location: index.php?page=buku/index"); exit(); }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') { header("Location: index.php?page=buku/index"); exit(); }

        $id_buku = $_POST['id_buku'] ?? null;
        $isbn = trim($_POST['isbn'] ?? '');
        $judul = trim($_POST['judul'] ?? '');
        // ... (ambil data lain sama seperti sebelumnya)
        $id_penulis = $_POST['id_penulis'] ?? null;
        $id_penerbit = $_POST['id_penerbit'] ?? null;
        $id_kategori = $_POST['id_kategori'] ?? null;
        $tahun_terbit = $_POST['tahun_terbit'] ?? null;
        $sinopsis = trim($_POST['sinopsis'] ?? '');
        $jumlah = $_POST['jumlah'] ?? 0;
        $foto_sampul_lama = $_POST['foto_sampul_lama'] ?? null;

        if (!$id_buku || empty($isbn)) { 
            $_SESSION['error_message'] = "Data tidak valid."; 
            header("Location: index.php?page=buku/edit&id=$id_buku"); exit(); 
        }

        if ($this->bukuModel->isbnExists($isbn, $id_buku)) {
            $_SESSION['error_message'] = "ISBN sudah dipakai buku lain.";
            header("Location: index.php?page=buku/edit&id=$id_buku"); exit();
        }

        $foto_sampul_baru = $foto_sampul_lama;
        $file_uploaded = false;
        if (isset($_FILES['foto_sampul']) && $_FILES['foto_sampul']['error'] == 0) {
            $upload_result = $this->handleFileUpload($_FILES['foto_sampul']);
            if (is_string($upload_result)) {
                $foto_sampul_baru = $upload_result;
                $file_uploaded = true;
            }
        }
        
        $data = [
            'isbn' => $isbn, 'judul' => $judul, 'id_penulis' => $id_penulis,
            'id_penerbit' => $id_penerbit, 'id_kategori' => $id_kategori,
            'tahun_terbit' => $tahun_terbit, 'sinopsis' => $sinopsis,
            'jumlah' => $jumlah, 'foto_sampul' => $file_uploaded ? $foto_sampul_baru : null
        ];

        if ($this->bukuModel->update($id_buku, $data)) {
            if ($file_uploaded && !empty($foto_sampul_lama)) { $this->deleteFile($foto_sampul_lama); }
            $_SESSION['success_message'] = "Buku diperbarui!";
            header("Location: index.php?page=buku/index");
            exit();
        } else {
            $_SESSION['error_message'] = "Gagal update.";
            header("Location: index.php?page=buku/edit&id=$id_buku");
            exit();
        }
    }

    public function delete() {
        // CEK LEVEL
        if ($this->user_level == 3) {
            $_SESSION['error_message'] = "Operator tidak diizinkan menghapus data buku.";
            header("Location: index.php?page=buku/index");
            exit();
        }

        $id_buku = $_GET['id'] ?? null;
        if (!$id_buku) { header("Location: index.php?page=buku/index"); exit(); }
        
        $foto_sampul = $this->bukuModel->getFotoSampul($id_buku);
        if ($this->bukuModel->delete($id_buku)) {
            if (!empty($foto_sampul)) { $this->deleteFile($foto_sampul); }
            $_SESSION['success_message'] = "Buku dihapus.";
            header("Location: index.php?page=buku/index");
            exit();
        } else {
            $_SESSION['error_message'] = "Gagal hapus.";
            header("Location: index.php?page=buku/index");
            exit();
        }
    }

    private function handleFileUpload($file) {
        $allowed = ['jpg', 'jpeg', 'png', 'gif'];
        $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        if (!in_array($ext, $allowed)) return ['error' => "Format file salah."];
        if ($file['size'] > 5*1024*1024) return ['error' => "File terlalu besar."];
        
        $new_name = uniqid('sampul_', true) . '.' . $ext;
        if (move_uploaded_file($file['tmp_name'], ROOT_PATH . 'public/' . $this->upload_dir . $new_name)) {
            return $new_name;
        }
        return ['error' => "Gagal upload."];
    }

    private function deleteFile($file_name) {
        $path = ROOT_PATH . 'public/' . $this->upload_dir . $file_name;
        if (file_exists($path)) unlink($path);
    }
}
?>