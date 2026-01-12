<?php
class AuthController {
    private $adminModel;
    private $anggotaModel;

    public function __construct() {
        if (session_status() === PHP_SESSION_NONE) session_start();

        require_once ROOT_PATH . 'src/config/Database.php';
        require_once ROOT_PATH . 'src/models/AdminModel.php'; 
        require_once ROOT_PATH . 'src/models/AnggotaModel.php';
        
        $database = new Database();
        $db = $database->getConnection();
        $this->adminModel = new AdminModel($db); 
        $this->anggotaModel = new AnggotaModel($db);
    }
    
    // ADMIN LOGIN
    public function login() {
        if (isset($_SESSION['user_id'])) {
            header("Location: index.php?page=dashboard/index");
            exit();
        }
        require_once ROOT_PATH . 'src/views/auth/login.php';
    }

    public function doLogin() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = trim($_POST['username']);
            $password = trim($_POST['password']);
            
            $user = $this->adminModel->login($username, $password);

            if ($user) {
                $_SESSION['user_id'] = $user['id_admin'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['nama_lengkap'] = $user['nama_lengkap'];
                $_SESSION['level'] = $user['level'];
                $_SESSION['user_type'] = 'admin';
                
                header("Location: index.php?page=dashboard/index");
                exit();
            } else {
                $_SESSION['error_message'] = "Username atau password salah.";
                header("Location: index.php?page=auth/login");
                exit();
            }
        } else {
            header("Location: index.php?page=auth/login");
            exit();
        }
    }

    // ANGGOTA LOGIN
    public function loginAnggota() {
        if (isset($_SESSION['anggota_id'])) {
            header("Location: index.php?page=anggota/dashboard");
            exit();
        }
        require_once ROOT_PATH . 'src/views/auth/loginAnggota.php';
    }

    public function doLoginAnggota() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = trim($_POST['username']);
            $password = trim($_POST['password']);
            
            $anggota = $this->anggotaModel->login($username, $password);

            if ($anggota) {
                $_SESSION['anggota_id'] = $anggota['id_anggota'];
                $_SESSION['anggota_username'] = $anggota['username'];
                $_SESSION['anggota_nama'] = $anggota['nama_lengkap'];
                $_SESSION['user_type'] = 'anggota';
                
                header("Location: index.php?page=anggota/dashboard");
                exit();
            } else {
                $_SESSION['error_message'] = "Username/password salah atau akun tidak aktif.";
                header("Location: index.php?page=auth/loginAnggota");
                exit();
            }
        } else {
            header("Location: index.php?page=auth/loginAnggota");
            exit();
        }
    }
    
    // LOGOUT (untuk Admin & Anggota)
    public function logout() {
        session_unset();
        session_destroy();
        header("Location: index.php?page=katalog/index");
        exit();
    }
}
?>