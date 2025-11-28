<?php

class PenulisModel {
    private $conn;
    private $table_name = "penulis";

    public function __construct($db) {
        $this->conn = $db;
    }

    public function readAll() {
        $query = "SELECT id_penulis, nama_penulis FROM " . $this->table_name . " 
                  ORDER BY nama_penulis ASC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    public function readById($id_penulis) {
        $query = "SELECT * FROM " . $this->table_name . " 
                  WHERE id_penulis = :id_penulis LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id_penulis', $id_penulis);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function create($data) {
        $query = "INSERT INTO " . $this->table_name . " SET
                  nama_penulis = :nama_penulis";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':nama_penulis', $data['nama_penulis']);
        return $stmt->execute();
    }

    public function update($id_penulis, $data) {
        $query = "UPDATE " . $this->table_name . " SET
                  nama_penulis = :nama_penulis
                  WHERE id_penulis = :id_penulis";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id_penulis', $id_penulis);
        $stmt->bindParam(':nama_penulis', $data['nama_penulis']);
        return $stmt->execute();
    }

    public function delete($id_penulis) {
        $query = "DELETE FROM " . $this->table_name . " WHERE id_penulis = :id_penulis";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id_penulis', $id_penulis);
        return $stmt->execute();
    }

    public function namaPenulisExists($nama_penulis) {
        $query = "SELECT id_penulis FROM " . $this->table_name . " 
                  WHERE nama_penulis = :nama_penulis LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':nama_penulis', $nama_penulis);
        $stmt->execute();
        return $stmt->rowCount() > 0;
    }
}
?>
