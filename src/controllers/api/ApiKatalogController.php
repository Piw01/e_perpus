<?php
/**
 * ApiKatalogController - Handle API requests untuk katalog publik
 * Mendukung: Search, Filter Kategori, Pagination
 */

header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

require_once __DIR__ . '/../../models/BukuModel.php';
require_once __DIR__ . '/../../models/KategoriModel.php';

class ApiKatalogController {
    private $bukuModel;
    private $kategoriModel;
    private $db;

    public function __construct($db) {
        $this->db = $db;
        $this->bukuModel = new BukuModel($db);
        $this->kategoriModel = new KategoriModel($db);
    }

    /**
     * GET List Buku dengan search & filter
     */
    public function getBukuList() {
        $search = $_GET['search'] ?? '';
        $kategori = $_GET['kategori'] ?? '';
        $limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 12;
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $offset = ($page - 1) * $limit;

        try {
            // Get buku data based on filters
            if (!empty($search) && !empty($kategori)) {
                // Search + Filter kategori (gabungan)
                $query = "SELECT b.*, k.nama_kategori, pen.nama_penulis, per.nama_penerbit 
                          FROM buku b
                          LEFT JOIN kategori k ON b.id_kategori = k.id_kategori
                          LEFT JOIN penulis pen ON b.id_penulis = pen.id_penulis
                          LEFT JOIN penerbit per ON b.id_penerbit = per.id_penerbit
                          WHERE (b.judul LIKE :search OR b.isbn LIKE :search 
                                 OR pen.nama_penulis LIKE :search OR b.sinopsis LIKE :search)
                          AND b.id_kategori = :kategori
                          AND b.status = 'tersedia'
                          ORDER BY b.judul ASC";
                
                $stmt = $this->db->prepare($query);
                $search_param = '%' . $search . '%';
                $stmt->bindParam(':search', $search_param);
                $stmt->bindParam(':kategori', $kategori);
                $stmt->execute();
                $all_buku = $stmt->fetchAll(PDO::FETCH_ASSOC);
            } elseif (!empty($search)) {
                $stmt = $this->bukuModel->search($search);
                $all_buku = $stmt->fetchAll(PDO::FETCH_ASSOC);
            } elseif (!empty($kategori)) {
                $stmt = $this->bukuModel->filterByKategori($kategori);
                $all_buku = $stmt->fetchAll(PDO::FETCH_ASSOC);
            } else {
                $stmt = $this->bukuModel->readAllPublic();
                $all_buku = $stmt->fetchAll(PDO::FETCH_ASSOC);
            }

            $total_buku = count($all_buku);
            
            // Pagination
            $buku_paginated = array_slice($all_buku, $offset, $limit);

            // Get kategori list untuk dropdown
            $stmt_kategori = $this->kategoriModel->readAll();
            $kategori_list = $stmt_kategori->fetchAll(PDO::FETCH_ASSOC);

            // Build response
            $response = [
                'success' => true,
                'data' => [
                    'buku' => $buku_paginated,
                    'kategori' => $kategori_list
                ],
                'pagination' => [
                    'total' => $total_buku,
                    'per_page' => $limit,
                    'current_page' => $page,
                    'total_pages' => ceil($total_buku / $limit)
                ],
                'filters' => [
                    'search' => $search,
                    'kategori' => $kategori
                ]
            ];

            echo json_encode($response);

        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode([
                'success' => false,
                'error' => 'Internal server error',
                'message' => $e->getMessage()
            ]);
        }
    }
}