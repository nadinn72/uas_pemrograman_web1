<?php 
$root_path = dirname(dirname(__DIR__));

// Include header
include $root_path . '/header.php';
?>

<div class="container">
    <h1 class="page-title">
        <i class="fas fa-user-edit"></i> Edit Profil
    </h1>
    
    <div class="profile-edit-container">
        <div class="edit-card">
            <?php if (isset($error)): ?>
            <div class="alert alert-danger">
                <i class="fas fa-exclamation-circle"></i> <?php echo $error; ?>
            </div>
            <?php endif; ?>
            
            <form action="/uas_web1/profile/edit" method="POST" id="profileForm">
                <div class="form-section">
                    <h3><i class="fas fa-user"></i> Informasi Dasar</h3>
                    
                    <div class="form-group">
                        <label for="name" class="form-label">Nama Lengkap</label>
                        <input type="text" id="name" name="name" class="form-control" 
                               value="<?php echo htmlspecialchars($user['name']); ?>" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" id="email" name="email" class="form-control" 
                               value="<?php echo htmlspecialchars($user['email']); ?>" required>
                    </div>
                </div>
                
                <div class="form-section">
                    <h3><i class="fas fa-lock"></i> Ubah Password</h3>
                    <p class="form-help">Kosongkan jika tidak ingin mengubah password</p>
                    
                    <div class="form-group">
                        <label for="current_password" class="form-label">Password Saat Ini</label>
                        <input type="password" id="current_password" name="current_password" class="form-control">
                    </div>
                    
                    <div class="form-group">
                        <label for="new_password" class="form-label">Password Baru</label>
                        <input type="password" id="new_password" name="new_password" class="form-control">
                        <small class="form-text">Minimal 6 karakter</small>
                    </div>
                    
                    <div class="form-group">
                        <label for="confirm_password" class="form-label">Konfirmasi Password Baru</label>
                        <input type="password" id="confirm_password" name="confirm_password" class="form-control">
                    </div>
                </div>
                
                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Simpan Perubahan
                    </button>
                    <a href="/uas_web1/profile" class="btn btn-secondary">
                        <i class="fas fa-times"></i> Batal
                    </a>
                </div>
            </form>
        </div>
        
        <div class="info-sidebar">
            <div class="info-box">
                <h4><i class="fas fa-info-circle"></i> Tips Keamanan</h4>
                <ul>
                    <li><i class="fas fa-check-circle"></i> Gunakan password yang kuat</li>
                    <li><i class="fas fa-check-circle"></i> Jangan gunakan password yang sama dengan akun lain</li>
                    <li><i class="fas fa-check-circle"></i> Pastikan email valid</li>
                </ul>
            </div>
            
            <div class="info-box">
                <h4><i class="fas fa-history"></i> Aktivitas Terakhir</h4>
                <p>Login terakhir: <?php echo date('d M Y H:i'); ?></p>
                <p>Role: <?php echo ucfirst($user['role']); ?></p>
            </div>
        </div>
    </div>
</div>

<style>
.profile-edit-container {
    display: flex;
    gap: 2rem;
    flex-wrap: wrap;
}

.edit-card {
    flex: 2;
    min-width: 300px;
    background: var(--white);
    border-radius: 15px;
    padding: 2rem;
    box-shadow: 0 5px 20px rgba(0, 0, 0, 0.08);
}

.info-sidebar {
    flex: 1;
    min-width: 250px;
}

.form-section {
    margin-bottom: 2rem;
    padding-bottom: 2rem;
    border-bottom: 1px solid #eee;
}

.form-section:last-child {
    border-bottom: none;
}

.form-section h3 {
    color: var(--purple-dark);
    margin-bottom: 1.5rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.form-help {
    color: var(--gray-dark);
    font-size: 0.9rem;
    margin-bottom: 1.5rem;
    padding: 0.5rem;
    background: var(--gray);
    border-radius: 6px;
}

.form-actions {
    display: flex;
    gap: 1rem;
    margin-top: 2rem;
    padding-top: 1.5rem;
    border-top: 1px solid #eee;
}

.info-box {
    background: var(--white);
    border-radius: 15px;
    padding: 1.5rem;
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
    margin-bottom: 1.5rem;
}

.info-box h4 {
    color: var(--purple-dark);
    margin-bottom: 1rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.info-box ul {
    list-style: none;
    padding: 0;
}

.info-box ul li {
    padding: 0.3rem 0;
    display: flex;
    align-items: center;
    gap: 0.5rem;
    color: var(--gray-dark);
}

.info-box ul li i {
    color: var(--purple);
}

.info-box p {
    color: var(--gray-dark);
    margin: 0.5rem 0;
}

@media (max-width: 768px) {
    .profile-edit-container {
        flex-direction: column;
    }
    
    .edit-card, .info-sidebar {
        width: 100%;
    }
}
</style>

<script>
document.getElementById('profileForm').addEventListener('submit', function(e) {
    const newPassword = document.getElementById('new_password').value;
    const confirmPassword = document.getElementById('confirm_password').value;
    const currentPassword = document.getElementById('current_password').value;
    
    if (newPassword || confirmPassword) {
        if (!currentPassword) {
            e.preventDefault();
            alert('Harap masukkan password saat ini untuk mengubah password');
            document.getElementById('current_password').focus();
            return false;
        }
        
        if (newPassword.length < 6) {
            e.preventDefault();
            alert('Password baru minimal 6 karakter');
            document.getElementById('new_password').focus();
            return false;
        }
        
        if (newPassword !== confirmPassword) {
            e.preventDefault();
            alert('Konfirmasi password tidak cocok');
            document.getElementById('confirm_password').focus();
            return false;
        }
    }
    
    return true;
});
</script>

<?php 
include $root_path . '/footer.php';
?>