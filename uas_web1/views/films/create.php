<?php

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

<div class="container">
    <h1 class="page-title">Tambah Film Baru</h1>
    
    <div class="card">
        <form action="/films/create" method="POST">
            <div class="form-group">
                <label for="title" class="form-label">Judul Film</label>
                <input type="text" id="title" name="title" class="form-control" required>
            </div>
            
            <div class="form-group">
                <label for="genre" class="form-label">Genre</label>
                <select id="genre" name="genre" class="form-control" required>
                    <option value="">Pilih Genre</option>
                    <option value="Action">Action</option>
                    <option value="Drama">Drama</option>
                    <option value="Comedy">Comedy</option>
                    <option value="Sci-Fi">Sci-Fi</option>
                    <option value="Horror">Horror</option>
                    <option value="Romance">Romance</option>
                    <option value="Thriller">Thriller</option>
                    <option value="Animation">Animation</option>
                </select>
            </div>
            
            <div class="form-group">
                <label for="rating" class="form-label">Rating (0-10)</label>
                <input type="number" id="rating" name="rating" class="form-control" 
                       min="0" max="10" step="0.1" value="7.0" required>
            </div>
            
            <div class="form-group">
                <label for="poster_url" class="form-label">URL Poster Film</label>
                <input type="url" id="poster_url" name="poster_url" class="form-control" 
                       placeholder="https://example.com/poster.jpg">
                <small class="form-text">Biarkan kosong untuk menggunakan poster default</small>
            </div>
            
            <div class="form-group">
                <label for="description" class="form-label">Deskripsi Film</label>
                <textarea id="description" name="description" class="form-control" 
                          rows="5" required></textarea>
            </div>
            
            <div class="form-group">
                <button type="submit" class="btn btn-primary">Simpan Film</button>
                <a href="/films" class="btn btn-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>