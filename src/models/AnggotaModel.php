<?php
class AnggotaModel {
    private $conn;
    private $table_name = "anggota"; // sesuaikan nama tabel

    public function __construct($db) {
        $this->conn = $db;
    }

    // ... method lainnya
}
?>