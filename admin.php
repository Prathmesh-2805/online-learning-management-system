<?php
require_once 'includes/auth.php';

// Redirect if not admin
if(!isAdmin()) {
    header('Location: index.php');
    exit;
}

$success = '';
$error = '';

// Handle course creation/editing
if($_SERVER['REQUEST_METHOD'] === 'POST' && verifyCSRFToken($_POST['csrf_token'])) {
    $title = sanitize($_POST['title']);
    $description = sanitize($_POST['description']);
    $link = sanitize($_POST['link']);
    
    // Handle image upload
    $image = '';
    if(isset($_FILES['image']) && $_FILES['image']['error'] === 0) {
        $allowed = ['jpg', 'jpeg', 'png', 'gif'];
        $filename = $_FILES['image']['name'];
        $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
        
        if(in_array($ext, $allowed)) {
            $upload_dir = 'images/courses/';
            if(!is_dir($upload_dir)) {
                mkdir($upload_dir, 0777, true);
            }
            
            $new_filename = uniqid() . '.' . $ext;
            if(move_uploaded_file($_FILES['image']['tmp_name'], $upload_dir . $new_filename)) {
                $image = $upload_dir . $new_filename;
            }
        }
    }
    
    try {
        if(isset($_POST['course_id'])) {
            // Update existing course
            $stmt = $pdo->prepare("UPDATE courses SET title = ?, description = ?, link = ? WHERE id = ?");
            if($image) {
                $stmt = $pdo->prepare("UPDATE courses SET title = ?, description = ?, link = ?, image = ? WHERE id = ?");
                $stmt->execute([$title, $description, $link, $image, $_POST['course_id']]);
            } else {
                $stmt->execute([$title, $description, $link, $_POST['course_id']]);
            }
            $success = "Course updated successfully!";
        } else {
            // Create new course
            $stmt = $pdo->prepare("INSERT INTO courses (title, description, image, link) VALUES (?, ?, ?, ?)");
            $stmt->execute([$title, $description, $image, $link]);
            $success = "Course created successfully!";
        }
    } catch(PDOException $e) {
        $error = "Error processing request.";
    }
}

// Fetch all courses for management
try {
    $stmt = $pdo->query("SELECT * FROM courses ORDER BY id DESC");
    $courses = $stmt->fetchAll();
} catch(PDOException $e) {
    $courses = [];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - LMS</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/aesthetic-effects.css">
</head>
<body>
    <nav class="navbar">
        <div class="nav-links">
            <a href="index.php">Home</a>
            <a href="courses.php">Courses</a>
            <a href="dashboard.php">Dashboard</a>
            <a href="logout.php">Logout</a>
        </div>
    </nav>

    <div class="container" style="margin-top: 80px;">
        <div class="glass-effect" style="padding: 20px; margin-bottom: 30px;">
            <h1>Admin Panel</h1>
            <p>Manage courses and system settings</p>
        </div>

        <?php if($success): ?>
            <div class="alert alert-success"><?php echo $success; ?></div>
        <?php endif; ?>

        <?php if($error): ?>
            <div class="alert alert-error"><?php echo $error; ?></div>
        <?php endif; ?>

        <!-- Course Creation Form -->
        <div class="card">
            <h2>Add New Course</h2>
            <form method="POST" action="" enctype="multipart/form-data">
                <input type="hidden" name="csrf_token" value="<?php echo generateCSRFToken(); ?>">
                
                <div class="form-group">
                    <label for="title">Course Title</label>
                    <input type="text" id="title" name="title" class="form-control" required>
                </div>

                <div class="form-group">
                    <label for="description">Description</label>
                    <textarea id="description" name="description" class="form-control" rows="4" required></textarea>
                </div>

                <div class="form-group">
                    <label for="image">Course Image</label>
                    <input type="file" id="image" name="image" class="form-control" accept="image/*">
                </div>

                <div class="form-group">
                    <label for="link">Course Link</label>
                    <input type="url" id="link" name="link" class="form-control">
                </div>

                <button type="submit" class="btn btn-effect">Create Course</button>
            </form>
        </div>

        <!-- Course Management -->
        <h2 style="margin-top: 40px;">Manage Courses</h2>
        <div class="grid">
            <?php foreach($courses as $course): ?>
                <div class="card hover-effect">
                    <?php if($course['image']): ?>
                        <img src="<?php echo sanitize($course['image']); ?>" 
                             alt="<?php echo sanitize($course['title']); ?>"
                             class="course-image">
                    <?php endif; ?>
                    
                    <h3><?php echo sanitize($course['title']); ?></h3>
                    <p><?php echo sanitize($course['description']); ?></p>
                    
                    <!-- Edit Form -->
                    <form method="POST" action="" enctype="multipart/form-data" style="margin-top: 20px;">
                        <input type="hidden" name="csrf_token" value="<?php echo generateCSRFToken(); ?>">
                        <input type="hidden" name="course_id" value="<?php echo $course['id']; ?>">
                        
                        <div class="form-group">
                            <input type="text" name="title" value="<?php echo sanitize($course['title']); ?>" class="form-control" required>
                        </div>

                        <div class="form-group">
                            <textarea name="description" class="form-control" rows="4" required><?php echo sanitize($course['description']); ?></textarea>
                        </div>

                        <div class="form-group">
                            <input type="file" name="image" class="form-control" accept="image/*">
                        </div>

                        <div class="form-group">
                            <input type="url" name="link" value="<?php echo sanitize($course['link']); ?>" class="form-control">
                        </div>

                        <button type="submit" class="btn btn-effect">Update Course</button>
                    </form>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</body>
</html>
