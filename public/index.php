<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}


// 1. Mendefinisikan Base Directory
define('ROOT_PATH', dirname(__DIR__) . '/');

// 2. Autoloading (sederhana)
spl_autoload_register(function ($class_name) {
    $directories = [
        'src/controllers/',
        'src/models/',
        'src/config/',
    ];
    
    foreach ($directories as $directory) {
        $file = ROOT_PATH . $directory . $class_name . '.php';
        if (file_exists($file)) {
            require_once $file;
            return;
        }
    }
});

// Logika Routing
$page = $_GET['page'] ?? 'dashboard/index'; 

// Halaman yang diizinkan tanpa login
$public_pages = ['auth/login', 'auth/doLogin'];

// Jika bukan halaman publik DAN user_id tidak diset
if (!in_array($page, $public_pages) && (!isset($_SESSION['user_id']) || empty($_SESSION['user_id']))) {
    // Redirect ke halaman login
    header("Location: index.php?page=auth/login");
    exit();
}

// Logika Routing

// Memisahkan 'Controller' dan 'Method'
list($controller_name, $method_name) = explode('/', $page);

$controller_class = ucfirst($controller_name) . 'Controller';

// Proses Eksekusi Controller
if (class_exists($controller_class)) {
    // Inisiasi Controller
    $controller = new $controller_class();
    
    // Cek method ada di Controller tersebut
    if (method_exists($controller, $method_name)) {
        // Eksekusi Method (Controller memanggil Model dan View)
        $controller->$method_name();
    } else {
        // Handle 404 - Method Not Found
        http_response_code(404);
        echo "Error 404: Method not found in Controller.";
    }
}


// ... (Kode Inisialisasi dan Autoloading di bagian atas) ...

// --- LOGIKA ROUTING ---
// Ambil variabel 'page' dari URL
$page = $_GET['page'] ?? 'dashboard/index';

// Pisahkan controller dan method
list($controller_name, $method_name) = explode('/', $page);

$controller_file = ROOT_PATH . 'src/controllers/' . ucfirst($controller_name) . 'Controller.php';

if (file_exists($controller_file)) {
    require_once $controller_file;
    $controller_class = ucfirst($controller_name) . 'Controller';
    $controller = new $controller_class();
    
    if (method_exists($controller, $method_name)) {
        // Panggil method yang sesuai
        $controller->$method_name();
    } else {
        // Handle 404 - Method Not Found
        http_response_code(404);
        echo "404 Not Found: Method {$method_name} tidak ditemukan di Controller {$controller_class}.";
    }
} else {
    // Handle 404 - Controller Not Found
    http_response_code(404);
    echo "404 Not Found: Controller {$controller_name} tidak ditemukan.";
}
?>