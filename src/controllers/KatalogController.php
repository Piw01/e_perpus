<?php
/**
 * KatalogController - OPAC (Online Public Access Catalog)
 * Halaman publik untuk browsing buku
 */
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

    // ========================================
    // OPAC - Halaman Utama Katalog Publik
    // ========================================
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

    // ========================================
    // Detail Buku Publik
    // ========================================
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
    
    // ========================================
    // Search API Endpoint (AJAX)
    // ========================================
    public function search() {
        header('Content-Type: application/json');
        $keyword = $_GET['q'] ?? '';
        
        if (empty($keyword)) {
            echo json_encode(['success' => false, 'message' => 'Keyword kosong']);
            exit();
        }
        
        $stmt = $this->bukuModel->search($keyword);
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        echo json_encode([
            'success' => true,
            'data' => $results,
            'count' => count($results)
        ]);
        exit();
    }
}
?>