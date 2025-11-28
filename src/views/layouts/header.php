<?php 
// src/views/layouts/header.php
// Ambil level dari session, default 0
$user_level = $_SESSION['level'] ?? 0;
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard | E-Perpus</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link href="/e_perpus/public/assets/css/style.css" rel="stylesheet">
    
    <style>
        .page-alert { position: fixed; top: 70px; right: 20px; z-index: 1050; opacity: 0.95; }
    </style>
</head>
<body class="d-flex flex-column min-vh-100">

<nav class="navbar navbar-expand-lg navbar-dark bg-primary shadow-sm sticky-top">
    <div class="container-fluid">
        <a class="navbar-brand" href="index.php?page=dashboard/index">
            <i class="fas fa-book-reader me-2"></i> <strong>E-PERPUS</strong>
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav me-auto">
                <li class="nav-item">
                    <a class="nav-link" href="index.php?page=dashboard/index">Dashboard</a>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMaster" role="button" data-bs-toggle="dropdown">
                        <i class="fas fa-database me-1"></i> Data Master
                    </a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="index.php?page=buku/index">Data Buku</a></li>
                        <li><a class="dropdown-item" href="index.php?page=siswa/index">Data Siswa</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item" href="index.php?page=kategori/index">Data Kategori</a></li>
                        <li><a class="dropdown-item" href="index.php?page=penulis/index">Data Penulis</a></li>
                        <li><a class="dropdown-item" href="index.php?page=penerbit/index">Data Penerbit</a></li>
                    </ul>
                </li>
            </ul>

            <ul class="navbar-nav d-flex align-items-center">
                <li class="nav-item me-3">
                    <span class="nav-link text-white">
                        <i class="fas fa-user-circle me-1"></i> 
                        <?= htmlspecialchars($_SESSION['username'] ?? 'Admin') ?>
                        <!-- Badge Level -->
                        <?php if($user_level == 1) echo '<span class="badge bg-warning text-dark ms-1">Super Admin</span>'; ?>
                        <?php if($user_level == 2) echo '<span class="badge bg-info text-dark ms-1">Admin</span>'; ?>
                        <?php if($user_level == 3) echo '<span class="badge bg-secondary ms-1">Operator</span>'; ?>
                    </span>
                </li>

                <!-- TOMBOL MANAGEMENT ADMIN: HANYA UNTUK LEVEL 1 (SUPER ADMIN) -->
                <?php if ($user_level == 1): ?>
                <li class="nav-item me-3">
                    <a class="btn btn-sm btn-info text-white" href="index.php?page=admin/index">
                        <i class="fas fa-user-lock me-1"></i> Management Admin
                    </a>
                </li>
                <?php endif; ?>

                <li class="nav-item">
                    <a class="btn btn-sm btn-outline-light" href="index.php?page=auth/logout">Logout</a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<!-- Flash Message Area -->
<?php
if (isset($_SESSION['success_message'])) {
    echo '<div class="alert alert-success page-alert alert-dismissible fade show" role="alert"><strong>Sukses!</strong> ' . $_SESSION['success_message'] . '<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>';
    unset($_SESSION['success_message']);
}
if (isset($_SESSION['error_message'])) {
    echo '<div class="alert alert-danger page-alert alert-dismissible fade show" role="alert"><strong>Error!</strong> ' . $_SESSION['error_message'] . '<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>';
    unset($_SESSION['error_message']);
}
?>