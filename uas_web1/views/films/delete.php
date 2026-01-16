<?php 
$root_path = dirname(dirname(__DIR__));
include $root_path . '/header.php';
?>

<div class="container m-4">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card border-danger">
                <div class="card-header bg-danger text-white">
                    <h3 class="mb-0"><i class="fas fa-exclamation-triangle"></i> Konfirmasi Hapus Film</h3>
                </div>
                <div class="card-body text-center">
                    <!-- Warning Icon -->
                    <div class="mb-4">
                        <i class="fas fa-trash-alt fa-5x text-danger"></i>
                    </div>
                    
                    <h4 class="text-danger mb-3">Apakah Anda yakin ingin menghapus film ini?</h4>
                    
                    <!-- Film Info -->
                    <div class="alert alert-warning">
                        <h5><?php echo htmlspecialchars($film['title']); ?></h5>
                        <p class="mb-1"><strong>Genre:</strong> <?php echo htmlspecialchars($film['genre']); ?></p>
                        <p class="mb-1"><strong>Director:</strong> <?php echo htmlspecialchars($film['director']); ?></p>
                        <p class="mb-0"><strong>Tahun:</strong> <?php echo htmlspecialchars($film['release_year']); ?></p>
                    </div>
                    
                    <div class="alert alert-danger">
                        <h6><i class="fas fa-exclamation-circle"></i> Peringatan</h6>
                        <p class="mb-0">Tindakan ini tidak dapat dibatalkan. Semua data film akan dihapus secara permanen.</p>
                    </div>
                    
                    <!-- Action Buttons -->
                    <div class="d-flex justify-content-center gap-3 mt-4">
                        <a href="/uas_web1/films/show/<?php echo $film['id']; ?>" class="btn btn-outline-secondary btn-lg">
                            <i class="fas fa-times"></i> Batal
                        </a>
                        <form action="/uas_web1/films/destroy/<?php echo $film['id']; ?>" method="POST" class="d-inline">
                            <input type="hidden" name="_method" value="DELETE">
                            <button type="submit" class="btn btn-danger btn-lg">
                                <i class="fas fa-trash"></i> Ya, Hapus Film
                            </button>
                        </form>
                    </div>
                    
                    <!-- Additional Info -->
                    <div class="mt-4 text-muted">
                        <small>
                            <i class="fas fa-info-circle"></i> 
                            Film ini ditambahkan pada <?php echo date('d M Y', strtotime($film['created_at'])); ?>
                        </small>
                    </div>
                </div>
            </div>
            
            <!-- Related Films Warning -->
            <?php if (!empty($related_data)): ?>
            <div class="card mt-4 border-warning">
                <div class="card-header bg-warning">
                    <h5 class="mb-0"><i class="fas fa-exclamation-triangle"></i> Data Terkait</h5>
                </div>
                <div class="card-body">
                    <p>Menghapus film ini juga akan memengaruhi:</p>
                    <ul class="list-group">
                        <?php if (!empty($related_data['reviews'])): ?>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            Review/rating
                            <span class="badge bg-danger rounded-pill"><?php echo count($related_data['reviews']); ?></span>
                        </li>
                        <?php endif; ?>
                        <?php if (!empty($related_data['watchlists'])): ?>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            Watchlist user
                            <span class="badge bg-warning rounded-pill"><?php echo count($related_data['watchlists']); ?></span>
                        </li>
                        <?php endif; ?>
                    </ul>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<style>
.btn-lg {
    padding: 10px 30px;
    font-size: 18px;
}

.fa-trash-alt {
    opacity: 0.8;
}

.alert h6 {
    font-weight: 600;
    margin-bottom: 10px;
}

.list-group-item {
    border-left: none;
    border-right: none;
}

.list-group-item:first-child {
    border-top: none;
}
</style>

<?php 
include $root_path . '/footer.php';
?>