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
            <li class="breadcrumb-item"><a href="/uas_web1/films/show/<?php echo $film['id']; ?>"><?php echo htmlspecialchars($film['title']); ?></a></li>
            <li class="breadcrumb-item active">Edit Film</li>
        </ol>
    </nav>
    
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header bg-warning text-white">
                    <h3 class="mb-0"><i class="fas fa-edit"></i> Edit Film</h3>
                    <small class="opacity-75">ID Film: <?php echo $film['id']; ?></small>
                </div>
                <div class="card-body">
                    <form action="/uas_web1/films/update/<?php echo $film['id']; ?>" method="POST" enctype="multipart/form-data">
                        <!-- ID Film (readonly) -->
                        <div class="mb-3">
                            <label for="id" class="form-label">ID Film</label>
                            <input type="text" class="form-control bg-light" id="id" 
                                   value="<?php echo $film['id']; ?>" readonly>
                            <div class="form-text">ID film tidak dapat diubah</div>
                        </div>
                        
                        <div class="row">
                            <!-- Kolom Kiri -->
                            <div class="col-md-6">
                                <!-- Judul -->
                                <div class="mb-3">
                                    <label for="title" class="form-label">Judul Film <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="title" name="title" 
                                           value="<?php echo htmlspecialchars($film['title']); ?>" required>
                                </div>
                                
                                <!-- Genre -->
                                <div class="mb-3">
                                    <label for="genre" class="form-label">Genre <span class="text-danger">*</span></label>
                                    <select class="form-select" id="genre" name="genre" required>
                                        <option value="">Pilih Genre</option>
                                        <option value="Action" <?php echo $film['genre'] == 'Action' ? 'selected' : ''; ?>>Action</option>
                                        <option value="Drama" <?php echo $film['genre'] == 'Drama' ? 'selected' : ''; ?>>Drama</option>
                                        <option value="Comedy" <?php echo $film['genre'] == 'Comedy' ? 'selected' : ''; ?>>Comedy</option>
                                        <option value="Sci-Fi" <?php echo $film['genre'] == 'Sci-Fi' ? 'selected' : ''; ?>>Sci-Fi</option>
                                        <option value="Horror" <?php echo $film['genre'] == 'Horror' ? 'selected' : ''; ?>>Horror</option>
                                        <option value="Romance" <?php echo $film['genre'] == 'Romance' ? 'selected' : ''; ?>>Romance</option>
                                        <option value="Animation" <?php echo $film['genre'] == 'Animation' ? 'selected' : ''; ?>>Animation</option>
                                        <option value="Documentary" <?php echo $film['genre'] == 'Documentary' ? 'selected' : ''; ?>>Documentary</option>
                                        <option value="Adventure" <?php echo $film['genre'] == 'Adventure' ? 'selected' : ''; ?>>Adventure</option>
                                        <option value="Thriller" <?php echo $film['genre'] == 'Thriller' ? 'selected' : ''; ?>>Thriller</option>
                                    </select>
                                </div>
                                
                                <!-- Rating -->
                                <div class="mb-3">
                                    <label for="rating" class="form-label">Rating (0-10) <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control" id="rating" name="rating" 
                                           step="0.1" min="0" max="10" 
                                           value="<?php echo htmlspecialchars($film['rating']); ?>" required>
                                    <div class="form-text">
                                        <div class="progress mt-2" style="height: 10px;">
                                            <div class="progress-bar bg-warning" 
                                                 style="width: <?php echo $film['rating'] * 10; ?>%">
                                            </div>
                                        </div>
                                        <small>Current: <?php echo $film['rating']; ?> / 10</small>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Kolom Kanan -->
                            <div class="col-md-6">
                                <!-- Poster URL -->
                                <div class="mb-3">
                                    <label for="poster_url" class="form-label">Poster URL <span class="text-danger">*</span></label>
                                    <input type="url" class="form-control" id="poster_url" name="poster_url" 
                                           value="<?php echo htmlspecialchars($film['poster_url']); ?>" 
                                           required
                                           placeholder="https://example.com/poster.jpg">
                                    <div class="form-text">Masukkan URL gambar poster film</div>
                                </div>
                                
                                <!-- Upload Poster -->
                                <div class="mb-3">
                                    <label for="poster_file" class="form-label">Atau Upload Poster Baru</label>
                                    <input type="file" class="form-control" id="poster_file" name="poster_file" accept="image/*">
                                    <div class="form-text">Maksimal 2MB, format: JPG, PNG, GIF</div>
                                </div>
                                
                                <!-- Preview Poster -->
                                <div class="mb-3">
                                    <label class="form-label">Preview Poster Saat Ini</label>
                                    <div class="text-center">
                                        <img src="<?php echo htmlspecialchars($film['poster_url']); ?>" 
                                             alt="Current Poster" 
                                             class="img-thumbnail" 
                                             style="max-height: 200px; max-width: 100%;"
                                             onerror="this.src='/uas_web1/assets/images/default-poster.jpg'">
                                        <div class="mt-2">
                                            <a href="<?php echo htmlspecialchars($film['poster_url']); ?>" 
                                               target="_blank" class="btn btn-sm btn-outline-primary">
                                                <i class="fas fa-external-link-alt"></i> Buka Gambar
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Description -->
                        <div class="mb-3">
                            <label for="description" class="form-label">Sinopsis / Description <span class="text-danger">*</span></label>
                            <textarea class="form-control" id="description" name="description" rows="8" required><?php echo htmlspecialchars($film['description']); ?></textarea>
                            <div class="form-text"><?php echo strlen($film['description']); ?> karakter</div>
                        </div>
                        
                        <!-- Created At (readonly) -->
                        <div class="mb-3">
                            <label class="form-label">Informasi Sistem</label>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="input-group mb-2">
                                        <span class="input-group-text"><i class="far fa-calendar"></i></span>
                                        <input type="text" class="form-control bg-light" 
                                               value="Created: <?php echo date('d M Y H:i', strtotime($film['created_at'])); ?>" readonly>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="input-group mb-2">
                                        <span class="input-group-text"><i class="fas fa-sync-alt"></i></span>
                                        <input type="text" class="form-control bg-light" 
                                               value="Last Updated: <?php echo date('d M Y H:i'); ?>" readonly>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Action Buttons -->
                        <div class="d-flex justify-content-between mt-4 pt-3 border-top">
                            <div>
                                <a href="/uas_web1/films/show/<?php echo $film['id']; ?>" class="btn btn-outline-secondary">
                                    <i class="fas fa-times"></i> Batal
                                </a>
                                <a href="/uas_web1/films/delete/<?php echo $film['id']; ?>" 
                                   class="btn btn-outline-danger ms-2"
                                   onclick="return confirm('Yakin ingin menghapus film ini?')">
                                    <i class="fas fa-trash"></i> Hapus Film
                                </a>
                            </div>
                            <div>
                                <button type="reset" class="btn btn-outline-warning">
                                    <i class="fas fa-redo"></i> Reset
                                </button>
                                <button type="submit" class="btn btn-warning ms-2">
                                    <i class="fas fa-save"></i> Update Film
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>


