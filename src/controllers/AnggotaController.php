<?php
require_once ROOT_PATH . 'src/models/AnggotaModel.php';
require_once ROOT_PATH . 'src/config/Database.php';

class AnggotaController {
    private $model;

    public function __construct() {
        if (session_status() == PHP_SESSION_NONE) session_start();
        $db = (new Database())->getConnection();
        $this->model = new AnggotaModel($db);
    }

    // ADMIN: Lihat daftar anggota
    public function index() {
        $stmt = $this->model->readAll();
        $data_anggota = $stmt->fetchAll(PDO::FETCH_ASSOC);
        require_once ROOT_PATH . 'src/views/anggota/index.php';
    }

    // PUBLIC: Form registrasi
    public function register() {
        require_once ROOT_PATH . 'src/views/anggota/register.php';
    }

    // PUBLIC: Proses registrasi
    public function doRegister() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: index.php?page=anggota/register");
            exit();
        }

        $nik = trim($_POST['nik']);
        $nama_lengkap = trim($_POST['nama_lengkap']);
        $jenis_kelamin = $_POST['jenis_kelamin'];
        $tempat_lahir = trim($_POST['tempat_lahir']);
        $tanggal_lahir = $_POST['tanggal_lahir'];
        $alamat = trim($_POST['alamat']);
        $kelurahan = trim($_POST['kelurahan']);
        $kecamatan = trim($_POST['kecamatan']);
        $kota = trim($_POST['kota']) ?: 'Subang';
        $kode_pos = trim($_POST['kode_pos']);
        $no_hp = trim($_POST['no_hp']);
        $email = trim($_POST['email']);
        $username = trim($_POST['username']);
        $password = $_POST['password'];
        $pekerjaan = trim($_POST['pekerjaan']);
        $instansi = trim($_POST['instansi']);

        // Validasi
        if (empty($nama_lengkap) || empty($username) || empty($password) || empty($email)) {
            $_SESSION['error_message'] = "Field wajib tidak boleh kosong.";
            header("Location: index.php?page=anggota/register");
            exit();
        }

        if ($this->model->usernameExists($username)) {
            $_SESSION['error_message'] = "Username sudah digunakan.";
            header("Location: index.php?page=anggota/register");
            exit();
        }

        if ($this->model->emailExists($email)) {
            $_SESSION['error_message'] = "Email sudah terdaftar.";
            header("Location: index.php?page=anggota/register");
            exit();
        }

        if (!empty($nik) && $this->model->nikExists($nik)) {
            $_SESSION['error_message'] = "NIK sudah terdaftar.";
            header("Location: index.php?page=anggota/register");
            exit();
        }

        $no_anggota = $this->model->generateNoAnggota();
        $password_hashed = password_hash($password, PASSWORD_DEFAULT);
        $tanggal_daftar = date('Y-m-d');
        $tanggal_expired = date('Y-m-d', strtotime('+1 year'));

        $data = [
            'no_anggota' => $no_anggota,
            'nik' => $nik,
            'nama_lengkap' => $nama_lengkap,
            'jenis_kelamin' => $jenis_kelamin,
            'tempat_lahir' => $tempat_lahir,
            'tanggal_lahir' => $tanggal_lahir,
            'alamat' => $alamat,
            'kelurahan' => $kelurahan,
            'kecamatan' => $kecamatan,
            'kota' => $kota,
            'kode_pos' => $kode_pos,
            'no_hp' => $no_hp,
            'email' => $email,
            'username' => $username,
            'password' => $password_hashed,
            'foto_profil' => 'default_user.png',
            'pekerjaan' => $pekerjaan,
            'instansi' => $instansi,
            'status_anggota' => 'aktif',
            'tanggal_daftar' => $tanggal_daftar,
            'tanggal_expired' => $tanggal_expired
        ];

        if ($this->model->create($data)) {
            $_SESSION['success_message'] = "Registrasi berhasil! Nomor Anggota Anda: $no_anggota. Silakan login.";
            header("Location: index.php?page=auth/loginAnggota");
            exit();
        } else {
            $_SESSION['error_message'] = "Registrasi gagal. Coba lagi.";
            header("Location: index.php?page=anggota/register");
            exit();
        }
    }

    // MEMBER: Dashboard profil
    public function dashboard() {
        if (!isset($_SESSION['anggota_id'])) {
            header("Location: index.php?page=auth/loginAnggota");
            exit();
        }

        $anggota = $this->model->readById($_SESSION['anggota_id']);
        require_once ROOT_PATH . 'src/views/anggota/dashboard.php';
    }

    // ADMIN: Delete anggota
    public function delete() {
        if (!isset($_SESSION['level']) || $_SESSION['level'] == 3) {
            $_SESSION['error_message'] = "Akses ditolak.";
            header("Location: index.php?page=anggota/index");
            exit();
        }

        $id = $_GET['id'] ?? null;
        if ($id && $this->model->delete($id)) {
            $_SESSION['success_message'] = "Anggota berhasil dihapus.";
        } else {
            $_SESSION['error_message'] = "Gagal menghapus anggota.";
        }
        header("Location: index.php?page=anggota/index");
        exit();
    }
}
?>