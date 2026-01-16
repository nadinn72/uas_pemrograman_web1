<?php
// controllers/ViewsController.php

require_once 'models/Film.php';

class ViewsController {
    private $filmModel;
    
    public function __construct() {
        $this->filmModel = new Film();
    }
    
    // Halaman utama views (menampilkan daftar film)
    public function index() {
        // Cek session
        session_start();
        if (!isset($_SESSION['user_id'])) {
            header('Location: /uas_web1/auth/login');
            exit();
        }
        
        // Get pagination parameters
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $limit = 12; // 12 film per halaman
        $search = $_GET['search'] ?? '';
        $genre = $_GET['genre'] ?? '';
        
        // Get films data
        $films = $this->filmModel->getAll($page, $limit, $search, $genre);
        $totalFilms = $this->filmModel->getTotalCount($search, $genre);
        $totalPages = ceil($totalFilms / $limit);
        
        // Pass data to view
        $data = [
            'films' => $films,
            'currentPage' => $page,
            'totalPages' => $totalPages,
            'totalFilms' => $totalFilms,
            'search' => $search,
            'genre' => $genre
        ];
        
        // Load view
        require_once __DIR__ . '/../views/films/index.php';
    }
    
    // Detail film
    public function show($id) {
        session_start();
        if (!isset($_SESSION['user_id'])) {
            header('Location: /uas_web1/auth/login');
            exit();
        }
        
        $film = $this->filmModel->getById($id);
        
        if (!$film) {
            $_SESSION['error'] = "Film tidak ditemukan";
            header('Location: /uas_web1/views');
            exit();
        }
        
        // Get similar films (by genre)
        $similarFilms = $this->filmModel->getByGenre($film['genre'], 4, $id);
        
        $data = [
            'film' => $film,
            'similarFilms' => $similarFilms
        ];
        
        require_once __DIR__ . '/../views/films/show.php';
    }
    
    // Menampilkan film berdasarkan genre
    public function genre($genre) {
        session_start();
        if (!isset($_SESSION['user_id'])) {
            header('Location: /uas_web1/auth/login');
            exit();
        }
        
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $limit = 12;
        
        $films = $this->filmModel->getByGenre($genre, $limit, 0, $page);
        $totalFilms = $this->filmModel->getCountByGenre($genre);
        $totalPages = ceil($totalFilms / $limit);
        
        $data = [
            'films' => $films,
            'currentPage' => $page,
            'totalPages' => $totalPages,
            'totalFilms' => $totalFilms,
            'genre' => $genre
        ];
        
        require_once __DIR__ . '/../views/films/genre.php';
    }
    
    // Search films
    public function search() {
        session_start();
        if (!isset($_SESSION['user_id'])) {
            header('Location: /uas_web1/auth/login');
            exit();
        }
        
        $search = $_GET['q'] ?? '';
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $limit = 12;
        
        if (empty($search)) {
            header('Location: /uas_web1/views');
            exit();
        }
        
        $films = $this->filmModel->search($search, $limit, $page);
        $totalFilms = $this->filmModel->getSearchCount($search);
        $totalPages = ceil($totalFilms / $limit);
        
        $data = [
            'films' => $films,
            'currentPage' => $page,
            'totalPages' => $totalPages,
            'totalFilms' => $totalFilms,
            'search' => $search
        ];
        
        require_once __DIR__ . '/../views/films/search.php';
    }
    
    // Most popular films
    public function popular() {
        session_start();
        if (!isset($_SESSION['user_id'])) {
            header('Location: /uas_web1/auth/login');
            exit();
        }
        
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $limit = 12;
        
        $films = $this->filmModel->getPopular($limit, $page);
        $totalFilms = $this->filmModel->getTotalCount();
        $totalPages = ceil($totalFilms / $limit);
        
        $data = [
            'films' => $films,
            'currentPage' => $page,
            'totalPages' => $totalPages,
            'totalFilms' => $totalFilms
        ];
        
        require_once __DIR__ . '/../views/films/popular.php';
    }
    
    // Latest films
    public function latest() {
        session_start();
        if (!isset($_SESSION['user_id'])) {
            header('Location: /uas_web1/auth/login');
            exit();
        }
        
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $limit = 12;
        
        $films = $this->filmModel->getLatest($limit, $page);
        $totalFilms = $this->filmModel->getTotalCount();
        $totalPages = ceil($totalFilms / $limit);
        
        $data = [
            'films' => $films,
            'currentPage' => $page,
            'totalPages' => $totalPages,
            'totalFilms' => $totalFilms
        ];
        
        require_once __DIR__ . '/../views/films/latest.php';
    }
    
    // API endpoint untuk film (JSON response)
    public function api() {
        header('Content-Type: application/json');
        
        $action = $_GET['action'] ?? 'list';
        
        switch ($action) {
            case 'list':
                $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
                $limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 10;
                $films = $this->filmModel->getAll($page, $limit);
                
                echo json_encode([
                    'success' => true,
                    'data' => $films,
                    'page' => $page,
                    'limit' => $limit
                ]);
                break;
                
            case 'detail':
                $id = $_GET['id'] ?? 0;
                $film = $this->filmModel->getById($id);
                
                if ($film) {
                    echo json_encode([
                        'success' => true,
                        'data' => $film
                    ]);
                } else {
                    echo json_encode([
                        'success' => false,
                        'message' => 'Film not found'
                    ]);
                }
                break;
                
            case 'genres':
                $genres = $this->filmModel->getAllGenres();
                
                echo json_encode([
                    'success' => true,
                    'data' => $genres
                ]);
                break;
                
            default:
                echo json_encode([
                    'success' => false,
                    'message' => 'Invalid action'
                ]);
        }
    }
}
?>