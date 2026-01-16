<?php 
$root_path = dirname(dirname(__DIR__));
include $root_path . '/header.php';
?>

<div class="container m-4">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/uas_web1">Home</a></li>
            <li class="breadcrumb-item"><a href="/uas_web1/films">Film</a></li>
            <li class="breadcrumb-item active"><?php echo htmlspecialchars($film['title']); ?></li>
        </ol>
    </nav>
    
    <div class="row">
        <!-- Poster Film -->
        <div class="col-md-4">
            <div class="card">
                <img src="<?php echo htmlspecialchars($film['poster_url']); ?>" 
                     class="card-img-top" 
                     alt="<?php echo htmlspecialchars($film['title']); ?>"
                     onerror="this.src='/uas_web1/assets/images/default-poster.jpg'">
                <div class="card-body text-center">
                    <div class="film-rating-large mb-3">
                        <span class="rating-star">⭐</span>
                        <span class="rating-value"><?php echo number_format($film['rating'], 1); ?></span>
                        <span class="rating-total">/10</span>
                    </div>
                    <div class="film-genre-badge mb-3">
                        <span class="badge bg-primary"><?php echo htmlspecialchars($film['genre']); ?></span>
                    </div>
                    <?php if (isset($_SESSION['role']) && $_SESSION['role'] == 'admin'): ?>
                    <div class="btn-group w-100">
                        <a href="/uas_web1/films/edit/<?php echo $film['id']; ?>" class="btn btn-outline-primary">
                            <i class="fas fa-edit"></i> Edit
                        </a>
                        <a href="/uas_web1/films/delete/<?php echo $film['id']; ?>" 
                           class="btn btn-outline-danger"
                           onclick="return confirm('Yakin ingin menghapus film ini?')">
                            <i class="fas fa-trash"></i> Hapus
                        </a>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        
        <!-- Detail Film -->
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h2 class="mb-0"><?php echo htmlspecialchars($film['title']); ?></h2>
                </div>
                <div class="card-body">
                    <div class="mb-4">
                        <h4 class="text-primary"><i class="fas fa-info-circle"></i> Sinopsis</h4>
                        <p class="lead"><?php echo nl2br(htmlspecialchars($film['description'])); ?></p>
                    </div>
                    
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <h5><i class="fas fa-info-circle text-primary"></i> Informasi Film</h5>
                            <table class="table table-bordered">
                                <tr>
                                    <th width="40%">ID Film</th>
                                    <td><?php echo htmlspecialchars($film['id']); ?></td>
                                </tr>
                                <tr>
                                    <th>Genre</th>
                                    <td><span class="badge bg-primary"><?php echo htmlspecialchars($film['genre']); ?></span></td>
                                </tr>
                                <tr>
                                    <th>Rating</th>
                                    <td>
                                        <span class="badge bg-warning text-dark">
                                            <?php echo number_format($film['rating'], 1); ?> / 10
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Poster URL</th>
                                    <td>
                                        <a href="<?php echo htmlspecialchars($film['poster_url']); ?>" 
                                           target="_blank" class="small">
                                            <?php 
                                            $url = htmlspecialchars($film['poster_url']);
                                            echo strlen($url) > 30 ? substr($url, 0, 30) . '...' : $url;
                                            ?>
                                        </a>
                                    </td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <h5><i class="fas fa-star text-warning"></i> Rating & Status</h5>
                            <div class="text-center mb-3">
                                <div class="display-4 text-warning"><?php echo number_format($film['rating'], 1); ?></div>
                                <div class="rating-stars">
                                    <?php 
                                    $rating = floor($film['rating']);
                                    for ($i = 1; $i <= 10; $i++): 
                                        if ($i <= $rating): ?>
                                            <span class="text-warning">★</span>
                                        <?php else: ?>
                                            <span class="text-muted">★</span>
                                        <?php endif;
                                    endfor; ?>
                                </div>
                                <div class="mt-2">
                                    <?php if ($film['rating'] >= 9.0): ?>
                                        <span class="badge bg-success">Excellent</span>
                                    <?php elseif ($film['rating'] >= 8.0): ?>
                                        <span class="badge bg-primary">Very Good</span>
                                    <?php elseif ($film['rating'] >= 7.0): ?>
                                        <span class="badge bg-info">Good</span>
                                    <?php else: ?>
                                        <span class="badge bg-secondary">Average</span>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Action Buttons -->
                    <div class="d-flex justify-content-between mt-4">
                        <a href="/uas_web1/films" class="btn btn-outline-secondary">
                            <i class="fas fa-arrow-left"></i> Kembali ke Daftar Film
                        </a>
                        <div>
                            <button class="btn btn-primary" onclick="shareFilm()">
                                <i class="fas fa-share"></i> Bagikan
                            </button>
                            <button class="btn btn-success ms-2" onclick="addToWatchlist()">
                                <i class="fas fa-plus"></i> Tambah ke Watchlist
                            </button>
                        </div>
                    </div>
                </div>
                <div class="card-footer text-muted">
                    <small>
                        <i class="far fa-clock"></i> Ditambahkan: <?php echo date('d M Y H:i', strtotime($film['created_at'])); ?>
                    </small>
                </div>
            </div>
            
            <!-- Related Films -->
            <?php if (!empty($related_films)): ?>
            <div class="card mt-4">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-film"></i> Film Serupa</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <?php foreach ($related_films as $related): ?>
                        <div class="col-md-4 mb-3">
                            <a href="/uas_web1/films/show/<?php echo $related['id']; ?>" class="text-decoration-none">
                                <div class="card h-100">
                                    <img src="<?php echo htmlspecialchars($related['poster_url']); ?>" 
                                         class="card-img-top" 
                                         alt="<?php echo htmlspecialchars($related['title']); ?>"
                                         style="height: 150px; object-fit: cover;">
                                    <div class="card-body">
                                        <h6 class="card-title"><?php echo htmlspecialchars($related['title']); ?></h6>
                                        <span class="badge bg-primary"><?php echo htmlspecialchars($related['genre']); ?></span>
                                        <span class="badge bg-warning text-dark float-end">
                                            <?php echo number_format($related['rating'], 1); ?>
                                        </span>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function addToWatchlist() {
    alert('Film "<?php echo addslashes($film['title']); ?>" berhasil ditambahkan ke watchlist!');
}

function shareFilm() {
    const url = window.location.href;
    const title = "<?php echo addslashes($film['title']); ?>";
    const text = `Check out this film: ${title}`;
    
    if (navigator.share) {
        navigator.share({
            title: title,
            text: text,
            url: url
        });
    } else {
        navigator.clipboard.writeText(url).then(() => {
            alert('Link film berhasil disalin ke clipboard!');
        });
    }
}
</script>

<style>
.rating-stars {
    font-size: 20px;
    margin: 10px 0;
}

.film-rating-large {
    font-size: 24px;
    font-weight: bold;
}

.film-rating-large .rating-star {
    color: gold;
    font-size: 28px;
}

.film-rating-large .rating-value {
    font-size: 32px;
}

.film-rating-large .rating-total {
    color: #666;
    font-size: 18px;
}

.breadcrumb {
    background-color: #f8f9fa;
    border-radius: 5px;
    padding: 10px 15px;
}

.card-img-top {
    height: 400px;
    object-fit: cover;
}

.badge {
    font-weight: 500;
}
</style>

<?php 
include $root_path . '/footer.php';
?>
<?php endif; ?>