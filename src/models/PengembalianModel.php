<?php
class PengembalianModel {
    private $conn;
    private $table = "pengembalian";

    public function __construct($db) {
        $this->conn = $db;
    }

    public function readAll() {
        $query = "SELECT pg.*, p.kode_peminjaman, a.nama_lengkap, a.no_anggota
                  FROM {$this->table} pg
                  JOIN peminjaman p ON pg.id_peminjaman = p.id_peminjaman
                  JOIN anggota a ON p.id_anggota = a.id_anggota
                  ORDER BY pg.tanggal_kembali DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    public function readById($id) {
        $query = "SELECT * FROM {$this->table} WHERE id_pengembalian = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function create($data) {
        $query = "INSERT INTO {$this->table} 
                  (kode_pengembalian, id_peminjaman, id_admin, tanggal_kembali,
                   keterlambatan_hari, denda_keterlambatan, denda_kerusakan, 
                   denda_kehilangan, total_denda, status_bayar, jumlah_dibayar, sisa_denda)
                  VALUES (:kode, :peminjaman, :admin, :tgl_kembali, :terlambat,
                          :denda_terlambat, :denda_rusak, :denda_hilang, :total,
                          :status, :dibayar, :sisa)";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':kode', $data['kode_pengembalian']);
        $stmt->bindParam(':peminjaman', $data['id_peminjaman']);
        $stmt->bindParam(':admin', $data['id_admin']);
        $stmt->bindParam(':tgl_kembali', $data['tanggal_kembali']);
        $stmt->bindParam(':terlambat', $data['keterlambatan_hari']);
        $stmt->bindParam(':denda_terlambat', $data['denda_keterlambatan']);
        $stmt->bindParam(':denda_rusak', $data['denda_kerusakan']);
        $stmt->bindParam(':denda_hilang', $data['denda_kehilangan']);
        $stmt->bindParam(':total', $data['total_denda']);
        $stmt->bindParam(':status', $data['status_bayar']);
        $stmt->bindParam(':dibayar', $data['jumlah_dibayar']);
        $stmt->bindParam(':sisa', $data['sisa_denda']);
        
        return $stmt->execute();
    }

    public function bayarDenda($id, $jumlah)
    {
        $query = "SELECT jumlah_dibayar, sisa_denda 
                FROM {$this->table}
                WHERE id_pengembalian = :id";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();

        $data = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$data) {
            return false;
        }

        $dibayar_lama = $data['jumlah_dibayar'];
        $sisa_lama    = $data['sisa_denda'];

        $dibayar_baru = $dibayar_lama + $jumlah;
        $sisa_baru    = $sisa_lama - $jumlah;

        if ($sisa_baru < 0) {
            return false;
        }

        if ($sisa_baru == 0) {
            $status = 'lunas';
        } else {
            $status = 'dicicil';
        }

        $update = "UPDATE {$this->table}
                SET jumlah_dibayar = :dibayar,
                    sisa_denda = :sisa,
                    status_bayar = :status
                WHERE id_pengembalian = :id";

        $stmt = $this->conn->prepare($update);

        $stmt->bindParam(':dibayar', $dibayar_baru);
        $stmt->bindParam(':sisa', $sisa_baru);
        $stmt->bindParam(':status', $status);
        $stmt->bindParam(':id', $id);

        return $stmt->execute();
    }

    public function sumDendaBelumLunas() {
        $query = "SELECT COALESCE(SUM(sisa_denda), 0) as total 
                  FROM {$this->table} WHERE status_bayar != 'lunas'";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC)['total'];
    }

    public function countBulanIni() {
        $query = "SELECT COUNT(*) as total FROM {$this->table} 
                  WHERE MONTH(tanggal_kembali) = MONTH(CURDATE()) 
                  AND YEAR(tanggal_kembali) = YEAR(CURDATE())";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC)['total'];
    }

    public function generateKodePengembalian() {
        $prefix = 'KMB-' . date('Ym') . '-';
        $query = "SELECT kode_pengembalian FROM {$this->table} 
                  WHERE kode_pengembalian LIKE :prefix 
                  ORDER BY kode_pengembalian DESC LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $search = $prefix . '%';
        $stmt->bindParam(':prefix', $search);
        $stmt->execute();
        
        if ($stmt->rowCount() > 0) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $last_number = (int) substr($row['kode_pengembalian'], -4);
            $new_number = $last_number + 1;
        } else {
            $new_number = 1;
        }
        
        return $prefix . str_pad($new_number, 4, '0', STR_PAD_LEFT);
    }
}
?>