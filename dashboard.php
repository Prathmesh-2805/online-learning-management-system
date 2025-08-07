<?php
require_once 'includes/auth.php';

// Redirect if not logged in
if(!isLoggedIn()) {
    header('Location: login.php');
    exit;
}

// Fetch user's enrolled courses
try {
    $stmt = $pdo->prepare("
        SELECT c.* 
        FROM courses c 
        JOIN enrollments e ON c.id = e.course_id 
        WHERE e.user_id = ?
        ORDER BY e.enrollment_date DESC
    ");
    $stmt->execute([$_SESSION['user_id']]);
    $enrolledCourses = $stmt->fetchAll();
} catch(PDOException $e) {
    $enrolledCourses = [];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - LMS</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/aesthetic-effects.css">
</head>
<body>
    <nav class="navbar">
        <div class="nav-links">
            <a href="index.php">Home</a>
            <a href="courses.php">Courses</a>
            <?php if(isAdmin()): ?>
                <a href="admin.php">Admin Panel</a>
            <?php endif; ?>
            <a href="logout.php">Logout</a>
        </div>
    </nav>

    <div class="container" style="margin-top: 80px;">
        <div class="glass-effect" style="padding: 20px; margin-bottom: 30px;">
            <h1>Welcome, <?php echo sanitize($_SESSION['username']); ?>!</h1>
            <p>Manage your enrolled courses and track your progress</p>
        </div>

        <div class="grid">
            <?php foreach($enrolledCourses as $course): ?>
                <div class="card hover-effect">
                    <?php if($course['image']): ?>
                        <img data-src="<?php echo sanitize($course['image']); ?>" 
                             alt="<?php echo sanitize($course['title']); ?>"
                             class="course-image img-hover">
                    <?php endif; ?>
                    
                    <h2><?php echo sanitize($course['title']); ?></h2>
                    <p><?php echo sanitize($course['description']); ?></p>
                    
                    <?php if($course['link']): ?>
                        <a href="<?php echo sanitize($course['link']); ?>" 
                           class="btn btn-effect" 
                           target="_blank"
                           style="background-color: var(--secondary-color);">
                            Start Learning
                        </a>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>

            <?php if(empty($enrolledCourses)): ?>
                <div class="card">
                    <h2>No Courses Yet</h2>
                    <p>You haven't enrolled in any courses yet.</p>
                    <a href="courses.php" class="btn btn-effect">Browse Courses</a>
                </div>
            <?php endif; ?>
        </div>
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
