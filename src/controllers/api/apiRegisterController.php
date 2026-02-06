<?php
/**
 * ApiRegisterController - Handle API registrasi anggota
 */

header('Access-Control-Allow-Origin: *');

require_once __DIR__ . '/../../models/AnggotaModel.php';

class ApiRegisterController {
    private $anggotaModel;
    private $db;

    public function __construct($db) {
        $this->db = $db;
        $this->anggotaModel = new AnggotaModel($db);
    }

    /**
     * POST Register
     */
    public function register() {
        // Get JSON input
        $input = json_decode(file_get_contents('php://input'), true);

        // Validasi field wajib
        $required_fields = ['nama_lengkap', 'jenis_kelamin', 'alamat', 'no_hp', 'email', 'username', 'password'];
        foreach ($required_fields as $field) {
            if (empty($input[$field])) {
                http_response_code(400);
                echo json_encode([
                    'success' => false,
                    'error' => "Field {$field} wajib diisi"
                ]);
                return;
            }
        }

        try {
            // Check duplicate username
            if ($this->anggotaModel->usernameExists($input['username'])) {
                http_response_code(409);
                echo json_encode([
                    'success' => false,
                    'error' => 'Username sudah digunakan'
                ]);
                return;
            }

            // Check duplicate email
            if ($this->anggotaModel->emailExists($input['email'])) {
                http_response_code(409);
                echo json_encode([
                    'success' => false,
                    'error' => 'Email sudah terdaftar'
                ]);
                return;
            }

            // Check NIK jika ada
            if (!empty($input['nik']) && $this->anggotaModel->nikExists($input['nik'])) {
                http_response_code(409);
                echo json_encode([
                    'success' => false,
                    'error' => 'NIK sudah terdaftar'
                ]);
                return;
            }

            // Generate no anggota & prepare data
            $no_anggota = $this->anggotaModel->generateNoAnggota();
            $password_hashed = password_hash($input['password'], PASSWORD_DEFAULT);
            $tanggal_daftar = date('Y-m-d');
            $tanggal_expired = date('Y-m-d', strtotime('+1 year'));

            $data = [
                'no_anggota' => $no_anggota,
                'nik' => $input['nik'] ?? null,
                'nama_lengkap' => $input['nama_lengkap'],
                'jenis_kelamin' => $input['jenis_kelamin'],
                'tempat_lahir' => $input['tempat_lahir'] ?? null,
                'tanggal_lahir' => $input['tanggal_lahir'] ?? null,
                'alamat' => $input['alamat'],
                'kelurahan' => $input['kelurahan'] ?? null,
                'kecamatan' => $input['kecamatan'] ?? null,
                'kota' => $input['kota'] ?? 'Subang',
                'kode_pos' => $input['kode_pos'] ?? null,
                'no_hp' => $input['no_hp'],
                'email' => $input['email'],
                'username' => $input['username'],
                'password' => $password_hashed,
                'foto_profil' => 'default_user.png',
                'pekerjaan' => $input['pekerjaan'] ?? 'Pelajar/Mahasiswa',
                'instansi' => $input['instansi'] ?? null,
                'status_anggota' => 'aktif',
                'tanggal_daftar' => $tanggal_daftar,
                'tanggal_expired' => $tanggal_expired
            ];

            if ($this->anggotaModel->create($data)) {
                echo json_encode([
                    'success' => true,
                    'message' => 'Registrasi berhasil! Silakan login dengan akun Anda.',
                    'data' => [
                        'no_anggota' => $no_anggota
                    ]
                ]);
            } else {
                throw new Exception('Gagal menyimpan data ke database');
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