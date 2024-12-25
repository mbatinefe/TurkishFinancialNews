<?php
session_start();
require_once 'config.php';

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Securely handle user input
    if (isset($_GET['email'])) {
        $email = $_GET['email'];
    } else {
        $email = $_POST['email'];
    }

    if ($email == '') {
        $error = 'Email is required.';
    } else {
        // Validate email format
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $error = 'Invalid email format.';
        }
    }

    if (empty($error)) {
        /*
        FIXED: Use prepared statements to prevent SQL Injection
        */
        $stmt = $conn->prepare("SELECT * FROM subscribers WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $success .= "Email " . htmlentities($row['email']) . " is already subscribed!<br>";
            }
        } else {
            // Use prepared statement for insertion
            $stmt = $conn->prepare("INSERT INTO subscribers (email) VALUES (?)");
            $stmt->bind_param("s", $email);

            if ($stmt->execute()) {
                $success = 'Subscription successful.';
            } else {
                $error = 'Subscription failed.';
            }
        }

        // Close statement
        $stmt->close();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Subscribe - Turkish Financial News</title>
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
        .subscribe-card {
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            max-width: 600px;
            margin: 0 auto;
        }
    </style>
</head>
<body>
    <?php include 'navbar.php'; ?>
    
    <div class="container container-main">
        <div class="subscribe-card">
            <h2 class="text-center mb-4">Subscribe to Our Newsletter</h2>
            <p class="text-muted text-center mb-4">Stay updated with the latest Turkish financial news and market insights.</p>
            
            <?php if($error): ?>
                <div class="alert alert-danger"><?php echo $error; ?></div>
            <?php endif; ?>
            
            <?php if($success): ?>
                <div class="alert alert-success"><?php echo $success; ?></div>
            <?php endif; ?>

            <form method="POST" class="mb-4">
                <div class="form-group">
                    <label>Email Address</label>
                    <input type="text" name="email" class="form-control" placeholder="Enter your email address">
                    <small class="form-text text-muted">We'll never share your email with anyone else.</small>
                </div>
                <div class="btn-group w-100">
                    <button type="submit" name="action" value="subscribe" class="btn btn-primary">Subscribe Now</button>
                    <button type="submit" name="action" value="unsubscribe" class="btn btn-danger">Unsubscribe</button>
                </div>
            </form>
            
            <div class="text-center">
                <a href="index.php" class="btn btn-link">Back to Home</a>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
