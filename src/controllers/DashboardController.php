<?php
require_once ROOT_PATH . 'src/models/BukuModel.php';
require_once ROOT_PATH . 'src/models/SiswaModel.php';
require_once ROOT_PATH . 'src/config/Database.php';

class DashboardController {
    private $bukuModel;
    private $siswaModel;

    public function __construct() {
        $database = new Database(); 
        $db = $database->getConnection();
        
        $this->bukuModel = new BukuModel($db);
        $this->siswaModel = new SiswaModel($db);
    }

    // Method yang dipanggil oleh router: index.php?page=dashboard/index
    public function index() {
        // Ambil data hitungan dari Model
        $total_buku = $this->bukuModel->countAll();
        $total_siswa = $this->siswaModel->countAll();        
        // Data dummy untuk 'Buku Dipinjam' karena belum ada Model Transaksi
        $buku_dipinjam = 15; 
        
        require_once ROOT_PATH . 'src/views/dashboard/index.php';
    }
}
?>