<?php 
include 'header.php';
require 'config.php';
if (!isLoggedIn() || $_SESSION['role'] != 'employer') redirect('login.php');

$stmt = $pdo->prepare("SELECT * FROM companies WHERE user_id = ?");
$stmt->execute([$_SESSION['user_id']]);
$company = $stmt->fetch();

if (!$company) {
    $pdo->prepare("INSERT INTO companies (user_id, company_name) VALUES (?, ?)")->execute([$_SESSION['user_id'], $_SESSION['name'] . "'s Company"]);
    $stmt = $pdo->prepare("SELECT * FROM companies WHERE user_id = ?");
    $stmt->execute([$_SESSION['user_id']]);
    $company = $stmt->fetch();
}

$jobsStmt = $pdo->prepare("SELECT * FROM jobs WHERE company_id = ? ORDER BY created_at DESC");
$jobsStmt->execute([$company['id']]);
$jobs = $jobsStmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employer Dashboard | TalentAI</title>
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
            left: 0;
            top: 0;
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
            font-size: 1rem;
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
            box-shadow: 0 8px 20px rgba(99, 102, 241, 0.4);
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
            font-weight: 500;
        }

        .glass-table td {
            padding: 18px 15px;
            border-bottom: 1px solid var(--glass-border);
        }

        h1 { margin-bottom: 30px; font-size: 2rem; }
    </style>
</head>
<body>
    <div class="dashboard-layout">
        <nav class="sidebar">
            <div class="logo"><i class="fas fa-brain"></i> TalentAI</div>
            <a href="#" class="active"><i class="fas fa-briefcase"></i> My Jobs</a>
            <a href="#"><i class="fas fa-users"></i> Applicants</a>
            <a href="#"><i class="fas fa-chart-line"></i> Analytics</a>
            <a href="#"><i class="fas fa-cog"></i> Settings</a>
            <a href="logout.php" style="color: #ef4444; margin-top: 50px;"><i class="fas fa-sign-out-alt"></i> Logout</a>
        </nav>

        <main class="main-content">
            <h1>My Job Posts</h1>
            
            <a href="post_job.php" class="glass-btn">
                <i class="fas fa-plus"></i> Post New Job
            </a>
            <br><br>
            
            <div class="glass-panel">
                <table class="glass-table">
                    <thead>
                        <tr>
                            <th>Job Title</th>
                            <th>Applications</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(count($jobs) == 0): ?>
                        <tr>
                            <td colspan="4" style="text-align: center; color: var(--text-muted); padding: 40px;">
                                No jobs posted yet. Click "Post New Job" to add one.
                            </td>
                        </tr>
                        <?php else: ?>
                        <?php foreach($jobs as $job): ?>
                        <tr>
                            <td style="font-weight: 600; color: var(--text-main);">
                                <?= htmlspecialchars($job['title']) ?>
                            </td>
                            <td>
                                <?php 
                                $appCount = $pdo->prepare("SELECT COUNT(*) FROM applications WHERE job_id = ?");
                                $appCount->execute([$job['id']]);
                                echo $appCount->fetchColumn();
                                ?>
                            </td>
                            <td>
                                <span style="color: <?= $job['status'] == 'active' ? 'var(--cyan)' : 'var(--secondary)'; ?>">
                                    <?= ucfirst($job['status']) ?>
                                </span>
                            </td>
                            <td>
                                <a href="#" style="color: var(--text-main);"><i class="fas fa-eye"></i></a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </main>
    </div>
</body>
</html>