<?php 
$root_path = dirname(dirname(__DIR__));

// Include header
include $root_path . '/header.php';
?>

<div class="container">
    <h1 class="page-title">
        <i class="fas fa-users-cog"></i> Kelola Pengguna
    </h1>
    
    <!-- User Management -->
    <div class="user-management">
        <div class="management-header">
            <h2>Daftar Pengguna</h2>
            <div class="header-actions">
                <div class="search-box">
                    <input type="text" id="userSearch" placeholder="Cari pengguna..." class="form-control">
                </div>
            </div>
        </div>
        
        <div class="table-responsive">
            <table class="user-table" id="userTable">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nama</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Tanggal Bergabung</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($users)): ?>
                        <?php foreach ($users as $user): ?>
                        <tr data-user-id="<?php echo $user['id']; ?>">
                            <td><?php echo $user['id']; ?></td>
                            <td>
                                <div class="user-info">
                                    <div class="user-avatar-small">
                                        <i class="fas fa-user"></i>
                                    </div>
                                    <div>
                                        <strong><?php echo htmlspecialchars($user['name']); ?></strong>
                                    </div>
                                </div>
                            </td>
                            <td><?php echo htmlspecialchars($user['email']); ?></td>
                            <td>
                                <span class="role-badge <?php echo $user['role']; ?>">
                                    <?php echo ucfirst($user['role']); ?>
                                </span>
                            </td>
                            <td><?php echo date('d M Y', strtotime($user['created_at'])); ?></td>
                            <td>
                                <div class="action-buttons">
                                    <button class="btn-action edit-user" data-id="<?php echo $user['id']; ?>" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button class="btn-action delete-user" data-id="<?php echo $user['id']; ?>" data-name="<?php echo htmlspecialchars($user['name']); ?>" title="Hapus">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                    <?php if ($user['role'] == 'user'): ?>
                                    <button class="btn-action promote-user" data-id="<?php echo $user['id']; ?>" data-name="<?php echo htmlspecialchars($user['name']); ?>" title="Jadikan Admin">
                                        <i class="fas fa-star"></i>
                                    </button>
                                    <?php elseif ($user['role'] == 'admin' && $user['id'] != $_SESSION['user_id']): ?>
                                    <button class="btn-action demote-user" data-id="<?php echo $user['id']; ?>" data-name="<?php echo htmlspecialchars($user['name']); ?>" title="Jadikan User">
                                        <i class="fas fa-user"></i>
                                    </button>
                                    <?php endif; ?>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6" class="text-center">
                                <div class="no-data">
                                    <i class="fas fa-users-slash"></i>
                                    <p>Tidak ada pengguna</p>
                                </div>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        <?php if ($total_pages > 1): ?>
        <div class="pagination-container">
            <ul class="pagination">
                <?php if ($page > 1): ?>
                <li class="page-item">
                    <a href="/uas_web1/profile/users?page=<?php echo $page - 1; ?>" class="page-link">
                        <i class="fas fa-chevron-left"></i>
                    </a>
                </li>
                <?php endif; ?>
                
                <?php 
                $startPage = max(1, $page - 2);
                $endPage = min($total_pages, $page + 2);
                
                for ($i = $startPage; $i <= $endPage; $i++): ?>
                <li class="page-item <?php echo $page == $i ? 'active' : ''; ?>">
                    <a href="/uas_web1/profile/users?page=<?php echo $i; ?>" class="page-link"><?php echo $i; ?></a>
                </li>
                <?php endfor; ?>
                
                <?php if ($page < $total_pages): ?>
                <li class="page-item">
                    <a href="/uas_web1/profile/users?page=<?php echo $page + 1; ?>" class="page-link">
                        <i class="fas fa-chevron-right"></i>
                    </a>
                </li>
                <?php endif; ?>
            </ul>
            
            <div class="page-info">
                Halaman <?php echo $page; ?> dari <?php echo $total_pages; ?>
            </div>
        </div>
        <?php endif; ?>
    </div>
</div>

