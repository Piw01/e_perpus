<?php
/**
 * API Login Anggota
 * Endpoint: /public/api/apiLoginAnggota.php
 * Method: POST
 * 
 * Body (JSON):
 * {
 *   "username": "string",
 *   "password": "string"
 * }
 * 
 * Response Success:
 * {
 *   "success": true,
 *   "message": "Login berhasil",
 *   "data": {
 *     "id_anggota": 1,
 *     "no_anggota": "ANG-2026-001",
 *     "nama_lengkap": "...",
 *     "email": "...",
 *     "status_anggota": "aktif",
 *     "token": "jwt_token_here"
 *   }
 * }
 */

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');

require_once __DIR__ . '/../../src/config/Database.php';
require_once __DIR__ . '/../../src/controllers/api/ApiLoginAnggotaController.php';

$database = new Database();
$db = $database->getConnection();
$controller = new ApiLoginAnggotaController($db);

$method = $_SERVER['REQUEST_METHOD'];

if ($method !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed']);
    exit();
}

$controller->login();