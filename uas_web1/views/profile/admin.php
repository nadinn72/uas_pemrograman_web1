<?php 
// Set page title untuk header
$page_title = 'Dashboard Admin';
$root_path = dirname(dirname(__DIR__));
$header_path = $root_path . '/header.php';

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

<div class="container">
    <h1 class="page-title mb-4">Dashboard Admin</h1>
    
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card">
                <div class="card-body text-center">
                    <h2 class="display-4 text-primary">2</h2>
                    <p class="text-muted">Total Pengguna</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body text-center">
                    <h2 class="display-4 text-success">6</h2>
                    <p class="text-muted">Total Film</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body text-center">
                    <h2 class="display-4 text-warning">2</h2>
                    <p class="text-muted">User Baru (Bulan Ini)</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body text-center">
                    <h2 class="display-4 text-danger">1</h2>
                    <p class="text-muted">Total Admin</p>
                </div>
            </div>
        </div>
    </div>

    <hr class="my-4">

    <div class="row">
        <!-- Aksi Cepat -->
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-bolt me-2"></i>Aksi Cepat</h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="/uas_web1/films/create" class="btn btn-primary">
                            <i class="fas fa-plus me-2"></i>Tambah Film Baru
                        </a>
                        <a href="/uas_web1/profile/users/create" class="btn btn-success">
                            <i class="fas fa-user-plus me-2"></i>Tambah Pengguna
                        </a>
                        <a href="#" class="btn btn-info">
                            <i class="fas fa-cog me-2"></i>Pengaturan Sistem
                        </a>
                        <a href="#" class="btn btn-warning">
                            <i class="fas fa-chart-bar me-2"></i>Lihat Laporan
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- User Terbaru -->
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-users me-2"></i>User Terbaru</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Nama</th>
                                    <th>Email</th>
                                    <th>Role</th>
                                    <th>Tanggal Daftar</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>1</td>
                                    <td>Admin</td>
                                    <td>admin@film.com</td>
                                    <td><span class="badge bg-primary">ADMIN</span></td>
                                    <td>13 Jan 2026</td>
                                    <td>
                                        <button class="btn btn-sm btn-outline-primary">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                    </td>
                                </tr>
                                <tr>
                                    <td>2</td>
                                    <td>User</td>
                                    <td>user@film.com</td>
                                    <td><span class="badge bg-success">USER</span></td>
                                    <td>13 Jan 2026</td>
                                    <td>
                                        <button class="btn btn-sm btn-outline-primary">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Tambahan fitur admin lainnya -->
    <div class="row mt-4">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-film me-2"></i>Film Terbaru</h5>
                </div>
                <div class="card-body">
                    <div class="list-group">
                        <a href="#" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="mb-1">Avengers: Endgame</h6>
                                <small class="text-muted">Ditambahkan: 12 Jan 2026</small>
                            </div>
                            <span class="badge bg-primary rounded-pill">Aksi</span>
                        </a>
                        <a href="#" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="mb-1">Inception</h6>
                                <small class="text-muted">Ditambahkan: 11 Jan 2026</small>
                            </div>
                            <span class="badge bg-warning rounded-pill">Sci-Fi</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-chart-line me-2"></i>Statistik Sistem</h5>
                </div>
                <div class="card-body">
                    <ul class="list-unstyled">
                        <li class="mb-2">
                            <i class="fas fa-hdd text-primary me-2"></i>
                            Penggunaan Storage: <strong>45%</strong>
                            <div class="progress mt-1">
                                <div class="progress-bar bg-primary" style="width: 45%"></div>
                            </div>
                        </li>
                        <li class="mb-2">
                            <i class="fas fa-database text-success me-2"></i>
                            Total Data Film: <strong>6 item</strong>
                        </li>
                        <li class="mb-2">
                            <i class="fas fa-user-check text-info me-2"></i>
                            User Aktif: <strong>2 user</strong>
                        </li>
                        <li>
                            <i class="fas fa-calendar-alt text-warning me-2"></i>
                            Pembaruan Terakhir: <strong>13 Jan 2026</strong>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
// Coba include footer dengan beberapa alternatif path
$footer_included = false;

// Alternatif path untuk footer
$footer_path1 = __DIR__ . '/../footer.php';
$footer_path2 = dirname(__DIR__) . '/footer.php';
$footer_path3 = 'C:/xampp/htdocs/uas_web1/footer.php';

$footer_paths = [$footer_path1, $footer_path2, $footer_path3];

foreach ($footer_paths as $path) {
    if (file_exists($path)) {
        include $path;
        $footer_included = true;
        break;
    }
}

// Jika footer tidak ditemukan, buat minimal footer
if (!$footer_included) {
    echo '
    </main>
    <footer class="footer mt-5 py-3 bg-light border-top">
        <div class="container text-center">
            <p class="mb-0 text-muted">
                &copy; ' . date('Y') . ' UAS Film. All rights reserved.
            </p>
        </div>
    </footer>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    </body>
    </html>';
}
?>