<!-- Edit User Modal -->
<div id="editUserModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h3><i class="fas fa-user-edit"></i> Edit Pengguna</h3>
            <button class="close-modal">&times;</button>
        </div>
        <div class="modal-body">
            <form id="editUserForm">
                <input type="hidden" id="editUserId" name="id">
                
                <div class="form-group">
                    <label for="editUserName" class="form-label">Nama</label>
                    <input type="text" id="editUserName" name="name" class="form-control" required>
                </div>
                
                <div class="form-group">
                    <label for="editUserEmail" class="form-label">Email</label>
                    <input type="email" id="editUserEmail" name="email" class="form-control" required>
                </div>
                
                <div class="form-group">
                    <label for="editUserRole" class="form-label">Role</label>
                    <select id="editUserRole" name="role" class="form-control" required>
                        <option value="user">User</option>
                        <option value="admin">Admin</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label class="form-label">Ubah Password</label>
                    <input type="password" id="editUserPassword" name="password" class="form-control" placeholder="Kosongkan jika tidak ingin mengubah">
                </div>
            </form>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary close-modal">Batal</button>
            <button type="button" class="btn btn-primary" id="saveUserChanges">Simpan</button>
        </div>
    </div>
</div>

<style>
.user-management {
    background: var(--white);
    border-radius: 15px;
    padding: 2rem;
    box-shadow: 0 5px 20px rgba(0, 0, 0, 0.08);
}

.management-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 2rem;
    flex-wrap: wrap;
    gap: 1rem;
}

.management-header h2 {
    color: var(--purple-dark);
    margin: 0;
}

.header-actions {
    display: flex;
    gap: 1rem;
    align-items: center;
}

.search-box {
    min-width: 300px;
}

.user-table {
    width: 100%;
    border-collapse: collapse;
}

.user-table thead {
    background: linear-gradient(135deg, var(--purple-light), var(--purple));
}

.user-table th {
    color: white;
    padding: 1rem;
    text-align: left;
    font-weight: 600;
    border: none;
}

.user-table tbody tr {
    border-bottom: 1px solid #eee;
    transition: background 0.3s ease;
}

.user-table tbody tr:hover {
    background: var(--gray);
}

.user-table td {
    padding: 1rem;
    color: var(--gray-dark);
}

.user-info {
    display: flex;
    align-items: center;
    gap: 1rem;
}

.user-avatar-small {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background: linear-gradient(135deg, var(--purple), var(--purple-dark));
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
}

.action-buttons {
    display: flex;
    gap: 0.5rem;
}

.btn-action {
    width: 36px;
    height: 36px;
    border-radius: 6px;
    border: none;
    background: var(--gray);
    color: var(--purple);
    cursor: pointer;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    justify-content: center;
}

.btn-action:hover {
    background: var(--purple);
    color: white;
    transform: translateY(-2px);
}

.btn-action.delete-user:hover {
    background: #ff6b6b;
}

.btn-action.promote-user:hover {
    background: #43e97b;
}

.btn-action.demote-user:hover {
    background: #f093fb;
}

.pagination-container {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-top: 2rem;
    padding-top: 1.5rem;
    border-top: 1px solid #eee;
    flex-wrap: wrap;
    gap: 1rem;
}

.page-info {
    color: var(--gray-dark);
    font-size: 0.9rem;
}

/* Modal Styles */
.modal {
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.5);
    z-index: 1000;
    align-items: center;
    justify-content: center;
}

.modal-content {
    background: white;
    border-radius: 15px;
    width: 90%;
    max-width: 500px;
    max-height: 90vh;
    overflow-y: auto;
    animation: modalSlideIn 0.3s ease;
}

