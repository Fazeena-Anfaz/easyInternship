<?php
require 'includes/db.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

$stmt = $pdo->prepare("SELECT a.*, i.title, i.company_name, i.duration, i.deadline 
                       FROM applications a 
                       JOIN internships i ON a.internship_id = i.id 
                       WHERE a.student_id = ? 
                       ORDER BY applied_at DESC");
$stmt->execute([$user_id]);
$applications = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Applications | EasyIntern</title>
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
            <a href="applications.php" class="nav-item active"><i class="bi bi-briefcase"></i> My Applications</a>
            <a href="profile.php" class="nav-item"><i class="bi bi-person"></i> Profile</a>
            <a href="notifications.php" class="nav-item"><i class="bi bi-bell"></i> Notifications</a>
            <div class="mt-auto"><a href="logout.php" class="nav-item text-danger"><i class="bi bi-box-arrow-left"></i> Logout</a></div>
        </nav>
    </aside>

    <main class="main-content">
        <header class="top-navbar mb-4">
            <h4 class="mb-0 fw-bold me-auto">Application Tracking</h4>
            <div class="d-flex align-items-center gap-2">
                <img src="https://ui-avatars.com/api/?name=<?php echo urlencode($_SESSION['name']); ?>&background=4f46e5&color=fff" class="rounded-circle" width="32">
                <span class="fw-semibold"><?php echo htmlspecialchars($_SESSION['name']); ?></span>
            </div>
        </header>

        <div class="glass-card">
            <?php if(empty($applications)): ?>
                <div class="text-center py-5">
                    <i class="bi bi-briefcase fs-1 text-muted"></i>
                    <p class="text-muted mt-3">You haven't applied to any internships yet.</p>
                    <a href="internships.php" class="btn-indigo text-decoration-none mt-2">Browse Internships</a>
                </div>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="bg-light">
                            <tr>
                                <th class="border-0">Internship</th>
                                <th class="border-0">Status</th>
                                <th class="border-0">Applied On</th>
                                <th class="border-0">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($applications as $app): ?>
                                <tr>
                                    <td>
                                        <div class="fw-bold"><?php echo htmlspecialchars($app['title']); ?></div>
                                        <div class="small text-muted"><?php echo htmlspecialchars($app['company_name']); ?></div>
                                    </td>
                                    <td>
                                        <?php if($app['status'] == 'Pending'): ?>
                                            <span class="badge bg-primary-light text-primary">Pending</span>
                                        <?php elseif($app['status'] == 'Approved'): ?>
                                            <span class="badge bg-success-subtle text-success">Approved</span>
                                        <?php else: ?>
                                            <span class="badge bg-danger-subtle text-danger">Rejected</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="small text-muted"><?php echo date('M d, Y', strtotime($app['applied_at'])); ?></td>
                                    <td>
                                        <a href="internship_details.php?id=<?php echo $app['internship_id']; ?>" class="btn btn-sm btn-light">View Posting</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </main>
</div>

</body>
</html>
