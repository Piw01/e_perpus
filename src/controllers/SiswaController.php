<?php
require_once ROOT_PATH . 'src/models/SiswaModel.php';
require_once ROOT_PATH . 'src/config/Database.php';

class SiswaController {
    private $siswaModel;

    public function __construct() {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        
        $database = new Database();
        $db = $database->getConnection();
        $this->siswaModel = new SiswaModel($db);
    }
    
    private function checkAccess() {
        if (!isset($_SESSION['level']) || $_SESSION['level'] == 3) {
            $_SESSION['error_message'] = "Akses ditolak! Anda tidak memiliki izin untuk melakukan aksi ini.";
            header("Location: index.php?page=siswa/index");
            exit();
        }
    }

    public function index() {
        $stmt = $this->siswaModel->readAll();
        $data_siswa = $stmt->fetchAll(PDO::FETCH_ASSOC);
        require_once ROOT_PATH . 'src/views/siswa/index.php';
    }

    public function create() {
        $this->checkAccess();
        require_once ROOT_PATH . 'src/views/siswa/create.php';
    }

    public function store() {
        $this->checkAccess();
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nisn = trim($_POST['nisn']);
            $nama_siswa = trim($_POST['nama_siswa']);
            $jenis_kelamin = trim($_POST['jenis_kelamin']);
            $tempat_lahir = trim($_POST['tempat_lahir']);
            $tgl_lahir = trim($_POST['tgl_lahir']);
            $alamat = trim($_POST['alamat']);
            $no_hp = trim($_POST['no_hp']);
            
            $foto_siswa = 'default.png';
            if (isset($_FILES['foto_siswa']) && $_FILES['foto_siswa']['error'] === UPLOAD_ERR_OK) {
                $foto_siswa = $this->handleFileUpload($_FILES['foto_siswa']);
            }

            if ($this->siswaModel->nisnExists($nisn)) {
                $_SESSION['error_message'] = "NISN $nisn sudah terdaftar!";
                header("Location: index.php?page=siswa/create");
                exit();
            }

            $data = [
                'nisn' => $nisn,
                'nama_siswa' => $nama_siswa,
                'jenis_kelamin' => $jenis_kelamin,
                'tempat_lahir' => $tempat_lahir,
                'tgl_lahir' => $tgl_lahir,
                'alamat' => $alamat,
                'no_hp' => $no_hp,
                'foto_siswa' => $foto_siswa
            ];

            if ($this->siswaModel->create($data)) {
                $_SESSION['success_message'] = "Data siswa berhasil ditambahkan!";
                header("Location: index.php?page=siswa/index");
                exit();
            } else {
                $_SESSION['error_message'] = "Gagal menyimpan data siswa!";
                header("Location: index.php?page=siswa/create");
                exit();
            }
        } else {
            header("Location: index.php?page=siswa/index");
            exit();
        }
    }

    public function edit() {
        $this->checkAccess();
        
        $nisn = $_GET['id'] ?? null;
        
        // FIX BUG: Pastikan $data_siswa selalu ada
        if (!$nisn) {
            $_SESSION['error_message'] = "ID siswa tidak valid!";
            header("Location: index.php?page=siswa/index");
            exit();
        }
        
        $data_siswa = $this->siswaModel->readById($nisn);

        if (!$data_siswa) {
            $_SESSION['error_message'] = "Data siswa tidak ditemukan!";
            header("Location: index.php?page=siswa/index");
            exit();
        }

        // Pastikan variable ada sebelum ke view
        require_once ROOT_PATH . 'src/views/siswa/edit.php';
    }

    public function update() {
        $this->checkAccess();
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nisn = trim($_POST['nisn']);
            $nama_siswa = trim($_POST['nama_siswa']);
            $jenis_kelamin = trim($_POST['jenis_kelamin']);
            $tempat_lahir = trim($_POST['tempat_lahir']);
            $tgl_lahir = trim($_POST['tgl_lahir']);
            $alamat = trim($_POST['alamat']);
            $no_hp = trim($_POST['no_hp']);
            
            // Ambil foto lama
            $current = $this->siswaModel->readById($nisn);
            $foto_siswa = $current['foto_siswa'] ?? 'default.png';
            
            if (isset($_FILES['foto_siswa']) && $_FILES['foto_siswa']['error'] === UPLOAD_ERR_OK) {
                $new_foto = $this->handleFileUpload($_FILES['foto_siswa']);
                if ($new_foto) {
                    // Hapus foto lama jika bukan default
                    if ($foto_siswa !== 'default.png') {
                        $this->deleteFile($foto_siswa);
                    }
                    $foto_siswa = $new_foto;
                }
            }

            $data = [
                'nama_siswa' => $nama_siswa,
                'jenis_kelamin' => $jenis_kelamin,
                'tempat_lahir' => $tempat_lahir,
                'tgl_lahir' => $tgl_lahir,
                'alamat' => $alamat,
                'no_hp' => $no_hp,
                'foto_siswa' => $foto_siswa
            ];

            if ($this->siswaModel->update($nisn, $data)) {
                $_SESSION['success_message'] = "Data siswa berhasil diperbarui!";
                header("Location: index.php?page=siswa/index");
                exit();
            } else {
                $_SESSION['error_message'] = "Gagal memperbarui data siswa!";
                header("Location: index.php?page=siswa/edit&id=$nisn");
                exit();
            }
        } else {
            header("Location: index.php?page=siswa/index");
            exit();
        }
    }

    public function delete() {
        $this->checkAccess();
        
        $nisn = $_GET['id'] ?? null;

        if (!$nisn) {
            header("Location: index.php?page=siswa/index");
            exit();
        }

        // Ambil data untuk hapus foto
        $siswa = $this->siswaModel->readById($nisn);
        
        if ($this->siswaModel->delete($nisn)) {
            // Hapus foto jika bukan default
            if ($siswa && $siswa['foto_siswa'] !== 'default.png') {
                $this->deleteFile($siswa['foto_siswa']);
            }
            
            $_SESSION['success_message'] = "Data siswa berhasil dihapus!";
            header("Location: index.php?page=siswa/index");
            exit();
        } else {
            $_SESSION['error_message'] = "Gagal menghapus data siswa. Mungkin data ini digunakan di tabel lain.";
            header("Location: index.php?page=siswa/index");
            exit();
        }
    }
    
    // Helper function untuk upload foto
    private function handleFileUpload($file) {
        $upload_dir = ROOT_PATH . 'public/assets/img/foto_siswa/';
        
        // Buat folder jika belum ada
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0755, true);
        }
        
        $allowed = ['jpg', 'jpeg', 'png'];
        $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        
        if (!in_array($ext, $allowed)) {
            $_SESSION['error_message'] = "Format file harus JPG, JPEG, atau PNG!";
            return false;
        }
        
        if ($file['size'] > 2 * 1024 * 1024) { // 2MB
            $_SESSION['error_message'] = "Ukuran file maksimal 2MB!";
            return false;
        }
        
        $new_name = 'siswa_' . uniqid() . '.' . $ext;
        
        if (move_uploaded_file($file['tmp_name'], $upload_dir . $new_name)) {
            return $new_name;
        }
        
        return false;
    }
    
    private function deleteFile($filename) {
        $file_path = ROOT_PATH . 'public/assets/img/foto_siswa/' . $filename;
        if (file_exists($file_path)) {
            unlink($file_path);
        }
    }
}