@keyframes modalSlideIn {
    from {
        opacity: 0;
        transform: translateY(-50px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.modal-header {
    padding: 1.5rem 2rem;
    border-bottom: 1px solid #eee;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.modal-header h3 {
    color: var(--purple-dark);
    margin: 0;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.close-modal {
    background: none;
    border: none;
    font-size: 1.5rem;
    color: var(--gray-dark);
    cursor: pointer;
    padding: 0;
    width: 30px;
    height: 30px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 50%;
}

.close-modal:hover {
    background: var(--gray);
}

.modal-body {
    padding: 2rem;
}

.modal-footer {
    padding: 1.5rem 2rem;
    border-top: 1px solid #eee;
    display: flex;
    justify-content: flex-end;
    gap: 1rem;
}

.no-data {
    text-align: center;
    padding: 2rem;
}

.no-data i {
    font-size: 3rem;
    color: var(--purple-light);
    margin-bottom: 1rem;
}

.no-data p {
    color: var(--gray-dark);
    margin: 0;
}

@media (max-width: 768px) {
    .management-header {
        flex-direction: column;
        align-items: flex-start;
    }
    
    .header-actions {
        width: 100%;
    }
    
    .search-box {
        min-width: 100%;
    }
    
    .user-table {
        font-size: 0.9rem;
    }
    
    .action-buttons {
        flex-wrap: wrap;
    }
    
    .pagination-container {
        flex-direction: column;
        align-items: center;
        text-align: center;
    }
}
</style>

<script>
// User Search
document.getElementById('userSearch').addEventListener('input', function(e) {
    const searchTerm = e.target.value.toLowerCase();
    const rows = document.querySelectorAll('#userTable tbody tr');
    
    rows.forEach(row => {
        const name = row.querySelector('td:nth-child(2)').textContent.toLowerCase();
        const email = row.querySelector('td:nth-child(3)').textContent.toLowerCase();
        
        if (name.includes(searchTerm) || email.includes(searchTerm)) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    });
});

// Modal Functions
const modal = document.getElementById('editUserModal');
const closeModalButtons = document.querySelectorAll('.close-modal');
const saveButton = document.getElementById('saveUserChanges');
const editForm = document.getElementById('editUserForm');

// Open modal for editing user
document.querySelectorAll('.edit-user').forEach(button => {
    button.addEventListener('click', function() {
        const userId = this.getAttribute('data-id');
        const row = document.querySelector(`tr[data-user-id="${userId}"]`);
        
        if (row) {
            const name = row.querySelector('td:nth-child(2) strong').textContent;
            const email = row.querySelector('td:nth-child(3)').textContent;
            const role = row.querySelector('.role-badge').textContent.toLowerCase();
            
            document.getElementById('editUserId').value = userId;
            document.getElementById('editUserName').value = name;
            document.getElementById('editUserEmail').value = email;
            document.getElementById('editUserRole').value = role;
            document.getElementById('editUserPassword').value = '';
            
            modal.style.display = 'flex';
        }
    });
});

// Close modal
closeModalButtons.forEach(button => {
    button.addEventListener('click', () => {
        modal.style.display = 'none';
    });
});

// Close modal when clicking outside
window.addEventListener('click', (e) => {
    if (e.target === modal) {
        modal.style.display = 'none';
    }
});

// Save user changes
saveButton.addEventListener('click', async function() {
    const formData = new FormData(editForm);
    
    try {
        const response = await fetch('/uas_web1/profile/update', {
            method: 'POST',
            body: formData
        });
        
        const result = await response.json();
        
        if (result.success) {
            alert('User berhasil diperbarui!');
            location.reload();
        } else {
            alert('Error: ' + result.message);
        }
    } catch (error) {
        alert('Terjadi kesalahan: ' + error.message);
    }
});

// Delete user
document.querySelectorAll('.delete-user').forEach(button => {
    button.addEventListener('click', function() {
        const userId = this.getAttribute('data-id');
        const userName = this.getAttribute('data-name');
        
        if (confirm(`Yakin ingin menghapus user "${userName}"?`)) {
            // Implement delete logic here
            console.log('Delete user:', userId);
        }
    });
});

// Promote to admin
document.querySelectorAll('.promote-user').forEach(button => {
    button.addEventListener('click', function() {
        const userId = this.getAttribute('data-id');
        const userName = this.getAttribute('data-name');
        
        if (confirm(`Jadikan "${userName}" sebagai admin?`)) {
            // Implement promote logic here
            console.log('Promote user:', userId);
        }
    });
});

// Demote to user
document.querySelectorAll('.demote-user').forEach(button => {
    button.addEventListener('click', function() {
        const userId = this.getAttribute('data-id');
        const userName = this.getAttribute('data-name');
        
        if (confirm(`Jadikan "${userName}" sebagai user biasa?`)) {
            // Implement demote logic here
            console.log('Demote user:', userId);
        }
    });
});
</script>

<?php 
include $root_path . '/footer.php';
?>