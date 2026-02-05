<?php
/**
 * API Register Anggota
 * Endpoint: /public/api/apiRegister.php
 * Method: POST
 * 
 * Body (JSON):
 * {
 *   "nama_lengkap": "string",
 *   "jenis_kelamin": "L/P",
 *   "alamat": "string",
 *   "no_hp": "string",
 *   "email": "string",
 *   "username": "string",
 *   "password": "string",
 *   ... (optional fields)
 * }
 * 
 * Response Success:
 * {
 *   "success": true,
 *   "message": "Registrasi berhasil",
 *   "data": {
 *     "no_anggota": "ANG-2026-XXX"
 *   }
 * }
 */

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');

require_once __DIR__ . '/../../src/config/Database.php';
require_once __DIR__ . '/../../src/controllers/api/ApiRegisterController.php';

$database = new Database();
$db = $database->getConnection();
$controller = new ApiRegisterController($db);

$method = $_SERVER['REQUEST_METHOD'];

if ($method !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed']);
    exit();
}

$controller->register();