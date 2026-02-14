<?php
require 'includes/db.php';
session_start();

if (!isset($_SESSION['user_id']) || !isset($_GET['id'])) {
    header("Location: login.php");
    exit();
}

$internship_id = $_GET['id'];
$student_id = $_SESSION['user_id'];

// Check if already applied
$stmt = $pdo->prepare("SELECT id FROM applications WHERE internship_id = ? AND student_id = ?");
$stmt->execute([$internship_id, $student_id]);
if ($stmt->fetch()) {
    header("Location: applications.php?msg=already_applied");
    exit();
}

try {
    $stmt = $pdo->prepare("INSERT INTO applications (internship_id, student_id, status) VALUES (?, ?, 'Pending')");
    $stmt->execute([$internship_id, $student_id]);
    
    // Add Notification
    $msg = "Your application for the internship has been submitted successfully!";
    $stmt = $pdo->prepare("INSERT INTO notifications (user_id, message) VALUES (?, ?)");
    $stmt->execute([$student_id, $msg]);

    header("Location: applications.php?msg=success");
} catch (PDOException $e) {
    die("Error processing application: " . $e->getMessage());
}
?>
