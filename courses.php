<?php
require_once 'includes/auth.php';
require_once 'includes/db.php';

// Fetch all courses
try {
    $stmt = $pdo->query("SELECT * FROM courses ORDER BY id DESC");
    $courses = $stmt->fetchAll();
} catch(PDOException $e) {
    $courses = [];
}

// Handle course enrollment
if(isLoggedIn() && isset($_POST['enroll']) && verifyCSRFToken($_POST['csrf_token'])) {
    $course_id = (int)$_POST['course_id'];
    
    try {
        // Check if already enrolled
        $stmt = $pdo->prepare("SELECT id FROM enrollments WHERE user_id = ? AND course_id = ?");
        $stmt->execute([$_SESSION['user_id'], $course_id]);
        
        if($stmt->rowCount() === 0) {
            $stmt = $pdo->prepare("INSERT INTO enrollments (user_id, course_id, enrollment_date) VALUES (?, ?, NOW())");
            $stmt->execute([$_SESSION['user_id'], $course_id]);
            $success = "Successfully enrolled in the course!";
        } else {
            $error = "You are already enrolled in this course.";
        }
    } catch(PDOException $e) {
        $error = "Error processing enrollment.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Courses - LMS</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/aesthetic-effects.css">
</head>
<body>
    <nav class="navbar">
        <div class="nav-links">
            <a href="index.php">Home</a>
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

    <div class="container" style="margin-top: 80px;">
        <h1>Available Courses</h1>

        <?php if(isset($error)): ?>
            <div class="alert alert-error"><?php echo $error; ?></div>
        <?php endif; ?>

        <?php if(isset($success)): ?>
            <div class="alert alert-success"><?php echo $success; ?></div>
        <?php endif; ?>

        <div class="grid">
            <?php foreach($courses as $course): ?>
                <div class="card hover-effect">
                    <?php if($course['image']): ?>
                        <img data-src="<?php echo sanitize($course['image']); ?>" 
                             alt="<?php echo sanitize($course['title']); ?>"
                             class="course-image img-hover">
                    <?php endif; ?>
                    
                    <h2><?php echo sanitize($course['title']); ?></h2>
                    <p><?php echo sanitize($course['description']); ?></p>
                    
                    <div class="course-actions" style="display: flex; gap: 10px; margin-top: 20px;">
                        <?php if(isLoggedIn()): ?>
                            <form method="POST" action="" style="margin-right: 10px;">
                                <input type="hidden" name="csrf_token" value="<?php echo generateCSRFToken(); ?>">
                                <input type="hidden" name="course_id" value="<?php echo $course['id']; ?>">
                                <button type="submit" name="enroll" class="btn btn-effect">Enroll Now</button>
                            </form>
                            <?php if($course['link']): ?>
                                <a href="<?php echo sanitize($course['link']); ?>" 
                                   target="_blank" 
                                   class="btn btn-effect" 
                                   style="background-color: var(--secondary-color);">
                                    Start Learning
                                </a>
                            <?php endif; ?>
                        <?php else: ?>
                            <a href="login.php" class="btn btn-effect">Login to Enroll</a>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
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
