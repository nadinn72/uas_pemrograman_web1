<?php
// header.php - File header utama

// Start session jika belum
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Set default timezone
date_default_timezone_set('Asia/Jakarta');

// Define base URL
$base_url = '/uas_web1';

// Get current URL for active menu
$current_url = $_SERVER['REQUEST_URI'];
$current_path = parse_url($current_url, PHP_URL_PATH);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $page_title ?? 'UAS Web 1 - Sistem Manajemen Film'; ?></title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Custom CSS -->
    <link rel="stylesheet" href="<?php echo $base_url; ?>/assets/css/style.css">
    
    <style>
    /* PERBAIKAN: Tambahkan ini agar navbar fixed */
    body {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        background-color: #f8f9fa;
        color: #333;
        padding-top: 70px; /* Space for fixed navbar */
        min-height: 100vh;
    }
    
    /* PERBAIKAN: Navbar fixed */
    .navbar-custom {
        background: linear-gradient(135deg, #6f42c1, #5a32a3);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        padding: 10px 0;
        position: fixed;
        top: 0;
        width: 100%;
        z-index: 1000;
    }
    
    .navbar-custom .navbar-brand {
        color: white;
        font-weight: 700;
        font-size: 1.5rem;
    }
    
    .navbar-custom .nav-link {
        color: rgba(255, 255, 255, 0.9);
        font-weight: 500;
        padding: 8px 15px;
        border-radius: 6px;
        transition: all 0.3s ease;
        margin: 0 3px;
    }
    
    .navbar-custom .nav-link:hover {
        color: white;
        background-color: rgba(255, 255, 255, 0.1);
    }
    
    /* PERBAIKAN: Active menu berdasarkan URL */
    .navbar-custom .nav-link.active {
        background-color: rgba(255, 255, 255, 0.2);
        color: white;
    }
    
    /* PERBAIKAN: Container untuk konten */
    .main-container {
        margin-top: 20px;











        
        min-height: calc(100vh - 150px);
    }
    
    /* Style lainnya tetap sama */
    :root {
        --primary: #6f42c1;
        --primary-dark: #5a32a3;
        --purple-light: #9370db;
    }
    
    .page-title {
        color: var(--primary-dark);
        margin: 20px 0;
        padding-bottom: 15px;
        border-bottom: 2px solid var(--purple-light);
        font-weight: 700;
    }
    
    .card {
        border: none;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
        border-radius: 12px;
        margin-bottom: 20px;
    }
    
    .btn-primary {
        background: linear-gradient(135deg, var(--primary), var(--primary-dark));
        border: none;
        padding: 10px 20px;
        border-radius: 8px;
        font-weight: 600;
        transition: all 0.3s ease;
    }
    
    .btn-primary:hover {
        background: linear-gradient(135deg, var(--primary-dark), var(--primary));
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(111, 66, 193, 0.3);
    }
    
    .user-info {
        color: white;
        font-weight: 500;
        display: flex;
        align-items: center;
        gap: 10px;
    }
    
    .user-avatar {
        width: 35px;
        height: 35px;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.2);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.2rem;
    }
    </style>
</head>
<body>
    <!-- Navigation Bar - PERBAIKAN: Fixed navbar -->
    <nav class="navbar navbar-expand-lg navbar-custom">
        <div class="container">
            <a class="navbar-brand" href="<?php echo $base_url; ?>/">
                <i class="fas fa-film me-2"></i>UAS Film
            </a>
            
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link <?php echo (strpos($current_path, '/home') !== false || $current_path == '/' || $current_path == '/uas_web1/' || $current_path == '/uas_web1/home') ? 'active' : ''; ?>" 
                           href="<?php echo $base_url; ?>/home">
                            <i class="fas fa-home me-1"></i> Home
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?php echo (strpos($current_path, '/films') !== false || strpos($current_path, '/views') !== false) ? 'active' : ''; ?>" 
                           href="<?php echo $base_url; ?>/films">
                            <i class="fas fa-film me-1"></i> Film
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?php echo (strpos($current_path, '/profile') !== false) ? 'active' : ''; ?>" 
                           href="<?php echo $base_url; ?>/profile">
                            <i class="fas fa-user me-1"></i> Profil
                        </a>
                    </li>
                    <?php if (isset($_SESSION['role']) && $_SESSION['role'] == 'admin'): ?>
                    <li class="nav-item">
                        <a class="nav-link <?php echo (strpos($current_path, '/profile/admin') !== false) ? 'active' : ''; ?>" 
                           href="<?php echo $base_url; ?>/profile/admin">
                            <i class="fas fa-admin me-1"></i> Admin
                        </a>
                    </li>
                    <?php endif; ?>
                </ul>
                
                <div class="d-flex align-items-center">
                    <?php if (isset($_SESSION['user_id'])): ?>
                    <div class="user-info me-3">
                        <div class="user-avatar">
                            <i class="fas fa-user"></i>
                        </div>
                        <span class="d-none d-md-inline"><?php echo htmlspecialchars($_SESSION['user_name'] ?? 'User'); ?></span>
                    </div>
                    <a href="<?php echo $base_url; ?>/auth/logout" class="btn btn-outline-light btn-sm">
                        <i class="fas fa-sign-out-alt me-1"></i> Logout
                    </a>
                    <?php else: ?>
                    <a href="<?php echo $base_url; ?>/auth/login" class="btn btn-outline-light btn-sm me-2">
                        <i class="fas fa-sign-in-alt me-1"></i> Login
                    </a>
                    <a href="<?php echo $base_url; ?>/auth/register" class="btn btn-light btn-sm">
                        <i class="fas fa-user-plus me-1"></i> Register
                    </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content - PERBAIKAN: Container utama -->
    <div class="main-container">
        <div class="container">
            <!-- Success/Error Messages -->
            <?php if (isset($_SESSION['success'])): ?>
            <div class="alert alert-success alert-dismissible fade show mt-3" role="alert">
                <i class="fas fa-check-circle me-2"></i><?php echo $_SESSION['success']; ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            <?php unset($_SESSION['success']); ?>
            <?php endif; ?>
            
            <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-danger alert-dismissible fade show mt-3" role="alert">
                <i class="fas fa-exclamation-circle me-2"></i><?php echo $_SESSION['error']; ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            <?php unset($_SESSION['error']); ?>
            <?php endif; ?>