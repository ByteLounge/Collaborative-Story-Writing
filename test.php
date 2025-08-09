<?php
// Test file for Glitch deployment
echo "<h1>Collaborative Story Platform - Glitch Test</h1>";

// Test database connection
try {
    require_once 'config/database-glitch.php';
    $pdo = getDBConnection();
    echo "<p style='color: green;'>✅ Database connection successful!</p>";
    
    // Test database initialization
    if (initializeDatabase()) {
        echo "<p style='color: green;'>✅ Database tables initialized successfully!</p>";
        
        // Test query
        $stmt = $pdo->query("SELECT COUNT(*) as count FROM stories");
        $result = $stmt->fetch();
        echo "<p style='color: blue;'>📊 Found " . $result['count'] . " stories in database</p>";
        
    } else {
        echo "<p style='color: red;'>❌ Database initialization failed</p>";
    }
    
} catch (Exception $e) {
    echo "<p style='color: red;'>❌ Error: " . $e->getMessage() . "</p>";
}

// Test environment variables
echo "<h2>Environment Check:</h2>";
echo "<p>DATABASE_URL: " . (getenv('DATABASE_URL') ? 'Set' : 'Not set') . "</p>";
echo "<p>PORT: " . (getenv('PORT') ?: 'Not set') . "</p>";

// Test file structure
echo "<h2>File Structure Check:</h2>";
$required_files = [
    'index.php',
    'story.php',
    'config/database-glitch.php',
    'assets/css/style.css',
    'package.json',
    'glitch.json',
    'composer.json'
];

foreach ($required_files as $file) {
    if (file_exists($file)) {
        echo "<p style='color: green;'>✅ $file exists</p>";
    } else {
        echo "<p style='color: red;'>❌ $file missing</p>";
    }
}

echo "<hr>";
echo "<p><a href='index.php'>Go to main application</a></p>";
?>
