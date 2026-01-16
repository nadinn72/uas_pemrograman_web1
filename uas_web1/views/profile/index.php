<?php
// LINE 1-10: Hapus session_start() karena sudah dimulai di controller
// session_start(); // <-- HAPUS BARIS INI

// Debug: Cek apakah data user ada
if (!isset($user)) {
    echo "Error: Data user tidak ditemukan!<br>";
    echo "Silakan kembali ke <a href='/uas_web1'>halaman utama</a>";
    exit();
}

$root_path = dirname(dirname(__DIR__));
$header_path = $root_path . '/header.php';

// Debug path
// echo "Root path: " . $root_path . "<br>";
// echo "Header path: " . $header_path . "<br>";
// echo "File exists: " . (file_exists($header_path) ? 'YES' : 'NO') . "<br>";

if (file_exists($header_path)) {
    include $header_path;
} else {
    echo "<h3>Error: Header file tidak ditemukan!</h3>";
    echo "<p>Path yang dicari: " . $header_path . "</p>";
    echo "<p>Coba alternatif:</p>";
    
    // Coba beberapa alternatif path
    $alt_paths = [
        $root_path . '/header.php',
        dirname(__DIR__) . '/../header.php',
        'C:/xampp/htdocs/uas_web1/header.php',
        __DIR__ . '/../../../header.php'
    ];
    
    foreach ($alt_paths as $alt) {
        echo "• " . $alt . " = " . (file_exists($alt) ? 'ADA' : 'TIDAK ADA') . "<br>";
    }
    
    exit();
}
?>

<!-- KONTEN HTML ANDA (sama seperti sebelumnya) -->
<div class="container">
    <h1 class="page-title">
        <i class="fas fa-user-circle"></i> Profil Saya
    </h1>
    
    <div class="profile-container">
        <div class="row">
            <!-- Sidebar Profil -->
            <div class="col-sidebar">
                <div class="profile-card">
                    <div class="profile-avatar">
                        <div class="avatar-circle">
                            <i class="fas fa-user"></i>
                        </div>
                        <h3><?php echo htmlspecialchars($user['name']); ?></h3>
                        <p class="user-email"><?php echo htmlspecialchars($user['email']); ?></p>
                        <span class="user-role <?php echo htmlspecialchars($user['role']); ?>">
                            <?php echo ucfirst(htmlspecialchars($user['role'])); ?>
                        </span>
                    </div>
                    
                    <div class="profile-stats">
                        <div class="stat-item">
                            <i class="fas fa-calendar-alt"></i>
                            <div>
                                <small>Bergabung</small>
                                <p><?php echo date('d M Y', strtotime($user['created_at'])); ?></p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="profile-actions">
                        <a href="/uas_web1/profile/edit" class="btn btn-primary btn-block">
                            <i class="fas fa-edit"></i> Edit Profil
                        </a>
                        <?php if (isset($_SESSION['role']) && $_SESSION['role'] == 'admin'): ?>
                        <a href="/uas_web1/profile/admin" class="btn btn-secondary btn-block">
                            <i class="fas fa-tachometer-alt"></i> Dashboard Admin
                        </a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            
            <!-- Konten Utama -->
            <div class="col-content">
                <div class="info-card">
                    <h3><i class="fas fa-info-circle"></i> Informasi Akun</h3>
                    <div class="info-grid">
                        <div class="info-item">
                            <label><i class="fas fa-id-card"></i> ID Pengguna</label>
                            <p><?php echo htmlspecialchars($user['id']); ?></p>
                        </div>
                        <div class="info-item">
                            <label><i class="fas fa-user"></i> Nama Lengkap</label>
                            <p><?php echo htmlspecialchars($user['name']); ?></p>
                        </div>
                        <div class="info-item">
                            <label><i class="fas fa-envelope"></i> Email</label>
                            <p><?php echo htmlspecialchars($user['email']); ?></p>
                        </div>
                        <div class="info-item">
                            <label><i class="fas fa-user-tag"></i> Role</label>
                            <p><span class="role-badge <?php echo htmlspecialchars($user['role']); ?>">
                                <?php echo ucfirst(htmlspecialchars($user['role'])); ?>
                            </span></p>
                        </div>
                        <div class="info-item">
                            <label><i class="fas fa-calendar-plus"></i> Tanggal Bergabung</label>
                            <p><?php echo date('d F Y H:i', strtotime($user['created_at'])); ?></p>
                        </div>
                        <div class="info-item">
                            <label><i class="fas fa-clock"></i> Durasi Keanggotaan</label>
                            <p>
                                <?php 
                                $join_date = new DateTime($user['created_at']);
                                $now = new DateTime();
                                $interval = $join_date->diff($now);
                                echo $interval->format('%a hari');
                                ?>
                            </p>
                        </div>
                    </div>
                </div>
                
                <!-- Quick Actions -->
                <div class="quick-actions">
                    <h3><i class="fas fa-bolt"></i> Aksi Cepat</h3>
                    <div class="actions-grid">
                        <a href="/uas_web1/films" class="action-card">
                            <i class="fas fa-film"></i>
                            <span>Lihat Film</span>
                        </a>
                        <?php if (isset($_SESSION['role']) && $_SESSION['role'] == 'admin'): ?>
                        <a href="/uas_web1/films/create" class="action-card">
                            <i class="fas fa-plus-circle"></i>
                            <span>Tambah Film</span>
                        </a>
                        <a href="/uas_web1/profile/users" class="action-card">
                            <i class="fas fa-users"></i>
                            <span>Kelola User</span>
                        </a>
                        <?php endif; ?>
                        <a href="/uas_web1/profile/edit" class="action-card">
                            <i class="fas fa-cog"></i>
                            <span>Pengaturan</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
