<?php
/**
 * BukuController - DENGAN FITUR UPLOAD GAMBAR YANG BENAR
 * 
 * PENJELASAN UNTUK PEMULA:
 * Controller ini mengatur semua yang berhubungan dengan data buku, termasuk:
 * - Menampilkan daftar buku
 * - Menambah buku baru (dengan upload gambar)
 * - Mengedit buku (dengan upload gambar baru)
 * - Menghapus buku (termasuk file gambarnya)
 */

require_once ROOT_PATH . 'src/models/BukuModel.php';
require_once ROOT_PATH . 'src/config/Database.php';

class BukuController {
    private $bukuModel;
    
    // PENTING: Path ini RELATIF dari folder 'public/'
    // Artinya gambar akan disimpan di: public/assets/img/sampul_buku/
    private $upload_dir = 'assets/img/sampul_buku/';
    
    private $user_level;

    public function __construct() {
        // Mulai session untuk cek login
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        
        // Ambil level user dari session (1=Super Admin, 2=Admin, 3=Operator)
        $this->user_level = $_SESSION['level'] ?? 0;

        // Koneksi ke database
        $database = new Database();
        $db = $database->getConnection();
        $this->bukuModel = new BukuModel($db);
    }

    /**
     * MENAMPILKAN DAFTAR SEMUA BUKU
     */
    public function index() {
        $stmt = $this->bukuModel->readAll();
        $data_buku = $stmt->fetchAll(PDO::FETCH_ASSOC);
        require_once ROOT_PATH . 'src/views/buku/index.php';
    }

    /**
     * MENAMPILKAN FORM TAMBAH BUKU BARU
     * Operator (level 3) TIDAK BOLEH akses halaman ini
     */
    public function create() {
        // CEK AKSES: Hanya Super Admin (1) dan Admin (2) yang boleh tambah buku
        if ($this->user_level == 3) {
            $_SESSION['error_message'] = "❌ Operator tidak diizinkan menambah data buku.";
            header("Location: index.php?page=buku/index");
            exit();
        }

        // Ambil data untuk dropdown
        $kategori = $this->bukuModel->getKategori();
        $penulis = $this->bukuModel->getPenulis();
        $penerbit = $this->bukuModel->getPenerbit();
        
        require_once ROOT_PATH . 'src/views/buku/create.php';
    }

    /**
     * MENYIMPAN BUKU BARU KE DATABASE
     * Termasuk upload gambar sampul
     */
    public function store() {
        // CEK AKSES
        if ($this->user_level == 3) {
            header("Location: index.php?page=buku/index");
            exit();
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: index.php?page=buku/create");
            exit();
        }

        // AMBIL DATA DARI FORM
        $isbn = trim($_POST['isbn'] ?? '');
        $judul = trim($_POST['judul'] ?? '');
        $id_penulis = $_POST['id_penulis'] ?? null;
        $id_penerbit = $_POST['id_penerbit'] ?? null;
        $id_kategori = $_POST['id_kategori'] ?? null;
        $tahun_terbit = $_POST['tahun_terbit'] ?? null;
        $sinopsis = trim($_POST['sinopsis'] ?? '');
        $jumlah = $_POST['jumlah'] ?? 0;

        // VALIDASI: Pastikan field penting tidak kosong
        if (empty($isbn) || empty($judul) || empty($id_penulis) || 
            empty($id_penerbit) || empty($id_kategori) || empty($tahun_terbit)) {
            $_SESSION['error_message'] = "❌ Semua field wajib diisi!";
            header("Location: index.php?page=buku/create");
            exit();
        }

        // CEK: Apakah ISBN sudah ada di database?
        if ($this->bukuModel->isbnExists($isbn)) {
            $_SESSION['error_message'] = "❌ ISBN $isbn sudah terdaftar!";
            header("Location: index.php?page=buku/create");
            exit();
        }

        // PROSES UPLOAD GAMBAR
        $foto_sampul = 'default_book.jpg'; // Default jika tidak upload
        
        if (isset($_FILES['foto_sampul']) && $_FILES['foto_sampul']['error'] == 0) {
            $upload_result = $this->handleFileUpload($_FILES['foto_sampul']);
            
            // Cek hasil upload
            if (is_string($upload_result)) {
                // Berhasil - $upload_result berisi nama file
                $foto_sampul = $upload_result;
            } else {
                // Gagal - tampilkan error
                $_SESSION['error_message'] = $upload_result['error'];
                header("Location: index.php?page=buku/create");
                exit();
            }
        }
        
        // SIAPKAN DATA UNTUK DISIMPAN
        $data = [
            'isbn' => $isbn,
            'judul' => $judul,
            'id_penulis' => $id_penulis,
            'id_penerbit' => $id_penerbit,
            'id_kategori' => $id_kategori,
            'tahun_terbit' => $tahun_terbit,
            'sinopsis' => $sinopsis,
            'jumlah' => $jumlah,
            'foto_sampul' => $foto_sampul  // HANYA NAMA FILE (bukan path lengkap)
        ];

        // SIMPAN KE DATABASE
        if ($this->bukuModel->create($data)) {
            $_SESSION['success_message'] = "✅ Buku berhasil ditambahkan!";
            header("Location: index.php?page=buku/index");
            exit();
        } else {
            $_SESSION['error_message'] = "❌ Gagal menambah buku.";
            header("Location: index.php?page=buku/create");
            exit();
        }
    }

