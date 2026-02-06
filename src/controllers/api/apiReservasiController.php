<?php
/**
 * ApiReservasiController - Handle API reservasi buku (protected)
 */

header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

require_once __DIR__ . '/../../models/ReservasiModel.php';
require_once __DIR__ . '/../../models/BukuModel.php';
require_once __DIR__ . '/ApiLoginAnggotaController.php';

class ApiReservasiController {
    private $reservasiModel;
    private $bukuModel;
    private $db;

    public function __construct($db) {
        $this->db = $db;
        $this->reservasiModel = new ReservasiModel($db);
        $this->bukuModel = new BukuModel($db);
    }

    /**
     * POST Create Reservasi (Protected - butuh token)
     */
    public function createReservasi() {
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

        // Get JSON input
        $input = json_decode(file_get_contents('php://input'), true);
        $id_buku = $input['id_buku'] ?? null;

        if (!$id_buku) {
            http_response_code(400);
            echo json_encode([
                'success' => false,
                'error' => 'id_buku wajib diisi'
            ]);
            return;
        }

        try {
            // Check buku availability
            $buku = $this->bukuModel->readById($id_buku);
            
            if (!$buku || $buku['jumlah_tersedia'] <= 0 || $buku['status'] != 'tersedia') {
                http_response_code(400);
                echo json_encode([
                    'success' => false,
                    'error' => 'Buku tidak tersedia untuk reservasi'
                ]);
                return;
            }

            // Generate kode reservasi
            $kode = $this->reservasiModel->generateKodeReservasi();
            $expired = date('Y-m-d H:i:s', strtotime('+3 days')); // 3 hari

            $data = [
                'kode_reservasi' => $kode,
                'id_anggota' => $id_anggota,
                'id_buku' => $id_buku,
                'tanggal_expired' => $expired,
                'status' => 'pending'
            ];

            if ($this->reservasiModel->create($data)) {
                echo json_encode([
                    'success' => true,
                    'message' => 'Reservasi berhasil dibuat. Ambil buku dalam 3 hari.',
                    'data' => [
                        'kode_reservasi' => $kode,
                        'judul_buku' => $buku['judul'],
                        'batas_ambil' => date('d-m-Y H:i', strtotime($expired))
                    ]
                ]);
            } else {
                throw new Exception('Gagal membuat reservasi');
            }

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