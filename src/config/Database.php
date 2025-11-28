<?php
// src/config/Database.php

/**
 * Kelas untuk mengelola koneksi database menggunakan PDO.
 * Catatan: Ganti nilai konstanta sesuai dengan konfigurasi database lokal Anda.
 */
class Database {
    // Konfigurasi Database (Ganti sesuai lingkungan Anda)
    private const DB_HOST = 'localhost';
    private const DB_NAME = 'perpus_satu'; // Sesuai dengan perpus_satu_ori.sql
    private const DB_USER = 'root'; // Username default XAMPP/WAMP
    private const DB_PASS = '';     // Password default XAMPP/WAMP
    private $conn;

    /**
     * Mendapatkan koneksi database.
     * @return PDO|null Objek koneksi PDO jika sukses, null jika gagal.
     */
    public function getConnection() {
        $this->conn = null;

        try {
            // Gunakan DSN untuk koneksi MySQL via PDO
            $dsn = "mysql:host=" . self::DB_HOST . ";dbname=" . self::DB_NAME . ";charset=utf8mb4";
            
            $this->conn = new PDO($dsn, self::DB_USER, self::DB_PASS);
            
            // Set error mode ke Exception
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            
            // Set mode fetch default ke asosiatif (array dengan nama kolom)
            $this->conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

        } catch(PDOException $exception) {
            // Log atau tampilkan error koneksi (HANYA untuk development)
            error_log("Connection error: " . $exception->getMessage());
            die("Koneksi database gagal: " . $exception->getMessage());
        }

        return $this->conn;
    }
}
?>