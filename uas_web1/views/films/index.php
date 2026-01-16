<?php 
$root_path = dirname(dirname(__DIR__));

// Include header
include $root_path . '/header.php';
?>

<div class="container m-4">
    <h1 class="mb-4"><i class="fas fa-film"></i> Daftar Film</h1>
    
    <?php if ($_SESSION['role'] == 'admin'): ?>
    <div class="mb-3">
        <a href="/uas_web1/films/create" class="btn btn-primary">Tambah Film Baru</a>
    </div>
    <?php endif; ?>
    
    <!-- Search & Filter -->
    <div class="search-filter">
        <div class="search-box">
            <input type="text" id="searchInput" class="form-control" placeholder="Cari film berdasarkan judul atau genre...">
        </div>
        <select id="genreFilter" class="filter-select">
            <option value="">Semua Genre</option>
            <option value="Action">Action</option>
            <option value="Drama">Drama</option>
            <option value="Comedy">Comedy</option>
            <option value="Sci-Fi">Sci-Fi</option>
            <option value="Horror">Horror</option>
        </select>
        <select id="sortFilter" class="filter-select">
            <option value="newest">Terbaru</option>
            <option value="rating">Rating Tertinggi</option>
            <option value="title">Judul A-Z</option>
        </select>
    </div>
    
    <!-- Film Grid -->
    <div class="film-grid" id="filmContainer">
        <?php foreach ($films as $film): ?>
        <div class="film-card" data-genre="<?= htmlspecialchars($film['genre']) ?>">
            <img src="<?= htmlspecialchars($film['poster_url'] ?: 'assets/images/default-poster.jpg') ?>" 
                 alt="<?= htmlspecialchars($film['title']) ?>" class="film-poster">
            <div class="film-info">
                <h3 class="film-title"><?= htmlspecialchars($film['title']) ?></h3>
                <p class="film-genre"><?= htmlspecialchars($film['genre']) ?></p>
                <div class="film-rating">
                    <span>⭐</span>
                    <span><?= number_format($film['rating'], 1) ?></span>
                </div>
                <p class="film-description"><?= substr(htmlspecialchars($film['description']), 0, 100) ?>...</p>
                <div class="film-actions">
                    <a href="/uas_web1/films/show/<?= $film['id'] ?>" class="btn btn-primary btn-small">Detail</a>
                    <?php if ($_SESSION['role'] == 'admin'): ?>
                    <a href="/uas_web1/films/edit/<?= $film['id'] ?>" class="btn btn-secondary btn-small">Edit</a>
                    <a href="/uas_web1/films/delete/<?= $film['id'] ?>" 
                       class="btn btn-danger btn-small" 
                       onclick="return confirm('Hapus film ini?')">Hapus</a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
    
    <!-- Pagination -->
    <?php if ($totalPages > 1): ?>
    <ul class="pagination">
        <?php for ($i = 1; $i <= $totalPages; $i++): ?>
        <li class="page-item <?= $currentPage == $i ? 'active' : '' ?>">
            <a href="/films?page=<?= $i ?>"><?= $i ?></a>
        </li>
        <?php endfor; ?>
    </ul>
    <?php endif; ?>
</div>

<script>
// Client-side filtering
document.getElementById('searchInput').addEventListener('input', filterFilms);
document.getElementById('genreFilter').addEventListener('change', filterFilms);
document.getElementById('sortFilter').addEventListener('change', sortFilms);

function filterFilms() {
    const searchTerm = document.getElementById('searchInput').value.toLowerCase();
    const selectedGenre = document.getElementById('genreFilter').value;
    const filmCards = document.querySelectorAll('.film-card');
    
    filmCards.forEach(card => {
        const title = card.querySelector('.film-title').textContent.toLowerCase();
        const genre = card.getAttribute('data-genre');
        const description = card.querySelector('.film-description').textContent.toLowerCase();
        
        const matchesSearch = title.includes(searchTerm) || description.includes(searchTerm);
        const matchesGenre = !selectedGenre || genre === selectedGenre;
        
        card.style.display = (matchesSearch && matchesGenre) ? 'block' : 'none';
    });
}

function sortFilms() {
    const sortBy = document.getElementById('sortFilter').value;
    const container = document.getElementById('filmContainer');
    const filmCards = Array.from(container.querySelectorAll('.film-card'));
    
    filmCards.sort((a, b) => {
        if (sortBy === 'title') {
            return a.querySelector('.film-title').textContent.localeCompare(b.querySelector('.film-title').textContent);
        } else if (sortBy === 'rating') {
            const ratingA = parseFloat(a.querySelector('.film-rating span:nth-child(2)').textContent);
            const ratingB = parseFloat(b.querySelector('.film-rating span:nth-child(2)').textContent);
            return ratingB - ratingA;
        } else {
            // Default: newest (by ID)
            return 0;
        }
    });
    
    // Re-append sorted cards
    filmCards.forEach(card => container.appendChild(card));
}
</script>
<style>
/* Film Grid Styles */
.film-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
    gap: 20px;
    margin: 30px 0;
}

.film-card {
    background: white;
    border-radius: 10px;
    overflow: hidden;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
    transition: transform 0.3s, box-shadow 0.3s;
}

/* ... dan seterusnya (salin semua CSS di sini) ... */
</style>

<?php include 'views/footer.php'; ?>