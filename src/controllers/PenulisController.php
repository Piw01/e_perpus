<?php

class PenulisController {
    private $penulisModel;

    public function __construct() {
        $database = new Database();
        $db = $database->getConnection();
        $this->penulisModel = new PenulisModel($db);
    }

    public function index() {
        $stmt = $this->penulisModel->readAll();
        $data_penulis = $stmt->fetchAll(PDO::FETCH_ASSOC);
        require_once ROOT_PATH . 'src/views/penulis/index.php';
    }

    public function create() {
        require_once ROOT_PATH . 'src/views/penulis/create.php';
    }

    public function store() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nama_penulis = trim($_POST['nama_penulis']);

            if ($this->penulisModel->namaPenulisExists($nama_penulis)) {
                $_SESSION['error_message'] = "Penulis sudah ada!";
                header("Location: index.php?page=penulis/create");
                exit();
            }

            $data = ['nama_penulis' => $nama_penulis];

            if ($this->penulisModel->create($data)) {
                $_SESSION['success_message'] = "Penulis berhasil ditambahkan!";
                header("Location: index.php?page=penulis/index");
                exit();
            } else {
                $_SESSION['error_message'] = "Gagal menambahkan penulis!";
                header("Location: index.php?page=penulis/create");
                exit();
            }
        }
    }

    public function edit() {
        $id_penulis = $_GET['id'] ?? null;
        if (!$id_penulis) {
            header("Location: index.php?page=penulis/index");
            exit();
        }
        $data_penulis = $this->penulisModel->readById($id_penulis);
        if (!$data_penulis) {
            $_SESSION['error_message'] = "Penulis tidak ditemukan!";
            header("Location: index.php?page=penulis/index");
            exit();
        }
        require_once ROOT_PATH . 'src/views/penulis/edit.php';
    }

    public function update() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id_penulis = (int)$_POST['id_penulis'];
            $nama_penulis = trim($_POST['nama_penulis']);
            $data = ['nama_penulis' => $nama_penulis];

            if ($this->penulisModel->update($id_penulis, $data)) {
                $_SESSION['success_message'] = "Penulis berhasil diperbarui!";
                header("Location: index.php?page=penulis/index");
                exit();
            } else {
                $_SESSION['error_message'] = "Gagal memperbarui penulis!";
                header("Location: index.php?page=penulis/edit&id=$id_penulis");
                exit();
            }
        }
    }

    public function delete() {
        $id_penulis = $_GET['id'] ?? null;
        if (!$id_penulis) {
            header("Location: index.php?page=penulis/index");
            exit();
        }

        if ($this->penulisModel->delete($id_penulis)) {
            $_SESSION['success_message'] = "Penulis berhasil dihapus!";
        } else {
            $_SESSION['error_message'] = "Gagal menghapus penulis!";
        }
        header("Location: index.php?page=penulis/index");
        exit();
    }
}
?>