<?php 
include $root_path . '/footer.php';
?>

<script>
// Live character counter
document.getElementById('description').addEventListener('input', function() {
    const charCount = this.value.length;
    this.nextElementSibling.textContent = charCount + ' karakter';
});

// Preview image when URL changes
document.getElementById('poster_url').addEventListener('input', function() {
    const preview = document.querySelector('.img-thumbnail');
    if (this.value) {
        preview.src = this.value;
    }
});

// Preview uploaded file
document.getElementById('poster_file').addEventListener('change', function(e) {
    const file = e.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            document.querySelector('.img-thumbnail').src = e.target.result;
        }
        reader.readAsDataURL(file);
    }
});
</script>

<style>
.form-label {
    font-weight: 600;
    color: #333;
}

.img-thumbnail {
    border: 2px solid #dee2e6;
    padding: 5px;
    background-color: #f8f9fa;
}

.btn-warning {
    background-color: #ffc107;
    border-color: #ffc107;
    color: #000;
    font-weight: 600;
    padding: 10px 30px;
}

.btn-warning:hover {
    background-color: #e0a800;
    border-color: #e0a800;
    color: #000;
}

.input-group-text {
    background-color: #f8f9fa;
}

.form-control:read-only, .form-control[readonly] {
    background-color: #f8f9fa;
    opacity: 1;
}
</style>
