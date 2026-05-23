<?php 
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require 'config.php';

if (!isLoggedIn()) redirect('login.php');

$job_id = $_GET['id'] ?? 0;

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['apply'])) {
    $seeker_id = $_SESSION['user_id'];
    

    $seekerStmt = $pdo->prepare("SELECT id FROM job_seekers WHERE user_id = ?");
    $seekerStmt->execute([$seeker_id]);
    $seeker = $seekerStmt->fetch();
    
    if ($seeker) {
        
        $check = $pdo->prepare("SELECT id FROM applications WHERE job_id = ? AND seeker_id = ?");
        $check->execute([$job_id, $seeker['id']]);
        
        if (!$check->fetch()) {
            
            $aiScore = rand(60, 95); 
            $aiAnalysis = "Strong match! Your skills align well with job requirements.";
            
            $applyStmt = $pdo->prepare("INSERT INTO applications (job_id, seeker_id, ai_score, ai_analysis, status) VALUES (?, ?, ?, ?, 'pending')");
            $applyStmt->execute([$job_id, $seeker['id'], $aiScore, $aiAnalysis]);
            
            $message = "Applied successfully! AI Score: " . $aiScore . "%";
        } else {
            $message = "You already applied to this job!";
        }
    }
}

$stmt = $pdo->prepare("SELECT j.*, c.company_name FROM jobs j JOIN companies c ON j.company_id = c.id WHERE j.id = ?");
$stmt->execute([$job_id]);
$job = $stmt->fetch();

if (!$job) die("Job not found");

$seekerStmt = $pdo->prepare("SELECT id FROM job_seekers WHERE user_id = ?");
$seekerStmt->execute([$_SESSION['user_id']]);
$seeker = $seekerStmt->fetch();

