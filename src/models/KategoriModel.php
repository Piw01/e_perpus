<?php
class KategoriModel {
    private $conn;
    private $table_name = "kategori";

    public function __construct($db) {
        $this->conn = $db;
    }

    /**
     * READ - Ambil semua data kategori
     */
    public function readAll() {
        $query = "SELECT id_kategori, nama_kategori FROM " . $this->table_name . " 
                  ORDER BY nama_kategori ASC";
        
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    /**
     * READ - Ambil satu kategori berdasarkan ID
     */
    public function readById($id_kategori) {
        $query = "SELECT * FROM " . $this->table_name . " 
                  WHERE id_kategori = :id_kategori LIMIT 1";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id_kategori', $id_kategori);
        $stmt->execute();
        
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * CREATE - Tambah kategori baru
     */
    public function create($data) {
        $query = "INSERT INTO " . $this->table_name . " SET
                  nama_kategori = :nama_kategori";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':nama_kategori', $data['nama_kategori']);
        
        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    /**
     * UPDATE - Ubah data kategori
     */
    public function update($id_kategori, $data) {
        $query = "UPDATE " . $this->table_name . " SET
                  nama_kategori = :nama_kategori
                  WHERE id_kategori = :id_kategori";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id_kategori', $id_kategori);
        $stmt->bindParam(':nama_kategori', $data['nama_kategori']);
        
        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    /**
     * DELETE - Hapus kategori
     */
    public function delete($id_kategori) {
        $query = "DELETE FROM " . $this->table_name . " WHERE id_kategori = :id_kategori";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id_kategori', $id_kategori);
        
        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    /**
     * CHECK - Cek apakah nama kategori sudah ada
     */
    public function namaKategoriExists($nama_kategori) {
        $query = "SELECT id_kategori FROM " . $this->table_name . " 
                  WHERE nama_kategori = :nama_kategori LIMIT 1";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':nama_kategori', $nama_kategori);
        $stmt->execute();
        
        return $stmt->rowCount() > 0;
    }
}
?>