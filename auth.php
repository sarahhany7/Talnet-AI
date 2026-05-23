<?php
require 'config.php';

$message = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $action = $_POST['action'] ?? 'login';
    $name = $_POST['name'] ?? '';
    $email = $_POST['email'];
    $password = $_POST['password'];
    $role = $_POST['role'] ?? 'seeker';

    if ($action == 'register') {
        $hashed = password_hash($password, PASSWORD_BCRYPT);
        

        $stmt = $pdo->prepare("INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, ?)");
        if($stmt->execute([$name, $email, $hashed, $role])) {
            $user_id = $pdo->lastInsertId();
            
            if ($role == 'seeker') {
                $pdo->prepare("INSERT INTO job_seekers (user_id, title) VALUES (?, ?)")->execute([$user_id, 'New Seeker']);
            }elseif ($role == 'employer') {
                $pdo->prepare("INSERT INTO companies (user_id, company_name) VALUES (?, ?)")->execute([$user_id, $name . "'s Company"]);
            }
            
            $message = "Registration successful! Please login.";
        }
    } elseif ($action == 'login') {
        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['role'] = $user['role'];
            $_SESSION['name'] = $user['name'];

            if ($user['role'] == 'admin') redirect('admin_dashboard.php');
            elseif ($user['role'] == 'employer') redirect('employer_dashboard.php');
            else redirect('seeker_dashboard.php');
        } else {
            $message = "Invalid credentials.";
        }
    }
}
?>