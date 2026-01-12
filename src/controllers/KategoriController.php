<?php
require_once ROOT_PATH . 'src/models/KategoriModel.php';
require_once ROOT_PATH . 'src/config/Database.php';

class KategoriController {
    private $kategoriModel;

    public function __construct() {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        
        $database = new Database();
        $db = $database->getConnection();
        $this->kategoriModel = new KategoriModel($db);
    }
    
    private function checkAccess() {
        if (!isset($_SESSION['level']) || $_SESSION['level'] == 3) {
            $_SESSION['error_message'] = "Akses ditolak! Anda tidak memiliki izin untuk melakukan aksi ini.";
            header("Location: index.php?page=kategori/index");
            exit();
        }
    }

    public function index() {
        $stmt = $this->kategoriModel->readAll();
        $data_kategori = $stmt->fetchAll(PDO::FETCH_ASSOC);
        require_once ROOT_PATH . 'src/views/kategori/index.php';
    }

    public function create() {
        $this->checkAccess();
        require_once ROOT_PATH . 'src/views/kategori/create.php';
    }

    public function store() {
        $this->checkAccess();
        
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

    public function edit() {
        $this->checkAccess();
        
        $id_kategori = $_GET['id'] ?? null;
        $kategori = $this->kategoriModel->readById($id_kategori); // KONSISTEN: $kategori

        if (!$kategori) {
            $_SESSION['error_message'] = "Kategori tidak ditemukan!";
            header("Location: index.php?page=kategori/index");
            exit();
        }

        require_once ROOT_PATH . 'src/views/kategori/edit.php';
    }

    public function update() {
        $this->checkAccess();
        
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

    public function delete() {
        $this->checkAccess();
        
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