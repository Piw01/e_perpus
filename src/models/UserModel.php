<?php
// src/models/UserModel.php

class UserModel {
    private $conn;
    private $table_name = "admin";

    public function __construct($db) {
        $this->conn = $db;
    }

    /**
     * Metode untuk mencari admin berdasarkan username dan memverifikasi password.
     * Menggunakan PDO dan password_verify() untuk keamanan.
     */
    public function login($username, $password) {
        // Query menggunakan Prepared Statement untuk mencegah SQL Injection
        $query = "SELECT id_admin, nama_lengkap, username, password FROM "
                 . $this->table_name 
                 . " WHERE username = :username LIMIT 0,1";

        $stmt = $this->conn->prepare($query);

        // Bind parameter
        $stmt->bindParam(':username', $username);
        $stmt->execute();

        // Ambil data user
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        

        if ($row) {
            
            // Verifikasi password (asumsi password sudah di-hash saat registrasi)
            if (password_verify($password, $row['password'])) {
                // Login berhasil
                return $row;
            } 
            // Untuk simulasi sederhana tanpa hashing:
            if ($password === $row['password']) {
                 return $row;
            }
        }
        
        // Login gagal
        return false;
    }
}
?>