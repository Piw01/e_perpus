<?php
/**
 * ApiAnggotaDashboardController - Handle API dashboard anggota (protected)
 */

header('Access-Control-Allow-Origin: *');

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
     * GET Dashboard Data (Protected)
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

            // Get riwayat peminjaman
            $query_peminjaman = "SELECT p.*, 
                                 DATE_FORMAT(p.tanggal_pinjam, '%d-%m-%Y') as tgl_pinjam_formatted,
                                 DATE_FORMAT(p.tanggal_harus_kembali, '%d-%m-%Y') as tgl_harus_kembali_formatted,
                                 DATE_FORMAT(p.tanggal_kembali, '%d-%m-%Y') as tgl_kembali_formatted,
                                 DATEDIFF(CURDATE(), p.tanggal_harus_kembali) as hari_terlambat
                          FROM peminjaman p
                          WHERE p.id_anggota = :id_anggota
                          ORDER BY p.tanggal_pinjam DESC
                          LIMIT 10";
            
            $stmt_pinjam = $this->db->prepare($query_peminjaman);
            $stmt_pinjam->bindParam(':id_anggota', $id_anggota);
            $stmt_pinjam->execute();
            $riwayat_pinjam = $stmt_pinjam->fetchAll(PDO::FETCH_ASSOC);

            // Get reservasi aktif
            $stmt_reservasi = $this->reservasiModel->readByAnggota($id_anggota);
            $reservasi_aktif = $stmt_reservasi->fetchAll(PDO::FETCH_ASSOC);

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