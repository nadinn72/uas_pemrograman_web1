<?php
require_once 'models/User.php';

class AuthController {
    private $userModel;

    public function __construct() {
        $this->userModel = new User();
    }

    // Halaman login
    public function login() {
        // Jika sudah login, redirect ke dashboard
        if(isset($_SESSION['user_id'])) {
            header('Location: /uas_web1/films');
            exit();
        }

        // Proses login
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            $email = trim($_POST['email'] ?? '');
            $password = trim($_POST['password'] ?? '');
            
            // DEBUG: Tampilkan input
            // echo "DEBUG - Email: $email, Password: $password<br>";
            
            $user = $this->userModel->login($email, $password);
            
            if($user) {
                // DEBUG: Tampilkan data user
                // echo "<pre>User data: ";
                // print_r($user);
                // echo "</pre>";
                
                // Set session
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_name'] = $user['name'];
                $_SESSION['user_email'] = $user['email'];
                $_SESSION['role'] = $user['role'];
                
                // Debug session
                // echo "<pre>Session set: ";
                // print_r($_SESSION);
                // echo "</pre>";
                
                // Redirect ke films
                header('Location: /uas_web1/films');
                exit();
            } else {
                $error = "Email atau password salah!";
                require_once 'views/auth/login.php';
            }
        } else {
            require_once 'views/auth/login.php';
        }
    }

    // Halaman register
    public function register() {
        // Jika sudah login, redirect ke dashboard
        if(isset($_SESSION['user_id'])) {
            header('Location: /uas_web1/films');
            exit();
        }

        // Proses register
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            $name = trim($_POST['name'] ?? '');
            $email = trim($_POST['email'] ?? '');
            $password = trim($_POST['password'] ?? '');
            $confirm_password = trim($_POST['confirm_password'] ?? '');
            
            // Validasi
            $errors = [];
            
            if(empty($name)) {
                $errors[] = "Nama harus diisi";
            }
            
            if(empty($email)) {
                $errors[] = "Email harus diisi";
            } elseif(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $errors[] = "Format email tidak valid";
            } elseif($this->userModel->checkEmail($email)) {
                $errors[] = "Email sudah terdaftar";
            }
            
            if(empty($password)) {
                $errors[] = "Password harus diisi";
            } elseif(strlen($password) < 6) {
                $errors[] = "Password minimal 6 karakter";
            } elseif($password !== $confirm_password) {
                $errors[] = "Konfirmasi password tidak cocok";
            }
            
            if(empty($errors)) {
                $this->userModel->name = $name;
                $this->userModel->email = $email;
                $this->userModel->password = $password;
                $this->userModel->role = 'user'; // Default role
                
                if($this->userModel->register()) {
                    $_SESSION['success'] = "Registrasi berhasil! Silakan login.";
                    header('Location: /uas_web1/auth/login');
                    exit();
                } else {
                    $error = "Terjadi kesalahan saat registrasi";
                }
            } else {
                $error = implode("<br>", $errors);
            }
        }
        
        require_once 'views/auth/register.php';
    }

    // Logout
    public function logout() {
        session_destroy();
        header('Location: /uas_web1/auth/login');
        exit();
    }
}
?>