<?php include 'config.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TalentAI | The Future of Recruitment</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>

    <div class="grid-bg"></div>

    <nav>
        <div class="logo"><i class="fas fa-brain"></i> TalentAI</div>
        <div>
            <a href="login.php" class="glass-btn" style="padding: 8px 20px; font-size: 0.9rem;">Login</a>
            <a href="register.php" class="glass-btn" style="background: transparent; border: 1px solid var(--cyan); margin-left: 10px;">Get Started</a>
        </div>
    </nav>

    <section class="hero">
        <div class="glass-panel" style="max-width: 900px; padding: 4rem;">
            <div style="color: var(--cyan); font-weight: 600; letter-spacing: 2px; margin-bottom: 10px;">THE NEXT GEN PLATFORM</div>
            <h1>Hire Smarter with <span class="highlight">AI</span></h1>
            <p>Leverage the power of advanced Artificial Intelligence to match talent with opportunity. Real-time analytics, automated screening, and a futuristic glassmorphism design.</p>
            
            <div style="display: flex; gap: 20px; justify-content: center; margin-top: 30px;">
                <a href="register.php" class="glass-btn">Post a Job <i class="fas fa-arrow-right"></i></a>
                <a href="register.php" class="glass-btn" style="background: transparent; border: 1px solid var(--purple);">Find Work</a>
            </div>

            
            <div style="display: flex; justify-content: space-around; margin-top: 50px; border-top: 1px solid var(--glass-border); padding-top: 30px;">
                <div>
                    <h2 style="font-size: 2.5rem; color: var(--cyan);">10k+</h2>
                    <p style="color: var(--text-muted);">Active Jobs</p>
                </div>
                <div>
                    <h2 style="font-size: 2.5rem; color: var(--secondary);">5k+</h2>
                    <p style="color: var(--text-muted);">Companies</p>
                </div>
                <div>
                    <h2 style="font-size: 2.5rem; color: var(--purple);">98%</h2>
                    <p style="color: var(--text-muted);">AI Accuracy</p>
                </div>
            </div>
        </div>
    </section>

    
    <section style="padding: 100px 10%;">
        <h2 style="text-align: center; margin-bottom: 60px; font-size: 2.5rem;">Intelligent Features</h2>
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 30px;">
            
            <div class="glass-panel">
                <div style="width: 50px; height: 50px; background: linear-gradient(135deg, var(--cyan), var(--blue)); border-radius: 12px; display: flex; align-items: center; justify-content: center; margin-bottom: 20px; font-size: 1.5rem; color: white;">
                    <i class="fas fa-robot"></i>
                </div>
                <h3 style="margin-bottom: 10px;">AI CV Analysis</h3>
                <p style="color: var(--text-muted);">Our intelligent bots scan and grade resumes against job requirements instantly.</p>
            </div>
            
            <div class="glass-panel">
                <div style="width: 50px; height: 50px; background: linear-gradient(135deg, var(--secondary), var(--purple)); border-radius: 12px; display: flex; align-items: center; justify-content: center; margin-bottom: 20px; font-size: 1.5rem; color: white;">
                    <i class="fas fa-bolt"></i>
                </div>
                <h3 style="margin-bottom: 10px;">Real-time Match</h3>
                <p style="color: var(--text-muted);">Get instant notifications when the perfect candidate applies.</p>
            </div>
            
            <div class="glass-panel">
                <div style="width: 50px; height: 50px; background: linear-gradient(135deg, var(--primary), var(--purple)); border-radius: 12px; display: flex; align-items: center; justify-content: center; margin-bottom: 20px; font-size: 1.5rem; color: white;">
                    <i class="fas fa-chart-line"></i>
                </div>
                <h3 style="margin-bottom: 10px;">Deep Analytics</h3>
                <p style="color: var(--text-muted);">Visual dashboards showing hiring trends, skills gaps, and more.</p>
            </div>
        </div>
    </section>


    <footer style="text-align: center; padding: 50px; border-top: 1px solid var(--glass-border); color: var(--text-muted);">
        <p>&copy; 2023 TalentAI. All rights reserved.</p>
    </footer>

</body>
</html>