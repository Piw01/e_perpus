<?php
/**
 * API Detail Buku
 * Endpoint: /public/api/apiDetailBuku.php?id={id_buku}
 * Method: GET
 */

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET');

require_once __DIR__ . '/../../src/config/Database.php';
require_once __DIR__ . '/../../src/models/BukuModel.php';

$database = new Database();
$db = $database->getConnection();
$bukuModel = new BukuModel($db);

$method = $_SERVER['REQUEST_METHOD'];

if ($method !== 'GET') {
    http_response_code(405);
    echo json_encode(['success' => false, 'error' => 'Method not allowed']);
    exit();
}

$id_buku = $_GET['id'] ?? null;

if (!$id_buku) {
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => 'Parameter id wajib diisi']);
    exit();
}

try {
    $buku = $bukuModel->readByIdDetail($id_buku);

    if (!$buku) {
        http_response_code(404);
        echo json_encode(['success' => false, 'error' => 'Buku tidak ditemukan']);
        exit();
    }

    // Check apakah buku bisa direservasi
    $buku['can_reserve'] = ($buku['jumlah_tersedia'] > 0 && $buku['status'] == 'tersedia');

    echo json_encode(['success' => true, 'data' => $buku]);

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'error' => 'Internal server error',
        'message' => $e->getMessage()
    ]);
}