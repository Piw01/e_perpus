<?php
class BukuModel {
    private $conn;
    private $table_name = "buku";

    public function __construct($db) {
        $this->conn = $db;
    }

    /**
     * READ - Ambil semua data buku dengan JOIN
     */
    public function readAll() {
        $query = "SELECT 
            b.id_buku, b.isbn, b.judul, b.tahun_terbit, b.jumlah, b.foto_sampul,
            k.nama_kategori, pen.nama_penulis, per.nama_penerbit 
            FROM " . $this->table_name . " b
            LEFT JOIN kategori k ON b.id_kategori = k.id_kategori
            LEFT JOIN penulis pen ON b.id_penulis = pen.id_penulis
            LEFT JOIN penerbit per ON b.id_penerbit = per.id_penerbit
            ORDER BY b.judul ASC";
            
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    /**
     * READ - Ambil satu buku berdasarkan ID
     */
    public function readById($id_buku) {
        $query = "SELECT * FROM " . $this->table_name . " 
                  WHERE id_buku = :id_buku LIMIT 1";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id_buku', $id_buku);
        $stmt->execute();
        
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * CREATE - Tambah buku baru
     */
    public function create($data) {
        $query = "INSERT INTO " . $this->table_name . " 
                  (isbn, judul, id_penulis, id_penerbit, id_kategori, tahun_terbit, sinopsis, jumlah, foto_sampul) 
                  VALUES (:isbn, :judul, :id_penulis, :id_penerbit, :id_kategori, :tahun_terbit, :sinopsis, :jumlah, :foto_sampul)";
        
        $stmt = $this->conn->prepare($query);
        
        $isbn_sanitized = htmlspecialchars(strip_tags($data['isbn']));
        $judul_sanitized = htmlspecialchars(strip_tags($data['judul']));
        
        $stmt->bindParam(':isbn', $isbn_sanitized);
        $stmt->bindParam(':judul', $judul_sanitized);
        $stmt->bindParam(':id_penulis', $data['id_penulis']);
        $stmt->bindParam(':id_penerbit', $data['id_penerbit']);
        $stmt->bindParam(':id_kategori', $data['id_kategori']);
        $stmt->bindParam(':tahun_terbit', $data['tahun_terbit']);
        $stmt->bindParam(':sinopsis', $data['sinopsis']);
        $stmt->bindParam(':jumlah', $data['jumlah']);
        $stmt->bindParam(':foto_sampul', $data['foto_sampul']);
        
        
        $query = "INSERT INTO " . $this->table_name . " 
                  (id_buku, isbn, judul, id_penulis, id_penerbit, id_kategori, tahun_terbit, sinopsis, jumlah, foto_sampul) 
                  VALUES (:id_buku, :isbn, :judul, :id_penulis, :id_penerbit, :id_kategori, :tahun_terbit, :sinopsis, :jumlah, :foto_sampul)";
        $stmt->bindParam(':id_buku', $data['id_buku']);
        
        
        if ($stmt->execute()) {
            return true;
        }
        return false;
    }
    
    /**
     * UPDATE - Perbarui buku
     */
    public function update($id_buku, $data) {
        $query = "UPDATE " . $this->table_name . " SET 
                  isbn = :isbn, 
                  judul = :judul, 
                  id_penulis = :id_penulis, 
                  id_penerbit = :id_penerbit, 
                  id_kategori = :id_kategori, 
                  tahun_terbit = :tahun_terbit, 
                  sinopsis = :sinopsis, 
                  jumlah = :jumlah" .
                  (!empty($data['foto_sampul']) ? ", foto_sampul = :foto_sampul" : "") .
                  " WHERE id_buku = :id_buku";
        
        $stmt = $this->conn->prepare($query);
        
        $isbn_sanitized = htmlspecialchars(strip_tags($data['isbn']));
        $judul_sanitized = htmlspecialchars(strip_tags($data['judul']));
        
        $stmt->bindParam(':id_buku', $id_buku);
        $stmt->bindParam(':isbn', $isbn_sanitized);
        $stmt->bindParam(':judul', $judul_sanitized);
        $stmt->bindParam(':id_penulis', $data['id_penulis']);
        $stmt->bindParam(':id_penerbit', $data['id_penerbit']);
        $stmt->bindParam(':id_kategori', $data['id_kategori']);
        $stmt->bindParam(':tahun_terbit', $data['tahun_terbit']);
        $stmt->bindParam(':sinopsis', $data['sinopsis']);
        $stmt->bindParam(':jumlah', $data['jumlah']);
        
        if (!empty($data['foto_sampul'])) {
            $stmt->bindParam(':foto_sampul', $data['foto_sampul']);
        }
        
        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    /**
     * DELETE - Hapus buku
     */
    public function delete($id_buku) {
        $query = "DELETE FROM " . $this->table_name . " WHERE id_buku = :id_buku";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id_buku', $id_buku);
        
        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    /**
     * CHECK - Cek apakah ID buku ada
     */
    public function idExists($id_buku) {
        $query = "SELECT id_buku FROM " . $this->table_name . " 
                  WHERE id_buku = :id_buku LIMIT 1";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id_buku', $id_buku);
        $stmt->execute();
        
        return $stmt->rowCount() > 0;
    }
    
    /**
     * CHECK - Cek apakah ISBN sudah ada
     */
    public function isbnExists($isbn, $except_id = null) {
        $query = "SELECT id_buku FROM " . $this->table_name . " WHERE isbn = :isbn";
        
        if ($except_id) {
            $query .= " AND id_buku != :except_id";
        }
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':isbn', $isbn);
        
        if ($except_id) {
            $stmt->bindParam(':except_id', $except_id);
        }
        
        $stmt->execute();
        
        return $stmt->rowCount() > 0;
    }

    // --- Dropdown Data ---
    public function getKategori() {
        $query = "SELECT id_kategori, nama_kategori FROM kategori ORDER BY nama_kategori ASC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getPenulis() {
        $query = "SELECT id_penulis, nama_penulis FROM penulis ORDER BY nama_penulis ASC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getPenerbit() {
        $query = "SELECT id_penerbit, nama_penerbit FROM penerbit ORDER BY nama_penerbit ASC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getFotoSampul($id_buku) {
        $query = "SELECT foto_sampul FROM " . $this->table_name . " WHERE id_buku = :id_buku LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id_buku', $id_buku);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ? $row['foto_sampul'] : null;
    }

    public function countAll() {
        $query = "SELECT COUNT(*) as total FROM " . $this->table_name;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['total']; 
    }
}
?>