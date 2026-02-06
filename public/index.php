<?php

ini_set('display_errors', 1);
error_reporting(E_ALL);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

define('ROOT_PATH', dirname(__DIR__) . '/');

// Autoload
spl_autoload_register(function ($class_name) {
    $directories = ['src/controllers/', 'src/models/', 'src/config/'];
    foreach ($directories as $directory) {
        $file = ROOT_PATH . $directory . $class_name . '.php';
        if (file_exists($file)) {
            require_once $file;
            return;
        }
    }
});

// ========================================
// ROUTING - PUBLIC CATALOG FIRST!
// ========================================

$page = $_GET['page'] ?? 'katalog/index'; // Default: Katalog Publik

// Halaman publik (tidak perlu login)
$public_pages = [
    'katalog/index', 'katalog/detail', 'katalog/search',
    'anggota/register', 'anggota/doRegister',
    'auth/loginAnggota', 'auth/doLoginAnggota',
    'auth/login', 'auth/doLogin',  // PERBAIKAN: Tambahkan auth/login ke public
    'auth/logout'
];

// Halaman khusus ADMIN
$admin_pages = [
    'dashboard/index',
    'buku/index', 'buku/create', 'buku/store', 'buku/edit', 'buku/update', 'buku/delete',
    'kategori/index', 'kategori/create', 'kategori/store', 'kategori/edit', 'kategori/update', 'kategori/delete',
    'penulis/index', 'penulis/create', 'penulis/store', 'penulis/edit', 'penulis/update', 'penulis/delete',
    'penerbit/index', 'penerbit/create', 'penerbit/store', 'penerbit/edit', 'penerbit/update', 'penerbit/delete',
    'siswa/index', 'siswa/create', 'siswa/store', 'siswa/edit', 'siswa/update', 'siswa/delete',
    'anggota/index', 'anggota/delete',
    'peminjaman/index', 'peminjaman/create', 'peminjaman/store',
    'pengembalian/index', 'pengembalian/create', 'pengembalian/store', 'pengembalian/bayarDenda',
    'reservasi/index', 'reservasi/updateStatus',
    'admin/index', 'admin/create', 'admin/store', 'admin/edit', 'admin/update', 'admin/delete'
];

// Halaman khusus ANGGOTA
$member_pages = ['anggota/dashboard', 'reservasi/create'];

// PROTEKSI ADMIN PAGES - PERBAIKAN: Jangan redirect jika sedang di halaman login
if (in_array($page, $admin_pages) && (!isset($_SESSION['user_id']) || $_SESSION['user_type'] != 'admin')) {
    header("Location: index.php?page=auth/login");
    exit();
}

// PROTEKSI MEMBER PAGES  
if (in_array($page, $member_pages) && (!isset($_SESSION['anggota_id']) || $_SESSION['user_type'] != 'anggota')) {
    header("Location: index.php?page=auth/loginAnggota");
    exit();
}

// EXECUTE ROUTING
list($controller_name, $method_name) = explode('/', $page);
$controller_class = ucfirst($controller_name) . 'Controller';

if (class_exists($controller_class)) {
    $controller = new $controller_class();
    if (method_exists($controller, $method_name)) {
        $controller->$method_name();
    } else {
        http_response_code(404);
        echo "404: Method tidak ditemukan";
    }
} else {
    http_response_code(404);
    echo "404: Controller tidak ditemukan";
}
?>