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
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_SESSION['user_id'])) {
    $comment = trim($_POST['comment']);
    if (!empty($comment)) {
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
        /* Copy existing styles from index.php */
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
    <!-- Copy navbar from index.php -->
    
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

            <?php foreach($comments as $comment): ?>
                <div class="comment">
                    <strong><?php echo htmlspecialchars($comment['username']); ?></strong>
                    <small class="text-muted ml-2">
                        <?php echo date('M j, Y g:i A', strtotime($comment['created_at'])); ?>
                    </small>
                    <p class="mb-0"><?php echo htmlspecialchars($comment['comment']); ?></p>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- Copy scripts from index.php -->
</body>
</html>