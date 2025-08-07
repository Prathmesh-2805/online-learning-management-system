<?php
require_once 'includes/auth.php';

$error = '';
$success = '';

if(isset($_GET['registered']) && $_GET['registered'] == 1) {
    $success = 'Registration successful! Please login with your credentials.';
}

if($_SERVER['REQUEST_METHOD'] === 'POST') {
    if(verifyCSRFToken($_POST['csrf_token'])) {
        $email = sanitize($_POST['email']);
        $password = $_POST['password'];
        
        try {
            // Check if user exists
            $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
            $stmt->execute([$email]);
            $user = $stmt->fetch();
            
            if($user) {
                if(password_verify($password, $user['password'])) {
                    $_SESSION['user_id'] = $user['id'];
                    $_SESSION['username'] = $user['username'];
                    $_SESSION['role'] = $user['role'];
                    header('Location: dashboard.php');
                    exit;
                } else {
                    $error = 'Invalid password';
                }
            } else {
                $error = 'Email not found';
            }
        } catch(PDOException $e) {
            $error = 'Database error: ' . $e->getMessage();
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
    <title>Login - LMS</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/aesthetic-effects.css">
</head>
<body>
    <nav class="navbar">
        <div class="nav-links">
            <a href="index.php">Home</a>
            <a href="register.php">Register</a>
        </div>
    </nav>

    <div class="container" style="margin-top: 80px;">
        <div class="card glass-effect" style="max-width: 500px; margin: 0 auto;">
            <h2>Login</h2>
            
            <?php if($error): ?>
                <div class="alert alert-error"><?php echo $error; ?></div>
            <?php endif; ?>
            
            <?php if($success): ?>
                <div class="alert alert-success"><?php echo $success; ?></div>
            <?php endif; ?>

            <form method="POST" action="">
                <input type="hidden" name="csrf_token" value="<?php echo generateCSRFToken(); ?>">
                
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" class="form-control" required>
                </div>

                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" class="form-control" required>
                </div>

                <button type="submit" class="btn btn-effect">Login</button>
            </form>

            <p style="margin-top: 20px;">
                Don't have an account? <a href="register.php">Register here</a>
            </p>
        </div>
    </div>
</body>
</html>
