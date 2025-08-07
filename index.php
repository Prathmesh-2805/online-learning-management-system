<?php
require_once 'includes/auth.php';
$pageTitle = "Modern Learning Management System";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $pageTitle; ?></title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/aesthetic-effects.css">
</head>
<body>
    <nav class="navbar">
        <div class="nav-links">
            <a href="index.php">Home</a>
            <a href="courses.php">Courses</a>
            <?php if(isLoggedIn()): ?>
                <a href="dashboard.php">Dashboard</a>
                <?php if(isAdmin()): ?>
                    <a href="admin.php">Admin Panel</a>
                <?php endif; ?>
                <a href="logout.php">Logout</a>
            <?php else: ?>
                <a href="login.php">Login</a>
                <a href="register.php">Register</a>
            <?php endif; ?>
        </div>
    </nav>

    <div class="container">
        <header class="glass-effect" style="margin-top: 80px; padding: 40px; text-align: center;">
            <h1 class="text-gradient">Welcome to Modern Learning</h1>
            <p>Discover a new way of learning with our cutting-edge platform</p>
        </header>

        <section class="grid" style="margin-top: 40px;">
            <div class="card hover-effect">
                <h2>Featured Courses</h2>
                <p>Explore our most popular courses</p>
                <a href="courses.php" class="btn btn-effect">Browse Courses</a>
            </div>

            <div class="card hover-effect">
                <h2>Start Learning</h2>
                <p>Join our community of learners</p>
                <?php if(!isLoggedIn()): ?>
                    <a href="register.php" class="btn btn-effect">Sign Up Now</a>
                <?php else: ?>
                    <a href="dashboard.php" class="btn btn-effect">Go to Dashboard</a>
                <?php endif; ?>
            </div>
        </section>
    </div>

    <script>
        // Lazy loading for images
        document.addEventListener('DOMContentLoaded', function() {
            const images = document.querySelectorAll('img[data-src]');
            const imageObserver = new IntersectionObserver((entries, observer) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        const img = entry.target;
                        img.src = img.dataset.src;
                        img.removeAttribute('data-src');
                        observer.unobserve(img);
                    }
                });
            });

            images.forEach(img => imageObserver.observe(img));
        });
    </script>
</body>
</html>
