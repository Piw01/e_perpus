<?php
/**
 * API Katalog Publik - GET List Buku
 * Endpoint: /public/api/apiKatalog.php
 * Method: GET
 * 
 * Query Parameters:
 * - search: string (optional) - cari berdasarkan judul/penulis/ISBN
 * - kategori: int (optional) - filter by kategori ID
 * - limit: int (default: 12) - jumlah buku per page
 * - page: int (default: 1) - halaman
 */

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET');

require_once __DIR__ . '/../../src/config/Database.php';
require_once __DIR__ . '/../../src/controllers/api/ApiKatalogController.php';

$database = new Database();
$db = $database->getConnection();
$controller = new ApiKatalogController($db);

$method = $_SERVER['REQUEST_METHOD'];

if ($method !== 'GET') {
    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed']);
    exit();
}

// Handle request
$controller->getBukuList();