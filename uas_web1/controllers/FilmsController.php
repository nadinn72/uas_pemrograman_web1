<?php
require_once 'models/Film.php';

class FilmsController {
    private $filmModel;

    public function __construct() {
        $this->filmModel = new Film();
    }

    // Menampilkan semua film (dengan pagination)
    public function index() {
        $page = $_GET['page'] ?? 1;
        $limit = 6;
        
        $films = $this->filmModel->getAll($page, $limit);
        $totalFilms = $this->filmModel->getTotalCount();
        $totalPages = ceil($totalFilms / $limit);

        require_once 'views/films/index.php';
    }

    // Menampilkan detail film
    public function show($id = null) {
        if(!$id) {
            header('Location: /films');
            exit();
        }
        
        $film = $this->filmModel->getById($id);
        
        if(!$film) {
            http_response_code(404);
            echo "Film tidak ditemukan";
            exit();
        }

        require_once 'views/films/show.php';
    }

    // Form tambah film (admin only)
    public function create() {
        // Cek role admin
        if($_SESSION['role'] != 'admin') {
            header('Location: /films');
            exit();
        }
        
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            $this->filmModel->title = $_POST['title'] ?? '';
            $this->filmModel->description = $_POST['description'] ?? '';
            $this->filmModel->genre = $_POST['genre'] ?? '';
            $this->filmModel->rating = $_POST['rating'] ?? 0;
            $this->filmModel->poster_url = $_POST['poster_url'] ?? '';
            
            if($this->filmModel->create()) {
                $_SESSION['success'] = "Film berhasil ditambahkan!";
                header('Location: /films');
                exit();
            } else {
                $error = "Gagal menambahkan film";
            }
        }
        
        require_once 'views/header.php';
        require_once 'views/films/create.php';
        require_once 'views/footer.php';
    }

    // Form edit film (admin only)
    public function edit($id = null) {
        // Cek role admin
        if($_SESSION['role'] != 'admin') {
            header('Location: /films');
            exit();
        }
        
        if(!$id) {
            header('Location: /films');
            exit();
        }
        
        $film = $this->filmModel->getById($id);
        
        if(!$film) {
            header('Location: /films');
            exit();
        }
        
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            $this->filmModel->id = $id;
            $this->filmModel->title = $_POST['title'] ?? '';
            $this->filmModel->description = $_POST['description'] ?? '';
            $this->filmModel->genre = $_POST['genre'] ?? '';
            $this->filmModel->rating = $_POST['rating'] ?? 0;
            $this->filmModel->poster_url = $_POST['poster_url'] ?? '';
            
            if($this->filmModel->update()) {
                $_SESSION['success'] = "Film berhasil diupdate!";
                header('Location: /films');
                exit();
            } else {
                $error = "Gagal mengupdate film";
            }
        }
        
        require_once 'views/header.php';
        require_once 'views/films/edit.php';
        require_once 'views/footer.php';
    }

    // Hapus film (admin only)
    public function delete($id = null) {
        // Cek role admin
        if($_SESSION['role'] != 'admin') {
            header('Location: /films');
            exit();
        }
        
        if(!$id) {
            header('Location: /films');
            exit();
        }
        
        if($this->filmModel->delete($id)) {
            $_SESSION['success'] = "Film berhasil dihapus!";
        } else {
            $_SESSION['error'] = "Gagal menghapus film";
        }
        
        header('Location: /films');
        exit();
    }

    // Search film
    public function search() {
        $keyword = $_GET['q'] ?? '';
        $genre = $_GET['genre'] ?? null;
        
        $films = $this->filmModel->search($keyword, $genre);
        
        require_once 'views/header.php';
        require_once 'views/films/search.php';
        require_once 'views/footer.php';
    }
}
?>