<?php
require 'includes/db.php';
session_start();

$action = $_GET['action'] ?? '';

if ($action == 'register' && $_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
    $role = $_POST['role']; // Student, Company, Admin

    try {
        $stmt = $pdo->prepare("INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, ?)");
        $stmt->execute([$name, $email, $password, $role]);
        $user_id = $pdo->lastInsertId();

        // Initialize student profile if role is Student
        if ($role == 'Student') {
            $stmt = $pdo->prepare("INSERT INTO student_profiles (user_id, profile_completion) VALUES (?, 0)");
            $stmt->execute([$user_id]);
        }

        header("Location: login.php?msg=registered");
    } catch (PDOException $e) {
        header("Location: register.php?error=" . urlencode("Email already exists or database error."));
    }
}

if ($action == 'login' && $_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['name'] = $user['name'];
        $_SESSION['role'] = $user['role'];

        // Redirect based on role
        if ($user['role'] == 'Student') {
            header("Location: student_dashboard.php");
        } elseif ($user['role'] == 'Company') {
            header("Location: company_dashboard.php");
        } elseif ($user['role'] == 'Admin') {
            header("Location: admin_dashboard.php");
        } else {
            header("Location: index.php");
        }
    } else {
        header("Location: login.php?error=invalid");
    }
}
?>
