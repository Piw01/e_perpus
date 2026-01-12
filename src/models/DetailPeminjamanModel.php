<?php
class DetailPeminjamanModel {
    private $conn;
    private $table = 'detail_peminjaman';

    public function __construct($db) {
        $this->conn = $db;
    }

    // Add Detail Peminjaman
    public function addBuku($id_peminjaman, $id_buku, $jumlah) {
        // Check apakah sudah ada
        $query_check = "SELECT * FROM " . $this->table . " 
                       WHERE id_peminjaman = :id_peminjaman AND id_buku = :id_buku";
        $stmt_check = $this->conn->prepare($query_check);
        $stmt_check->bindParam(':id_peminjaman', $id_peminjaman);
        $stmt_check->bindParam(':id_buku', $id_buku);
        $stmt_check->execute();
        
        if ($stmt_check->rowCount() > 0) {
            // Update jumlah
            $query = "UPDATE " . $this->table . " SET jumlah_pinjam = jumlah_pinjam + :jumlah
                      WHERE id_peminjaman = :id_peminjaman AND id_buku = :id_buku";
        } else {
            // Insert baru
            $query = "INSERT INTO " . $this->table . " (id_peminjaman, id_buku, jumlah_pinjam)
                      VALUES (:id_peminjaman, :id_buku, :jumlah)";
        }
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id_peminjaman', $id_peminjaman);
        $stmt->bindParam(':id_buku', $id_buku);
        $stmt->bindParam(':jumlah', $jumlah);
        
        return $stmt->execute();
    }

    // Check Stok Buku
    public function checkStok($id_buku, $jumlah_pinjam) {
        $query = "SELECT jumlah FROM buku WHERE id_buku = :id_buku";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id_buku', $id_buku);
        $stmt->execute();
        
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['jumlah'] >= $jumlah_pinjam;
    }

    // Update Stok (Kurangi saat pinjam)
    public function kurangiStok($id_buku, $jumlah) {
        $query = "UPDATE buku SET jumlah = jumlah - :jumlah WHERE id_buku = :id_buku";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':jumlah', $jumlah);
        $stmt->bindParam(':id_buku', $id_buku);
        return $stmt->execute();
    }

    // Update Stok (Tambah saat kembali)
    public function tambahStok($id_buku, $jumlah) {
        $query = "UPDATE buku SET jumlah = jumlah + :jumlah WHERE id_buku = :id_buku";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':jumlah', $jumlah);
        $stmt->bindParam(':id_buku', $id_buku);
        return $stmt->execute();
    }

    // Get Detail Peminjaman
    public function getDetail($id_peminjaman) {
        $query = "SELECT dp.*, b.judul, b.isbn, b.foto_sampul
                  FROM " . $this->table . " dp
                  LEFT JOIN buku b ON dp.id_buku = b.id_buku
                  WHERE dp.id_peminjaman = :id_peminjaman";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id_peminjaman', $id_peminjaman);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Delete Detail (hapus buku dari peminjaman)
    public function deleteBuku($id_peminjaman, $id_buku) {
        $query = "DELETE FROM " . $this->table . " 
                  WHERE id_peminjaman = :id_peminjaman AND id_buku = :id_buku";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id_peminjaman', $id_peminjaman);
        $stmt->bindParam(':id_buku', $id_buku);
        
        return $stmt->execute();
    }
}
?>