<?php
/**
 * API Dashboard Anggota
 * Endpoint: /public/api/apiAnggotaDashboard.php
 * Method: GET
 * Headers: Authorization: Bearer {token}
 * 
 * Response Success:
 * {
 *   "success": true,
 *   "data": {
 *     "profil": { ... },
 *     "statistik": {
 *       "total_pinjam": 4,
 *       "denda_aktif": 0,
 *       "status": "aktif"
 *     },
 *     "riwayat_pinjam": [ ... ],
 *     "reservasi_aktif": [ ... ]
 *   }
 * }
 */

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

require_once __DIR__ . '/../../src/config/Database.php';
require_once __DIR__ . '/../../src/controllers/api/ApiAnggotaDashboardController.php';

$database = new Database();
$db = $database->getConnection();
$controller = new ApiAnggotaDashboardController($db);

$method = $_SERVER['REQUEST_METHOD'];

if ($method !== 'GET') {
    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed']);
    exit();
}

$controller->getDashboard();