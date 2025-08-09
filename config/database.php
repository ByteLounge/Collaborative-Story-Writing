<?php
// Database configuration
define('DB_HOST', 'localhost:5000');
define('DB_NAME', 'collaborative_story_db');
define('DB_USER', 'root');
define('DB_PASS', '');

// Create database connection
function getDBConnection() {
    try {
        $pdo = new PDO(
            "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4",
            DB_USER,
            DB_PASS,
            array(
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false
            )
        );
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
?>
