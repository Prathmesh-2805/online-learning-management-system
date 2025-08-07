<?php
require_once 'includes/auth.php';

$error = '';
$success = '';

if($_SERVER['REQUEST_METHOD'] === 'POST') {
    if(verifyCSRFToken($_POST['csrf_token'])) {
        $username = sanitize($_POST['username']);
        $email = sanitize($_POST['email']);
        $password = $_POST['password'];
        $confirm_password = $_POST['confirm_password'];
        
        if($password !== $confirm_password) {
            $error = 'Passwords do not match';
        } else if(strlen($password) < 8) {
            $error = 'Password must be at least 8 characters long';
        } else {
            try {
                // Check if user exists
                $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ? OR username = ?");
                $stmt->execute([$email, $username]);
                
                if($stmt->rowCount() > 0) {
                    $error = 'Username or email already exists';
                } else {
                    // Hash password and create user
                    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
                    $stmt = $pdo->prepare("INSERT INTO users (username, email, password, role) VALUES (?, ?, ?, 'user')");
                    
                    if($stmt->execute([$username, $email, $hashedPassword])) {
                        $_SESSION['registration_success'] = true;
                        header('Location: login.php?registered=1');
                        exit;
                    } else {
                        $error = 'Failed to create user account';
                    }
                }
            } catch(PDOException $e) {
                $error = 'Database error: ' . $e->getMessage();
            }
        }
    } else {
        $error = 'Invalid token';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - LMS</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/aesthetic-effects.css">
</head>
<body>
    <nav class="navbar">
        <div class="nav-links">
            <a href="index.php">Home</a>
            <a href="login.php">Login</a>
        </div>
    </nav>

    <div class="container" style="margin-top: 80px;">
        <div class="card glass-effect" style="max-width: 500px; margin: 0 auto;">
            <h2>Register</h2>
            
            <?php if($error): ?>
                <div class="alert alert-error"><?php echo $error; ?></div>
            <?php endif; ?>

            <?php if($success): ?>
                <div class="alert alert-success">
                    <?php echo $success; ?>
                    <br>
                    <a href="login.php">Click here to login</a>
                </div>
            <?php endif; ?>

            <form method="POST" action="">
                <input type="hidden" name="csrf_token" value="<?php echo generateCSRFToken(); ?>">
                
                <div class="form-group">
                    <label for="username">Username</label>
                    <input type="text" id="username" name="username" class="form-control" required>
                </div>

                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" class="form-control" required>
                </div>

                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" class="form-control" required>
                </div>

                <div class="form-group">
                    <label for="confirm_password">Confirm Password</label>
                    <input type="password" id="confirm_password" name="confirm_password" class="form-control" required>
                </div>

                <button type="submit" class="btn btn-effect">Register</button>
            </form>

            <p style="margin-top: 20px;">
                Already have an account? <a href="login.php">Login here</a>
            </p>
        </div>
    </div>
</body>
</html>
