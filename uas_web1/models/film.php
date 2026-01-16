<?php
require_once 'config/database.php';

class Film {
    private $conn;
    private $table = 'films';

    public $id;
    public $title;
    public $description;
    public $genre;
    public $rating;
    public $poster_url;
    public $created_at;

    public function __construct() {
        $database = new Database();
        $this->conn = $database->connect();
    }

    // Get all films dengan pagination
    public function getAll($page = 1, $limit = 6) {
        $offset = ($page - 1) * $limit;
        
        $query = "SELECT * FROM " . $this->table . " 
                  ORDER BY created_at DESC 
                  LIMIT :limit OFFSET :offset";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        
        return $stmt->fetchAll();
    }

    // Get film by ID
    public function getById($id) {
        $query = "SELECT * FROM " . $this->table . " WHERE id = :id";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Create new film
    public function create() {
        $query = "INSERT INTO " . $this->table . " 
                  SET title = :title, description = :description, 
                      genre = :genre, rating = :rating, poster_url = :poster_url";
        
        $stmt = $this->conn->prepare($query);
        
        // Bind parameter
        $stmt->bindParam(':title', $this->title);
        $stmt->bindParam(':description', $this->description);
        $stmt->bindParam(':genre', $this->genre);
        $stmt->bindParam(':rating', $this->rating);
        $stmt->bindParam(':poster_url', $this->poster_url);
        
        if($stmt->execute()) {
            return $this->conn->lastInsertId();
        }
        return false;
    }

    // Update film
    public function update() {
        $query = "UPDATE " . $this->table . " 
                  SET title = :title, description = :description, 
                      genre = :genre, rating = :rating, poster_url = :poster_url
                  WHERE id = :id";
        
        $stmt = $this->conn->prepare($query);
        
        // Bind parameter
        $stmt->bindParam(':title', $this->title);
        $stmt->bindParam(':description', $this->description);
        $stmt->bindParam(':genre', $this->genre);
        $stmt->bindParam(':rating', $this->rating);
        $stmt->bindParam(':poster_url', $this->poster_url);
        $stmt->bindParam(':id', $this->id);
        
        return $stmt->execute();
    }

    // Delete film
    public function delete($id) {
        $query = "DELETE FROM " . $this->table . " WHERE id = :id";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        
        return $stmt->execute();
    }

    // Get total films count
    public function getTotalCount() {
        $query = "SELECT COUNT(*) as total FROM " . $this->table;
        
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['total'];
    }
    // models/Film.php - tambahkan method ini

public function getFeatured($limit = 6) {
    try {
        $query = "SELECT * FROM films 
                 WHERE rating >= 4.0 
                 ORDER BY rating DESC 
                 LIMIT :limit";
        
        $database = new Database();
        $conn = $database->connect();
        $stmt = $conn->prepare($query);
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        error_log("Get featured films error: " . $e->getMessage());
        return [];
    }
}

public function getLatest($limit = 4) {
    try {
        $query = "SELECT * FROM films 
                 ORDER BY created_at DESC 
                 LIMIT :limit";
        
        $database = new Database();
        $conn = $database->connect();
        $stmt = $conn->prepare($query);
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        error_log("Get latest films error: " . $e->getMessage());
        return [];
    }
}

public function getPopular($limit = 4) {
    try {
        $query = "SELECT * FROM films 
                 ORDER BY rating DESC 
                 LIMIT :limit";
        
        $database = new Database();
        $conn = $database->connect();
        $stmt = $conn->prepare($query);
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        error_log("Get popular films error: " . $e->getMessage());
        return [];
    }
}

    // Search films
    public function search($keyword, $genre = null) {
        $query = "SELECT * FROM " . $this->table . " 
                  WHERE (title LIKE :keyword OR description LIKE :keyword)";
        
        if($genre) {
            $query .= " AND genre = :genre";
        }
        
        $query .= " ORDER BY created_at DESC";
        
        $stmt = $this->conn->prepare($query);
        $keyword = "%" . $keyword . "%";
        $stmt->bindParam(':keyword', $keyword);
        
        if($genre) {
            $stmt->bindParam(':genre', $genre);
        }
        
        $stmt->execute();
        return $stmt->fetchAll();
    }
}
?>