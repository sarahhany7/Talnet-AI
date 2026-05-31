<?php 
$pageTitle = "Job Seeker Dashboard";
include 'header.php';

require 'config.php';
if (!isLoggedIn() || $_SESSION['role'] != 'seeker') redirect('login.php');

$userId = $_SESSION['user_id'];

$message = "";
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    
    if (isset($_POST['update_profile'])) {
        
        $skills = $_POST['skills'];
        $bio = $_POST['bio'];
        $title = $_POST['title'];
        
        $stmt = $pdo->prepare("UPDATE job_seekers SET title = ?, skills = ?, bio = ? WHERE user_id = ?");
        $stmt->execute([$title, $skills, $bio, $userId]);
        $message = "✅ Profile updated!";
    }
    
    if (isset($_FILES['cv']) && $_FILES['cv']['name']) {
        
        require 'cv_parser.php';
        $upload = uploadCV($_FILES['cv'], $userId);
        
        if (isset($upload['success'])) {
            $stmt = $pdo->prepare("UPDATE job_seekers SET cv_path = ? WHERE user_id = ?");
            $stmt->execute([$upload['path'], $userId]);
            $message = "✅ CV uploaded!";
        } else {
            $message = "❌ " . $upload['error'];
        }
    }
}

$stmt = $pdo->prepare("SELECT * FROM job_seekers WHERE user_id = ?");
$stmt->execute([$userId]);
$profile = $stmt->fetch();


$jobs = $pdo->query("SELECT j.*, c.company_name FROM jobs j JOIN companies c ON j.company_id = c.id WHERE j.status = 'active' ORDER BY j.created_at DESC")->fetchAll();
?>

<h1>My Profile</h1>

<!-- Profile Form -->
<div class="glass-panel" style="max-width: 800px; margin-bottom: 40px;">
    <form method="POST" enctype="multipart/form-data">
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
            <div>
                <label style="color: var(--text-muted);">Job Title</label>
                <input type="text" name="title" class="glass-input" value="<?= htmlspecialchars($profile['title'] ?? '') ?>" placeholder="e.g. Full Stack Developer" required>
            </div>
            <div>
                <label style="color: var(--text-muted);">Upload CV (PDF)</label>
                <input type="file" name="cv" class="glass-input" accept=".pdf,.doc,.docx">
            </div>
        </div>
        
        <label style="color: var(--text-muted); display: block; margin-top: 15px;">Skills (comma separated)</label>
        <input type="text" name="skills" class="glass-input" value="<?= htmlspecialchars($profile['skills'] ?? '') ?>" placeholder="PHP, JavaScript, MySQL, Laravel, React">
        
        <label style="color: var(--text-muted); display: block; margin-top: 15px;">Bio</label>
        <textarea name="bio" class="glass-input" rows="3" placeholder="Tell us about yourself..."><?= htmlspecialchars($profile['bio'] ?? '') ?></textarea>
        
        <button type="submit" name="update_profile" class="glass-btn" style="width: auto; margin-top: 20px;">Save Profile</button>
        
        <?php if($message): ?>
            <span style="margin-left: 20px; color: var(--cyan);"><?= $message ?></span>
        <?php endif; ?>
    </form>
</div>

<h1>Browse Jobs</h1>

<div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 25px;">
    <?php foreach($jobs as $job): ?>
    <div class="glass-panel">
        <div style="display: flex; justify-content: space-between; margin-bottom: 15px;">
            <h3><?= htmlspecialchars($job['title']) ?></h3>
            <span style="background: rgba(6, 182, 212, 0.2); color: var(--cyan); padding: 5px 12px; border-radius: 20px; font-size: 0.8rem;">Active</span>
        </div>
        <div style="color: var(--secondary); font-weight: bold; margin-bottom: 10px;">
            <i class="fas fa-building"></i> <?= htmlspecialchars($job['company_name']) ?>
        </div>
        <p style="color: var(--text-muted); font-size: 0.9rem; margin-bottom: 15px;">
            <?= substr(htmlspecialchars($job['description']), 0, 80) ?>...
        </p>
        <a href="job_details.php?id=<?= $job['id'] ?>" class="glass-btn" style="font-size: 0.85rem; padding: 10px 20px;">View & Apply</a>
    </div>
    <?php endforeach; ?>
</div>

</main>
</div>
</body>
</html>