    /**
     * MENAMPILKAN FORM EDIT BUKU
     */
    public function edit() {
        // CEK AKSES
        if ($this->user_level == 3) {
            $_SESSION['error_message'] = "❌ Operator tidak diizinkan mengedit data buku.";
            header("Location: index.php?page=buku/index");
            exit();
        }

        $id_buku = $_GET['id'] ?? null;
        if (!$id_buku) {
            header("Location: index.php?page=buku/index");
            exit();
        }

        // Ambil data buku yang akan diedit
        $buku = $this->bukuModel->readById($id_buku);
        if (!$buku) {
            header("Location: index.php?page=buku/index");
            exit();
        }

        // Ambil data untuk dropdown
        $kategori = $this->bukuModel->getKategori();
        $penulis = $this->bukuModel->getPenulis();
        $penerbit = $this->bukuModel->getPenerbit();
        
        require_once ROOT_PATH . 'src/views/buku/edit.php';
    }

    /**
     * UPDATE DATA BUKU
     * Bisa upload gambar baru atau tetap pakai gambar lama
     */
    public function update() {
        // CEK AKSES
        if ($this->user_level == 3) {
            header("Location: index.php?page=buku/index");
            exit();
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: index.php?page=buku/index");
            exit();
        }

        // AMBIL DATA DARI FORM
        $id_buku = $_POST['id_buku'] ?? null;
        $isbn = trim($_POST['isbn'] ?? '');
        $judul = trim($_POST['judul'] ?? '');
        $id_penulis = $_POST['id_penulis'] ?? null;
        $id_penerbit = $_POST['id_penerbit'] ?? null;
        $id_kategori = $_POST['id_kategori'] ?? null;
        $tahun_terbit = $_POST['tahun_terbit'] ?? null;
        $sinopsis = trim($_POST['sinopsis'] ?? '');
        $jumlah = $_POST['jumlah'] ?? 0;
        $foto_sampul_lama = $_POST['foto_sampul_lama'] ?? 'default_book.jpg';

        if (!$id_buku || empty($isbn)) {
            $_SESSION['error_message'] = "❌ Data tidak valid.";
            header("Location: index.php?page=buku/edit&id=$id_buku");
            exit();
        }

        // CEK: Apakah ISBN sudah dipakai buku lain?
        if ($this->bukuModel->isbnExists($isbn, $id_buku)) {
            $_SESSION['error_message'] = "❌ ISBN sudah dipakai buku lain.";
            header("Location: index.php?page=buku/edit&id=$id_buku");
            exit();
        }

        // PROSES UPLOAD GAMBAR BARU (jika ada)
        $foto_sampul_baru = $foto_sampul_lama; // Default: pakai gambar lama
        $file_uploaded = false;
        
        if (isset($_FILES['foto_sampul']) && $_FILES['foto_sampul']['error'] == 0) {
            $upload_result = $this->handleFileUpload($_FILES['foto_sampul']);
            
            if (is_string($upload_result)) {
                // Berhasil upload gambar baru
                $foto_sampul_baru = $upload_result;
                $file_uploaded = true;
            }
            // Jika gagal upload, tetap pakai gambar lama
        }
        
        // SIAPKAN DATA UNTUK UPDATE
        $data = [
            'isbn' => $isbn,
            'judul' => $judul,
            'id_penulis' => $id_penulis,
            'id_penerbit' => $id_penerbit,
            'id_kategori' => $id_kategori,
            'tahun_terbit' => $tahun_terbit,
            'sinopsis' => $sinopsis,
            'jumlah' => $jumlah,
            'foto_sampul' => $file_uploaded ? $foto_sampul_baru : null
        ];

        // UPDATE DATABASE
        if ($this->bukuModel->update($id_buku, $data)) {
            // Jika upload gambar baru berhasil, hapus gambar lama
            if ($file_uploaded && !empty($foto_sampul_lama) && 
                $foto_sampul_lama !== 'default_book.jpg') {
                $this->deleteFile($foto_sampul_lama);
            }
            
            $_SESSION['success_message'] = "✅ Buku berhasil diperbarui!";
            header("Location: index.php?page=buku/index");
            exit();
        } else {
            $_SESSION['error_message'] = "❌ Gagal memperbarui buku.";
            header("Location: index.php?page=buku/edit&id=$id_buku");
            exit();
        }
    }

