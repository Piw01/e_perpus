<?php
/**
 * ApiDetailBukuController - Handle API requests untuk detail buku
 */

header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

require_once __DIR__ . '/../../models/BukuModel.php';

class ApiDetailBukuController {
    private $bukuModel;
    private $db;

    public function __construct($db) {
        $this->db = $db;
        $this->bukuModel = new BukuModel($db);
    }

    /**
     * GET Detail Buku by ID
     */
    public function getDetailBuku() {
        $id_buku = $_GET['id'] ?? null;

        if (!$id_buku) {
            http_response_code(400);
            echo json_encode([
                'success' => false,
                'error' => 'Parameter id wajib diisi'
            ]);
            return;
        }

        try {
            $buku = $this->bukuModel->readByIdDetail($id_buku);

            if (!$buku) {
                http_response_code(404);
                echo json_encode([
                    'success' => false,
                    'error' => 'Buku tidak ditemukan'
                ]);
                return;
            }

            // Check apakah buku bisa direservasi
            $buku['can_reserve'] = ($buku['jumlah_tersedia'] > 0 && $buku['status'] == 'tersedia');

            echo json_encode([
                'success' => true,
                'data' => $buku
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