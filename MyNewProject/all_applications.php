<?php
require 'includes/db.php';
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Company') {
    header("Location: login.php");
    exit();
}

$company_id = $_SESSION['user_id'];
$company_name = $_SESSION['company_name'] ?? $_SESSION['name'];

// Handle Status Updates
if (isset($_GET['id']) && isset($_GET['status'])) {
    $app_id = $_GET['id'];
    $status = $_GET['status'];
    
    // Verify the application belongs to an internship of this company
    $stmt = $pdo->prepare("SELECT a.id, a.student_id, i.title FROM applications a 
                           JOIN internships i ON a.internship_id = i.id 
                           WHERE a.id = ? AND i.company_id = ?");
    $stmt->execute([$app_id, $company_id]);
    $app = $stmt->fetch();
    
    if ($app) {
        $stmt = $pdo->prepare("UPDATE applications SET status = ? WHERE id = ?");
        $stmt->execute([$status, $app_id]);
        
        // Notify Student
        $msg = "Your application for '{$app['title']}' has been " . strtolower($status) . ".";
        $stmt = $pdo->prepare("INSERT INTO notifications (user_id, message) VALUES (?, ?)");
        $stmt->execute([$app['student_id'], $msg]);
        
        header("Location: all_applications.php?msg=" . strtolower($status));
        exit();
    }
}

$stmt = $pdo->prepare("SELECT a.*, u.name as student_name, u.email as student_email, i.title as internship_title, sp.cv_url 
                       FROM applications a 
                       JOIN users u ON a.student_id = u.id 
                       JOIN internships i ON a.internship_id = i.id 
                       LEFT JOIN student_profiles sp ON u.id = sp.user_id
                       WHERE i.company_id = ? ORDER BY a.applied_at DESC");
$stmt->execute([$company_id]);
$applications = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>All Applicants | EasyIntern</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/saas-style.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
</head>
<body>

<div class="dashboard-wrapper">
    <aside class="sidebar">
        <div class="sidebar-logo">
            <i class="bi bi-building-fill-check"></i> TechFlow
        </div>
        <nav class="d-flex flex-column h-100">
            <a href="company_dashboard.php" class="nav-item"><i class="bi bi-grid-1x2"></i> Overview</a>
            <a href="post_internship.php" class="nav-item"><i class="bi bi-plus-circle"></i> Post New</a>
            <a href="manage_internships.php" class="nav-item"><i class="bi bi-list-task"></i> My Internships</a>
            <a href="all_applications.php" class="nav-item active"><i class="bi bi-people"></i> Applicants</a>
            <a href="settings.php" class="nav-item"><i class="bi bi-gear"></i> Settings</a>
            <div class="mt-auto">
                <a href="logout.php" class="nav-item text-danger"><i class="bi bi-box-arrow-left"></i> Logout</a>
            </div>
        </nav>
    </aside>

    <main class="main-content">
        <header class="top-navbar mb-4">
            <h4 class="mb-0 fw-bold me-auto">Manage All Applicants</h4>
            <div class="d-flex align-items-center gap-2">
                <span class="fw-semibold"><?php echo htmlspecialchars($company_name); ?></span>
                <img src="https://ui-avatars.com/api/?name=<?php echo urlencode($company_name); ?>&background=10b981&color=fff" class="rounded-circle" width="32">
            </div>
        </header>

        <?php if (isset($_GET['msg'])): ?>
            <div class="alert alert-success border-0 py-2 small mb-4">
                Application has been <?php echo htmlspecialchars($_GET['msg']); ?>!
            </div>
        <?php endif; ?>

        <div class="glass-card">
            <?php if (empty($applications)): ?>
                <div class="text-center py-5">
                    <i class="bi bi-people fs-1 text-muted"></i>
                    <p class="text-muted mt-3">No applications received yet.</p>
                </div>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="bg-light">
                            <tr>
                                <th class="border-0">Student</th>
                                <th class="border-0">Internship</th>
                                <th class="border-0">Status</th>
                                <th class="border-0">Applied At</th>
                                <th class="border-0 text-end">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($applications as $app): ?>
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center gap-2">
                                            <img src="https://ui-avatars.com/api/?name=<?php echo urlencode($app['student_name']); ?>&background=random" class="rounded-circle" width="32">
                                            <div>
                                                <div class="fw-bold"><?php echo htmlspecialchars($app['student_name']); ?></div>
                                                <div class="small text-muted"><?php echo htmlspecialchars($app['student_email']); ?></div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="small fw-bold"><?php echo htmlspecialchars($app['internship_title']); ?></div>
                                    </td>
                                    <td>
                                        <?php if ($app['status'] == 'Pending'): ?>
                                            <span class="badge bg-primary-light text-primary">Pending</span>
                                        <?php elseif ($app['status'] == 'Approved'): ?>
                                            <span class="badge bg-success-subtle text-success">Approved</span>
                                        <?php else: ?>
                                            <span class="badge bg-danger-subtle text-danger">Rejected</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="small text-muted"><?php echo date('M d, Y', strtotime($app['applied_at'])); ?></td>
                                    <td class="text-end">
                                        <div class="dropdown">
                                            <button class="btn btn-sm btn-light" type="button" data-bs-toggle="dropdown"><i class="bi bi-three-dots-vertical"></i></button>
                                            <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0">
                                                <li><a class="dropdown-item" href="<?php echo $app['cv_url'] ?? '#'; ?>"><i class="bi bi-file-earmark-pdf me-2"></i> View CV</a></li>
                                                <li><hr class="dropdown-divider"></li>
                                                <li><a class="dropdown-item text-success" href="all_applications.php?id=<?php echo $app['id']; ?>&status=Approved"><i class="bi bi-check-circle me-2"></i> Approve</a></li>
                                                <li><a class="dropdown-item text-danger" href="all_applications.php?id=<?php echo $app['id']; ?>&status=Rejected"><i class="bi bi-x-circle me-2"></i> Reject</a></li>
                                            </ul>
                                        </div>
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

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
