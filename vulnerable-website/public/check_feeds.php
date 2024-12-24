<?php
session_start();
require_once 'config.php';

// Basic admin check
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header('Location: index.php');
    exit;
}

$result = '';
$error = '';

// Vulnerable SSRF implementation
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $url = $_POST['url'];
    try {
        // SSRF vulnerability using file_get_contents
        $content = file_get_contents($url);
        $result = "Feed is accessible. Content length: " . strlen($content) . " bytes";
    } catch (Exception $e) {
        $error = "Failed to fetch feed: " . $e->getMessage();
    }

    // SSRF vulnerability using cURL
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    $output = curl_exec($ch);
    curl_close($ch);
    $result .= " | cURL content length: " . strlen($output) . " bytes";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Check News Feeds - Turkish Financial News</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            min-height: 100vh;
        }
        .container-main {
            padding-top: 80px;
        }
        .check-card {
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <?php include 'navbar.php'; ?>
    
    <div class="container container-main">
        <div class="check-card">
            <h2>Check News Feed Status</h2>
            <p class="text-muted">Enter RSS feed URL to check its availability</p>

            <?php if($error): ?>
                <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
            <?php endif; ?>

            <?php if($result): ?>
                <div class="alert alert-success"><?php echo htmlspecialchars($result); ?></div>
                <?php if(isset($content)): ?>
                    <div class="feed-preview">
                        <h4>Feed Preview:</h4>
                        <pre><?php echo htmlspecialchars(substr($content, 0, 1000)) . '...'; ?></pre>
                    </div>
                <?php endif; ?>
            <?php endif; ?>

            <form method="POST">
                <div class="form-group">
                    <label>Feed URL</label>
                    <input type="url" name="url" class="form-control" required 
                           placeholder="https://example.com/feed.rss">
                    <div class="example-urls">
                        Example URLs:
                        <ul>
                            <li>https://www.hurriyet.com.tr/rss/ekonomi</li>
                            <li>https://www.bloomberght.com/rss</li>
                        </ul>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary">Check Feed</button>
            </form>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>