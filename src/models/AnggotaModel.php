<?php
class AnggotaModel {
    private $conn;
    private $table_name = "anggota";

    public function __construct($db) {
        $this->conn = $db;
    }

    public function countAll() {
        $query = "SELECT COUNT(*) as total FROM " . $this->table_name;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row['total'];
    }

    public function readAll() {
        $query = "SELECT * FROM " . $this->table_name . " ORDER BY nama_lengkap ASC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    public function readById($id) {
        $query = "SELECT * FROM " . $this->table_name . " WHERE id_anggota = ? LIMIT 0,1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function create($data) {
        $query = "INSERT INTO " . $this->table_name . " SET
                    no_anggota = :no_anggota,
                    nik = :nik,
                    nama_lengkap = :nama_lengkap,
                    jenis_kelamin = :jenis_kelamin,
                    tempat_lahir = :tempat_lahir,
                    tanggal_lahir = :tanggal_lahir,
                    alamat = :alamat,
                    kelurahan = :kelurahan,
                    kecamatan = :kecamatan,
                    kota = :kota,
                    kode_pos = :kode_pos,
                    no_hp = :no_hp,
                    email = :email,
                    username = :username,
                    password = :password,
                    foto_profil = :foto_profil,
                    pekerjaan = :pekerjaan,
                    instansi = :instansi,
                    status_anggota = :status_anggota,
                    tanggal_daftar = :tanggal_daftar,
                    tanggal_expired = :tanggal_expired";

        $stmt = $this->conn->prepare($query);

        // Sanitize data
        $data['no_anggota'] = htmlspecialchars(strip_tags($data['no_anggota']));
        $data['nik'] = htmlspecialchars(strip_tags($data['nik']));
        $data['nama_lengkap'] = htmlspecialchars(strip_tags($data['nama_lengkap']));
        $data['jenis_kelamin'] = htmlspecialchars(strip_tags($data['jenis_kelamin']));
        $data['tempat_lahir'] = htmlspecialchars(strip_tags($data['tempat_lahir']));
        $data['tanggal_lahir'] = htmlspecialchars(strip_tags($data['tanggal_lahir']));
        $data['alamat'] = htmlspecialchars(strip_tags($data['alamat']));
        $data['kelurahan'] = htmlspecialchars(strip_tags($data['kelurahan']));
        $data['kecamatan'] = htmlspecialchars(strip_tags($data['kecamatan']));
        $data['kota'] = htmlspecialchars(strip_tags($data['kota']));
        $data['kode_pos'] = htmlspecialchars(strip_tags($data['kode_pos']));
        $data['no_hp'] = htmlspecialchars(strip_tags($data['no_hp']));
        $data['email'] = htmlspecialchars(strip_tags($data['email']));
        $data['username'] = htmlspecialchars(strip_tags($data['username']));
        $data['password'] = htmlspecialchars(strip_tags($data['password']));
        $data['foto_profil'] = htmlspecialchars(strip_tags($data['foto_profil']));
        $data['pekerjaan'] = htmlspecialchars(strip_tags($data['pekerjaan']));
        $data['instansi'] = htmlspecialchars(strip_tags($data['instansi']));
        $data['status_anggota'] = htmlspecialchars(strip_tags($data['status_anggota']));
        $data['tanggal_daftar'] = htmlspecialchars(strip_tags($data['tanggal_daftar']));
        $data['tanggal_expired'] = htmlspecialchars(strip_tags($data['tanggal_expired']));


        // Bind values
        $stmt->bindParam(':no_anggota', $data['no_anggota']);
        $stmt->bindParam(':nik', $data['nik']);
        $stmt->bindParam(':nama_lengkap', $data['nama_lengkap']);
        $stmt->bindParam(':jenis_kelamin', $data['jenis_kelamin']);
        $stmt->bindParam(':tempat_lahir', $data['tempat_lahir']);
        $stmt->bindParam(':tanggal_lahir', $data['tanggal_lahir']);
        $stmt->bindParam(':alamat', $data['alamat']);
        $stmt->bindParam(':kelurahan', $data['kelurahan']);
        $stmt->bindParam(':kecamatan', $data['kecamatan']);
        $stmt->bindParam(':kota', $data['kota']);
        $stmt->bindParam(':kode_pos', $data['kode_pos']);
        $stmt->bindParam(':no_hp', $data['no_hp']);
        $stmt->bindParam(':email', $data['email']);
        $stmt->bindParam(':username', $data['username']);
        $stmt->bindParam(':password', $data['password']);
        $stmt->bindParam(':foto_profil', $data['foto_profil']);
        $stmt->bindParam(':pekerjaan', $data['pekerjaan']);
        $stmt->bindParam(':instansi', $data['instansi']);
        $stmt->bindParam(':status_anggota', $data['status_anggota']);
        $stmt->bindParam(':tanggal_daftar', $data['tanggal_daftar']);
        $stmt->bindParam(':tanggal_expired', $data['tanggal_expired']);


        if ($stmt->execute()) {
            return true;
        }

        return false;
    }

