<?php
class SiswaModel {
    private $conn;
    private $table_name = "siswa";

    public function __construct($db) {
        $this->conn = $db;
    }

    /**
     * READ - Ambil semua data siswa
     */
    public function readAll() {
        $query = "SELECT nisn, nama_siswa, jenis_kelamin, tempat_lahir, 
                  tgl_lahir, alamat, no_hp 
                  FROM " . $this->table_name . " 
                  ORDER BY nama_siswa ASC";
        
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    /**
     * READ - Ambil satu data siswa berdasarkan NISN
     */
    public function readById($nisn) {
        $query = "SELECT * FROM " . $this->table_name . " 
                  WHERE nisn = :nisn LIMIT 1";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':nisn', $nisn);
        $stmt->execute();
        
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * CREATE - Tambah data siswa baru
     */
    public function create($data) {
        $query = "INSERT INTO " . $this->table_name . " 
                  (nisn, nama_siswa, jenis_kelamin, tempat_lahir, tgl_lahir, alamat, no_hp, foto_siswa)
                  VALUES (:nisn, :nama_siswa, :jenis_kelamin, :tempat_lahir, :tgl_lahir, :alamat, :no_hp, :foto_siswa)";
        $stmt = $this->conn->prepare($query);
        
        // Bind parameters
        $stmt->bindParam(':nisn', $data['nisn']);
        $stmt->bindParam(':nama_siswa', $data['nama_siswa']);
        $stmt->bindParam(':jenis_kelamin', $data['jenis_kelamin']);
        $stmt->bindParam(':tempat_lahir', $data['tempat_lahir']);
        $stmt->bindParam(':tgl_lahir', $data['tgl_lahir']);
        $stmt->bindParam(':alamat', $data['alamat']);
        $stmt->bindParam(':no_hp', $data['no_hp']);
        $stmt->bindParam(':foto_siswa', $data['foto_siswa']);
        
        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    /**
     * UPDATE - Ubah data siswa
     */
    public function update($nisn, $data) {
        $query = "UPDATE " . $this->table_name . " SET
                  nama_siswa = :nama_siswa,
                  jenis_kelamin = :jenis_kelamin,
                  tempat_lahir = :tempat_lahir,
                  tgl_lahir = :tgl_lahir,
                  alamat = :alamat,
                  no_hp = :no_hp,
                  foto_siswa = :foto_siswa
                  WHERE nisn = :nisn";
        
        $stmt = $this->conn->prepare($query);
        
        // Bind parameters
        $stmt->bindParam(':nisn', $nisn);
        $stmt->bindParam(':nama_siswa', $data['nama_siswa']);
        $stmt->bindParam(':jenis_kelamin', $data['jenis_kelamin']);
        $stmt->bindParam(':tempat_lahir', $data['tempat_lahir']);
        $stmt->bindParam(':tgl_lahir', $data['tgl_lahir']);
        $stmt->bindParam(':alamat', $data['alamat']);
        $stmt->bindParam(':no_hp', $data['no_hp']);
        $stmt->bindParam(':foto_siswa', $data['foto_siswa']);
        
        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    /**
     * DELETE - Hapus data siswa
     */
    public function delete($nisn) {
        $query = "DELETE FROM " . $this->table_name . " WHERE nisn = :nisn";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':nisn', $nisn);
        
        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    /**
     * CHECK - Cek apakah NISN sudah ada
     */
    public function nisnExists($nisn) {
        $query = "SELECT nisn FROM " . $this->table_name . " 
                  WHERE nisn = :nisn LIMIT 1";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':nisn', $nisn);
        $stmt->execute();
        
        return $stmt->rowCount() > 0;
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