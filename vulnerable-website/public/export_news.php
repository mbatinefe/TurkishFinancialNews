<?php
session_start();
require_once 'config.php';

$message = '';
$exportFile = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Create exports directory if not exists
    $exportDir = 'exports/';
    if (!file_exists($exportDir)) {
        mkdir($exportDir, 0777, true);
    }

    // Generate report
    $rss = simplexml_load_file("https://www.hurriyet.com.tr/rss/ekonomi");
    $reportContent = "Turkish Financial News Export\n";
    $reportContent .= "Generated: " . date('Y-m-d H:i:s') . "\n\n";

    foreach ($rss->channel->item as $item) {
        $reportContent .= "Title: " . $item->title . "\n";
        $reportContent .= "Link: " . $item->link . "\n";
        $reportContent .= "Date: " . $item->pubDate . "\n";
        $reportContent .= "Description: " . $item->description . "\n";
        $reportContent .= str_repeat("-", 80) . "\n\n";
    }

    // Vulnerable file creation - using timestamp in filename
    $filename = 'news_export_' . time() . '.txt';
    $filepath = $exportDir . $filename;
    file_put_contents($filepath, $reportContent);
    
    $exportFile = $filename;
    $message = "Export generated successfully!";
}

// Vulnerable download implementation
if (isset($_GET['file'])) {
    $requestedFile = $_GET['file'];
    $filePath = "exports/" . $requestedFile; // Vulnerable to path traversal
    
    if (file_exists($filePath)) {
        header('Content-Type: text/plain');
        header('Content-Disposition: attachment; filename="' . basename($requestedFile) . '"');
        readfile($filePath);
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Export News - Turkish Financial News</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            min-height: 100vh;
        }
        .navbar {
            background: rgba(255, 255, 255, 0.95);
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .container-main {
            padding-top: 80px;
        }
        .export-card {
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
    </style>
</head>
<body>
    <!-- Replace or adjust navbar to remove admin references -->
    <?php include 'navbar.php'; /* e.g., remove admin checks inside navbar.php */ ?>
    
    <div class="container container-main">
        <div class="export-card">
            <h2>Export News Data</h2>
            <p class="text-muted">Generate and download complete news export</p>

            <?php if($message): ?>
                <div class="alert alert-success">
                    <?php echo htmlspecialchars($message); ?>
                    <?php if($exportFile): ?>
                        <br>
                        <a href="export_news.php?file=<?php echo urlencode($exportFile); ?>" 
                           class="btn btn-sm btn-primary mt-2">Download Export</a>
                    <?php endif; ?>
                </div>
            <?php endif; ?>

            <form method="POST">
                <button type="submit" class="btn btn-primary">Generate Export</button>
            </form>

            <div class="mt-4">
                <h4>Previous Exports</h4>
                <?php
                $exports = glob('exports/*.txt');
                if($exports): ?>
                    <ul class="list-group">
                        <?php foreach($exports as $export): ?>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <?php echo basename($export); ?>
                                <a href="export_news.php?file=<?php echo urlencode(basename($export)); ?>" 
                                   class="btn btn-sm btn-outline-primary">Download</a>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                <?php else: ?>
                    <p class="text-muted">No previous exports found.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>