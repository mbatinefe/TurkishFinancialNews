<?php

/*----------------------------------------------------------------------------------------
 * Copyright (c) Microsoft Corporation. All rights reserved.
 * Licensed under the MIT License. See LICENSE in the project root for license information.
 *---------------------------------------------------------------------------------------*/

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
            background-color: #f8f9fa;
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
    </style>
</head>
<body>
    <div class="container mt-4">
        <h1 class="text-center mb-4">Turkish Financial News</h1>
        
        <!-- Carousel for featured news -->
        <div id="newsCarousel" class="carousel slide mb-4" data-ride="carousel">
            <ol class="carousel-indicators">
                <?php
                for($i = 0; $i < count($newsItems); $i++) {
                    echo "<li data-target='#newsCarousel' data-slide-to='{$i}' " . ($i == 0 ? "class='active'" : "") . "></li>";
                }
                ?>
            </ol>
            <div class="carousel-inner">
                <?php
                $isActive = true;
                foreach ($newsItems as $news) {
                    if ($isActive) {
                        echo "<div class='carousel-item active'>
                                <img src='{$news['image']}' class='d-block w-100' alt='News Image'>
                                <div class='carousel-caption d-none d-md-block'>
                                    <h5><a href='{$news['link']}' target='_blank' class='text-white'>{$news['title']}</a></h5>
                                    <p>{$news['description']}</p>
                                </div>
                              </div>";
                        $isActive = false;
                    } else {
                        echo "<div class='carousel-item'>
                                <img src='{$news['image']}' class='d-block w-100' alt='News Image'>
                                <div class='carousel-caption d-none d-md-block'>
                                    <h5><a href='{$news['link']}' target='_blank' class='text-white'>{$news['title']}</a></h5>
                                    <p>{$news['description']}</p>
                                </div>
                              </div>";
                    }
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

        <!-- Search form -->
        <form method="GET" action="index.php" class="form-inline my-4 justify-content-center">
            <input type="text" name="search" class="form-control mr-2" placeholder="Search news...">
            <button type="submit" class="btn btn-primary">Search</button>
        </form>

        <!-- News list -->
        <div class="news-list">
            <?php
            $searchQuery = isset($_GET['search']) ? strtolower(trim($_GET['search'])) : '';
            if (!empty($newsItems)) {
                foreach ($newsItems as $news) {
                    if ($searchQuery === '' || strpos(strtolower($news['title']), $searchQuery) !== false || strpos(strtolower($news['description']), $searchQuery) !== false) {
                        echo "<div class='card mb-3'>
                                <div class='row no-gutters'>
                                    <div class='col-md-4 position-relative'>
                                        <img src='{$news['image']}' class='card-img-top' alt='News Image'>
                                    </div>
                                    <div class='col-md-8'>
                                        <div class='card-body'>
                                            <h5 class='card-title'><a href='{$news['link']}' target='_blank'>{$news['title']}</a></h5>
                                            <p class='card-text'>{$news['description']}</p>
                                            <p class='card-text'><small class='text-muted'>Published on: {$news['pubDate']}</small></p>
                                        </div>
                                    </div>
                                </div>
                              </div>";
                    }
                }
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