<?php 
include 'header.php';
require 'config.php';
if (!isLoggedIn() || $_SESSION['role'] != 'employer') redirect('login.php');

$message = "";

$stmt = $pdo->prepare("SELECT * FROM companies WHERE user_id = ?");
$stmt->execute([$_SESSION['user_id']]);
$company = $stmt->fetch();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $requirements = $_POST['requirements'];
    $salary = $_POST['salary'];
    $location = $_POST['location'];

    $stmt = $pdo->prepare("INSERT INTO jobs (company_id, title, description, requirements, salary, location, status) VALUES (?, ?, ?, ?, ?, ?, 'active')");
    $stmt->execute([$company['id'], $title, $description, $requirements, $salary, $location]);
    
    $message = "Job posted successfully!";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Post New Job | TalentAI</title>
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
        }

        * { box-sizing: border-box; margin: 0; padding: 0; }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: var(--bg-dark);
            color: var(--text-main);
            min-height: 100vh;
        }

        
        .dashboard-layout {
            display: flex;
            min-height: 100vh;
        }

        .sidebar {
            width: 260px;
            min-height: 100vh;
            background: var(--bg-card);
            border-right: 1px solid var(--glass-border);
            padding: 30px 20px;
            position: fixed;
        }

        .main-content {
            flex: 1;
            margin-left: 260px;
            padding: 40px;
        }

        .logo {
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

        .glass-input {
            background: rgba(15, 23, 42, 0.6);
            border: 1px solid var(--glass-border);
            padding: 14px;
            border-radius: 12px;
            color: white;
            width: 100%;
            margin-bottom: 20px;
            font-size: 1rem;
            outline: none;
            transition: 0.3s;
        }

        .glass-input:focus {
            border-color: var(--cyan);
            box-shadow: 0 0 15px rgba(6, 182, 212, 0.2);
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

        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }

        label {
            display: block;
            margin-bottom: 8px;
            color: var(--text-muted);
        }

        .success-msg {
            background: rgba(6, 182, 212, 0.15);
            color: var(--cyan);
            padding: 15px;
            border-radius: 12px;
            margin-bottom: 20px;
            border: 1px solid var(--cyan);
        }

        h1 { margin-bottom: 30px; }
    </style>
</head>
<body>
    <div class="dashboard-layout">
        <nav class="sidebar">
            <div class="logo"><i class="fas fa-brain"></i> TalentAI</div>
            <a href="employer_dashboard.php" class="active"><i class="fas fa-arrow-left"></i> Back to Jobs</a>
            <a href="#"><i class="fas fa-users"></i> Applicants</a>
            <a href="#"><i class="fas fa-chart-line"></i> Analytics</a>
            <a href="logout.php" style="color: #ef4444; margin-top: 50px;"><i class="fas fa-sign-out-alt"></i> Logout</a>
        </nav>

        <main class="main-content">
            <h1>Post New Job</h1>
            
            <?php if($message): ?>
                <div class="success-msg">
                    <?= $message ?>
                </div>
            <?php endif; ?>

            <div class="glass-panel" style="max-width: 800px;">
                <form method="POST">
                    <label>Job Title</label>
                    <input type="text" name="title" class="glass-input" placeholder="e.g. Senior PHP Developer" required>

                    <label>Job Description</label>
                    <textarea name="description" class="glass-input" rows="4" placeholder="Describe the job role..." required></textarea>

                    <label>Requirements & Skills</label>
                    <textarea name="requirements" class="glass-input" rows="3" placeholder="Skills required..." required></textarea>

                    <div class="form-row">
                        <div>
                            <label>Salary Range</label>
                            <input type="text" name="salary" class="glass-input" placeholder="e.g. $50k - $80k">
                        </div>
                        <div>
                            <label>Location</label>
                            <input type="text" name="location" class="glass-input" placeholder="e.g. Remote / Cairo">
                        </div>
                    </div>

                    <button type="submit" class="glass-btn" style="width: 100%; margin-top: 10px;">
                        <i class="fas fa-plus"></i> Post Job
                    </button>
                </form>
            </div>
        </main>
    </div>
</body>
</html>