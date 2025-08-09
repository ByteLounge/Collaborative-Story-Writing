<?php
require_once 'config/database.php';

$pdo = getDBConnection();
$message = '';
$errors = [];

// Get story ID from URL
$story_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if (!$story_id) {
    header("Location: index.php");
    exit();
}

// Get story details
try {
    $stmt = $pdo->prepare("SELECT * FROM stories WHERE id = ?");
    $stmt->execute([$story_id]);
    $story = $stmt->fetch();
    
    if (!$story) {
        header("Location: index.php");
        exit();
    }
} catch (PDOException $e) {
    die("Error loading story: " . $e->getMessage());
}

// Handle new contribution
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'add_contribution') {
    $author_name = trim($_POST['author_name'] ?? '');
    $content = trim($_POST['content'] ?? '');
    
    // Validate input
    $errors = validateRequired($_POST, ['author_name', 'content']);
    
    if (empty($errors)) {
        $author_name = sanitizeInput($author_name);
        $content = sanitizeInput($content);
        
        try {
            $stmt = $pdo->prepare("INSERT INTO contributions (story_id, author_name, content) VALUES (?, ?, ?)");
            $stmt->execute([$story_id, $author_name, $content]);
            
            // Redirect to prevent form resubmission
            header("Location: story.php?id=$story_id&success=1");
            exit();
        } catch (PDOException $e) {
            $errors[] = "Error adding contribution: " . $e->getMessage();
        }
    }
}

// Get all contributions for this story
try {
    $stmt = $pdo->prepare("SELECT * FROM contributions WHERE story_id = ? ORDER BY contributed_at ASC");
    $stmt->execute([$story_id]);
    $contributions = $stmt->fetchAll();
} catch (PDOException $e) {
    $errors[] = "Error loading contributions: " . $e->getMessage();
    $contributions = [];
}

// Check for success message
if (isset($_GET['success'])) {
    $message = "Contribution added successfully!";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($story['title']); ?> - Collaborative Story</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <div class="container">
        <div class="header">
            <h1><?php echo htmlspecialchars($story['title']); ?></h1>
            <p>Created on <?php echo date('F j, Y', strtotime($story['created_at'])); ?></p>
            <div style="margin-top: 20px; opacity: 0.8;">
                <span style="background: rgba(255,255,255,0.2); padding: 8px 16px; border-radius: 20px; font-size: 0.9rem;">
                    <?php echo count($contributions); ?> contributions • Last updated: <?php echo count($contributions) > 0 ? date('M j, Y', strtotime(end($contributions)['contributed_at'])) : 'Never'; ?>
                </span>
            </div>
        </div>

        <!-- Navigation -->
        <div class="nav">
            <a href="index.php" class="nav-link">← Back to All Stories</a>
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

        <!-- Add New Contribution Form -->
        <div class="card">
            <h2>Add Your Contribution</h2>
            <form method="POST" action="">
                <input type="hidden" name="action" value="add_contribution">
                <div class="form-group">
                    <label for="author_name">Your Name:</label>
                    <input type="text" id="author_name" name="author_name" class="form-control" 
                           placeholder="Enter your creative pen name..." required>
                </div>
                <div class="form-group">
                    <label for="content">Your Contribution:</label>
                    <textarea id="content" name="content" class="form-control" 
                              placeholder="Continue the story with your unique voice..." required></textarea>
                </div>
                <button type="submit" class="btn btn-success">Add Contribution</button>
            </form>
        </div>

        <!-- Story Contributions -->
        <div class="card">
            <h2>Story Contributions</h2>
            <?php if (empty($contributions)): ?>
                <p>No contributions yet. Be the first to add to this story!</p>
            <?php else: ?>
                <div class="contributions">
                    <?php foreach ($contributions as $contribution): ?>
                        <div class="contribution">
                            <div class="contribution-header">
                                <span class="author-name"><?php echo htmlspecialchars($contribution['author_name']); ?></span>
                                <span class="contribution-date">
                                    <?php echo date('M j, Y g:i A', strtotime($contribution['contributed_at'])); ?>
                                </span>
                            </div>
                            <div class="contribution-content">
                                <?php echo nl2br(htmlspecialchars($contribution['content'])); ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
