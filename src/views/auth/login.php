<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Login | E-Perpus</title>
    <link href="/e_perpus/public/assets/css/bootstrap.min.css" rel="stylesheet"> 
    <style>
        .login-container { max-width: 400px; margin-top: 100px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6 login-container">
                <div class="card shadow-lg">
                    <div class="card-header text-center bg-primary text-white">
                        <h4 class="mb-0">Login E-Perpus</h4>
                    </div>
                    <div class="card-body">
                        <?php 
                        // Tampilkan pesan error jika ada (Pastikan session_start() sudah dijalankan di index.php/AuthController)
                        if (isset($_SESSION['error_message'])): ?>
                            <div class="alert alert-danger" role="alert">
                                <?= htmlspecialchars($_SESSION['error_message']); ?>
                                <?php unset($_SESSION['error_message']); ?>
                            </div>
                        <?php endif; ?>

                        <form action="index.php?page=auth/doLogin" method="POST">
                            <div class="mb-3">
                                <label for="username" class="form-label">Username</label>
                                <input type="text" class="form-control" id="username" name="username" required>
                            </div>
                            <div class="mb-3">
                                <label for="password" class="form-label">Password</label>
                                <input type="password" class="form-control" id="password" name="password" required>
                            </div>
                            <button type="submit" class="btn btn-primary w-100">Login</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="/e_perpus/public/assets/js/bootstrap.bundle.min.js"></script>
</body>
</html>