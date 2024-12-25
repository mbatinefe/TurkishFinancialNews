<?php
session_start();

 // Enable error reporting for debugging (remove in production)
 ini_set('display_errors', 1);
 error_reporting(E_ALL);

 // Function to debug and print RSS feed structure
function debug($data) {
    echo '<pre>';
    print_r($data);
    echo '</pre>';
    exit;
}

// Make phpinfo() available
/*
    VULNERABLE since it exposes sensitive information to attackers.
    We can learn about the server configuration, PHP version, extensions and more which
    might help attackers to do path traversal, SQL injection, or other attacks.
    // Example: localhost/index.php?phpinfo
*/
if (isset($_GET['phpinfo'])) {
    phpinfo();
    exit;
}

// Fetch RSS feed from Turkish financial news source
$newsFeedUrl = "https://www.hurriyet.com.tr/rss/ekonomi"; // Example RSS feed URL
$newsItems = [];

try {
    $rss = simplexml_load_file($newsFeedUrl);
    if ($rss && isset($rss->channel->item)) {
        // Debug the entire RSS feed structure
        // debug($rss);

        foreach ($rss->channel->item as $item) {
            $namespaces = $item->getNamespaces(true);
            $imageUrl = '';
            if (isset($namespaces['media'])) {
                $media = $item->children($namespaces['media']);
                $imageUrl = isset($media->content) ? (string)$media->content->attributes()->url : '';
            }

            $newsItems[] = [
                'title' => (string)$item->title,
                'link' => (string)$item->link,
                'description' => (string)$item->description,
                'pubDate' => (string)$item->pubDate,
                'category' => (string)$item->category,
                'image' => $imageUrl
            ];
        }
    } else {
        throw new Exception("Unable to parse RSS feed.");
    }
} catch (Exception $e) {
    echo "<p>Error fetching news: " . $e->getMessage() . "</p>";
}

// After loading news items, filter based on search
$searchQuery = isset($_GET['search']) ? strtolower(trim($_GET['search'])) : '';
if ($searchQuery !== '') {
    $newsItems = array_filter($newsItems, function($news) use ($searchQuery) {
        return strpos(strtolower($news['title']), $searchQuery) !== false || 
               strpos(strtolower($news['description']), $searchQuery) !== false;
    });
}

// Pagination logic
$itemsPerPage = 5;
$currentPage = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$totalPages = ceil(count($newsItems) / $itemsPerPage);
$currentPage = max(1, min($currentPage, $totalPages));
$offset = ($currentPage - 1) * $itemsPerPage;

// Calculate visible page numbers
$visiblePages = 5;
$startPage = max(1, min($currentPage - floor($visiblePages/2), $totalPages - $visiblePages + 1));
$endPage = min($startPage + $visiblePages - 1, $totalPages);

