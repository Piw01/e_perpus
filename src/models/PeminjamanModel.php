<?php
class PeminjamanModel {
    private $conn;
    private $table = "peminjaman";

    public function __construct($db) {
        $this->conn = $db;
    }


public function readAll() {
        $query = "SELECT p.*, a.nama_lengkap, a.no_anggota, adm.nama_lengkap as admin_nama
                  FROM {$this->table} p
                  JOIN anggota a ON p.id_anggota = a.id_anggota
                  JOIN admin adm ON p.id_admin = adm.id_admin
                  ORDER BY p.tanggal_pinjam DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }
    public function readById($id) {
        $query = "SELECT p.*, a.nama_lengkap, a.no_hp, a.no_anggota
                  FROM {$this->table} p
                  JOIN anggota a ON p.id_anggota = a.id_anggota
                  WHERE p.id_peminjaman = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function create($data) {
        $query = "INSERT INTO {$this->table} 
                  (kode_peminjaman, id_anggota, id_admin, tanggal_pinjam, 
                   tanggal_harus_kembali, durasi_hari, total_buku, status_pinjam)
                  VALUES (:kode, :anggota, :admin, :pinjam, :harus_kembali, 
                          :durasi, :total, :status)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':kode', $data['kode_peminjaman']);
        $stmt->bindParam(':anggota', $data['id_anggota']);
        $stmt->bindParam(':admin', $data['id_admin']);
        $stmt->bindParam(':pinjam', $data['tanggal_pinjam']);
        $stmt->bindParam(':harus_kembali', $data['tanggal_harus_kembali']);
        $stmt->bindParam(':durasi', $data['durasi_hari']);
        $stmt->bindParam(':total', $data['total_buku']);
        $stmt->bindParam(':status', $data['status_pinjam']);
        
        if ($stmt->execute()) {
            return $this->conn->lastInsertId();
        }
        return false;
    }

    public function addDetail($id_peminjaman, $id_buku) {
        $query = "INSERT INTO detail_peminjaman (id_peminjaman, id_buku, kondisi_pinjam)
                  VALUES (:peminjaman, :buku, 'baik')";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':peminjaman', $id_peminjaman);
        $stmt->bindParam(':buku', $id_buku);
        return $stmt->execute();
    }

    public function getDetailBuku($id_peminjaman) {
        $query = "SELECT dp.*, b.judul, b.isbn 
                  FROM detail_peminjaman dp
                  JOIN buku b ON dp.id_buku = b.id_buku
                  WHERE dp.id_peminjaman = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id_peminjaman);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function updateStatus($id, $status, $tanggal_kembali = null) {
        $query = "UPDATE {$this->table} SET status_pinjam = :status";
        if ($tanggal_kembali) {
            $query .= ", tanggal_kembali = :tgl_kembali";
        }
        $query .= " WHERE id_peminjaman = :id";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':status', $status);
        $stmt->bindParam(':id', $id);
        if ($tanggal_kembali) {
            $stmt->bindParam(':tgl_kembali', $tanggal_kembali);
        }
        return $stmt->execute();
    }

    public function getPeminjamanAktif() {
        $query = "SELECT p.*, a.nama_lengkap, a.no_anggota
                  FROM {$this->table} p
                  JOIN anggota a ON p.id_anggota = a.id_anggota
                  WHERE p.status_pinjam = 'dipinjam'
                  ORDER BY p.tanggal_pinjam DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    public function filterByStatus($status) {
        $query = "SELECT p.*, a.nama_lengkap, a.no_anggota
                  FROM {$this->table} p
                  JOIN anggota a ON p.id_anggota = a.id_anggota
                  WHERE p.status_pinjam = :status
                  ORDER BY p.tanggal_pinjam DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':status', $status);
        $stmt->execute();
        return $stmt;
    }

    public function countDipinjam() {
        $query = "SELECT COUNT(*) as total FROM {$this->table} WHERE status_pinjam = 'dipinjam'";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC)['total'];
    }

    public function countTerlambat() {
        $query = "SELECT COUNT(*) as total FROM {$this->table} 
                  WHERE status_pinjam = 'dipinjam' 
                  AND tanggal_harus_kembali < CURDATE()";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC)['total'];
    }

    public function countBulanIni() {
        $query = "SELECT COUNT(*) as total FROM {$this->table} 
                  WHERE MONTH(tanggal_pinjam) = MONTH(CURDATE()) 
                  AND YEAR(tanggal_pinjam) = YEAR(CURDATE())";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC)['total'];
    }

    public function getSetting() {
        $query = "SELECT * FROM setting_perpus LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function generateKodePeminjaman() {
        $prefix = 'PJM-' . date('Ym') . '-';
        $query = "SELECT kode_peminjaman FROM {$this->table} 
                  WHERE kode_peminjaman LIKE :prefix 
                  ORDER BY kode_peminjaman DESC LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $search = $prefix . '%';
        $stmt->bindParam(':prefix', $search);
        $stmt->execute();
        
        if ($stmt->rowCount() > 0) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $last_number = (int) substr($row['kode_peminjaman'], -4);
            $new_number = $last_number + 1;
        } else {
            $new_number = 1;
        }
        
        return $prefix . str_pad($new_number, 4, '0', STR_PAD_LEFT);
    }
}
?>