<?php
session_start();
require_once 'config.php';

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['register'])) {
    // Create uploads directory if it doesn't exist
    $uploaddir = 'uploads/';
    if (!file_exists($uploaddir)) {
        mkdir($uploaddir, 0777, true);
    }
    
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    
    // File upload handling
    $profile_picture = '';
    if(isset($_FILES['profile_picture'])) {
        $uploadfile = $uploaddir . basename($_FILES['profile_picture']['name']);
        
        /*
            FIXED: Validate file type before uploading. We only allow JPG, PNG and GIF files
        */
        $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];
        $file_type = mime_content_type($_FILES['profile_picture']['tmp_name']);
        
        if (in_array($file_type, $allowed_types)) {
            if(move_uploaded_file($_FILES['profile_picture']['tmp_name'], $uploadfile)) {
                $profile_picture = $uploadfile;
            } else {
                $error = "Failed to upload file.";
            }
        }
        /*
            If the file type is not allowed, we do not upload the file
            We do not give error message here to prevent leaking information
            So, attacker will be signed-up without a profile picture
        */
    }
    
    if ($password !== $confirm_password) {
        $error = "Passwords do not match";
    } else {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        // Update SQL to include profile picture
        $sql = "INSERT INTO users (username, email, password, profile_picture) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssss", $username, $email, $hashed_password, $profile_picture);
        
        if ($stmt->execute()) {
            $_SESSION['register_success'] = true;
            header("Location: login.php");
            exit;
        } else {
            $error = "Error: " . $stmt->error;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up - Turkish Financial News</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
        }
        .auth-container {
            max-width: 400px;
            margin: 0 auto;
            padding: 20px;
        }
        .auth-card {
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .navigation-links {
            text-align: center;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="container auth-container">
        <div class="auth-card">
            <h2 class="text-center mb-4">Sign Up</h2>

            <?php if($error): ?>
                <div class="alert alert-danger"><?php echo $error; ?></div>
            <?php endif; ?>
            <?php if($success): ?>
                <div class="alert alert-success"><?php echo $success; ?></div>
            <?php endif; ?>

            <form method="POST" enctype="multipart/form-data">
                <div class="form-group">
                    <label>Username</label>
                    <input type="text" name="username" class="form-control" required>
                </div>
                <div class="form-group">
                    <label>Email</label>
                    <input type="email" name="email" class="form-control" required>
                </div>
                <div class="form-group">
                    <label>Password</label>
                    <input type="password" name="password" class="form-control" required>
                </div>
                <div class="form-group">
                    <label>Confirm Password</label>
                    <input type="password" name="confirm_password" class="form-control" required>
                </div>
                <div class="form-group">
                    <label>Profile Picture</label>
                    <input type="file" name="profile_picture" class="form-control-file">
                </div>
                <button type="submit" name="register" class="btn btn-primary btn-block">Sign Up</button>
            </form>

            <div class="navigation-links">
                <p>Already have an account? <a href="login.php">Login</a></p>
                <a href="index.php" class="btn btn-outline-secondary">Back to Home</a>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>