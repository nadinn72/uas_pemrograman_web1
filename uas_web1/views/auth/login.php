<?php 
// views/auth/login.php
// JANGAN panggil session_start() di sini karena sudah dipanggil di index.php
// Hanya cek apakah session sudah aktif
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Aplikasi Review Film</title>
    <link rel="stylesheet" href="/uas_web1/assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --peach: #FFDAB9;
            --peach-light: #FFEBD6;
            --peach-dark: #FFC8A2;
            --purple: #9370DB;
            --purple-light: #B19CD9;
            --purple-dark: #7B68EE;
            --white: #FFFFFF;
            --gray: #F5F5F5;
            --gray-dark: #333333;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        body {
            background: linear-gradient(135deg, var(--purple) 0%, var(--purple-dark) 100%);
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            padding: 20px;
        }
        
        .auth-container {
            width: 100%;
            max-width: 450px;
            background: var(--white);
            border-radius: 15px;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.2);
            padding: 2.5rem;
            animation: fadeIn 0.5s ease;
        }
        
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        .auth-title {
            text-align: center;
            color: var(--purple-dark);
            margin-bottom: 2rem;
            font-size: 1.8rem;
        }
        
        .form-group {
            margin-bottom: 1.5rem;
        }
        
        .form-label {
            display: block;
            margin-bottom: 0.5rem;
            color: var(--purple-dark);
            font-weight: 600;
        }
        
        .form-control {
            width: 100%;
            padding: 0.8rem 1rem;
            border: 2px solid #e0e0e0;
            border-radius: 8px;
            font-size: 1rem;
            transition: all 0.3s ease;
        }
        
        .form-control:focus {
            outline: none;
            border-color: var(--purple);
            box-shadow: 0 0 0 3px rgba(147, 112, 219, 0.2);
        }
        
        .btn {
            display: inline-block;
            padding: 0.8rem 1.5rem;
            border-radius: 6px;
            text-decoration: none;
            font-weight: 600;
            cursor: pointer;
            border: none;
            transition: all 0.3s ease;
            font-size: 1rem;
        }
        
        .btn-primary {
            background-color: var(--purple);
            color: var(--white);
        }
        
        .btn-primary:hover {
            background-color: var(--purple-dark);
        }
        
        .btn-secondary {
            background-color: var(--peach);
            color: var(--purple-dark);
        }
        
        .btn-secondary:hover {
            background-color: var(--peach-dark);
        }
        
        .btn-block {
            width: 100%;
            text-align: center;
        }
        
        .text-center {
            text-align: center;
            margin-top: 1.5rem;
        }
        
        .alert {
            padding: 1rem;
            border-radius: 8px;
            margin-bottom: 1.5rem;
            animation: slideIn 0.3s ease;
        }
        
        .alert-danger {
            background-color: #ffebee;
            color: #c62828;
            border: 1px solid #ffcdd2;
        }
        
        @keyframes slideIn {
            from { opacity: 0; transform: translateX(-10px); }
            to { opacity: 1; transform: translateX(0); }
        }
        
        a {
            color: var(--purple);
            text-decoration: none;
            font-weight: 600;
        }
        
        a:hover {
            text-decoration: underline;
        }
        
        .logo-login {
            text-align: center;
            margin-bottom: 2rem;
            font-size: 2rem;
        }
        
        .logo-login i {
            color: var(--peach);
            margin-right: 0.5rem;
        }
        
        small {
            font-size: 0.85rem;
            color: #666;
        }
        
        .form-text {
            display: block;
            margin-top: 0.25rem;
            font-size: 0.85rem;
            color: #666;
        }
        
        /* Responsive */
        @media (max-width: 480px) {
            .auth-container {
                padding: 1.5rem;
                margin: 0 10px;
            }
            
            .auth-title {
                font-size: 1.5rem;
            }
        }
    </style>
