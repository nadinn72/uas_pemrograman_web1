<?php
// views/home/index.php
$rootPath = dirname(dirname(__DIR__));
$headerPath = $rootPath . '/header.php';

// Perbaikan: Pastikan variabel ada
$isLoggedIn = isset($isLoggedIn) ? $isLoggedIn : false;
$userName = isset($userName) ? $userName : '';
$userRole = isset($userRole) ? $userRole : 'guest';
$latestFilms = isset($latestFilms) ? $latestFilms : [];
$popularFilms = isset($popularFilms) ? $popularFilms : [];

if (file_exists($headerPath)) {
    include $headerPath;
} else {
    // Simple header if file not found
    echo '<!DOCTYPE html>
    <html lang="id">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Home - Film Management</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
        <style>
            :root {
                --primary: #6f42c1;
                --primary-dark: #5a32a3;
                --gradient: linear-gradient(135deg, var(--primary), var(--primary-dark));
            }
            body { font-family: "Segoe UI", sans-serif; background: #f8f9fa; }
            .hero-section {
                background: var(--gradient);
                color: white;
                padding: 80px 0;
                border-radius: 0 0 30px 30px;
                margin-bottom: 40px;
            }
            
            /* ========== PERBAIKAN UTAMA: STYLING KARTU QUICK ACTIONS ========== */
            /* Kartu Feature (Quick Actions) */
            .feature-card {
                background: white;
                border-radius: 15px;
                padding: 25px;
                text-align: center;
                border: 2px solid #dee2e6;
                box-shadow: 0 5px 15px rgba(0,0,0,0.05);
                height: 100%;
                transition: all 0.3s ease;
                position: relative;
                overflow: hidden;
            }

            .feature-card:hover {
                transform: translateY(-5px);
                box-shadow: 0 10px 25px rgba(0,0,0,0.1);
                border-color: var(--primary);
            }

            /* Icon dalam kartu */
            .quick-action-icon {
                width: 70px;
                height: 70px;
                border-radius: 50%;
                display: flex;
                align-items: center;
                justify-content: center;
                margin: 0 auto 20px;
                color: white;
                font-size: 1.5rem;
                border: 3px solid white;
                box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            }

            /* Badge dalam kartu */
            .badge-custom {
                display: inline-block;
                padding: 8px 20px;
                background: linear-gradient(135deg, #6f42c1, #5a32a3);
                color: white;
                font-weight: 500;
                border-radius: 20px;
                text-decoration: none;
                border: 2px solid white;
                box-shadow: 0 3px 10px rgba(0,0,0,0.1);
                transition: all 0.3s ease;
            }

            .badge-custom:hover {
                transform: translateY(-2px);
                box-shadow: 0 5px 15px rgba(0,0,0,0.2);
                text-decoration: none;
                color: white;
            }
            
            /* ========== STYLING LAINNYA ========== */
            .film-card {
                border: 2px solid #dee2e6;
                border-radius: 12px;
                overflow: hidden;
                box-shadow: 0 4px 12px rgba(0,0,0,0.08);
                transition: all 0.3s ease;
                height: 100%;
                background: white;
            }
            
            .film-card:hover {
                transform: translateY(-5px);
                box-shadow: 0 8px 25px rgba(0,0,0,0.15);
                border-color: var(--primary);
            }
            
            .film-card img {
                height: 250px;
                object-fit: cover;
                border-bottom: 3px solid var(--primary);
            }
            
            .section-title {
                position: relative;
                padding-bottom: 15px;
                margin-bottom: 30px;
                color: #333;
            }
            
            .section-title::after {
                content: "";
                position: absolute;
                bottom: 0;
                left: 0;
                width: 60px;
                height: 4px;
                background: var(--gradient);
                border-radius: 2px;
            }
            
            .welcome-badge {
                display: inline-block;
                background: rgba(255,255,255,0.2);
                padding: 8px 20px;
                border-radius: 30px;
                margin-bottom: 15px;
                border: 1px solid rgba(255,255,255,0.3);
            }
            
            /* Tombol dengan border */
            .btn-custom {
                border: 2px solid;
                border-radius: 8px;
                font-weight: 500;
                transition: all 0.3s ease;
            }
            
            .btn-custom:hover {
                transform: translateY(-2px);
                box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            }
            
            /* Card untuk statistik user */
            .user-stats-card {
                border: 3px solid var(--primary);
                border-radius: 15px;
                box-shadow: 0 8px 20px rgba(111, 66, 193, 0.15);
            }
        </style>
    </head>
    <body>';
}
?>

<!-- User Stats (if logged in) -->
<?php if ($isLoggedIn): ?>
<div class="container mb-5">
    <div class="card user-stats-card border-0 shadow-lg">
        <div class="card-body p-4">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h4 class="fw-bold mb-2">
                        <i class="fas fa-user-check text-success me-2"></i>
                        Selamat Datang Kembali, <?php echo htmlspecialchars($userName); ?>!
                    </h4>
                    <p class="text-muted mb-0">
                        Anda login sebagai 
                        <span class="badge bg-<?php echo $userRole == 'admin' ? 'danger' : 'primary'; ?> px-3 py-2" 
                              style="border: 2px solid white;">
                            <i class="fas fa-<?php echo $userRole == 'admin' ? 'crown' : 'user'; ?> me-1"></i>
                            <?php echo ucfirst($userRole); ?>
                        </span>
                        . Nikmati fitur lengkap sistem kami.
                    </p>
                </div>
                <div class="col-md-4 text-end">
                    <div class="btn-group">
                        <a href="/uas_web1/profile" class="btn btn-primary btn-custom">
                            <i class="fas fa-user-cog me-1"></i> Profil
                        </a>
                        <a href="/uas_web1/films" class="btn btn-success btn-custom">
                            <i class="fas fa-film me-1"></i> Film
                        </a>
                        <?php if ($userRole == 'admin'): ?>
                        <a href="/uas_web1/profile/admin" class="btn btn-warning btn-custom">
                            <i class="fas fa-cog me-1"></i> Admin
                        </a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>

<!-- Hero Section -->
<section class="hero-section">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-8">
                <span class="welcome-badge">
                    <i class="fas fa-film me-2"></i>Sistem Manajemen Film
                </span>
                <h1 class="display-4 fw-bold mb-3">
                    Selamat Datang di <br>Koleksi Film Terlengkap
                </h1>
                <p class="lead mb-4">
                    Temukan film-film terbaik dari berbagai genre. 
                    <?php if ($isLoggedIn): ?>
                    Halo, <strong><?php echo htmlspecialchars($userName); ?></strong>! Selamat menikmati.
                    <?php else: ?>
                    Login untuk menikmati fitur lengkap sistem kami.
                    <?php endif; ?>
                </p>
                <div class="d-flex flex-wrap gap-3">
                    <a href="/uas_web1/films" class="btn btn-light btn-lg px-4 btn-custom">
                        <i class="fas fa-play me-2"></i>Jelajahi Film
                    </a>
                </div>
            </div>
            <div class="col-lg-4 text-center">
                <div class="quick-action-icon">
                    <i class="fas fa-film fa-2x"></i>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Quick Actions -->
<div class="container mb-5" style="margin: 20px;">
    <h3 class="section-title fw-bold">
        <i class="fas fa-bolt text-warning me-2"></i>Quick Actions
    </h3>
    <div class="row g-4">
        <!-- Mengubah "g-4" menjadi row biasa dan gunakan margin manual -->
        
        <!-- Kotak 1: Lihat Film -->
        <div class="col-lg-3 col-md-5 mb-4" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border: 3px solid white !important; border-radius: 15px; padding: 25px;">
            <div style="color: white !important; text-align: center;">
                <div style="width: 70px; height: 70px; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 20px; background: rgba(255,255,255,0.2); border: 3px solid white;">
                    <i class="fas fa-film fa-2x" style="color: white;"></i>
                </div>
                <h4 class="fw-bold mb-2" style="color: white !important;">Lihat Film</h4>
                <p class="mb-3" style="color: white !important; opacity: 0.9;">Jelajahi koleksi film terbaik kami</p>
                <a href="/uas_web1/films" class="d-inline-block" style="background: white; color: #667eea !important; padding: 8px 20px; border-radius: 20px; text-decoration: none; font-weight: 500; border: 2px solid white;">
                    Mulai Jelajah →
                </a>
                <div class="mt-4 small" style="color: white !important; opacity: 0.8;">
                    <i class="fas fa-hashtag me-1"></i> Multiple Genres
                </div>
            </div>
        </div>
        
        <!-- Kotak 2: Profil atau Login -->
        <div class="col-lg-3 col-md-5 mb-4" style="background: linear-gradient(135deg, #79d39d 0%, #6fbc8a 100%); border: 3px solid white !important; border-radius: 15px; padding: 25px;">
            <?php if ($isLoggedIn): ?>
                <!-- Jika user sudah login: Tampilkan Profil -->
                <div style="color: white !important; text-align: center;">
                    <div style="width: 70px; height: 70px; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 20px; background: rgba(255,255,255,0.2); border: 3px solid white;">
                        <i class="fas fa-user-circle fa-2x" style="color: white;"></i>
                    </div>
                    <h4 class="fw-bold mb-2" style="color: white !important;">Profil Saya</h4>
                    <p class="mb-3" style="color: white !important; opacity: 0.9;">Kelola akun dan preferensi Anda</p>
                    <a href="/uas_web1/profile" class="d-inline-block" style="background: white; color: #667eea !important; padding: 8px 20px; border-radius: 20px; text-decoration: none; font-weight: 500; border: 2px solid white;">
                        Lihat Profil →
                    </a>
                    <div class="mt-3 small" style="color: white !important; opacity: 0.8;">
                        <i class="fas fa-user me-1"></i> <?php echo htmlspecialchars($userName); ?>
                    </div>
                </div>
            <?php else: ?>
                <!-- Jika user belum login: Tampilkan Login -->
                <div style="color: white !important; text-align: center;">
                    <div style="width: 70px; height: 70px; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 20px; background: rgba(255,255,255,0.2); border: 3px solid white;">
                        <i class="fas fa-sign-in-alt fa-2x" style="color: white;"></i>
                    </div>
                    <h4 class="fw-bold mb-2" style="color: white !important;">Login</h4>
                    <p class="mb-3" style="color: white !important; opacity: 0.9;">Masuk untuk akses penuh</p>
                    <a href="/uas_web1/auth/login" class="d-inline-block" style="background: white; color: #667eea !important; padding: 8px 20px; border-radius: 20px; text-decoration: none; font-weight: 500; border: 2px solid white;">
                        Login Sekarang →
                    </a>
                    <div class="mt-3 small" style="color: white !important; opacity: 0.8;">
                        <i class="fas fa-lock me-1"></i> Secure Access
                    </div>
                </div>
            <?php endif; ?>
        </div>
        
        <!-- Kotak 3: Admin, Daftar, atau Tambah Film -->
        <div class="col-lg-3 col-md-5 mb-4" style="background: linear-gradient(135deg, #ffc107, #fd7e14); border: 3px solid white !important; border-radius: 15px; padding: 25px;">
            <?php if ($isLoggedIn && $userRole == 'admin'): ?>
                <!-- Jika admin login: Tampilkan Admin Panel -->
                <div style="color: white !important; text-align: center;">
                    <div style="width: 70px; height: 70px; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 20px; background: rgba(255,255,255,0.2); border: 3px solid white;">
                        <i class="fas fa-tachometer-alt fa-2x" style="color: white;"></i>
                    </div>
                    <h4 class="fw-bold mb-2" style="color: white !important;">Admin Panel</h4>
                    <p class="mb-3" style="color: white !important; opacity: 0.9;">Kelola sistem dan pengguna</p>
                    <a href="/uas_web1/profile/admin" class="d-inline-block" style="background: white; color: #fd7e14 !important; padding: 8px 20px; border-radius: 20px; text-decoration: none; font-weight: 500; border: 2px solid white;">
                        Dashboard →
                    </a>
                    <div class="mt-3 small" style="color: white !important; opacity: 0.8;">
                        <i class="fas fa-shield-alt me-1"></i> Admin Access
                    </div>
                </div>
            <?php elseif (!$isLoggedIn): ?>
                <!-- Jika user belum login: Tampilkan Daftar -->
                <div style="color: white !important; text-align: center;">
                    <div style="width: 70px; height: 70px; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 20px; background: rgba(255,255,255,0.2); border: 3px solid white;">
                        <i class="fas fa-user-plus fa-2x" style="color: white;"></i>
                    </div>
                    <h4 class="fw-bold mb-2" style="color: white !important;">Daftar Baru</h4>
                    <p class="mb-3" style="color: white !important; opacity: 0.9;">Buat akun gratis sekarang</p>
                    <a href="/uas_web1/auth/register" class="d-inline-block" style="background: white; color: #dc3545 !important; padding: 8px 20px; border-radius: 20px; text-decoration: none; font-weight: 500; border: 2px solid white;">
                        Daftar →
                    </a>
                    <div class="mt-3 small" style="color: white !important; opacity: 0.8;">
                        <i class="fas fa-user-plus me-1"></i> Free Account
                    </div>
                </div>
            <?php else: ?>
                <!-- Jika user biasa login: Tampilkan Tambah Film -->
                <div style="color: white !important; text-align: center;">
                    <div style="width: 70px; height: 70px; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 20px; background: rgba(255,255,255,0.2); border: 3px solid white;">
                        <i class="fas fa-plus-circle fa-2x" style="color: white;"></i>
                    </div>
                    <h4 class="fw-bold mb-2" style="color: white !important;">Tambah Film</h4>
                    <p class="mb-3" style="color: white !important; opacity: 0.9;">Tambahkan film baru ke koleksi</p>
                    <a href="/uas_web1/films/create" class="d-inline-block" style="background: white; color: #fd7e14 !important; padding: 8px 20px; border-radius: 20px; text-decoration: none; font-weight: 500; border: 2px solid white;">
                        Tambah →
                    </a>
                    <div class="mt-3 small" style="color: white !important; opacity: 0.8;">
                        <i class="fas fa-plus me-1"></i> Add Content
                    </div>
                </div>
            <?php endif; ?>
        </div>
        
        <!-- Kotak 4: Trending -->
        <div class="col-lg-3 col-md-5 mb-4" style="background: linear-gradient(135deg, #e83e8c, #d63384); border: 3px solid white !important; border-radius: 15px; padding: 25px;">
            <div style="color: white !important; text-align: center;">
                <div style="width: 70px; height: 70px; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 20px; background: rgba(255,255,255,0.2); border: 3px solid white;">
                    <i class="fas fa-fire fa-2x" style="color: white;"></i>
                </div>
                <h4 class="fw-bold mb-2" style="color: white !important;">Trending</h4>
                <p class="mb-3" style="color: white !important; opacity: 0.9;">Film yang sedang populer</p>
                <a href="/uas_web1/films?sort=popular" class="d-inline-block" style="background: white; color: #e83e8c !important; padding: 8px 20px; border-radius: 20px; text-decoration: none; font-weight: 500; border: 2px solid white;">
                    Lihat Trending →
                </a>
                <div class="mt-3 small" style="color: white !important; opacity: 0.8;">
                    <i class="fas fa-chart-line me-1"></i> Popular Now
                </div>
            </div>
        </div>
        
    </div>
</div>

<!-- Latest Films -->
<?php if (!empty($latestFilms)): ?>
<div class="container mb-5">
    <h3 class="section-title fw-bold">
        <i class="fas fa-clock text-primary me-2"></i>Film Terbaru
    </h3>
    <div class="row g-4">
        <?php foreach ($latestFilms as $film): ?>
        <div class="col-xl-2 col-lg-3 col-md-4 col-sm-6">
            <div class="film-card">
                <img src="<?php echo htmlspecialchars($film['poster_url'] ?: 'https://via.placeholder.com/300x450/6f42c1/ffffff?text=No+Image'); ?>" 
                     class="card-img-top" 
                     alt="<?php echo htmlspecialchars($film['title']); ?>"
                     onerror="this.src='https://via.placeholder.com/300x450/6f42c1/ffffff?text=No+Image'">
                <div class="card-body p-3">
                    <h6 class="card-title fw-bold mb-2"><?php echo htmlspecialchars($film['title']); ?></h6>
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <span class="badge bg-secondary px-3 py-2" style="border: 1px solid white;">
                            <?php echo htmlspecialchars($film['genre']); ?>
                        </span>
                        <span class="text-warning fw-bold">
                            <i class="fas fa-star"></i> <?php echo number_format($film['rating'], 1); ?>
                        </span>
                    </div>
                    <a href="/uas_web1/films/show/<?php echo $film['id']; ?>" 
                       class="btn btn-sm btn-primary w-100 btn-custom">
                        <i class="fas fa-info-circle me-1"></i> Detail
                    </a>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
</div>
<?php endif; ?>

<!-- Popular Films -->
<?php if (!empty($popularFilms)): ?>
<div class="container mb-5">
    <h3 class="section-title fw-bold">
        <i class="fas fa-fire text-warning me-2"></i>Film Populer
    </h3>
    <div class="row g-4">
        <?php foreach ($popularFilms as $film): ?>
        <div class="col-xl-2 col-lg-3 col-md-4 col-sm-6">
            <div class="film-card">
                <img src="<?php echo htmlspecialchars($film['poster_url'] ?: 'https://via.placeholder.com/300x450/ff6b6b/ffffff?text=No+Image'); ?>" 
                     class="card-img-top" 
                     alt="<?php echo htmlspecialchars($film['title']); ?>"
                     onerror="this.src='https://via.placeholder.com/300x450/ff6b6b/ffffff?text=No+Image'">
                <div class="card-body p-3">
                    <h6 class="card-title fw-bold mb-2"><?php echo htmlspecialchars($film['title']); ?></h6>
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <span class="badge bg-danger px-3 py-2" style="border: 1px solid white;">
                            <?php echo htmlspecialchars($film['genre']); ?>
                        </span>
                        <span class="text-warning fw-bold">
                            <i class="fas fa-star"></i> <?php echo number_format($film['rating'], 1); ?>
                        </span>
                    </div>
                    <a href="/uas_web1/films/show/<?php echo $film['id']; ?>" 
                       class="btn btn-sm btn-danger w-100 btn-custom">
                        <i class="fas fa-play me-1"></i> Tonton
                    </a>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
</div>
<?php endif; ?>

<!-- Call to Action -->
<div class="container mb-5">
    <div class="card text-white border-4" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border-color: white !important; border-radius: 15px;">
        <div class="card-body p-5 text-center">
            <h2 class="fw-bold mb-3">Siap Menjelajah?</h2>
            <p class="mb-4">Temukan ribuan film dari berbagai genre. Mulai petualangan film Anda sekarang!</p>
            <a href="/uas_web1/films" class="btn btn-light btn-lg px-4 btn-custom" style="border-color: #764ba2 !important;">
                <i class="fas fa-play-circle me-2"></i> Mulai Menonton
            </a>
        </div>
    </div>
</div>

<?php

$footerPath = $rootPath . '/footer.php';

if (file_exists($footerPath)) {
    include $footerPath;
} else {
    // Simple footer
    echo '<footer class="bg-dark text-white py-4 mt-5" style="border-top: 4px solid #6e42c1;">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <h5 class="mb-3"><i class="fas fa-film"></i> UAS Film Management</h5>
                    <p class="mb-0">© ' . date('Y') . ' - Sistem Manajemen Film</p>
                </div>
                <div class="col-md-6 text-end">
                    <p class="mb-0">
                        Dibuat dengan <i class="fas fa-heart text-danger"></i> untuk UAS Web 1
                        <br>
                        <span class="text-muted">v1.0.0</span>
                    </p>
                </div>
            </div>
        </div>
    </footer>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Tambahkan efek hover yang lebih smooth
        document.addEventListener("DOMContentLoaded", function() {
            const cards = document.querySelectorAll(".feature-card, .film-card");
            cards.forEach(card => {
                card.addEventListener("mouseenter", function() {
                    this.style.transition = "all 0.3s ease";
                });
            });
        });
    </script>
    </body>
    </html>';
}
?>