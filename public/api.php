<?php
/**
 * API Controller - RESTful Endpoints
 * Endpoint: /public/api.php
 * 
 * Contoh penggunaan:
 * - GET  /api.php?resource=buku          → List semua buku
 * - GET  /api.php?resource=buku&id=1     → Detail buku ID 1
 * - GET  /api.php?resource=buku&search=laskar → Search buku
 * - GET  /api.php?resource=kategori      → List kategori
 * - GET  /api.php?resource=anggota&id=1  → Profile anggota
 * - POST /api.php?resource=reservasi     → Buat reservasi baru
 */

require_once __DIR__ . '/../src/config/Database.php';
require_once __DIR__ . '/../src/models/BukuModel.php';
require_once __DIR__ . '/../src/models/KategoriModel.php';
require_once __DIR__ . '/../src/models/AnggotaModel.php';
require_once __DIR__ . '/../src/models/ReservasiModel.php';

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');

$database = new Database();
$db = $database->getConnection();

$method = $_SERVER['REQUEST_METHOD'];
$resource = $_GET['resource'] ?? null;

// ========================================
// ROUTING API
// ========================================

if (!$resource) {
    http_response_code(400);
    echo json_encode(['error' => 'Resource tidak dispesifikasi']);
    exit();
}

switch($resource) {
    
    // ========================================
    // API: BUKU
    // ========================================
    case 'buku':
        $bukuModel = new BukuModel($db);
        
        if ($method == 'GET') {
            // GET Detail Buku by ID
            if (isset($_GET['id'])) {
                $buku = $bukuModel->readByIdDetail($_GET['id']);
                if ($buku) {
                    echo json_encode(['success' => true, 'data' => $buku]);
                } else {
                    http_response_code(404);
                    echo json_encode(['error' => 'Buku tidak ditemukan']);
                }
            }
            // GET Search Buku
            elseif (isset($_GET['search'])) {
                $stmt = $bukuModel->search($_GET['search']);
                $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
                echo json_encode(['success' => true, 'data' => $results, 'count' => count($results)]);
            }
            // GET Filter by Kategori
            elseif (isset($_GET['kategori'])) {
                $stmt = $bukuModel->filterByKategori($_GET['kategori']);
                $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
                echo json_encode(['success' => true, 'data' => $results, 'count' => count($results)]);
            }
            // GET All Buku (Public)
            else {
                $stmt = $bukuModel->readAllPublic();
                $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
                echo json_encode(['success' => true, 'data' => $results, 'count' => count($results)]);
            }
        }
        break;
    
    // ========================================
    // API: KATEGORI
    // ========================================
    case 'kategori':
        $kategoriModel = new KategoriModel($db);
        
        if ($method == 'GET') {
            $stmt = $kategoriModel->readAll();
            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
            echo json_encode(['success' => true, 'data' => $results, 'count' => count($results)]);
        }
        break;
    
    // ========================================
    // API: ANGGOTA
    // ========================================
    case 'anggota':
        $anggotaModel = new AnggotaModel($db);
        
        if ($method == 'GET' && isset($_GET['id'])) {
            // Profile anggota (protected - harus login)
            session_start();
            if (!isset($_SESSION['anggota_id'])) {
                http_response_code(401);
                echo json_encode(['error' => 'Unauthorized - Login required']);
                exit();
            }
            
            $anggota = $anggotaModel->readById($_GET['id']);
            if ($anggota) {
                // Jangan kirim password!
                unset($anggota['password']);
                echo json_encode(['success' => true, 'data' => $anggota]);
            } else {
                http_response_code(404);
                echo json_encode(['error' => 'Anggota tidak ditemukan']);
            }
        }
        break;
    
    // ========================================
    // API: RESERVASI
    // ========================================
    case 'reservasi':
        $reservasiModel = new ReservasiModel($db);
        
        if ($method == 'POST') {
            // Buat reservasi baru (protected)
            session_start();
            if (!isset($_SESSION['anggota_id'])) {
                http_response_code(401);
                echo json_encode(['error' => 'Unauthorized - Login required']);
                exit();
            }
            
            $input = json_decode(file_get_contents('php://input'), true);
            $id_buku = $input['id_buku'] ?? null;
            
            if (!$id_buku) {
                http_response_code(400);
                echo json_encode(['error' => 'id_buku wajib diisi']);
                exit();
            }
            
            $kode = $reservasiModel->generateKodeReservasi();
            $expired = date('Y-m-d H:i:s', strtotime('+3 days'));
            
            $data = [
                'kode_reservasi' => $kode,
                'id_anggota' => $_SESSION['anggota_id'],
                'id_buku' => $id_buku,
                'tanggal_expired' => $expired,
                'status' => 'pending'
            ];
            
            if ($reservasiModel->create($data)) {
                echo json_encode([
                    'success' => true,
                    'message' => 'Reservasi berhasil dibuat',
                    'kode_reservasi' => $kode
                ]);
            } else {
                http_response_code(500);
                echo json_encode(['error' => 'Gagal membuat reservasi']);
            }
        }
        
        elseif ($method == 'GET') {
            // Get reservasi by anggota (protected)
            session_start();
            if (!isset($_SESSION['anggota_id'])) {
                http_response_code(401);
                echo json_encode(['error' => 'Unauthorized']);
                exit();
            }
            
            $stmt = $reservasiModel->readByAnggota($_SESSION['anggota_id']);
            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
            echo json_encode(['success' => true, 'data' => $results]);
        }
        break;
    
    // ========================================
    // API: STATS (Statistics Dashboard)
    // ========================================
    case 'stats':
        if ($method == 'GET') {
            $bukuModel = new BukuModel($db);
            $anggotaModel = new AnggotaModel($db);
            
            $stats = [
                'total_buku' => $bukuModel->countAll(),
                'total_anggota' => $anggotaModel->countAll(),
                'buku_terpopuler' => $bukuModel->getBukuTerpopuler(5)
            ];
            
            echo json_encode(['success' => true, 'data' => $stats]);
        }
        break;
    
    default:
        http_response_code(404);
        echo json_encode(['error' => 'Resource tidak ditemukan']);
        break;
}
?>