</head>
<body>
    <div class="auth-container">
        <div class="logo-login">
            <i class="fas fa-film"></i>
            <span style="color: var(--purple-dark);">Review</span><span style="color: var(--peach);">Film</span>
        </div>
        
        <h2 class="auth-title">Login ke Sistem Review Film</h2>
        
        <?php if (isset($error)): ?>
        <div class="alert alert-danger">
            <i class="fas fa-exclamation-circle"></i> <?= htmlspecialchars($error) ?>
        </div>
        <?php endif; ?>
        
        <?php if (isset($_SESSION['success'])): ?>
        <div class="alert alert-success">
            <i class="fas fa-check-circle"></i> <?= htmlspecialchars($_SESSION['success']) ?>
            <?php unset($_SESSION['success']); ?>
        </div>
        <?php endif; ?>
        
        <form action="/uas_web1/auth/login" method="POST">
            <div class="form-group">
                <label for="email" class="form-label">
                    <i class="fas fa-envelope"></i> Email
                </label>
                <input type="email" id="email" name="email" class="form-control" required 
                       placeholder="Masukkan email Anda"
                       value="<?= isset($_POST['email']) ? htmlspecialchars($_POST['email']) : '' ?>">
            </div>
            
            <div class="form-group">
                <label for="password" class="form-label">
                    <i class="fas fa-lock"></i> Password
                </label>
                <input type="password" id="password" name="password" class="form-control" required
                       placeholder="Masukkan password">
            </div>
            
            <div class="form-group">
                <button type="submit" class="btn btn-primary btn-block">
                    <i class="fas fa-sign-in-alt"></i> Login
                </button>
            </div>
            
            <div class="text-center">
                <p>Belum punya akun? <a href="/uas_web1/auth/register">Daftar disini</a></p>
                <p><small>
                    <i class="fas fa-info-circle"></i> 
                    Demo Account: admin@film.com / admin123<br>
                    User Account: user@film.com / user123
                </small></p>
            </div>
        </form>
    </div>

    <script>
        // Validasi form
        document.querySelector('form').addEventListener('submit', function(e) {
            const email = document.getElementById('email').value.trim();
            const password = document.getElementById('password').value.trim();
            
            if (!email || !password) {
                e.preventDefault();
                showAlert('Harap isi semua field!', 'error');
                return false;
            }
            
            if (!validateEmail(email)) {
                e.preventDefault();
                showAlert('Format email tidak valid!', 'error');
                return false;
            }
            
            return true;
        });
        
        function validateEmail(email) {
            const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            return re.test(email);
        }
        
        function showAlert(message, type) {
            // Hapus alert lama jika ada
            const oldAlert = document.querySelector('.custom-alert');
            if (oldAlert) oldAlert.remove();
            
            // Buat alert baru
            const alert = document.createElement('div');
            alert.className = `custom-alert alert-${type}`;
            alert.innerHTML = `
                <i class="fas fa-${type === 'error' ? 'exclamation-triangle' : 'check-circle'}"></i>
                ${message}
            `;
            
            // Styling alert
            alert.style.cssText = `
                position: fixed;
                top: 20px;
                right: 20px;
                padding: 15px 20px;
                border-radius: 8px;
                background: ${type === 'error' ? '#ffebee' : '#d4edda'};
                color: ${type === 'error' ? '#c62828' : '#155724'};
                border: 1px solid ${type === 'error' ? '#ffcdd2' : '#c3e6cb'};
                box-shadow: 0 4px 12px rgba(0,0,0,0.15);
                z-index: 1000;
                animation: slideInRight 0.3s ease;
                display: flex;
                align-items: center;
                gap: 10px;
            `;
            
            // Animasi masuk
            const style = document.createElement('style');
            style.textContent = `
                @keyframes slideInRight {
                    from { transform: translateX(100%); opacity: 0; }
                    to { transform: translateX(0); opacity: 1; }
                }
            `;
            document.head.appendChild(style);
            
            document.body.appendChild(alert);
            
            // Hapus alert setelah 5 detik
            setTimeout(() => {
                alert.style.animation = 'slideOutRight 0.3s ease forwards';
                setTimeout(() => alert.remove(), 300);
            }, 5000);
        }
        
        // Auto focus on email field
        document.getElementById('email').focus();
        
        // Clear error jika user mulai mengetik
        document.getElementById('email').addEventListener('input', function() {
            const errorAlert = document.querySelector('.alert-danger');
            if (errorAlert) errorAlert.remove();
        });
        
        document.getElementById('password').addEventListener('input', function() {
            const errorAlert = document.querySelector('.alert-danger');
            if (errorAlert) errorAlert.remove();
        });
    </script>
</body>
</html>