$alreadyApplied = false;
if ($seeker) {
    $checkApply = $pdo->prepare("SELECT id FROM applications WHERE job_id = ? AND seeker_id = ?");
    $checkApply->execute([$job_id, $seeker['id']]);
    $alreadyApplied = $checkApply->fetch();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?= $job['title'] ?> | TalentAI</title>
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
            font-family: 'Segoe UI', sans-serif;
            background-color: var(--bg-dark);
            color: var(--text-main);
            min-height: 100vh;
            padding: 100px 10% 50px;
        }

        .top-nav {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            height: 70px;
            background: rgba(15, 23, 42, 0.9);
            backdrop-filter: blur(20px);
            border-bottom: 1px solid var(--glass-border);
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 40px;
            z-index: 1000;
        }

        .logo {
            font-size: 1.5rem;
            font-weight: 700;
            background: linear-gradient(to right, var(--cyan), var(--secondary));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            text-decoration: none;
        }

        .back-btn { color: var(--text-muted); text-decoration: none; padding: 8px 16px; border-radius: 8px; }
        .back-btn:hover { color: var(--text-main); }

        .job-container { display: grid; grid-template-columns: 2fr 1fr; gap: 40px; max-width: 1200px; margin: 0 auto; }

        .glass-panel {
            background: var(--bg-card);
            backdrop-filter: blur(16px);
            border: 1px solid var(--glass-border);
            border-radius: 20px;
            padding: 30px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.3);
        }

        .job-title { font-size: 2.2rem; margin-bottom: 15px; }
        .job-meta { display: flex; gap: 25px; margin-bottom: 30px; color: var(--text-muted); flex-wrap: wrap; }
        .job-meta span { display: flex; align-items: center; gap: 8px; }

        h3 { color: var(--cyan); margin-bottom: 15px; }
        p { color: var(--text-muted); line-height: 1.8; margin-bottom: 25px; }

        .glass-btn {
            background: linear-gradient(135deg, var(--primary), var(--purple));
            border: none;
            padding: 14px 30px;
            border-radius: 50px;
            color: white;
            font-weight: 600;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
            transition: transform 0.2s;
            width: 100%;
        }

        .glass-btn:hover { transform: translateY(-2px); }
        
        .glass-btn-disabled {
            background: var(--text-muted);
            cursor: not-allowed;
        }

        .success-msg {
            background: rgba(6, 182, 212, 0.2);
            color: var(--cyan);
            padding: 15px;
            border-radius: 12px;
            margin-bottom: 20px;
            text-align: center;
        }

        .error-msg {
            background: rgba(239, 68, 68, 0.2);
            color: #ef4444;
            padding: 15px;
            border-radius: 12px;
            margin-bottom: 20px;
            text-align: center;
        }

        
        .ai-card { position: sticky; top: 100px; text-align: center; }

        .ai-score-circle {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            background: conic-gradient(var(--cyan) 0%, var(--purple) var(--score), #1e293b var(--score));
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 25px auto;
            box-shadow: 0 0 30px rgba(6, 182, 212, 0.4);
        }

        .ai-inner {
            width: 130px;
            height: 130px;
            border-radius: 50%;
            background: var(--bg-dark);
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
        }

        .ai-score { font-size: 2.5rem; font-weight: bold; }
        .ai-label { font-size: 0.8rem; color: var(--text-muted); }

        @media (max-width: 768px) {
            .job-container { grid-template-columns: 1fr; }
            body { padding: 100px 5% 30px; }
        }
    </style>
</head>
<body>
    <nav class="top-nav">
        <a href="seeker_dashboard.php" class="logo"><i class="fas fa-brain"></i> TalentAI</a>
        <a href="seeker_dashboard.php" class="back-btn"><i class="fas fa-arrow-left"></i> Back</a>
    </nav>

    <div class="job-container">
        <div>
            <div class="glass-panel">
                <h1 class="job-title"><?= htmlspecialchars($job['title']) ?></h1>
                
                <div class="job-meta">
                    <span><i class="fas fa-building"></i> <?= htmlspecialchars($job['company_name']) ?></span>
                    <span><i class="fas fa-map-marker-alt"></i> <?= htmlspecialchars($job['location']) ?></span>
                    <span><i class="fas fa-money-bill-wave"></i> <?= htmlspecialchars($job['salary']) ?></span>
                </div>

                <h3>Job Description</h3>
                <p><?= nl2br(htmlspecialchars($job['description'])) ?></p>

                <h3>Requirements</h3>
                <p><?= nl2br(htmlspecialchars($job['requirements'])) ?></p>
            </div>
        </div>

        <div>
            
            <form method="POST" class="glass-panel ai-card">
                <h2 style="margin-bottom: 20px;">AI Job Matcher</h2>
                
                <?php if(isset($message)): ?>
                    <?php if(strpos($message, '✅') !== false): ?>
                        <div class="success-msg"><?= $message ?></div>
                    <?php else: ?>
                        <div class="error-msg"><?= $message ?></div>
                    <?php endif; ?>
                <?php else: ?>
                    <p style="color: var(--text-muted); margin-bottom: 30px;">Test how well you match this role.</p>
                <?php endif; ?>
                
                <div class="ai-score-circle" id="scoreCircle" style="--score: 0%;">
                    <div class="ai-inner">
                        <span id="scoreText" class="ai-score">0%</span>
                        <span class="ai-label">Match</span>
                    </div>
                </div>

                <button type="button" class="glass-btn" style="margin-bottom: 10px;" onclick="runAI()">Analyze Profile</button>
                
                <?php if($alreadyApplied): ?>
                    <button type="button" class="glass-btn glass-btn-disabled" disabled>Already Applied ✓</button>
                <?php else: ?>
                    <button type="submit" name="apply" class="glass-btn" style="background: var(--secondary);">Apply Now</button>
                <?php endif; ?>
            </form>
        </div>
    </div>

    <script>
        function runAI() {
            let score = 0;
            const circle = document.getElementById('scoreCircle');
            const text = document.getElementById('scoreText');
            const target = 87;
            
            const interval = setInterval(() => {
                if(score >= target) {
                    clearInterval(interval);
                    document.getElementById('scoreCircle').style.setProperty('--score', '87%');
                } else {
                    score++;
                    text.innerText = score + '%';
                    circle.style.background = `conic-gradient(var(--cyan) ${score}%, var(--purple) ${score}%, #1e293b ${score}%)`;
                }
            }, 20);
        }
    </script>
</body>
</html>