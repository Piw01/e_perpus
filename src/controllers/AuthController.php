<?php
// src/controllers/AuthController.php

class AuthController {
    private $userModel;

    public function __construct() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        require_once ROOT_PATH . 'src/config/Database.php';
        require_once ROOT_PATH . 'src/models/AdminModel.php'; 
        
        $database = new Database();
        $db = $database->getConnection();
        $this->userModel = new AdminModel($db); 
    }
    
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
            
            $user = $this->userModel->login($username, $password);

            if ($user) {
                // Set Session, sekarang termasuk LEVEL
                $_SESSION['user_id'] = $user['id_admin'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['nama_lengkap'] = $user['nama_lengkap'];
                $_SESSION['level'] = $user['level']; // <--- PENTING: Simpan level
                
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
    
    public function logout() {
        session_unset();
        session_destroy();
        header("Location: index.php?page=auth/login");
        exit();
    }
}
?>