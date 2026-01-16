<?php
// controllers/HomeController.php
require_once 'models/Film.php';

class HomeController {
    private $filmModel;
    
    public function __construct() {
        $this->filmModel = new Film();
    }
    
    public function index() {
        // Start session if not started
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        // Get films data
        $featured_films = $this->filmModel->getFeatured(6);
        $latest_films = $this->filmModel->getLatest(6);
        $popular_films = $this->filmModel->getPopular(6);
        
        // Get statistics for admin
        $stats = [];
        if (isset($_SESSION['role']) && $_SESSION['role'] == 'admin') {
            require_once 'models/User.php';
            $userModel = new User();
            $stats = [
                'total_users' => $userModel->getUserStatistics()['total_users'] ?? 0,
                'total_films' => $this->filmModel->getTotalCount()
            ];
        }
        
        // Extract data for view - PERBAIKAN DI SINI
        $isLoggedIn = isset($_SESSION['user_id']);
        $userName = $_SESSION['user_name'] ?? '';
        $userRole = $_SESSION['role'] ?? 'guest';
        
        // Include view dengan variabel yang tepat
        require_once __DIR__ . '/../views/home/index.php';
    }
    
    public function about() {
        require_once __DIR__ . '/../views/home/about.php';
    }
    
    public function contact() {
        require_once __DIR__ . '/../views/home/contact.php';
    }
    
    // Helper methods
    private function getLatestFilms($limit = 6) {
        try {
            $query = "SELECT * FROM films ORDER BY created_at DESC LIMIT :limit";
            $database = new Database();
            $conn = $database->connect();
            $stmt = $conn->prepare($query);
            $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            error_log("Error getting latest films: " . $e->getMessage());
            return [];
        }
    }
    
    private function getPopularFilms($limit = 6) {
        try {
            $query = "SELECT * FROM films ORDER BY rating DESC LIMIT :limit";
            $database = new Database();
            $conn = $database->connect();
            $stmt = $conn->prepare($query);
            $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            error_log("Error getting popular films: " . $e->getMessage());
            return [];
        }
    }
}
?>