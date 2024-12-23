<?php

/*----------------------------------------------------------------------------------------
 * Copyright (c) Microsoft Corporation. All rights reserved.
 * Licensed under the MIT License. See LICENSE in the project root for license information.
 *---------------------------------------------------------------------------------------*/

 // Enable error reporting for debugging (remove in production)
 ini_set('display_errors', 1);
 error_reporting(E_ALL);
 
 // Fetch RSS feed from Turkish financial news source
 $newsFeedUrl = "https://bigpara.hurriyet.com.tr/rss/"; // Example RSS feed URL
 $newsItems = [];
 
 try {
     $rss = simplexml_load_file($newsFeedUrl);
     if ($rss && isset($rss->channel->item)) {
         foreach ($rss->channel->item as $item) {
             $namespaces = $item->getNamespaces(true);
             $media = $item->children($namespaces['media']);
             $imageUrl = isset($media->content) ? (string)$media->content->attributes()->url : '';

             $newsItems[] = [
                 'title' => (string)$item->title,
                 'link' => (string)$item->link,
                 'description' => (string)$item->description,
                 'pubDate' => (string)$item->pubDate,
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
 </head>
 <body>
     <div class="container mt-4">
         <h1 class="text-center">Turkish Financial News</h1>
         <form method="GET" action="index.php" class="form-inline my-4 justify-content-center">
             <input type="text" name="search" class="form-control mr-2" placeholder="Search news...">
             <button type="submit" class="btn btn-primary">Search</button>
         </form>
 
         <div class="news-list">
             <?php
             $searchQuery = isset($_GET['search']) ? strtolower(trim($_GET['search'])) : '';
             if (!empty($newsItems)) {
                 foreach ($newsItems as $news) {
                     if ($searchQuery === '' || strpos(strtolower($news['title']), $searchQuery) !== false || strpos(strtolower($news['description']), $searchQuery) !== false) {
                         echo "<div class='card mb-3'>
                                 <div class='row no-gutters'>
                                     <div class='col-md-4'>
                                         <img src='{$news['image']}' class='card-img' alt='News Image'>
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