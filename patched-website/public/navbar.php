<nav class="navbar navbar-expand-lg fixed-top navbar-light">
    <div class="container">
        <a class="navbar-brand" href="index.php">Turkish Financial News</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
            <a href="subscribe.php" class="btn btn-outline-success mr-3">Subscribe</a>
            <a href="export_news.php" class="btn btn-outline-info mr-3">Export News</a>
            <a href="check_feeds.php" class="btn btn-outline-warning mr-3">Check Feeds</a>
            <form method="GET" action="index.php" class="form-inline my-2 my-lg-0 search-form-nav mr-3">
                <input type="text" name="search" class="form-control mr-2" placeholder="Search ...">
                <button type="submit" class="btn btn-outline-primary my-2 my-sm-0">Search</button>
            </form>
            <?php if(isset($_SESSION['user_id'])): ?>
                <div class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" id="navbarDropdown" role="button" data-toggle="dropdown">
                    <!--
                        FIX:
                        - Use $_SESSION instead of $_COOKIE to check the role
                    -->    
                    <?php if(isset($_SESSION['profile_picture']) && $_SESSION['profile_picture']): ?>
                            <img src="<?php echo htmlspecialchars($_SESSION['profile_picture']); ?>" 
                                 class="rounded-circle mr-2" 
                                 style="width: 30px; height: 30px; object-fit: cover;" 
                                 alt="Profile">
                        <?php endif; ?>
                        <?php if(isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
                            Welcome Admin, <?php echo htmlspecialchars($_SESSION['username']); ?>
                        <?php else: ?>
                            Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?>
                        <?php endif; ?>
                    </a>
                    <div class="dropdown-menu">
                        <a class="dropdown-item" href="logout.php">Logout</a>
                        <?php if(isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
                            <a class="dropdown-item" href="admin_users.php">Manage Users</a>
                        <?php endif; ?>
                    </div>
                </div>
            <?php else: ?>
                <div class="auth-buttons">
                    <a href="login.php" class="btn btn-outline-primary mr-2">Login</a>
                    <a href="signup.php" class="btn btn-primary">Sign Up</a>
                </div>
            <?php endif; ?>
        </div>
    </div>
</nav>