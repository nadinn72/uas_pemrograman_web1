<?php
class Database {
    private $host = 'localhost';
    private $db_name = 'review_film_db';
    private $username = 'root';
    private $password = '';
    private $conn;

    public function connect() {
        $this->conn = null;

        try {
            // Coba koneksi dengan PDO
            $this->conn = new PDO(
                "mysql:host=" . $this->host . ";dbname=" . $this->db_name,
                $this->username,
                $this->password,
                array(
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"
                )
            );
            
            // echo "Database connected successfully!";
            
        } catch(PDOException $e) {
            // Tampilkan error yang lebih informatif
            $error_msg = "Koneksi database gagal: " . $e->getMessage() . "<br>";
            $error_msg .= "Host: " . $this->host . "<br>";
            $error_msg .= "Database: " . $this->db_name . "<br>";
            $error_msg .= "Username: " . $this->username . "<br>";
            
            // Cek apakah database ada
            if ($e->getCode() == 1049) { // Error database tidak ditemukan
                $error_msg .= "<br><strong>Database '{$this->db_name}' tidak ditemukan!</strong><br>";
                $error_msg .= "Silakan buat database terlebih dahulu di phpMyAdmin.";
            }
            
            // Cek apakah MySQL berjalan
            if ($e->getCode() == 2002) { // Error koneksi ke host
                $error_msg .= "<br><strong>MySQL tidak berjalan atau host salah!</strong><br>";
                $error_msg .= "Pastikan XAMPP/WAMP MySQL service sedang berjalan.";
            }
            
            die($error_msg);
        }

        return $this->conn;
    }
    
    // Method untuk test koneksi
    public function testConnection() {
        try {
            $conn = $this->connect();
            return "Database connected successfully!";
        } catch(Exception $e) {
            return "Connection failed: " . $e->getMessage();
        }
    }
}
?>