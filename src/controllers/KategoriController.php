<?php
// src/controllers/KategoriController.php

class KategoriController {
    private $kategoriModel;

    public function __construct() {
        $database = new Database();
        $db = $database->getConnection();
        $this->kategoriModel = new KategoriModel($db);
    }

    /**
     * READ - Tampilkan daftar kategori
     */
    public function index() {
        $stmt = $this->kategoriModel->readAll();
        $data_kategori = $stmt->fetchAll(PDO::FETCH_ASSOC);

        require_once ROOT_PATH . 'src/views/kategori/index.php';
    }

    /**
     * CREATE - Tampilkan form tambah kategori
     */
    public function create() {
        require_once ROOT_PATH . 'src/views/kategori/create.php';
    }

    /**
     * STORE - Proses simpan kategori baru
     */
    public function store() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nama_kategori = trim($_POST['nama_kategori']);

            if ($this->kategoriModel->namaKategoriExists($nama_kategori)) {
                $_SESSION['error_message'] = "Kategori sudah ada!";
                header("Location: index.php?page=kategori/create");
                exit();
            }

            $data = ['nama_kategori' => $nama_kategori];

            if ($this->kategoriModel->create($data)) {
                $_SESSION['success_message'] = "Kategori berhasil ditambahkan!";
                header("Location: index.php?page=kategori/index");
                exit();
            } else {
                $_SESSION['error_message'] = "Gagal menambahkan kategori!";
                header("Location: index.php?page=kategori/create");
                exit();
            }
        } else {
            header("Location: index.php?page=kategori/create");
            exit();
        }
    }

    /**
     * EDIT - Tampilkan form edit kategori
     */
    public function edit() {
        $id_kategori = $_GET['id'] ?? null;
        
        if (!$id_kategori) {
            header("Location: index.php?page=kategori/index");
            exit();
        }

        $data_kategori = $this->kategoriModel->readById($id_kategori);

        if (!$data_kategori) {
            $_SESSION['error_message'] = "Kategori tidak ditemukan!";
            header("Location: index.php?page=kategori/index");
            exit();
        }

        require_once ROOT_PATH . 'src/views/kategori/edit.php';
    }

    /**
     * UPDATE - Proses update kategori
     */
    public function update() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id_kategori = (int)$_POST['id_kategori'];
            $nama_kategori = trim($_POST['nama_kategori']);

            $data = ['nama_kategori' => $nama_kategori];

            if ($this->kategoriModel->update($id_kategori, $data)) {
                $_SESSION['success_message'] = "Kategori berhasil diperbarui!";
                header("Location: index.php?page=kategori/index");
                exit();
            } else {
                $_SESSION['error_message'] = "Gagal memperbarui kategori!";
                header("Location: index.php?page=kategori/edit&id=$id_kategori");
                exit();
            }
        } else {
            header("Location: index.php?page=kategori/index");
            exit();
        }
    }

    /**
     * DELETE - Proses hapus kategori
     */
    public function delete() {
        $id_kategori = $_GET['id'] ?? null;

        if (!$id_kategori) {
            header("Location: index.php?page=kategori/index");
            exit();
        }

        if ($this->kategoriModel->delete($id_kategori)) {
            $_SESSION['success_message'] = "Kategori berhasil dihapus!";
        } else {
            $_SESSION['error_message'] = "Gagal menghapus kategori!";
        }

        header("Location: index.php?page=kategori/index");
        exit();
    }
}
?>