<?php
require_once ROOT_PATH . 'src/models/KategoriModel.php';
require_once ROOT_PATH . 'src/config/Database.php';

class KategoriController {
    private $kategoriModel;

    public function __construct() {
        // Mulai session jika belum dimulai
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        
        $database = new Database();
        $db = $database->getConnection();
        $this->kategoriModel = new KategoriModel($db);
    }
    
    /**
     * Helper untuk membatasi akses CUD
     */
    private function checkAccess() {
        // Hanya izinkan Level < 3 (misal: Admin/Super Admin)
        if (!isset($_SESSION['level']) || $_SESSION['level'] == 3) {
            $_SESSION['error_message'] = "Akses ditolak! Anda tidak memiliki izin untuk melakukan aksi ini.";
            header("Location: index.php?page=kategori/index");
            exit();
        }
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
        $this->checkAccess(); // Pengecekan Akses
        require_once ROOT_PATH . 'src/views/kategori/create.php';
    }

    /**
     * STORE - Proses simpan kategori baru
     */
    public function store() {
        $this->checkAccess(); // Pengecekan Akses
        
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
                $_SESSION['error_message'] = "Gagal menyimpan kategori!";
                header("Location: index.php?page=kategori/create");
                exit();
            }
        } else {
            header("Location: index.php?page=kategori/index");
            exit();
        }
    }

    /**
     * EDIT - Tampilkan form edit kategori
     */
    public function edit() {
        $this->checkAccess(); // Pengecekan Akses
        
        $id_kategori = $_GET['id'] ?? null;
        $kategori = $this->kategoriModel->readById($id_kategori);

        if (!$kategori) {
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
        $this->checkAccess(); // Pengecekan Akses
        
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
        $this->checkAccess(); // Pengecekan Akses
        
        $id_kategori = $_GET['id'] ?? null;

        if (!$id_kategori) {
            header("Location: index.php?page=kategori/index");
            exit();
        }

        if ($this->kategoriModel->delete($id_kategori)) {
            $_SESSION['success_message'] = "Kategori berhasil dihapus!";
            header("Location: index.php?page=kategori/index");
            exit();
        } else {
            $_SESSION['error_message'] = "Gagal menghapus kategori. Mungkin data ini digunakan di tabel lain.";
            header("Location: index.php?page=kategori/index");
            exit();
        }
    }
}