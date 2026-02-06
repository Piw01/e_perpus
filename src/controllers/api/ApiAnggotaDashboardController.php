<?php
/**
 * ApiAnggotaDashboardController - Handle API dashboard anggota (protected)
 * Menampilkan: Profil, Statistik, Riwayat Peminjaman, Reservasi Aktif
 */

header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

require_once __DIR__ . '/../../models/AnggotaModel.php';
require_once __DIR__ . '/../../models/PeminjamanModel.php';
require_once __DIR__ . '/../../models/ReservasiModel.php';
require_once __DIR__ . '/ApiLoginAnggotaController.php';

class ApiAnggotaDashboardController {
    private $anggotaModel;
    private $peminjamanModel;
    private $reservasiModel;
    private $db;

    public function __construct($db) {
        $this->db = $db;
        $this->anggotaModel = new AnggotaModel($db);
        $this->peminjamanModel = new PeminjamanModel($db);
        $this->reservasiModel = new ReservasiModel($db);
    }

    /**
     * GET Dashboard Data (Protected - butuh token)
     */
    public function getDashboard() {
        // Get token from header
        $headers = getallheaders();
        $token = null;

        if (isset($headers['Authorization'])) {
            $auth = $headers['Authorization'];
            $token = str_replace('Bearer ', '', $auth);
        }

        if (!$token) {
            http_response_code(401);
            echo json_encode([
                'success' => false,
                'error' => 'Token tidak ditemukan. Silakan login.'
            ]);
            return;
        }

        // Verify token
        $decoded = ApiLoginAnggotaController::verifyToken($token);
        if (!$decoded) {
            http_response_code(401);
            echo json_encode([
                'success' => false,
                'error' => 'Token tidak valid atau sudah kadaluarsa'
            ]);
            return;
        }

        $id_anggota = $decoded['id'];

        try {
            // Get profil anggota
            $anggota = $this->anggotaModel->readById($id_anggota);
            if (!$anggota) {
                http_response_code(404);
                echo json_encode([
                    'success' => false,
                    'error' => 'Data anggota tidak ditemukan'
                ]);
                return;
            }

            // Remove password dari response
            unset($anggota['password']);

            // Get riwayat peminjaman (10 terakhir)
            $query_peminjaman = "SELECT p.*, 
                                 DATE_FORMAT(p.tanggal_pinjam, '%d-%m-%Y') as tgl_pinjam_formatted,
                                 DATE_FORMAT(p.tanggal_harus_kembali, '%d-%m-%Y') as tgl_harus_kembali_formatted,
                                 DATE_FORMAT(p.tanggal_kembali, '%d-%m-%Y') as tgl_kembali_formatted,
                                 DATEDIFF(CURDATE(), p.tanggal_harus_kembali) as hari_terlambat,
                                 (SELECT COUNT(*) FROM detail_peminjaman dp WHERE dp.id_peminjaman = p.id_peminjaman) as jumlah_buku
                          FROM peminjaman p
                          WHERE p.id_anggota = :id_anggota
                          ORDER BY p.tanggal_pinjam DESC
                          LIMIT 10";
            
            $stmt_pinjam = $this->db->prepare($query_peminjaman);
            $stmt_pinjam->bindParam(':id_anggota', $id_anggota);
            $stmt_pinjam->execute();
            $riwayat_pinjam = $stmt_pinjam->fetchAll(PDO::FETCH_ASSOC);

            // Get detail buku untuk setiap peminjaman
            foreach ($riwayat_pinjam as &$pjm) {
                $query_detail = "SELECT b.judul, b.isbn, b.foto_sampul 
                                 FROM detail_peminjaman dp
                                 JOIN buku b ON dp.id_buku = b.id_buku
                                 WHERE dp.id_peminjaman = :id_peminjaman";
                $stmt_detail = $this->db->prepare($query_detail);
                $stmt_detail->bindParam(':id_peminjaman', $pjm['id_peminjaman']);
                $stmt_detail->execute();
                $pjm['buku_detail'] = $stmt_detail->fetchAll(PDO::FETCH_ASSOC);
            }

            // Get reservasi aktif (pending + belum expired)
            $stmt_reservasi = $this->reservasiModel->readByAnggota($id_anggota);
            $reservasi_aktif = $stmt_reservasi->fetchAll(PDO::FETCH_ASSOC);
            
            // Filter hanya yang pending
            $reservasi_aktif = array_filter($reservasi_aktif, function($r) {
                return $r['status'] == 'pending' && strtotime($r['tanggal_expired']) > time();
            });
            $reservasi_aktif = array_values($reservasi_aktif);

            // Build response
            echo json_encode([
                'success' => true,
                'data' => [
                    'profil' => $anggota,
                    'statistik' => [
                        'total_pinjam' => $anggota['total_pinjam'],
                        'denda_aktif' => $anggota['denda_aktif'],
                        'status' => $anggota['status_anggota'],
                        'masa_aktif' => date('d-m-Y', strtotime($anggota['tanggal_expired']))
                    ],
                    'riwayat_pinjam' => $riwayat_pinjam,
                    'reservasi_aktif' => $reservasi_aktif
                ]
            ]);

        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode([
                'success' => false,
                'error' => 'Internal server error',
                'message' => $e->getMessage()
            ]);
        }
    }
}