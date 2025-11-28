<?php

class PenerbitController {
    private $penerbitModel;

    public function __construct() {
        $database = new Database();
        $db = $database->getConnection();
        $this->penerbitModel = new PenerbitModel($db);
    }

    public function index() {
        $stmt = $this->penerbitModel->readAll();
        $data_penerbit = $stmt->fetchAll(PDO::FETCH_ASSOC);
        require_once ROOT_PATH . 'src/views/penerbit/index.php';
    }

    public function create() {
        require_once ROOT_PATH . 'src/views/penerbit/create.php';
    }

    public function store() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nama_penerbit = trim($_POST['nama_penerbit']);
            $kota = trim($_POST['kota']);

            if ($this->penerbitModel->namaPenerbitExists($nama_penerbit)) {
                $_SESSION['error_message'] = "Penerbit sudah ada!";
                header("Location: index.php?page=penerbit/create");
                exit();
            }

            $data = [
                'nama_penerbit' => $nama_penerbit,
                'kota' => $kota
            ];

            if ($this->penerbitModel->create($data)) {
                $_SESSION['success_message'] = "Penerbit berhasil ditambahkan!";
                header("Location: index.php?page=penerbit/index");
                exit();
            } else {
                $_SESSION['error_message'] = "Gagal menambahkan penerbit!";
                header("Location: index.php?page=penerbit/create");
                exit();
            }
        }
    }

    public function edit() {
        $id_penerbit = $_GET['id'] ?? null;
        if (!$id_penerbit) {
            header("Location: index.php?page=penerbit/index");
            exit();
        }
        $data_penerbit = $this->penerbitModel->readById($id_penerbit);
        if (!$data_penerbit) {
            $_SESSION['error_message'] = "Penerbit tidak ditemukan!";
            header("Location: index.php?page=penerbit/index");
            exit();
        }
        require_once ROOT_PATH . 'src/views/penerbit/edit.php';
    }

    public function update() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id_penerbit = (int)$_POST['id_penerbit'];
            $nama_penerbit = trim($_POST['nama_penerbit']);
            $kota = trim($_POST['kota']);
            $data = [
                'nama_penerbit' => $nama_penerbit,
                'kota' => $kota
            ];

            if ($this->penerbitModel->update($id_penerbit, $data)) {
                $_SESSION['success_message'] = "Penerbit berhasil diperbarui!";
                header("Location: index.php?page=penerbit/index");
                exit();
            } else {
                $_SESSION['error_message'] = "Gagal memperbarui penerbit!";
                header("Location: index.php?page=penerbit/edit&id=$id_penerbit");
                exit();
            }
        }
    }

    public function delete() {
        $id_penerbit = $_GET['id'] ?? null;
        if (!$id_penerbit) {
            header("Location: index.php?page=penerbit/index");
            exit();
        }

        if ($this->penerbitModel->delete($id_penerbit)) {
            $_SESSION['success_message'] = "Penerbit berhasil dihapus!";
        } else {
            $_SESSION['error_message'] = "Gagal menghapus penerbit!";
        }
        header("Location: index.php?page=penerbit/index");
        exit();
    }
}
?>