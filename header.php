<?php 
ob_start();
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$user_name = $_SESSION['name'] ?? 'User';
$user_role = $_SESSION['role'] ?? '';
$pageTitle = $pageTitle ?? 'Dashboard';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TalentAI | <?= $pageTitle ?></title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        :root {
            --bg-dark: #0f172a;
            --bg-card: rgba(30, 41, 59, 0.7);
            --primary: #6366f1;
            --secondary: #ec4899;
            --cyan: #06b6d4;
            --purple: #8b5cf6;
            --text-main: #f8fafc;
            --text-muted: #94a3b8;
            --glass-border: rgba(255, 255, 255, 0.1);
            --header-height: 70px;
        }

        * { box-sizing: border-box; margin: 0; padding: 0; }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: var(--bg-dark);
            color: var(--text-main);
            min-height: 100vh;
            padding-top: var(--header-height);
        }

        .main-header {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            height: var(--header-height);
            background: rgba(15, 23, 42, 0.9);
            backdrop-filter: blur(20px);
            border-bottom: 1px solid var(--glass-border);
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 40px;
            z-index: 1000;
        }

        .header-logo {
            font-size: 1.5rem;
            font-weight: 700;
            background: linear-gradient(to right, var(--cyan), var(--secondary));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            text-decoration: none;
        }

        .header-nav {
            display: flex;
            gap: 15px;
        }

        .header-link {
            color: var(--text-muted);
            text-decoration: none;
            padding: 8px 16px;
            border-radius: 8px;
            transition: 0.3s;
        }

        .header-link:hover {
            color: var(--text-main);
            background: rgba(255, 255, 255, 0.1);
        }

        .header-user {
            display: flex;
            align-items: center;
            gap: 15px;
            padding-left: 20px;
            border-left: 1px solid var(--glass-border);
        }

        .user-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--primary), var(--purple));
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
        }

        .logout-btn {
            color: #ef4444;
            text-decoration: none;
            padding: 8px 16px;
            border-radius: 8px;
            transition: 0.3s;
        }

        .logout-btn:hover {
            background: rgba(239, 68, 68, 0.2);
        }

        .dashboard-layout {
            display: flex;
            min-height: calc(100vh - var(--header-height));
        }

        .sidebar {
            width: 260px;
            min-height: calc(100vh - var(--header-height));
            background: var(--bg-card);
            border-right: 1px solid var(--glass-border);
            padding: 30px 20px;
            position: fixed;
            top: var(--header-height);
        }

        .main-content {
            flex: 1;
            margin-left: 260px;
            margin-top: var(--header-height);
            padding: 40px;
        }

        .logo-text {
            font-size: 1.8rem;
            font-weight: 700;
            background: linear-gradient(to right, var(--cyan), var(--secondary));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            margin-bottom: 50px;
            display: block;
        }

        .sidebar a {
            display: block;
            padding: 14px 18px;
            color: var(--text-muted);
            text-decoration: none;
            border-radius: 12px;
            margin-bottom: 8px;
            transition: all 0.3s ease;
        }

        .sidebar a:hover, .sidebar a.active {
            background: linear-gradient(135deg, rgba(99, 102, 241, 0.2), rgba(139, 92, 246, 0.1));
            color: var(--cyan);
            border-left: 3px solid var(--cyan);
        }

        .glass-panel {
            background: var(--bg-card);
            backdrop-filter: blur(16px);
            border: 1px solid var(--glass-border);
            border-radius: 20px;
            padding: 30px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.3);
        }

        .glass-btn {
            background: linear-gradient(135deg, var(--primary), var(--purple));
            border: none;
            padding: 12px 28px;
            border-radius: 50px;
            color: white;
            font-weight: 600;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
            transition: transform 0.2s;
        }

        .glass-btn:hover {
            transform: translateY(-2px);
        }

        .glass-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        .glass-table th {
            text-align: left;
            padding: 15px;
            color: var(--text-muted);
            border-bottom: 1px solid var(--glass-border);
        }

        .glass-table td {
            padding: 18px 15px;
            border-bottom: 1px solid var(--glass-border);
        }

        h1 { margin-bottom: 30px; font-size: 2rem; }

        @media (max-width: 768px) {
            .sidebar { display: none; }
            .main-content { margin-left: 0; }
            .header-nav { display: none; }
        }
    </style>
</head>
<body>
    <header class="main-header">
        <a href="index.php" class="header-logo"><i class="fas fa-brain"></i> TalentAI</a>
        
        <nav class="header-nav">
            <?php if($user_role == 'employer'): ?>
                <a href="employer_dashboard.php" class="header-link">Jobs</a>
                <a href="#" class="header-link">Applicants</a>
            <?php elseif($user_role == 'seeker'): ?>
                <a href="seeker_dashboard.php" class="header-link">Browse Jobs</a>
                <a href="#" class="header-link">Applications</a>
            <?php endif; ?>
        </nav>

        <div class="header-user">
            <span><?= htmlspecialchars($user_name) ?></span>
            <div class="user-avatar"><?= strtoupper($user_name[0]) ?></div>
            <a href="logout.php" class="logout-btn"><i class="fas fa-sign-out-alt"></i></a>
        </div>
    </header>


    <div class="dashboard-layout">
        
        <nav class="sidebar">
            <span class="logo-text">Dashboard</span>
            
            <?php if($user_role == 'employer'): ?>
                <a href="employer_dashboard.php" class="active"><i class="fas fa-briefcase"></i> My Jobs</a>
                <a href="#"><i class="fas fa-users"></i> Applicants</a>
                <a href="#"><i class="fas fa-chart-line"></i> Analytics</a>
            <?php elseif($user_role == 'seeker'): ?>
                <a href="seeker_dashboard.php" class="active"><i class="fas fa-search"></i> Browse Jobs</a>
                <a href="#"><i class="fas fa-file-alt"></i> Applications</a>
                <a href="#"><i class="fas fa-heart"></i> Saved Jobs</a>
            <?php endif; ?>
        </nav>

        <main class="main-content">