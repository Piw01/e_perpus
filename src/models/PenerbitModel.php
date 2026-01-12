<?php
class PenerbitModel {
    private $conn;
    private $table_name = "penerbit";

    public function __construct($db) {
        $this->conn = $db;
    }

    public function readAll() {
        $query = "SELECT id_penerbit, nama_penerbit, kota FROM " . $this->table_name . " 
                  ORDER BY nama_penerbit ASC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    public function readById($id_penerbit) {
        $query = "SELECT * FROM " . $this->table_name . " 
                  WHERE id_penerbit = :id_penerbit LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id_penerbit', $id_penerbit);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function create($data) {
        $query = "INSERT INTO " . $this->table_name . " SET
                  nama_penerbit = :nama_penerbit,
                  kota = :kota";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':nama_penerbit', $data['nama_penerbit']);
        $stmt->bindParam(':kota', $data['kota']);
        return $stmt->execute();
    }

    public function update($id_penerbit, $data) {
        $query = "UPDATE " . $this->table_name . " SET
                  nama_penerbit = :nama_penerbit,
                  kota = :kota
                  WHERE id_penerbit = :id_penerbit";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id_penerbit', $id_penerbit);
        $stmt->bindParam(':nama_penerbit', $data['nama_penerbit']);
        $stmt->bindParam(':kota', $data['kota']);
        return $stmt->execute();
    }

    public function delete($id_penerbit) {
        $query = "DELETE FROM " . $this->table_name . " WHERE id_penerbit = :id_penerbit";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id_penerbit', $id_penerbit);
        return $stmt->execute();
    }

    public function namaPenerbitExists($nama_penerbit) {
        $query = "SELECT id_penerbit FROM " . $this->table_name . " 
                  WHERE nama_penerbit = :nama_penerbit LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':nama_penerbit', $nama_penerbit);
        $stmt->execute();
        return $stmt->rowCount() > 0;
    }
}
?>


