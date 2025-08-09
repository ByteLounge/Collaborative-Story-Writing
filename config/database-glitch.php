<?php
// Database configuration for Glitch hosting
// Using Glitch's free database service

// Get database URL from environment variable (Glitch provides this)
$database_url = getenv('DATABASE_URL');

if ($database_url) {
    // Parse the database URL
    $url = parse_url($database_url);
    
    define('DB_HOST', $url['host']);
    define('DB_NAME', ltrim($url['path'], '/'));
    define('DB_USER', $url['user']);
    define('DB_PASS', $url['pass']);
    define('DB_PORT', $url['port'] ?? 3306);
} else {
    // Fallback for local development
    define('DB_HOST', 'localhost');
    define('DB_NAME', 'collaborative_story_db');
    define('DB_USER', 'root');
    define('DB_PASS', '');
    define('DB_PORT', 3306);
}

// Create database connection
function getDBConnection() {
    try {
        $dsn = "mysql:host=" . DB_HOST . ";port=" . DB_PORT . ";dbname=" . DB_NAME . ";charset=utf8mb4";
        $pdo = new PDO($dsn, DB_USER, DB_PASS, array(
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false
        ));
        return $pdo;
    } catch (PDOException $e) {
        die("Database connection failed: " . $e->getMessage());
    }
}

// Helper function to sanitize input
function sanitizeInput($input) {
    return htmlspecialchars(trim($input), ENT_QUOTES, 'UTF-8');
}

// Helper function to validate required fields
function validateRequired($data, $fields) {
    $errors = array();
    foreach ($fields as $field) {
        if (empty(trim($data[$field] ?? ''))) {
            $errors[] = ucfirst($field) . " is required.";
        }
    }
    return $errors;
}

// Function to initialize database tables
function initializeDatabase() {
    try {
        $pdo = getDBConnection();
        
        // Create stories table
        $pdo->exec("CREATE TABLE IF NOT EXISTS stories (
            id INT AUTO_INCREMENT PRIMARY KEY,
            title VARCHAR(255) NOT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )");
        
        // Create contributions table
        $pdo->exec("CREATE TABLE IF NOT EXISTS contributions (
            id INT AUTO_INCREMENT PRIMARY KEY,
            story_id INT NOT NULL,
            author_name VARCHAR(100) NOT NULL,
            content TEXT NOT NULL,
            contributed_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (story_id) REFERENCES stories(id) ON DELETE CASCADE
        )");
        
        // Insert sample data if tables are empty
        $stmt = $pdo->query("SELECT COUNT(*) FROM stories");
        if ($stmt->fetchColumn() == 0) {
            $pdo->exec("INSERT INTO stories (title) VALUES 
                ('The Mysterious Forest'),
                ('Space Adventure'),
                ('The Lost Treasure')");
                
            $pdo->exec("INSERT INTO contributions (story_id, author_name, content) VALUES 
                (1, 'Alice', 'Once upon a time, there was a mysterious forest that no one dared to enter.'),
                (1, 'Bob', 'The trees whispered ancient secrets to those who listened carefully.'),
                (2, 'Charlie', 'Captain Sarah stared at the vast expanse of space through her ship\'s viewport.'),
                (2, 'Diana', 'The stars seemed to pulse with an otherworldly rhythm.'),
                (3, 'Eve', 'Deep in the jungle, rumors spoke of a treasure beyond imagination.')");
        }
        
        return true;
    } catch (PDOException $e) {
        error_log("Database initialization failed: " . $e->getMessage());
        return false;
    }
}
?>
