<?php
session_start();
require_once 'config.php';

$news_url = isset($_GET['url']) ? $_GET['url'] : '';
if (empty($news_url)) {
    header('Location: index.php');
    exit;
}

// Fetch news details from RSS
$rss = simplexml_load_file("https://www.hurriyet.com.tr/rss/ekonomi");
$news_item = null;
foreach ($rss->channel->item as $item) {
    if ((string)$item->link === $news_url) {
        $news_item = $item;
        break;
    }
}

// Handle comment submission
/*
    VULNERABLE: comment submissions are not validated before sending database,
    causing malicious comment can be stored in database
*/
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_SESSION['user_id'])) {
    // No input sanitization or validation before storing user-submitted data, vulnerable to Stored XSS
    $comment = $_POST['comment'];
    if (!empty($comment)) {
        // Directly binding user input to SQL query, even though it's parameterized, still susceptible to XSS
        $stmt = $conn->prepare("INSERT INTO comments (news_url, user_id, comment) VALUES (?, ?, ?)");
        $stmt->bind_param("sis", $news_url, $_SESSION['user_id'], $comment);
        $stmt->execute();
    }
}

// Fetch comments
$comments = [];
$stmt = $conn->prepare("SELECT c.*, u.username FROM comments c JOIN users u ON c.user_id = u.id WHERE news_url = ? ORDER BY created_at DESC");
$stmt->bind_param("s", $news_url);
$stmt->execute();
$result = $stmt->get_result();
while ($row = $result->fetch_assoc()) {
    $comments[] = $row;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($news_item->title); ?></title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            min-height: 100vh;
        }
        /* Navbar styles */
        .navbar {
            background: rgba(255, 255, 255, 0.95);
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .navbar-brand {
            font-weight: 700;
            font-size: 1.5rem;
            color: #333;
        }
        .search-form-nav {
            margin: 0;
        }
        .container-main {
            padding-top: 80px;
        }
        .auth-buttons {
            display: flex;
            align-items: center;
        }
        .nav-item.dropdown .nav-link {
            color: #333;
        }
        @media (max-width: 991px) {
            .auth-buttons {
                margin-top: 1rem;
            }
            .search-form-nav {
                margin-bottom: 1rem;
            }
        }
        /* news.php styles */
        .news-content {
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            margin-bottom: 30px;
        }
        .comments-section {
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .comment {
            border-bottom: 1px solid #eee;
            padding: 15px 0;
        }
    </style>
</head>
<body>

    <?php include 'navbar.php'; ?>

    <div class="container container-main">
        <div class="news-content">
            <h1><?php echo htmlspecialchars($news_item->title); ?></h1>
            <p class="text-muted">Published on: <?php echo date('F j, Y', strtotime($news_item->pubDate)); ?></p>
            <img src="<?php echo $news_item->children('media', true)->content->attributes()->url; ?>" 
                 class="img-fluid mb-4" alt="News Image">
            <div class="news-text">
                <?php echo htmlspecialchars($news_item->description); ?>
            </div>
        </div>

        <div class="comments-section">
            <h3>Comments</h3>
            
            <?php if(isset($_SESSION['user_id'])): ?>
                <form method="POST" class="mb-4">
                    <div class="form-group">
                        <textarea name="comment" class="form-control" rows="3" required 
                                placeholder="Write your comment..."></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary">Post Comment</button>
                </form>
            <?php else: ?>
                <p><a href="login.php">Login</a> to post comments.</p>
            <?php endif; ?>

            <?php foreach ($comments as $comment): ?>
                <div class="comment">
                    <!-- 
                        VULNERABLE: comment is directly echoed into the HTML without sanitization or escaping, which means an injected malicious JavaScript into the comment can be displayed.
                    -->
                    <strong><?php echo $comment['username']; ?>:</strong> <?php echo $comment['comment']; ?>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- Add Bootstrap JavaScript dependencies -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>