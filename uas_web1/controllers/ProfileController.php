<?php
// controllers/ProfileController.php - LENGKAP
require_once 'models/User.php';
require_once 'models/Film.php';

class ProfileController {
    private $userModel;
    private $filmModel;

    public function __construct() {
        $this->userModel = new User();
        $this->filmModel = new Film();
    }

    // Halaman profil user (default: index)
    public function index() {
        if (!isset($_SESSION['user_id'])) {
            header('Location: /uas_web1/auth/login');
            exit();
        }
        
        $user_id = $_SESSION['user_id'];
        $user = $this->userModel->getUserById($user_id);
        
        if (!$user) {
            session_destroy();
            header('Location: /uas_web1/auth/login');
            exit();
        }
        
        // DEBUG: Hapus atau komentar ini jika sudah berhasil
        $base_path = 'C:/xampp/htdocs/uas_web1';
        $view_path = $base_path . '/views/profile/index.php';
        
        echo "Mencari file di: " . $view_path . "<br>";
        echo "File exists: " . (file_exists($view_path) ? 'YES' : 'NO') . "<br>";
        
        if (file_exists($view_path)) {
            require_once $view_path;
        } else {
            echo "<h3>Error: File tidak ditemukan!</h3>";
            echo "<p>Path yang dicari: " . $view_path . "</p>";
            echo "<p>Pastikan struktur folder:</p>";
            echo "<pre>";
            echo "uas_web1/<br>";
            echo "├── views/<br>";
            echo "│   └── profile/<br>";
            echo "│       └── index.php<br>";
            echo "└── controllers/<br>";
            echo "    └── ProfileController.php<br>";
            echo "</pre>";
        }
        // AKHIR DEBUG - hapus sampai sini jika sudah berhasil
    }

    // Form edit profil
    public function edit() {
        if (!isset($_SESSION['user_id'])) {
            header('Location: /uas_web1/auth/login');
            exit();
        }
        
        $user_id = $_SESSION['user_id'];
        $user = $this->userModel->getUserById($user_id);
        
        if (!$user) {
            session_destroy();
            header('Location: /uas_web1/auth/login');
            exit();
        }
        
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $name = trim($_POST['name'] ?? '');
            $email = trim($_POST['email'] ?? '');
            $current_password = $_POST['current_password'] ?? '';
            $new_password = $_POST['new_password'] ?? '';
            $confirm_password = $_POST['confirm_password'] ?? '';
            
            // Validasi
            $errors = [];
            
            if (empty($name)) {
                $errors[] = "Nama harus diisi";
            }
            
            if (empty($email)) {
                $errors[] = "Email harus diisi";
            } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $errors[] = "Format email tidak valid";
            } elseif ($email != $user['email']) {
                // Cek jika email berubah
                if ($this->userModel->checkEmail($email)) {
                    $errors[] = "Email sudah digunakan";
                }
            }
            
            // Validasi password jika ada perubahan
            if (!empty($new_password)) {
                if (empty($current_password)) {
                    $errors[] = "Password lama harus diisi untuk mengubah password";
                } elseif (strlen($new_password) < 6) {
                    $errors[] = "Password baru minimal 6 karakter";
                } elseif ($new_password !== $confirm_password) {
                    $errors[] = "Konfirmasi password tidak cocok";
                }
            }
            
            if (empty($errors)) {
                $result = $this->userModel->updateProfile(
                    $user_id, 
                    $name, 
                    $email, 
                    $current_password ?: null, 
                    $new_password ?: null
                );
                
                if ($result === true) {
                    $_SESSION['success'] = "Profil berhasil diperbarui!";
                    $_SESSION['user_name'] = $name;
                    $_SESSION['user_email'] = $email;
                    header('Location: /uas_web1/profile');
                    exit();
                } else {
                    $error = $result;
                }
            } else {
                $error = implode("<br>", $errors);
            }
        }
        
        require_once __DIR__ . '/../views/profile/edit.php';
    }

    // Halaman dashboard admin
    public function admin() {
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
            header('Location: /uas_web1/auth/login');
            exit();
        }
        
        // Get statistics
        $user_stats = $this->userModel->getUserStatistics();
        $total_films = $this->filmModel->getTotalCount();
        
        // Get recent users
        $recent_users = $this->getRecentUsers();
        
        // Get recent films
        $recent_films = $this->filmModel->getAll(1, 5);
        
        require_once __DIR__ . '/../views/profile/admin.php';
    }

    // Manage users (admin only)
    public function users() {
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
            header('Location: /uas_web1/auth/login');
            exit();
        }
        
        $page = $_GET['page'] ?? 1;
        $limit = 10;
        $users = $this->getAllUsers($page, $limit);
        $total_users = $this->userModel->getUserStatistics()['total_users'] ?? 0;
        $total_pages = ceil($total_users / $limit);
        
        require_once __DIR__ . '/../views/profile/users.php';
    }

    // Update user (admin only - untuk modal)
    public function update() {
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'message' => 'Unauthorized']);
            exit();
        }
        
        if ($_SERVER['REQUEST_METHOD'] != 'POST') {
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'message' => 'Invalid method']);
            exit();
        }
        
        $id = $_POST['id'] ?? 0;
        $name = trim($_POST['name'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $role = $_POST['role'] ?? 'user';
        $password = $_POST['password'] ?? '';
        
        // Validasi
        if (empty($id) || empty($name) || empty($email)) {
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'message' => 'Data tidak lengkap']);
            exit();
        }
        
        try {
            // Update user
            $database = new Database();
            $conn = $database->connect();
            
            if (!empty($password)) {
                // Update dengan password baru
                $hashed_password = password_hash($password, PASSWORD_DEFAULT);
                $query = "UPDATE users SET name = :name, email = :email, role = :role, password = :password WHERE id = :id";
                $stmt = $conn->prepare($query);
                $stmt->bindParam(':password', $hashed_password);
            } else {
                // Update tanpa password
                $query = "UPDATE users SET name = :name, email = :email, role = :role WHERE id = :id";
                $stmt = $conn->prepare($query);
            }
            
            $stmt->bindParam(':name', $name);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':role', $role);
            $stmt->bindParam(':id', $id);
            
            if ($stmt->execute()) {
                header('Content-Type: application/json');
                echo json_encode(['success' => true, 'message' => 'User berhasil diperbarui']);
            } else {
                header('Content-Type: application/json');
                echo json_encode(['success' => false, 'message' => 'Gagal memperbarui user']);
            }
            
        } catch (PDOException $e) {
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
        }
        exit();
    }

    // Method helper untuk mendapatkan semua users
    private function getAllUsers($page = 1, $limit = 10) {
        try {
            $offset = ($page - 1) * $limit;
            $query = "SELECT id, name, email, role, created_at 
                     FROM users 
                     ORDER BY created_at DESC 
                     LIMIT :limit OFFSET :offset";
            
            $database = new Database();
            $conn = $database->connect();
            $stmt = $conn->prepare($query);
            $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
            $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
            $stmt->execute();
            
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            error_log("Get all users error: " . $e->getMessage());
            return [];
        }
    }

    // Method helper untuk mendapatkan recent users
    private function getRecentUsers($limit = 5) {
        try {
            $query = "SELECT id, name, email, role, created_at 
                     FROM users 
                     ORDER BY created_at DESC 
                     LIMIT :limit";
            
            $database = new Database();
            $conn = $database->connect();
            $stmt = $conn->prepare($query);
            $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
            $stmt->execute();
            
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            error_log("Get recent users error: " . $e->getMessage());
            return [];
        }
    }
}
?>