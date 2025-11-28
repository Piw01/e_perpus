<?php
// src/controllers/SiswaController.php

class SiswaController {
    private $siswaModel;

    public function __construct() {
        // Inisialisasi Model
        $database = new Database();
        $db = $database->getConnection();
        $this->siswaModel = new SiswaModel($db);
    }

    /**
     * READ - Tampilkan daftar semua siswa
     * Dipanggil oleh: index.php?page=siswa/index
     */
    public function index() {
        $stmt = $this->siswaModel->readAll();
        $data_siswa = $stmt->fetchAll(PDO::FETCH_ASSOC);

        require_once ROOT_PATH . 'src/views/siswa/index.php';
    }

    /**
     * CREATE - Tampilkan form tambah siswa
     * Dipanggil oleh: index.php?page=siswa/create
     */
    public function create() {
        require_once ROOT_PATH . 'src/views/siswa/create.php';
    }

    /**
     * STORE - Proses simpan data siswa baru
     * Dipanggil oleh: index.php?page=siswa/store (POST)
     */
    public function store() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Ambil data dari form
            $nisn = trim($_POST['nisn']);
            $nama_siswa = trim($_POST['nama_siswa']);
            $jenis_kelamin = $_POST['jenis_kelamin'];
            $tempat_lahir = trim($_POST['tempat_lahir']);
            $tgl_lahir = $_POST['tgl_lahir'];
            $alamat = trim($_POST['alamat']);
            $no_hp = trim($_POST['no_hp']);
            $foto_siswa = 'default.png'; // Default image

            // Validasi NISN
            if ($this->siswaModel->nisnExists($nisn)) {
                $_SESSION['error_message'] = "NISN sudah terdaftar!";
                header("Location: index.php?page=siswa/create");
                exit();
            }

            // Persiapan data untuk disimpan
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

            // Simpan ke database
            if ($this->siswaModel->create($data)) {
                $_SESSION['success_message'] = "Data siswa berhasil ditambahkan!";
                header("Location: index.php?page=siswa/index");
                exit();
            } else {
                $_SESSION['error_message'] = "Gagal menambahkan data siswa!";
                header("Location: index.php?page=siswa/create");
                exit();
            }
        } else {
            header("Location: index.php?page=siswa/create");
            exit();
        }
    }

    /**
     * EDIT - Tampilkan form edit siswa
     * Dipanggil oleh: index.php?page=siswa/edit&id=NISN
     */
    public function edit() {
        $nisn = $_GET['id'] ?? null;
        
        if (!$nisn) {
            header("Location: index.php?page=siswa/index");
            exit();
        }

        $data_siswa = $this->siswaModel->readById($nisn);

        if (!$data_siswa) {
            $_SESSION['error_message'] = "Data siswa tidak ditemukan!";
            header("Location: index.php?page=siswa/index");
            exit();
        }

        require_once ROOT_PATH . 'src/views/siswa/edit.php';
    }

    /**
     * UPDATE - Proses update data siswa
     * Dipanggil oleh: index.php?page=siswa/update (POST)
     */
    public function update() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nisn = trim($_POST['nisn']);
            $nama_siswa = trim($_POST['nama_siswa']);
            $jenis_kelamin = $_POST['jenis_kelamin'];
            $tempat_lahir = trim($_POST['tempat_lahir']);
            $tgl_lahir = $_POST['tgl_lahir'];
            $alamat = trim($_POST['alamat']);
            $no_hp = trim($_POST['no_hp']);
            $foto_siswa = 'default.png';

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

    /**
     * DELETE - Proses hapus data siswa
     * Dipanggil oleh: index.php?page=siswa/delete&id=NISN
     */
    public function delete() {
        $nisn = $_GET['id'] ?? null;

        if (!$nisn) {
            header("Location: index.php?page=siswa/index");
            exit();
        }

        if ($this->siswaModel->delete($nisn)) {
            $_SESSION['success_message'] = "Data siswa berhasil dihapus!";
        } else {
            $_SESSION['error_message'] = "Gagal menghapus data siswa!";
        }

        header("Location: index.php?page=siswa/index");
        exit();
    }
}
?>