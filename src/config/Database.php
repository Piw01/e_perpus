<?php
/**
 * Kelas untuk mengelola koneksi database menggunakan PDO.
 */
class Database {
    private const DB_HOST = 'localhost';
    private const DB_NAME = 'perpus_satu';
    private const DB_USER = 'root';
    private const DB_PASS = '';
    private $conn;

    /**
     * @return PDO|null Objek koneksi PDO jika sukses, null jika gagal.
     */
    public function getConnection() {
        $this->conn = null;

        try {
            $dsn = "mysql:host=" . self::DB_HOST . ";dbname=" . self::DB_NAME . ";charset=utf8mb4";
            $this->conn = new PDO($dsn, self::DB_USER, self::DB_PASS);
            // Set error Exception
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        } catch(PDOException $exception) {
            // tampilkan error koneksi
            error_log("Connection error: " . $exception->getMessage());
            die("Koneksi database gagal: " . $exception->getMessage());
        }
        return $this->conn;
    }
}
?>