    /**
     * HAPUS BUKU
     * Termasuk hapus file gambarnya
     */
    public function delete() {
        // CEK AKSES
        if ($this->user_level == 3) {
            $_SESSION['error_message'] = "❌ Operator tidak diizinkan menghapus data buku.";
            header("Location: index.php?page=buku/index");
            exit();
        }

        $id_buku = $_GET['id'] ?? null;
        if (!$id_buku) {
            header("Location: index.php?page=buku/index");
            exit();
        }
        
        // Ambil nama file gambar sebelum dihapus
        $foto_sampul = $this->bukuModel->getFotoSampul($id_buku);
        
        // Hapus dari database
        if ($this->bukuModel->delete($id_buku)) {
            // Hapus file gambar (kecuali default)
            if (!empty($foto_sampul) && $foto_sampul !== 'default_book.jpg') {
                $this->deleteFile($foto_sampul);
            }
            
            $_SESSION['success_message'] = "✅ Buku berhasil dihapus.";
            header("Location: index.php?page=buku/index");
            exit();
        } else {
            $_SESSION['error_message'] = "❌ Gagal menghapus buku.";
            header("Location: index.php?page=buku/index");
            exit();
        }
    }

    /**
     * =====================================================
     * PRIVATE METHOD: HANDLE UPLOAD FILE
     * =====================================================
     * 
     * PENJELASAN:
     * Method ini memproses upload file gambar dengan validasi:
     * 1. Cek format file (harus JPG, JPEG, PNG, atau GIF)
     * 2. Cek ukuran file (maksimal 5MB)
     * 3. Buat nama file unik agar tidak bentrok
     * 4. Upload ke folder public/assets/img/sampul_buku/
     * 5. Return nama file jika berhasil, atau array error jika gagal
     */
    private function handleFileUpload($file) {
        // FORMAT FILE YANG DIIZINKAN
        $allowed = ['jpg', 'jpeg', 'png', 'gif'];
        $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        
        // VALIDASI 1: CEK FORMAT FILE
        if (!in_array($ext, $allowed)) {
            return ['error' => "❌ Format file salah! Gunakan JPG, JPEG, PNG, atau GIF."];
        }
        
        // VALIDASI 2: CEK UKURAN FILE (max 5MB = 5*1024*1024 bytes)
        if ($file['size'] > 5*1024*1024) {
            return ['error' => "❌ File terlalu besar! Maksimal 5MB."];
        }
        
        // BUAT NAMA FILE UNIK
        // Contoh: sampul_63f8a1b4c2e3f.jpg
        $new_name = uniqid('sampul_', true) . '.' . $ext;
        
        // TENTUKAN PATH LENGKAP UPLOAD
        $upload_path = ROOT_PATH . 'public/' . $this->upload_dir;
        
        // BUAT FOLDER JIKA BELUM ADA
        if (!is_dir($upload_path)) {
            mkdir($upload_path, 0755, true);
            echo "<!-- Folder dibuat: $upload_path -->";
        }
        
        // UPLOAD FILE
        $full_path = $upload_path . $new_name;
        if (move_uploaded_file($file['tmp_name'], $full_path)) {
            // BERHASIL - return HANYA nama file (BUKAN path lengkap)
            echo "<!-- Upload berhasil: $new_name ke $full_path -->";
            return $new_name;
        }
        
        // GAGAL UPLOAD
        return ['error' => "❌ Gagal upload file ke server. Cek permission folder."];
    }

    /**
     * HAPUS FILE GAMBAR DARI SERVER
     */
    private function deleteFile($file_name) {
        $path = ROOT_PATH . 'public/' . $this->upload_dir . $file_name;
        if (file_exists($path)) {
            unlink($path);
            echo "<!-- File dihapus: $path -->";
        }
    }
}
?>