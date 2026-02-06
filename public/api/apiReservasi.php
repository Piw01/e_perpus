<?php
/**
 * API Reservasi Buku
 * Endpoint: /public/api/apiReservasi.php
 * Method: POST
 * Headers: Authorization: Bearer {token}
 * 
 * Body (JSON):
 * {
 *   "id_buku": 1
 * }
 * 
 * Response Success:
 * {
 *   "success": true,
 *   "message": "Reservasi berhasil dibuat",
 *   "data": {
 *     "kode_reservasi": "RSV-202602-0001",
 *     "judul_buku": "Laskar Pelangi",
 *     "batas_ambil": "09-02-2026 14:30"
 *   }
 * }
 */

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

// Handle preflight request
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

require_once __DIR__ . '/../../src/config/Database.php';
require_once __DIR__ . '/../../src/controllers/api/ApiReservasiController.php';

$database = new Database();
$db = $database->getConnection();
$controller = new ApiReservasiController($db);

$method = $_SERVER['REQUEST_METHOD'];

if ($method !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'error' => 'Method not allowed']);
    exit();
}

$controller->createReservasi();