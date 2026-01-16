<?php
// views/auth/register.php
// JANGAN panggil session_start() di sini
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Aplikasi Review Film</title>
    <link rel="stylesheet" href="/uas_web1/assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        /* Copy semua style dari login.php di sini */
        <?php 
        // Karena style sama, kita bisa include dari login.php
        // Atau copy semua style CSS dari login.php ke sini
        ?>
    </style>
</head>
<body>
    <div class="auth-container">
        <div class="logo-login">
            <i class="fas fa-film"></i>
            <span style="color: var(--purple-dark);">Review</span><span style="color: var(--peach);">Film</span>
        </div>
        
        <h2 class="auth-title">Daftar Akun Baru</h2>
        
        <?php if (isset($error)): ?>
        <div class="alert alert-danger">
            <i class="fas fa-exclamation-circle"></i> <?= htmlspecialchars($error) ?>
        </div>
        <?php endif; ?>
        
        <form action="/uas_web1/auth/register" method="POST">
            <div class="form-group">
                <label for="name" class="form-label">
                    <i class="fas fa-user"></i> Nama Lengkap
                </label>
                <input type="text" id="name" name="name" class="form-control" required
                       placeholder="Masukkan nama lengkap"
                       value="<?= isset($_POST['name']) ? htmlspecialchars($_POST['name']) : '' ?>">
            </div>
            
            <div class="form-group">
                <label for="email" class="form-label">
                    <i class="fas fa-envelope"></i> Email
                </label>
                <input type="email" id="email" name="email" class="form-control" required
                       placeholder="Masukkan email"
                       value="<?= isset($_POST['email']) ? htmlspecialchars($_POST['email']) : '' ?>">
            </div>
            
            <div class="form-group">
                <label for="password" class="form-label">
                    <i class="fas fa-lock"></i> Password
                </label>
                <input type="password" id="password" name="password" class="form-control" required
                       placeholder="Minimal 6 karakter">
                <small class="form-text">Minimal 6 karakter</small>
            </div>
            
            <div class="form-group">
                <label for="confirm_password" class="form-label">
                    <i class="fas fa-lock"></i> Konfirmasi Password
                </label>
                <input type="password" id="confirm_password" name="confirm_password" class="form-control" required
                       placeholder="Ketik ulang password">
            </div>
            
            <div class="form-group">
                <button type="submit" class="btn btn-primary btn-block">
                    <i class="fas fa-user-plus"></i> Daftar
                </button>
            </div>
            
            <div class="text-center">
                <p>Sudah punya akun? <a href="/uas_web1/auth/login">Login disini</a></p>
            </div>
        </form>
    </div>

    <script>
        // Copy semua JavaScript dari login.php
        // Validasi form register
        document.querySelector('form').addEventListener('submit', function(e) {
            const name = document.getElementById('name').value.trim();
            const email = document.getElementById('email').value.trim();
            const password = document.getElementById('password').value.trim();
            const confirmPassword = document.getElementById('confirm_password').value.trim();
            
            // Validasi
            const errors = [];
            
            if (!name) errors.push('Nama harus diisi');
            if (!email) errors.push('Email harus diisi');
            if (!password) errors.push('Password harus diisi');
            if (!confirmPassword) errors.push('Konfirmasi password harus diisi');
            
            if (email && !validateEmail(email)) {
                errors.push('Format email tidak valid');
            }
            
            if (password && password.length < 6) {
                errors.push('Password minimal 6 karakter');
            }
            
            if (password && confirmPassword && password !== confirmPassword) {
                errors.push('Password dan konfirmasi password tidak cocok');
            }
            
            if (errors.length > 0) {
                e.preventDefault();
                showAlert(errors.join('<br>'), 'error');
                return false;
            }
            
            return true;
        });
        
        function validateEmail(email) {
            const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            return re.test(email);
        }
        
        function showAlert(message, type) {
            // Implementasi sama seperti di login.php
        }
        
        // Auto focus
        document.getElementById('name').focus();
    </script>
</body>
</html>