/* Profile Styles - SAMA SEPERTI SEBELUMNYA */
.profile-container {
    display: flex;
    flex-direction: column;
    gap: 2rem;
}

.row {
    display: flex;
    gap: 2rem;
    flex-wrap: wrap;
}

.col-sidebar {
    flex: 1;
    min-width: 300px;
    max-width: 350px;
}

.col-content {
    flex: 2;
    min-width: 300px;
}

.profile-card {
    background: var(--white);
    border-radius: 15px;
    padding: 2rem;
    box-shadow: 0 5px 20px rgba(0, 0, 0, 0.08);
    text-align: center;
}

.profile-avatar {
    margin-bottom: 1.5rem;
}

.avatar-circle {
    width: 100px;
    height: 100px;
    border-radius: 50%;
    background: linear-gradient(135deg, var(--purple), var(--purple-dark));
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 1rem;
    color: white;
    font-size: 2.5rem;
}

.profile-avatar h3 {
    color: var(--purple-dark);
    margin-bottom: 0.5rem;
}

.user-email {
    color: var(--gray-dark);
    margin-bottom: 1rem;
    font-size: 0.9rem;
}

.user-role {
    display: inline-block;
    padding: 0.3rem 1rem;
    border-radius: 20px;
    font-size: 0.8rem;
    font-weight: 600;
    text-transform: uppercase;
}

.user-role.admin {
    background: linear-gradient(135deg, var(--purple), var(--purple-dark));
    color: white;
}

.user-role.user {
    background: linear-gradient(135deg, var(--peach), var(--peach-dark));
    color: var(--purple-dark);
}

.profile-stats {
    border-top: 1px solid #eee;
    border-bottom: 1px solid #eee;
    padding: 1rem 0;
    margin: 1.5rem 0;
}

.stat-item {
    display: flex;
    align-items: center;
    gap: 1rem;
    padding: 0.5rem 0;
}

.stat-item i {
    color: var(--purple);
    font-size: 1.2rem;
}

.stat-item small {
    color: var(--gray-dark);
    display: block;
    font-size: 0.8rem;
}

.stat-item p {
    color: var(--purple-dark);
    font-weight: 600;
    margin: 0;
}

.profile-actions {
    display: flex;
    flex-direction: column;
    gap: 0.8rem;
}

.btn-block {
    width: 100%;
}

.info-card {
    background: var(--white);
    border-radius: 15px;
    padding: 2rem;
    box-shadow: 0 5px 20px rgba(0, 0, 0, 0.08);
    margin-bottom: 2rem;
}

.info-card h3 {
    color: var(--purple-dark);
    margin-bottom: 1.5rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.info-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 1.5rem;
}

.info-item {
    padding: 1rem;
    background: var(--gray);
    border-radius: 10px;
    border-left: 4px solid var(--purple);
}

.info-item label {
    color: var(--purple);
    font-weight: 600;
    font-size: 0.9rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
    margin-bottom: 0.5rem;
}

.info-item p {
    color: var(--gray-dark);
    margin: 0;
    font-size: 1rem;
}

.role-badge {
    display: inline-block;
    padding: 0.2rem 0.8rem;
    border-radius: 15px;
    font-size: 0.8rem;
    font-weight: 600;
}

.role-badge.admin {
    background: linear-gradient(135deg, var(--purple), var(--purple-dark));
    color: white;
}

.role-badge.user {
    background: linear-gradient(135deg, var(--peach), var(--peach-dark));
    color: var(--purple-dark);
}

.quick-actions {
    background: var(--white);
    border-radius: 15px;
    padding: 2rem;
    box-shadow: 0 5px 20px rgba(0, 0, 0, 0.08);
}

.quick-actions h3 {
    color: var(--purple-dark);
    margin-bottom: 1.5rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.actions-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
    gap: 1rem;
}

.action-card {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    padding: 1.5rem 1rem;
    background: linear-gradient(135deg, var(--purple-light), var(--purple));
    border-radius: 10px;
    color: white;
    text-decoration: none;
    transition: all 0.3s ease;
    text-align: center;
}

.action-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 20px rgba(147, 112, 219, 0.3);
    background: linear-gradient(135deg, var(--purple), var(--purple-dark));
}

.action-card i {
    font-size: 2rem;
    margin-bottom: 0.8rem;
}

.action-card span {
    font-weight: 600;
    font-size: 0.9rem;
}

/* Responsive */
@media (max-width: 768px) {
    .row {
        flex-direction: column;
    }
    
    .col-sidebar, .col-content {
        max-width: 100%;
    }
    
    .info-grid {
        grid-template-columns: 1fr;
    }
    
    .actions-grid {
        grid-template-columns: repeat(2, 1fr);
    }
}

@media (max-width: 480px) {
    .profile-card, .info-card, .quick-actions {
        padding: 1.5rem;
    }
    
    .actions-grid {
        grid-template-columns: 1fr;
    }
}
</style>

<?php
// Include footer
$footer_path = $root_path . DIRECTORY_SEPARATOR . 'footer.php';
if (file_exists($footer_path)) {
    include $footer_path;
} else {
    echo '<!-- Footer file not found at: ' . $footer_path . ' -->';
    
    // TAMPILKAN FOOTER DEFAULT & TUTUP TAG
    echo '
    <footer class="mt-5 py-4 bg-dark text-white">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <h5><i class="fas fa-film"></i> Film Management System</h5>
                    <p class="mb-0">&copy; ' . date('Y') . ' - UAS Web Programming</p>
                </div>
                <div class="col-md-6 text-end">
                    <p class="mb-0">User Profile</p>
                </div>
            </div>
        </div>
    </footer>
    
    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Tambahan script jika diperlukan
    </script>
    </body>
    </html>';
}
?>