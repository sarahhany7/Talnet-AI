<?php 
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$message = "";
include 'config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    
    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {

        $_SESSION['user_id'] = $user['id'];
        $_SESSION['role'] = $user['role'];
        $_SESSION['name'] = $user['name'];


        if ($user['role'] == 'admin') {
            redirect('admin_dashboard.php');
        } elseif ($user['role'] == 'employer') {
            redirect('employer_dashboard.php');
        } else {
            redirect('seeker_dashboard.php');
        }
    } else {
        $message = " Invalid email or password.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login | TalentAI</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        :root { --bg-dark: #0f172a; --bg-card: rgba(30, 41, 59, 0.7); --primary: #6366f1; --secondary: #ec4899; --cyan: #06b6d4; --purple: #8b5cf6; --text-main: #f8fafc; --text-muted: #94a3b8; --glass-border: rgba(255, 255, 255, 0.1); }
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: 'Segoe UI', sans-serif; background-color: var(--bg-dark); color: var(--text-main); min-height: 100vh; display: flex; align-items: center; justify-content: center; background-image: radial-gradient(at 0% 0%, rgba(99, 102, 241, 0.15) 0px, transparent 50%), radial-gradient(at 100% 100%, rgba(6, 182, 212, 0.15) 0px, transparent 50%); }
        
        .glass-panel { background: var(--bg-card); backdrop-filter: blur(16px); border: 1px solid var(--glass-border); border-radius: 24px; padding: 40px; width: 100%; max-width: 400px; text-align: center; box-shadow: 0 8px 32px rgba(0, 0, 0, 0.3); }
        
        .glass-input { background: rgba(15, 23, 42, 0.6); border: 1px solid var(--glass-border); padding: 14px; border-radius: 12px; color: white; width: 100%; margin-bottom: 15px; font-size: 1rem; outline: none; transition: 0.3s; }
        .glass-input:focus { border-color: var(--cyan); box-shadow: 0 0 15px rgba(6, 182, 212, 0.2); }
        
        .glass-btn { background: linear-gradient(135deg, var(--primary), var(--purple)); border: none; padding: 14px 28px; border-radius: 50px; color: white; font-weight: 600; cursor: pointer; width: 100%; font-size: 1rem; transition: transform 0.2s; }
        .glass-btn:hover { transform: translateY(-2px); box-shadow: 0 8px 20px rgba(99, 102, 241, 0.4); }
        
        .logo { font-size: 2rem; font-weight: 700; background: linear-gradient(to right, var(--cyan), var(--secondary)); -webkit-background-clip: text; -webkit-text-fill-color: transparent; margin-bottom: 30px; }
        
        .msg { padding: 12px; border-radius: 8px; margin-bottom: 20px; }
        .msg-error { background: rgba(239, 68, 68, 0.2); color: #ef4444; }
        .msg-success { background: rgba(6, 182, 212, 0.2); color: var(--cyan); }
    </style>
</head>
<body>
    <div class="glass-panel">
        <div class="logo"><i class="fas fa-brain"></i> TalentAI</div>
        
        <?php if($message): ?>
            <div class="msg msg-error"><?= $message ?></div>
        <?php endif; ?>

        <form method="POST" action="">
            <input type="email" name="email" class="glass-input" placeholder="Email Address" required>
            <input type="password" name="password" class="glass-input" placeholder="Password" required>
            <button type="submit" class="glass-btn">Login</button>
        </form>
        
        <p style="margin-top: 20px; color: var(--text-muted);">
            Don't have an account? <a href="register.php" style="color: var(--cyan); text-decoration: none;">Sign up</a>
        </p>
    </div>
</body>
</html>