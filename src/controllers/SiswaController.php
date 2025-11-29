<?php
// src/controllers/SiswaController.php

// Pastikan file-file yang dibutuhkan di-load
require_once ROOT_PATH . 'src/models/SiswaModel.php';
require_once ROOT_PATH . 'src/config/Database.php';

class SiswaController {
    private $siswaModel;

    public function __construct() {
        // Mulai session jika belum dimulai (PENTING untuk pesan flash dan level)
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        
        // Inisialisasi Model
        $database = new Database();
        $db = $database->getConnection();
        $this->siswaModel = new SiswaModel($db);
    }
    
    /**
     * Helper untuk membatasi akses CUD
     */
    private function checkAccess() {
        // Hanya izinkan Level < 3 (misal: Admin/Super Admin)
        if (!isset($_SESSION['level']) || $_SESSION['level'] == 3) {
            $_SESSION['error_message'] = "Akses ditolak! Anda tidak memiliki izin untuk melakukan aksi ini.";
            header("Location: index.php?page=siswa/index");
            exit();
        }
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
    public function create(): void {
        $this->checkAccess(); // Tambahkan Pengecekan Akses
        require_once ROOT_PATH . 'src/views/siswa/create.php';
    }

    /**
     * STORE - Proses simpan data siswa baru
     * Dipanggil oleh: index.php?page=siswa/store (POST)
     */
    public function store() {
        $this->checkAccess(); // Tambahkan Pengecekan Akses
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Ambil data dari form
            $nisn = trim($_POST['nisn']);
            $nama_siswa = trim($_POST['nama_siswa']);
            $jenis_kelamin = trim($_POST['jenis_kelamin']);
            $tempat_lahir = trim($_POST['tempat_lahir']);
            $tgl_lahir = trim($_POST['tgl_lahir']);
            $alamat = trim($_POST['alamat']);
            $no_hp = trim($_POST['no_hp']);
            
            // Proses Upload Foto (Contoh sederhana, tambahkan validasi yang lebih ketat!)
            $foto_siswa = null;
            if (isset($_FILES['foto_siswa']) && $_FILES['foto_siswa']['error'] === UPLOAD_ERR_OK) {
                // Di sini Anda perlu memproses upload file dan mendapatkan nama filenya
                // Karena logika upload tidak ada, ini adalah placeholder.
                // Asumsi: Proses upload sukses dan mengembalikan nama file atau null
                $foto_siswa = 'placeholder_foto.jpg'; // Ganti dengan logika upload file sesungguhnya
            }


            // Cek apakah NISN sudah ada
            if ($this->siswaModel->nisnExists($nisn)) {
                $_SESSION['error_message'] = "NISN $nisn sudah terdaftar!";
                header("Location: index.php?page=siswa/create");
                exit();
            }

            // Buat array data
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

            // Panggil method create di Model
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
            // Jika diakses tidak melalui POST, arahkan ke index
            header("Location: index.php?page=siswa/index");
            exit();
        }
    }

    /**
     * EDIT - Tampilkan form edit siswa
     * Dipanggil oleh: index.php?page=siswa/edit&id=NISN
     */
    public function edit() {
        $this->checkAccess(); // Tambahkan Pengecekan Akses
        
        $nisn = $_GET['id'] ?? null;
        $siswa = $this->siswaModel->readById($nisn);

        if (!$siswa) {
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
        $this->checkAccess(); // Tambahkan Pengecekan Akses
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Ambil data dari form
            $nisn = trim($_POST['nisn']); // NISN as key, tidak boleh berubah
            $nama_siswa = trim($_POST['nama_siswa']);
            $jenis_kelamin = trim($_POST['jenis_kelamin']);
            $tempat_lahir = trim($_POST['tempat_lahir']);
            $tgl_lahir = trim($_POST['tgl_lahir']);
            $alamat = trim($_POST['alamat']);
            $no_hp = trim($_POST['no_hp']);
            
            // Penanganan foto (jika ada file baru diupload)
            $foto_siswa = $_POST['foto_lama'] ?? null; // Ambil foto lama
            
            if (isset($_FILES['foto_siswa']) && $_FILES['foto_siswa']['error'] === UPLOAD_ERR_OK) {
                // Proses upload file baru
                // Asumsi: Proses upload sukses dan mengembalikan nama file
                $foto_siswa = 'placeholder_foto_baru.jpg'; // Ganti dengan logika upload file sesungguhnya
            }


            // Buat array data
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
        $this->checkAccess(); // Tambahkan Pengecekan Akses
        
        $nisn = $_GET['id'] ?? null;

        if (!$nisn) {
            header("Location: index.php?page=siswa/index");
            exit();
        }

        if ($this->siswaModel->delete($nisn)) {
            $_SESSION['success_message'] = "Data siswa berhasil dihapus!";
            header("Location: index.php?page=siswa/index");
            exit();
        } else {
            $_SESSION['error_message'] = "Gagal menghapus data siswa. Mungkin data ini digunakan di tabel lain.";
            header("Location: index.php?page=siswa/index");
            exit();
        }
    }
}