    public function update($id, $data) {
        $query = "UPDATE " . $this->table_name . " SET
                    nik = :nik,
                    nama_lengkap = :nama_lengkap,
                    jenis_kelamin = :jenis_kelamin,
                    tempat_lahir = :tempat_lahir,
                    tanggal_lahir = :tanggal_lahir,
                    alamat = :alamat,
                    kelurahan = :kelurahan,
                    kecamatan = :kecamatan,
                    kota = :kota,
                    kode_pos = :kode_pos,
                    no_hp = :no_hp,
                    email = :email,
                    username = :username,
                    pekerjaan = :pekerjaan,
                    instansi = :instansi,
                    status_anggota = :status_anggota
                WHERE id = :id";

        $stmt = $this->conn->prepare($query);

        // Sanitize data
        $data['nik'] = htmlspecialchars(strip_tags($data['nik']));
        $data['nama_lengkap'] = htmlspecialchars(strip_tags($data['nama_lengkap']));
        $data['jenis_kelamin'] = htmlspecialchars(strip_tags($data['jenis_kelamin']));
        $data['tempat_lahir'] = htmlspecialchars(strip_tags($data['tempat_lahir']));
        $data['tanggal_lahir'] = htmlspecialchars(strip_tags($data['tanggal_lahir']));
        $data['alamat'] = htmlspecialchars(strip_tags($data['alamat']));
        $data['kelurahan'] = htmlspecialchars(strip_tags($data['kelurahan']));
        $data['kecamatan'] = htmlspecialchars(strip_tags($data['kecamatan']));
        $data['kota'] = htmlspecialchars(strip_tags($data['kota']));
        $data['kode_pos'] = htmlspecialchars(strip_tags($data['kode_pos']));
        $data['no_hp'] = htmlspecialchars(strip_tags($data['no_hp']));
        $data['email'] = htmlspecialchars(strip_tags($data['email']));
        $data['username'] = htmlspecialchars(strip_tags($data['username']));
        $data['pekerjaan'] = htmlspecialchars(strip_tags($data['pekerjaan']));
        $data['instansi'] = htmlspecialchars(strip_tags($data['instansi']));
        $data['status_anggota'] = htmlspecialchars(strip_tags($data['status_anggota']));
        $id = htmlspecialchars(strip_tags($id));

        // Bind values
        $stmt->bindParam(':nik', $data['nik']);
        $stmt->bindParam(':nama_lengkap', $data['nama_lengkap']);
        $stmt->bindParam(':jenis_kelamin', $data['jenis_kelamin']);
        $stmt->bindParam(':tempat_lahir', $data['tempat_lahir']);
        $stmt->bindParam(':tanggal_lahir', $data['tanggal_lahir']);
        $stmt->bindParam(':alamat', $data['alamat']);
        $stmt->bindParam(':kelurahan', $data['kelurahan']);
        $stmt->bindParam(':kecamatan', $data['kecamatan']);
        $stmt->bindParam(':kota', $data['kota']);
        $stmt->bindParam(':kode_pos', $data['kode_pos']);
        $stmt->bindParam(':no_hp', $data['no_hp']);
        $stmt->bindParam(':email', $data['email']);
        $stmt->bindParam(':username', $data['username']);
        $stmt->bindParam(':pekerjaan', $data['pekerjaan']);
        $stmt->bindParam(':instansi', $data['instansi']);
        $stmt->bindParam(':status_anggota', $data['status_anggota']);
        $stmt->bindParam(':id', $id);

        if ($stmt->execute()) {
            return true;
        }

        return false;
    }

    public function delete($id) {
        $query = "DELETE FROM " . $this->table_name . " WHERE id_anggota = ?";
        $stmt = $this->conn->prepare($query);
        $id = htmlspecialchars(strip_tags($id));
        $stmt->bindParam(1, $id);

        if ($stmt->execute()) {
            return true;
        }

        return false;
    }

    public function generateNoAnggota() {
        $year = date('Y');
        $query = "SELECT MAX(no_anggota) as max_no FROM " . $this->table_name . " WHERE no_anggota LIKE 'ANG-$year%'";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        $max_no = $row['max_no'];

        if ($max_no) {
            $last_number = (int) substr($max_no, -3);
            $next_number = $last_number + 1;
        } else {
            $next_number = 1;
        }

        return 'ANG-' . $year . '-' . sprintf('%03d', $next_number);
    }

    public function usernameExists($username) {
        $query = "SELECT id_anggota FROM " . $this->table_name . " WHERE username = ? LIMIT 0,1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $username);
        $stmt->execute();
        return $stmt->rowCount() > 0;
    }

    public function emailExists($email) {
        $query = "SELECT id_anggota FROM " . $this->table_name . " WHERE email = ? LIMIT 0,1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $email);
        $stmt->execute();
        return $stmt->rowCount() > 0;
    }

    public function nikExists($nik) {
        $query = "SELECT id_anggota FROM " . $this->table_name . " WHERE nik = ? LIMIT 0,1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $nik);
        $stmt->execute();
        return $stmt->rowCount() > 0;
    }
    /**
     * LOGIN - Verifikasi username & password anggota
     */
    public function login($username, $password) {
        $query = "SELECT id_anggota, no_anggota, nama_lengkap, username, password, 
                         status_anggota, tanggal_expired, total_pinjam, denda_aktif,
                         email, no_hp, pekerjaan, instansi, alamat, kota, tanggal_daftar
                  FROM " . $this->table_name . " 
                  WHERE username = :username 
                  AND status_anggota = 'aktif'
                  LIMIT 1";

        $stmt = $this->conn->prepare($query);
        $username = htmlspecialchars(strip_tags($username));
        $stmt->bindParam(':username', $username);
        $stmt->execute();
        
        if ($stmt->rowCount() === 1) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $hashed_password = $row['password'];

            // Verifikasi password
            if (password_verify($password, $hashed_password)) {
                unset($row['password']); // Jangan return password
                return $row; // Return data anggota
            }
        }
        return false;
    }
}
?>