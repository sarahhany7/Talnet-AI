<?php 
$message = ""; 
include 'config.php'; 

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $action = $_POST['action'] ?? 'register';
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $role = $_POST['role'] ?? 'seeker';

    if ($action == 'register') {
        $hashed = password_hash($password, PASSWORD_BCRYPT);
        
        try {
            $stmt = $pdo->prepare("INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, ?)");
            $stmt->execute([$name, $email, $hashed, $role]);
            $user_id = $pdo->lastInsertId();
            
            if ($role == 'seeker') {
                $pdo->prepare("INSERT INTO job_seekers (user_id, title) VALUES (?, ?)")->execute([$user_id, 'Job Seeker']);
            } elseif ($role == 'employer') {
                $pdo->prepare("INSERT INTO companies (user_id, company_name) VALUES (?, ?)")->execute([$user_id, $name . "'s Company"]);
            }
            
            $message = "Registration successful! Please login.";
        } catch (PDOException $e) {
            $message = "Email already exists.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Register | TalentAI</title>
    <link rel="stylesheet" href="style.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body style="display: flex; align-items: center; justify-content: center; min-height: 100vh;">
    <div class="grid-bg"></div>
    
    <div class="glass-panel" style="width: 100%; max-width: 450px;">
        <div class="logo" style="font-size: 2rem; text-align: center; margin-bottom: 30px;">TalentAI</div>
        
        <?php if(!empty($message)): ?>
            <div style="background: <?= strpos($message, 'successful') ? 'rgba(6, 182, 212, 0.2)' : 'rgba(239, 68, 68, 0.2)'; ?>; color: <?= strpos($message, 'successful') ? 'var(--cyan)' : '#ef4444'; ?>; padding: 10px; border-radius: 8px; margin-bottom: 20px;"><?= $message ?></div>
        <?php endif; ?>

        <form method="POST">
            <input type="hidden" name="action" value="register">
            <input type="text" name="name" class="glass-input" placeholder="Full Name / Company Name" required style="margin-bottom: 15px;">
            <input type="email" name="email" class="glass-input" placeholder="Email Address" required style="margin-bottom: 15px;">
            <input type="password" name="password" class="glass-input" placeholder="Password" required style="margin-bottom: 15px;">
            
            <select name="role" class="glass-input" style="margin-bottom: 20px;">
                <option value="seeker">I am looking for a job</option>
                <option value="employer">I want to hire</option>
            </select>
            
            <button type="submit" class="glass-btn" style="width: 100%;">Create Account</button>
        </form>
        
        <p style="margin-top: 20px; text-align: center; color: var(--text-muted);">Already have an account? <a href="login.php" style="color: var(--cyan); text-decoration: none;">Login</a></p>
    </div>
</body>
</html>