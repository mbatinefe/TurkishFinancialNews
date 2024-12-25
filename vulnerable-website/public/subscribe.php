<?php
session_start();
require_once 'config.php';

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Directly trust the user input with no sanitization
    if (isset($_GET['email'])) {
        $email = $_GET['email'];
    } else {
        $email = $_POST['email'];
    }

    if ($email == '') {
        $error = 'Email is required.';
    } else {
        $email = $email;
    }

    if (empty($error)) {
        /*
        VULNERABILITY: SQL Injection
            The $email variable is directly used in the SQL query with no sanitization
            Attacker can manipulate $email variable to inject SQL commands
        */
        $q = "SELECT * FROM subscribers WHERE email = '$email'";
        $result = mysqli_query($conn, $q);

        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $success .= "Email " . htmlentities($row['email']) . " is already subscribed!<br>";
            }
        } else {
            // Another vulnerable query for inserting user input directly
            // But we do not come to here because first q is already vulnerable
            $sql = "INSERT INTO subscribers (email) VALUES ('$email')";
            if (mysqli_query($conn, $sql)) {
                $success = 'Subscription successful.';
            } else {
                $error = 'Subscription failed.';
            }
        }
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
        .admin-card {
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .navbar {
            background: rgba(255, 255, 255, 0.95);
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
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