<?php
class AdminModel {
    private $conn;
    private $table_name = "admin";

    public function __construct($db) {
        $this->conn = $db;
    }

    /**
     * READ - Ambil semua data admin
     */
    public function readAll() {
        $query = "SELECT id_admin, nama_lengkap, username, level FROM " . $this->table_name . " ORDER BY nama_lengkap ASC";
        
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    /**
     * READ - Ambil satu data admin berdasarkan ID
     */
    public function readById($id_admin) {
        $query = "SELECT id_admin, nama_lengkap, username, level FROM " . $this->table_name . " WHERE id_admin = :id_admin LIMIT 1";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id_admin', $id_admin);
        $stmt->execute();
        
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * CREATE - Tambah data admin baru dengan Level
     */
    public function create($data) {
        $query = "INSERT INTO " . $this->table_name . " 
                  SET nama_lengkap = :nama_lengkap, 
                      username = :username, 
                      password = :password_hashed,
                      level = :level"; // Tambah Level
        
        $stmt = $this->conn->prepare($query);
        
        // Sanitize
        $data['nama_lengkap'] = htmlspecialchars(strip_tags($data['nama_lengkap']));
        $data['username'] = htmlspecialchars(strip_tags($data['username']));
        
        // Bind parameter
        $stmt->bindParam(':nama_lengkap', $data['nama_lengkap']);
        $stmt->bindParam(':username', $data['username']);
        $stmt->bindParam(':password_hashed', $data['password_hashed']);
        $stmt->bindParam(':level', $data['level']); // Bind Level
        
        if ($stmt->execute()) {
            return true;
        }
        return false;
    }
    
    /**
     * UPDATE - Perbarui data admin (termasuk Level)
     */
    public function update($id_admin, $data) {
        // Query dasar update
        $query_set = "nama_lengkap = :nama_lengkap, username = :username, level = :level";
        
        // Tambahkan password jika ada
        if (isset($data['password_hashed']) && !empty($data['password_hashed'])) {
            $query_set .= ", password = :password_hashed";
        }
        
        $query = "UPDATE " . $this->table_name . " 
                  SET " . $query_set . " 
                  WHERE id_admin = :id_admin";

        $stmt = $this->conn->prepare($query);

        // Bind parameter
        $stmt->bindParam(':id_admin', $id_admin);
        $stmt->bindParam(':nama_lengkap', htmlspecialchars(strip_tags($data['nama_lengkap'])));
        $stmt->bindParam(':username', htmlspecialchars(strip_tags($data['username'])));
        $stmt->bindParam(':level', $data['level']); // Bind Level

        if (isset($data['password_hashed']) && !empty($data['password_hashed'])) {
            $stmt->bindParam(':password_hashed', $data['password_hashed']);
        }
        
        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    /**
     * DELETE - Hapus data admin
     */
    public function delete($id_admin) {
        $query = "DELETE FROM " . $this->table_name . " WHERE id_admin = :id_admin";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id_admin', $id_admin);
        if ($stmt->execute()) { return true; }
        return false;
    }

    /**
     * CHECK - Username exists
     */
    public function usernameExists($username, $except_id = null) {
        $query = "SELECT id_admin FROM " . $this->table_name . " WHERE username = :username";
        if ($except_id) { $query .= " AND id_admin != :except_id"; }
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':username', htmlspecialchars(strip_tags($username)));
        if ($except_id) { $stmt->bindParam(':except_id', $except_id); }

        $stmt->execute();
        return $stmt->rowCount() > 0;
    }
    
    /**
     * LOGIN - Ambil data level juga
     */
    public function login($username, $password) {
        // Ambil kolom level juga
        $query = "SELECT id_admin, nama_lengkap, username, password, level 
                  FROM " . $this->table_name . " 
                  WHERE username = :username 
                  LIMIT 1";

        $stmt = $this->conn->prepare($query);
        $username = htmlspecialchars(strip_tags($username));
        $stmt->bindParam(':username', $username);
        $stmt->execute();
        
        if ($stmt->rowCount() === 1) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $hashed_password = $row['password'];

            if (password_verify($password, $hashed_password)) {
                unset($row['password']); 
                return $row; // Mengembalikan array user termasuk ['level']
            }
        }
        return false;
    }
}
?>