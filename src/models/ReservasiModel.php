<?php
class ReservasiModel {
    private $conn;
    private $table = "reservasi";

    public function __construct($db) {
        $this->conn = $db;
    }

    public function readAll() {
        $query = "SELECT r.*, a.nama_lengkap, a.no_hp, b.judul 
                  FROM {$this->table} r
                  JOIN anggota a ON r.id_anggota = a.id_anggota
                  JOIN buku b ON r.id_buku = b.id_buku
                  ORDER BY r.tanggal_reservasi DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    public function readByAnggota($id_anggota) {
        $query = "SELECT r.*, b.judul, b.isbn 
                  FROM {$this->table} r
                  JOIN buku b ON r.id_buku = b.id_buku
                  WHERE r.id_anggota = :id_anggota
                  ORDER BY r.tanggal_reservasi DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id_anggota', $id_anggota);
        $stmt->execute();
        return $stmt;
    }

    public function create($data) {
        $query = "INSERT INTO {$this->table} 
                  (kode_reservasi, id_anggota, id_buku, tanggal_expired, status)
                  VALUES (:kode, :id_anggota, :id_buku, :expired, :status)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':kode', $data['kode_reservasi']);
        $stmt->bindParam(':id_anggota', $data['id_anggota']);
        $stmt->bindParam(':id_buku', $data['id_buku']);
        $stmt->bindParam(':expired', $data['tanggal_expired']);
        $stmt->bindParam(':status', $data['status']);
        return $stmt->execute();
    }

    public function updateStatus($id, $status) {
        $query = "UPDATE {$this->table} SET status = :status WHERE id_reservasi = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':status', $status);
        return $stmt->execute();
    }

    public function generateKodeReservasi() {
        $prefix = 'RSV-' . date('Ym') . '-';
        $query = "SELECT kode_reservasi FROM {$this->table} 
                  WHERE kode_reservasi LIKE :prefix 
                  ORDER BY kode_reservasi DESC LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $search = $prefix . '%';
        $stmt->bindParam(':prefix', $search);
        $stmt->execute();
        
        if ($stmt->rowCount() > 0) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $last_number = (int) substr($row['kode_reservasi'], -4);
            $new_number = $last_number + 1;
        } else {
            $new_number = 1;
        }
        
        return $prefix . str_pad($new_number, 4, '0', STR_PAD_LEFT);
    }
}
?>