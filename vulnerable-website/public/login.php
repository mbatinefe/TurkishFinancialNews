<?php
session_start();

$success = '';
if (isset($_SESSION['register_success'])) {
    $success = "Registration successful! Please login.";
    unset($_SESSION['register_success']);
}

require_once 'config.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['login'])) {
        // directly trusting user input without proper sanitization
        $username = $_POST['username'];
        $password = $_POST['password'];

        /*
            Vulnerability: SQL Injection
            Directly embedding user input into the SQL query makes it vulnerable to SQL injection attacks.
            If an attacker provides malicious input such as "' OR 1=1 --", it will modify the query logic.
        */
        $sql = "SELECT * FROM users WHERE username = '$username' AND password = '$password'";
        $result = $conn->query($sql);

        if ($result) {
            // Fetching user information from the result without checking if a valid user record exists.
            $user = $result->fetch_assoc();

            // If SQL injection is successful, the attacker can gain unauthorized access.
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = $user['role'];
            $_SESSION['profile_picture'] = $user['profile_picture'];
            header("Location: index.php");
            exit;
        }
        $error = "Invalid username or password";
    }

    if (isset($_POST['register'])) {
        $username = trim($_POST['username']);
        $email = trim($_POST['email']);
        $password = $_POST['password'];
        $confirm_password = $_POST['confirm_password'];

        if ($password !== $confirm_password) {
            $error = "Passwords do not match";
        } else {
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $sql = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("sss", $username, $email, $hashed_password);

            if ($stmt->execute()) {
                $success = "Registration successful. Please login.";
            } else {
                $error = "Error: " . $stmt->error;
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
    <title>Login - Turkish Financial News</title>
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
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
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
            <h2 class="text-center mb-4">Login</h2>

            <?php if ($error): ?>
                <div class="alert alert-danger"><?php echo $error; ?></div>
            <?php endif; ?>

            <?php if ($success): ?>
                <div class="alert alert-success"><?php echo $success; ?></div>
            <?php endif; ?>

            <form method="POST">
                <div class="form-group">
                    <label>Username</label>
                    <input type="text" name="username" class="form-control" required>
                </div>
                <div class="form-group">
                    <label>Password</label>
                    <input type="password" name="password" class="form-control" required>
                </div>
                <button type="submit" name="login" class="btn btn-primary btn-block">Login</button>
            </form>

            <div class="navigation-links">
                <p>Don't have an account? <a href="signup.php">Sign Up</a></p>
                <a href="index.php" class="btn btn-outline-secondary">Back to Home</a>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>