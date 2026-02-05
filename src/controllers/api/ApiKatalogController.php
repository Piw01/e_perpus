<?php
/**
 * ApiKatalogController - Handle API requests untuk katalog publik
 */

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
            // Get buku data
            if (!empty($search)) {
                $stmt = $this->bukuModel->search($search);
            } elseif (!empty($kategori)) {
                $stmt = $this->bukuModel->filterByKategori($kategori);
            } else {
                $stmt = $this->bukuModel->readAllPublic();
            }

            $all_buku = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $total_buku = count($all_buku);
            
            // Pagination
            $buku_paginated = array_slice($all_buku, $offset, $limit);

            // Get kategori list
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