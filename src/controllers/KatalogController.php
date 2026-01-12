<?php
require_once ROOT_PATH . 'src/models/BukuModel.php';
require_once ROOT_PATH . 'src/models/KategoriModel.php';
require_once ROOT_PATH . 'src/config/Database.php';

class KatalogController {
    private $bukuModel;
    private $kategoriModel;

    public function __construct() {
        $database = new Database();
        $db = $database->getConnection();
        $this->bukuModel = new BukuModel($db);
        $this->kategoriModel = new KategoriModel($db);
    }

    // OPAC - Halaman publik pencarian buku
    public function index() {
        $search = $_GET['search'] ?? '';
        $kategori_filter = $_GET['kategori'] ?? '';
        
        if (!empty($search)) {
            $stmt = $this->bukuModel->search($search);
        } elseif (!empty($kategori_filter)) {
            $stmt = $this->bukuModel->filterByKategori($kategori_filter);
        } else {
            $stmt = $this->bukuModel->readAllPublic();
        }
        
        $data_buku = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $kategori_list = $this->kategoriModel->readAll()->fetchAll(PDO::FETCH_ASSOC);
        
        require_once ROOT_PATH . 'src/views/katalog/index.php';
    }

    // Detail buku publik
    public function detail() {
        $id_buku = $_GET['id'] ?? null;
        if (!$id_buku) {
            header("Location: index.php?page=katalog/index");
            exit();
        }

        $buku = $this->bukuModel->readByIdDetail($id_buku);
        if (!$buku) {
            $_SESSION['error_message'] = "Buku tidak ditemukan.";
            header("Location: index.php?page=katalog/index");
            exit();
        }

        require_once ROOT_PATH . 'src/views/katalog/detail.php';
    }
}
?>