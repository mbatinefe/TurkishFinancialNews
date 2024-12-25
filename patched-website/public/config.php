<?php
define('DB_SERVER', getenv('MYSQL_HOST') ?: 'localhost');
define('DB_USERNAME', getenv('MYSQL_USER') ?: 'root');
define('DB_PASSWORD', getenv('MYSQL_PASSWORD') ?: '');
define('DB_NAME', getenv('MYSQL_DATABASE') ?: 'financial_news');

$conn = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>