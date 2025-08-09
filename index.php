<?php
require_once 'config/database-glitch.php';

// Initialize database tables
initializeDatabase();

$pdo = getDBConnection();
$message = '';
$errors = [];

// Handle new story creation
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'create_story') {
    $title = trim($_POST['title'] ?? '');
    
    // Validate input
    $errors = validateRequired($_POST, ['title']);
    
    if (empty($errors)) {
        $title = sanitizeInput($title);
        
        try {
            $stmt = $pdo->prepare("INSERT INTO stories (title) VALUES (?)");
            $stmt->execute([$title]);
            
            $message = "Story created successfully!";
            // Redirect to prevent form resubmission
            header("Location: index.php?success=1");
            exit();
        } catch (PDOException $e) {
            $errors[] = "Error creating story: " . $e->getMessage();
        }
    }
}

// Get all stories
try {
    $stmt = $pdo->query("SELECT * FROM stories ORDER BY created_at DESC");
    $stories = $stmt->fetchAll();
} catch (PDOException $e) {
    $errors[] = "Error loading stories: " . $e->getMessage();
    $stories = [];
}

// Check for success message
if (isset($_GET['success'])) {
    $message = "Story created successfully!";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Collaborative Story Writing Platform</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Collaborative Story Writing</h1>
            <p>Create and contribute to amazing stories together</p>
            <div style="margin-top: 20px; opacity: 0.8;">
                <span style="background: rgba(255,255,255,0.2); padding: 8px 16px; border-radius: 20px; font-size: 0.9rem;">
                    Real-time collaboration • Sequential storytelling • Beautiful design
                </span>
            </div>
        </div>

        <!-- Display success/error messages -->
        <?php if ($message): ?>
            <div class="alert alert-success">
                <?php echo htmlspecialchars($message); ?>
            </div>
        <?php endif; ?>

        <?php if (!empty($errors)): ?>
            <div class="alert alert-error">
                <?php foreach ($errors as $error): ?>
                    <div><?php echo htmlspecialchars($error); ?></div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <!-- Create New Story Form -->
        <div class="card">
            <h2>Create a New Story</h2>
            <form method="POST" action="">
                <input type="hidden" name="action" value="create_story">
                <div class="form-group">
                    <label for="title">Story Title:</label>
                    <input type="text" id="title" name="title" class="form-control" 
                           placeholder="Enter your captivating story title..." required>
                </div>
                <button type="submit" class="btn btn-success">Create Story</button>
            </form>
        </div>

        <!-- Stories List -->
        <div class="card">
            <h2>All Stories</h2>
            <?php if (empty($stories)): ?>
                <p>No stories yet. Create the first one!</p>
            <?php else: ?>
                <div class="story-list">
                    <?php foreach ($stories as $story): ?>
                        <a href="story.php?id=<?php echo $story['id']; ?>" class="story-item">
                            <div class="story-title"><?php echo htmlspecialchars($story['title']); ?></div>
                            <div class="story-date">
                                Created: <?php echo date('M j, Y', strtotime($story['created_at'])); ?>
                            </div>
                        </a>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
