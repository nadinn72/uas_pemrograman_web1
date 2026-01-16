<?php
require_once 'config/database.php';

class User {
    private $conn;
    private $table = 'users';

    public $id;
    public $name;
    public $email;
    public $password;
    public $role;
    public $created_at;

    public function __construct() {
        $database = new Database();
        $this->conn = $database->connect();
        
        // Debug: cek koneksi
        if (!$this->conn) {
            die("Database connection failed in User constructor");
        }
    }

    // Register user baru
    public function register() {
        try {
            $query = "INSERT INTO " . $this->table . " 
                      SET name = :name, email = :email, password = :password, role = :role";
            
            $stmt = $this->conn->prepare($query);
            
            // Hash password
            $hashed_password = password_hash($this->password, PASSWORD_DEFAULT);
            
            // Bind parameter
            $stmt->bindParam(':name', $this->name);
            $stmt->bindParam(':email', $this->email);
            $stmt->bindParam(':password', $hashed_password);
            $stmt->bindParam(':role', $this->role);
            
            if($stmt->execute()) {
                return true;
            }
            return false;
        } catch (PDOException $e) {
            error_log("Register error: " . $e->getMessage());
            return false;
        }
    }

    // Login user - DENGAN DEBUGGING
    public function login($email, $password) {
        try {
            $query = "SELECT * FROM " . $this->table . " WHERE email = :email LIMIT 1";
            
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':email', $email);
            $stmt->execute();
            
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            
            // DEBUG OUTPUT - HAPUS SETELAH BERHASIL
            echo "<div style='background: #f0f0f0; padding: 10px; margin: 10px 0; border: 1px solid #ccc;'>";
            echo "<strong>DEBUG LOGIN:</strong><br>";
            echo "Email input: " . htmlspecialchars($email) . "<br>";
            echo "Password input: " . htmlspecialchars($password) . "<br>";
            
            if($user) {
                echo "User found in DB: YES<br>";
                echo "DB Password hash: " . htmlspecialchars($user['password']) . "<br>";
                
                // Coba password_verify
                $verify_result = password_verify($password, $user['password']);
                echo "password_verify result: " . ($verify_result ? "TRUE" : "FALSE") . "<br>";
                
                // Juga cek direct compare untuk testing
                echo "Direct compare (plain text): " . ($password === $user['password'] ? "MATCH" : "NO MATCH") . "<br>";
                
                if($verify_result) {
                    echo "<strong style='color: green;'>LOGIN SUCCESS</strong>";
                    echo "</div>";
                    return $user;
                } else {
                    echo "<strong style='color: red;'>LOGIN FAILED - Password mismatch</strong>";
                }
            } else {
                echo "User found in DB: NO<br>";
                echo "<strong style='color: red;'>LOGIN FAILED - User not found</strong>";
            }
            echo "</div>";
            
            return false;
            
        } catch (PDOException $e) {
            error_log("Login error: " . $e->getMessage());
            echo "<div style='background: #ffebee; padding: 10px; margin: 10px 0; border: 1px solid #c62828;'>";
            echo "<strong>Database Error:</strong> " . $e->getMessage();
            echo "</div>";
            return false;
        }
    }

    // Cek email sudah terdaftar
    public function checkEmail($email) {
        try {
            $query = "SELECT id FROM " . $this->table . " WHERE email = :email";
            
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':email', $email);
            $stmt->execute();
            
            return $stmt->rowCount() > 0;
        } catch (PDOException $e) {
            error_log("CheckEmail error: " . $e->getMessage());
            return false;
        }
    }

    // Get user by ID
    public function getUserById($id) {
        try {
            $query = "SELECT id, name, email, role, created_at FROM " . $this->table . " WHERE id = :id";
            
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':id', $id);
            $stmt->execute();
            
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("GetUserById error: " . $e->getMessage());
            return false;
        }
    }
    
    // METODE TAMBAHAN UNTUK TESTING
    public function getAllUsers() {
        try {
            $query = "SELECT id, name, email, role, created_at FROM " . $this->table;
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("GetAllUsers error: " . $e->getMessage());
            return [];
        }
    }
    
    // Reset password untuk testing
    public function resetPassword($email, $new_password) {
        try {
            $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
            $query = "UPDATE " . $this->table . " SET password = :password WHERE email = :email";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':password', $hashed_password);
            $stmt->bindParam(':email', $email);
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("ResetPassword error: " . $e->getMessage());
            return false;
        }
    }
     // Update user profile
    public function updateProfile($id, $name, $email, $current_password = null, $new_password = null) {
        try {
            // Jika ada password baru
            if ($new_password && $current_password) {
                // Verifikasi password lama
                $query = "SELECT password FROM " . $this->table . " WHERE id = :id";
                $stmt = $this->conn->prepare($query);
                $stmt->bindParam(':id', $id);
                $stmt->execute();
                $user = $stmt->fetch();
                
                if (!$user || !password_verify($current_password, $user['password'])) {
                    return "Password lama salah";
                }
                
                // Update dengan password baru
                $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
                $query = "UPDATE " . $this->table . " 
                         SET name = :name, email = :email, password = :password 
                         WHERE id = :id";
                $stmt = $this->conn->prepare($query);
                $stmt->bindParam(':password', $hashed_password);
            } else {
                // Update tanpa password
                $query = "UPDATE " . $this->table . " 
                         SET name = :name, email = :email 
                         WHERE id = :id";
                $stmt = $this->conn->prepare($query);
            }
            
            $stmt->bindParam(':name', $name);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':id', $id);
            
            if ($stmt->execute()) {
                return true;
            }
            return false;
            
        } catch (PDOException $e) {
            error_log("Update profile error: " . $e->getMessage());
            return "Terjadi kesalahan: " . $e->getMessage();
        }
    }

    // Get user statistics (untuk admin)
    public function getUserStatistics() {
        try {
            $stats = [];
            
            // Total users
            $query = "SELECT COUNT(*) as total FROM " . $this->table;
            $stmt = $this->conn->query($query);
            $stats['total_users'] = $stmt->fetch()['total'];
            
            // Users by role
            $query = "SELECT role, COUNT(*) as count FROM " . $this->table . " GROUP BY role";
            $stmt = $this->conn->query($query);
            $stats['by_role'] = $stmt->fetchAll();
            
            // New users this month
            $query = "SELECT COUNT(*) as count FROM " . $this->table . " 
                     WHERE MONTH(created_at) = MONTH(CURRENT_DATE()) 
                     AND YEAR(created_at) = YEAR(CURRENT_DATE())";
            $stmt = $this->conn->query($query);
            $stats['new_this_month'] = $stmt->fetch()['count'];
            
            return $stats;
            
        } catch (PDOException $e) {
            error_log("Get statistics error: " . $e->getMessage());
            return [];
        }
    }

}
?>