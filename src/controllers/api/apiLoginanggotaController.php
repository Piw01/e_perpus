<?php
/**
 * ApiLoginAnggotaController - Handle API login untuk anggota
 */

header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

require_once __DIR__ . '/../../models/AnggotaModel.php';

class ApiLoginAnggotaController {
    private $anggotaModel;
    private $db;

    public function __construct($db) {
        $this->db = $db;
        $this->anggotaModel = new AnggotaModel($db);
    }

    /**
     * POST Login
     */
    public function login() {
        // Get JSON input
        $input = json_decode(file_get_contents('php://input'), true);

        $username = $input['username'] ?? '';
        $password = $input['password'] ?? '';

        if (empty($username) || empty($password)) {
            http_response_code(400);
            echo json_encode([
                'success' => false,
                'error' => 'Username dan password wajib diisi'
            ]);
            return;
        }

        try {
            $anggota = $this->anggotaModel->login($username, $password);

            if (!$anggota) {
                http_response_code(401);
                echo json_encode([
                    'success' => false,
                    'error' => 'Username atau password salah'
                ]);
                return;
            }

            // Check status anggota
            if ($anggota['status_anggota'] != 'aktif') {
                http_response_code(403);
                echo json_encode([
                    'success' => false,
                    'error' => 'Akun Anda tidak aktif. Hubungi admin.'
                ]);
                return;
            }

            // Generate simple token
            $token = $this->generateToken($anggota['id_anggota'], $anggota['username']);

            // Response
            echo json_encode([
                'success' => true,
                'message' => 'Login berhasil',
                'data' => [
                    'id_anggota' => $anggota['id_anggota'],
                    'no_anggota' => $anggota['no_anggota'],
                    'nama_lengkap' => $anggota['nama_lengkap'],
                    'email' => $anggota['email'],
                    'no_hp' => $anggota['no_hp'],
                    'status_anggota' => $anggota['status_anggota'],
                    'tanggal_expired' => $anggota['tanggal_expired'],
                    'total_pinjam' => $anggota['total_pinjam'],
                    'denda_aktif' => $anggota['denda_aktif'],
                    'token' => $token
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

    /**
     * Generate simple token (base64 encoded)
     */
    private function generateToken($id_anggota, $username) {
        $data = [
            'id' => $id_anggota,
            'username' => $username,
            'type' => 'anggota',
            'exp' => time() + (7 * 24 * 60 * 60) // 7 days
        ];
        return base64_encode(json_encode($data));
    }

    /**
     * Verify token (helper method)
     */
    public static function verifyToken($token) {
        try {
            $decoded = json_decode(base64_decode($token), true);
            if ($decoded && $decoded['exp'] > time()) {
                return $decoded;
            }
            return false;
        } catch (Exception $e) {
            return false;
        }
    }
}