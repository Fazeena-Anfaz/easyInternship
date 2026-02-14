<?php
require 'includes/db.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Get notifications
$stmt = $pdo->prepare("SELECT * FROM notifications WHERE user_id = ? ORDER BY created_at DESC");
$stmt->execute([$user_id]);
$notifications = $stmt->fetchAll();

// Mark all as read logic (Simplified for demo)
if (isset($_GET['mark_read'])) {
    $stmt = $pdo->prepare("UPDATE notifications SET is_read = 1 WHERE user_id = ?");
    $stmt->execute([$user_id]);
    header("Location: notifications.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notifications | EasyIntern</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/saas-style.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
</head>
<body>

<div class="dashboard-wrapper">
    <aside class="sidebar">
        <div class="sidebar-logo"><i class="bi bi-rocket-takeoff-fill"></i> EasyIntern</div>
        <nav class="d-flex flex-column h-100">
            <a href="student_dashboard.php" class="nav-item"><i class="bi bi-grid-1x2"></i> Dashboard</a>
            <a href="internships.php" class="nav-item"><i class="bi bi-search"></i> Find Internships</a>
            <a href="applications.php" class="nav-item"><i class="bi bi-briefcase"></i> My Applications</a>
            <a href="profile.php" class="nav-item"><i class="bi bi-person"></i> Profile</a>
            <a href="notifications.php" class="nav-item active"><i class="bi bi-bell"></i> Notifications</a>
            <div class="mt-auto"><a href="logout.php" class="nav-item text-danger"><i class="bi bi-box-arrow-left"></i> Logout</a></div>
        </nav>
    </aside>

    <main class="main-content">
        <header class="top-navbar mb-4">
            <h4 class="mb-0 fw-bold me-auto">Notifications</h4>
            <div class="d-flex align-items-center gap-3">
                <a href="notifications.php?mark_read=1" class="text-primary small text-decoration-none">Mark all as read</a>
                <div class="d-flex align-items-center gap-2">
                    <img src="https://ui-avatars.com/api/?name=<?php echo urlencode($_SESSION['name']); ?>&background=4f46e5&color=fff" class="rounded-circle" width="32">
                    <span class="fw-semibold"><?php echo htmlspecialchars($_SESSION['name']); ?></span>
                </div>
            </div>
        </header>

        <div class="glass-card">
            <?php if(empty($notifications)): ?>
                <div class="text-center py-5">
                    <i class="bi bi-bell-slash fs-1 text-muted"></i>
                    <p class="text-muted mt-3">No notifications for you yet.</p>
                </div>
            <?php else: foreach($notifications as $notif): ?>
                <div class="p-3 border-bottom border-light animate-fade <?php echo !$notif['is_read'] ? 'bg-primary-light' : ''; ?>" style="border-radius: 0.5rem;">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <p class="mb-1 <?php echo !$notif['is_read'] ? 'fw-bold text-primary' : 'text-dark'; ?>">
                                <?php echo htmlspecialchars($notif['message']); ?>
                            </p>
                            <span class="small text-muted"><?php echo date('M d, h:i A', strtotime($notif['created_at'])); ?></span>
                        </div>
                        <?php if(!$notif['is_read']): ?>
                            <div class="bg-primary rounded-circle" style="width: 8px; height: 8px;"></div>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; endif; ?>
        </div>
    </main>
</div>

</body>
</html>
