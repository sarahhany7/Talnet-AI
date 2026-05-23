<?php 
$pageTitle = "Job Seeker Dashboard";
include 'header.php';

require 'config.php';
if (!isLoggedIn() || $_SESSION['role'] != 'seeker') redirect('login.php');

$jobsStmt = $pdo->query("SELECT j.*, c.company_name FROM jobs j JOIN companies c ON j.company_id = c.id WHERE j.status = 'active' ORDER BY j.created_at DESC");
$jobs = $jobsStmt->fetchAll();
?>

<h1>Find Your Dream Job</h1>

<div class="glass-panel" style="margin-bottom: 30px; display: flex; gap: 15px;">
    <input type="text" class="glass-input" placeholder="Search jobs..." style="margin-bottom: 0;">
    <button class="glass-btn"><i class="fas fa-search"></i></button>
</div>


<div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 25px;">
    <?php if(count($jobs) == 0): ?>
    <div class="glass-panel">No jobs available right now.</div>
    <?php else: ?>
    <?php foreach($jobs as $job): ?>
    <div class="glass-panel">
        <div style="display: flex; justify-content: space-between; margin-bottom: 15px;">
            <h3 style="color: var(--text-main); margin: 0;"><?= htmlspecialchars($job['title']) ?></h3>
            <span style="background: rgba(6, 182, 212, 0.2); color: var(--cyan); padding: 5px 12px; border-radius: 20px; font-size: 0.8rem;">Active</span>
        </div>
        <div style="color: var(--secondary); font-weight: bold; margin-bottom: 10px;">
            <i class="fas fa-building"></i> <?= htmlspecialchars($job['company_name']) ?>
        </div>
        <p style="color: var(--text-muted); font-size: 0.9rem; margin-bottom: 15px; line-height: 1.5;">
            <?= substr(htmlspecialchars($job['description']), 0, 100) ?>...
        </p>
        <div style="display: flex; gap: 10px;">
            <a href="job_details.php?id=<?= $job['id'] ?>" class="glass-btn" style="font-size: 0.85rem; padding: 10px 20px;">View</a>
            <button class="glass-btn" style="background: transparent; border: 1px solid var(--glass-border);">
                <i class="far fa-heart"></i>
            </button>
        </div>
    </div>
    <?php endforeach; ?>
    <?php endif; ?>
</div>
</main>
</div>
</body>
</html>