// Slice the news items array for current page
$currentNewsItems = array_slice($newsItems, $offset, $itemsPerPage);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Turkish Financial News</title>
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
        .carousel-item {
            position: relative;
        }
        .carousel-item::after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(rgba(0,0,0,0.1), rgba(0,0,0,0.7));
        }
        .carousel-item img {
            height: 500px;
            object-fit: cover;
            width: 100%;
        }
        .carousel-caption {
            bottom: 50px;
            background: rgba(0,0,0,0.5);
            padding: 20px;
            border-radius: 8px;
            max-width: 80%;
            margin: 0 auto;
        }
        .carousel-caption h5 {
            font-size: 1.5rem;
            margin-bottom: 15px;
        }
        .carousel-caption p {
            font-size: 1rem;
            display: -webkit-box;
            -webkit-line-clamp: 3;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
        .card {
            transition: transform 0.3s ease;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            border: none;
            margin-bottom: 25px;
        }
        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
        }
        .card-img-top {
            height: 250px;
            object-fit: cover;
        }
        .category-badge {
            position: absolute;
            top: 10px;
            right: 10px;
            background: rgba(0,0,0,0.7);
            color: white;
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 0.8rem;
        }
        .news-list {
            margin-top: 40px;
        }
        .card-title {
            font-weight: 500;
            font-size: 1.3rem;
        }
        .search-form {
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
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
    </style>
</head>
<body>
    <!-- Navbar -->
    <?php include 'navbar.php'; ?>

    <!-- Main Content -->
    <div class="container container-main">
        <!-- Carousel for featured news -->
        <div id="newsCarousel" class="carousel slide mb-4" data-ride="carousel">
            <ol class="carousel-indicators">
                <?php
                $carouselLimit = 10;
                for($i = 0; $i < min(count($newsItems), $carouselLimit); $i++) {
                    echo "<li data-target='#newsCarousel' data-slide-to='{$i}' " . ($i == 0 ? "class='active'" : "") . "></li>";
                }
                ?>
            </ol>
            <div class="carousel-inner">
                <?php
                $isActive = true;
                $count = 0;
                foreach ($newsItems as $news) {
                    if ($count >= $carouselLimit) break;
                    
                    if ($isActive) {
                        echo "<div class='carousel-item active'>
                                <img src='{$news['image']}' class='d-block w-100' alt='News Image'>
                                <div class='carousel-caption d-none d-md-block'>
                                    <h5><a href='news.php?url=" . urlencode($news['link']) . "' target='_blank' class='text-white'>{$news['title']}</a></h5>
                                    <p>{$news['description']}</p>
                                </div>
                              </div>";
                        $isActive = false;
                    } else {
                        echo "<div class='carousel-item'>
                                <img src='{$news['image']}' class='d-block w-100' alt='News Image'>
                                <div class='carousel-caption d-none d-md-block'>
                                    <h5><a href='news.php?url=" . urlencode($news['link']) . "' target='_blank' class='text-white'>{$news['title']}</a></h5>
                                    <p>{$news['description']}</p>
                                </div>
                              </div>";
                    }
                    $count++;
                }
                ?>
            </div>
            <a class="carousel-control-prev" href="#newsCarousel" role="button" data-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="sr-only">Previous</span>
            </a>
            <a class="carousel-control-next" href="#newsCarousel" role="button" data-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="sr-only">Next</span>
            </a>
        </div>

        <!-- News list -->
        <div class="news-list">
            <?php
            $searchQuery = isset($_GET['search']) ? strtolower(trim($_GET['search'])) : '';
            if (!empty($currentNewsItems)) {
                foreach ($currentNewsItems as $news) {
                    if ($searchQuery === '' || strpos(strtolower($news['title']), $searchQuery) !== false || strpos(strtolower($news['description']), $searchQuery) !== false) {
                        echo "<div class='card mb-3'>
                                <div class='row no-gutters'>
                                    <div class='col-md-4 position-relative'>
                                        <img src='{$news['image']}' class='card-img-top' alt='News Image'>
                                    </div>
                                    <div class='col-md-8'>
                                        <div class='card-body'>
                                            <h5 class='card-title'>
                                                <a href='news.php?url=" . urlencode($news['link']) . "'>{$news['title']}</a>
                                            </h5>
                                            <p class='card-text'>{$news['description']}</p>
                                            <p class='card-text'><small class='text-muted'>Published on: {$news['pubDate']}</small></p>
                                        </div>
                                    </div>
                                </div>
                              </div>";
                    }
                }
            ?>
                <!-- Add pagination -->
                <nav aria-label="News pagination">
                    <ul class="pagination justify-content-center">
                        <li class="page-item <?php echo $currentPage <= 1 ? 'disabled' : ''; ?>">
                            <a class="page-link" href="?page=<?php echo $currentPage-1; ?><?php echo $searchQuery ? '&search='.$searchQuery : ''; ?>">Previous</a>
                        </li>
                        
                        <?php if($startPage > 1): ?>
                            <li class="page-item">
                                <a class="page-link" href="?page=1<?php echo $searchQuery ? '&search='.$searchQuery : ''; ?>">1</a>
                            </li>
                            <?php if($startPage > 2): ?>
                                <li class="page-item disabled"><span class="page-link">...</span></li>
                            <?php endif; ?>
                        <?php endif; ?>

                        <?php for($i = $startPage; $i <= $endPage; $i++): ?>
                            <li class="page-item <?php echo $currentPage == $i ? 'active' : ''; ?>">
                                <a class="page-link" href="?page=<?php echo $i; ?><?php echo $searchQuery ? '&search='.$searchQuery : ''; ?>"><?php echo $i; ?></a>
                            </li>
                        <?php endfor; ?>

                        <?php if($endPage < $totalPages): ?>
                            <?php if($endPage < $totalPages - 1): ?>
                                <li class="page-item disabled"><span class="page-link">...</span></li>
                            <?php endif; ?>
                            <li class="page-item">
                                <a class="page-link" href="?page=<?php echo $totalPages; ?><?php echo $searchQuery ? '&search='.$searchQuery : ''; ?>"><?php echo $totalPages; ?></a>
                            </li>
                        <?php endif; ?>

                        <li class="page-item <?php echo $currentPage >= $totalPages ? 'disabled' : ''; ?>">
                            <a class="page-link" href="?page=<?php echo $currentPage+1; ?><?php echo $searchQuery ? '&search='.$searchQuery : ''; ?>">Next</a>
                        </li>
                    </ul>
                </nav>
            <?php
            } else {
                echo "<p class='text-center'>No news available at the moment.</p>";
            }
            ?>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>