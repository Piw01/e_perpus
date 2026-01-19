<?php
require_once ROOT_PATH . 'src/models/BukuModel.php';
require_once ROOT_PATH . 'src/models/AnggotaModel.php';
require_once ROOT_PATH . 'src/models/PeminjamanModel.php';
require_once ROOT_PATH . 'src/models/PengembalianModel.php';
require_once ROOT_PATH . 'src/config/Database.php';

class DashboardController {
    private $bukuModel;
    private $anggotaModel;
    private $peminjamanModel;
    private $pengembalianModel;

    public function __construct() {
        $database = new Database(); 
        $db = $database->getConnection();
        
        $this->bukuModel = new BukuModel($db);
        $this->anggotaModel = new AnggotaModel($db);
        $this->peminjamanModel = new PeminjamanModel($db);
        $this->pengembalianModel = new PengembalianModel($db);
    }

    public function index() {
        // Data real dari database
        $total_buku = $this->bukuModel->countAll();
        $total_anggota = $this->anggotaModel->countAll();
        $buku_dipinjam = $this->peminjamanModel->countDipinjam();
        $total_terlambat = $this->peminjamanModel->countTerlambat();
        $denda_belum_lunas = $this->pengembalianModel->sumDendaBelumLunas();
        
        // Data untuk chart
        $peminjaman_bulan_ini = $this->peminjamanModel->countBulanIni();
        $pengembalian_bulan_ini = $this->pengembalianModel->countBulanIni();
        
        // Buku terpopuler
        $buku_populer = $this->bukuModel->getBukuTerpopuler(5);
        
        require_once ROOT_PATH . 'src/views/dashboard/index.php';
    }
}
?>