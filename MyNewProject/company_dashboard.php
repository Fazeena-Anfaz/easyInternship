<?php
require 'includes/db.php';
session_start();

// Mock user for testing if session is empty (Company Role)
if (!isset($_SESSION['user_id'])) {
    $_SESSION['user_id'] = 2; // Fixed company user
    $_SESSION['role'] = 'Company';
    $_SESSION['company_name'] = 'TechFlow Inc.';
}

$company_id = $_SESSION['user_id'];

// Get Stats
$stmt = $pdo->prepare("SELECT COUNT(*) as total FROM internships WHERE company_id = ?");
$stmt->execute([$company_id]);
$total_internships = $stmt->fetch()['total'];

$stmt = $pdo->prepare("SELECT COUNT(*) as total FROM applications a JOIN internships i ON a.internship_id = i.id WHERE i.company_id = ?");
$stmt->execute([$company_id]);
$total_applications = $stmt->fetch()['total'];

// Get applications per internship for Chart
$stmt = $pdo->prepare("SELECT i.title, COUNT(a.id) as count FROM internships i LEFT JOIN applications a ON i.id = a.internship_id WHERE i.company_id = ? GROUP BY i.id");
$stmt->execute([$company_id]);
$chart_data = $stmt->fetchAll();

// Get Recent Applications
$stmt = $pdo->prepare("SELECT a.*, u.name as student_name, u.email as student_email, i.title as pos, sp.cv_url 
                       FROM applications a 
                       JOIN users u ON a.student_id = u.id 
                       JOIN internships i ON a.internship_id = i.id 
                       LEFT JOIN student_profiles sp ON u.id = sp.user_id
                       WHERE i.company_id = ? ORDER BY applied_at DESC LIMIT 5");
$stmt->execute([$company_id]);
$recent_apps = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Company Console | EasyIntern</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/saas-style.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>

<div class="dashboard-wrapper">
    <!-- Sidebar -->
    <aside class="sidebar">
        <div class="sidebar-logo">
            <i class="bi bi-building-fill-check"></i> TechFlow
        </div>
        <nav class="d-flex flex-column h-100">
            <a href="company_dashboard.php" class="nav-item active"><i class="bi bi-grid-1x2"></i> Overview</a>
            <a href="post_internship.php" class="nav-item"><i class="bi bi-plus-circle"></i> Post New</a>
            <a href="manage_internships.php" class="nav-item"><i class="bi bi-list-task"></i> My Internships</a>
            <a href="all_applications.php" class="nav-item"><i class="bi bi-people"></i> Applicants</a>
            <a href="settings.php" class="nav-item"><i class="bi bi-gear"></i> Settings</a>
            
            <div class="mt-auto">
                <a href="logout.php" class="nav-item text-danger"><i class="bi bi-box-arrow-left"></i> Logout</a>
            </div>
        </nav>
    </aside>

    <main class="main-content">
        <header class="top-navbar mb-4">
            <div class="d-flex align-items-center gap-2">
                <span class="fw-semibold"><?php echo htmlspecialchars($_SESSION['company_name'] ?? $_SESSION['name']); ?></span>
                <img src="https://ui-avatars.com/api/?name=<?php echo urlencode($_SESSION['company_name'] ?? $_SESSION['name']); ?>&background=10b981&color=fff" class="rounded-circle" width="32">
            </div>
        </header>

        <!-- Metrics -->
        <div class="row g-4 mb-5">
            <div class="col-md-4">
                <div class="glass-card">
                    <h6 class="text-muted small fw-bold text-uppercase mb-3">Total Internships</h6>
                    <div class="d-flex align-items-center justify-content-between">
                        <h2 class="fw-bold mb-0"><?php echo $total_internships; ?></h2>
                        <i class="bi bi-file-earmark-text text-primary fs-3"></i>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="glass-card">
                    <h6 class="text-muted small fw-bold text-uppercase mb-3">Total Applications</h6>
                    <div class="d-flex align-items-center justify-content-between">
                        <h2 class="fw-bold mb-0"><?php echo $total_applications; ?></h2>
                        <i class="bi bi-people text-secondary fs-3"></i>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="glass-card">
                    <h6 class="text-muted small fw-bold text-uppercase mb-3">Hiring Rate</h6>
                    <div class="d-flex align-items-center justify-content-between">
                        <h2 class="fw-bold mb-0">12%</h2>
                        <i class="bi bi-graph-up-arrow text-warning fs-3"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Analytics Chart -->
            <div class="col-lg-7 mb-4">
                <div class="glass-card h-100">
                    <h5 class="fw-bold mb-4">Applications per Internship</h5>
                    <div class="chart-container">
                        <canvas id="appsChart"></canvas>
                    </div>
                </div>
            </div>

            <!-- Recent Applicants -->
            <div class="col-lg-5 mb-4">
                <div class="glass-card h-100">
                    <h5 class="fw-bold mb-4">Recent Applicants</h5>
                    <?php if(empty($recent_apps)): ?>
                        <p class="text-muted">No applications yet.</p>
                    <?php else: foreach($recent_apps as $app): ?>
                        <div class="d-flex align-items-center justify-content-between mb-4 pb-2 border-bottom border-light">
                            <div class="d-flex align-items-center gap-3">
                                <img src="https://ui-avatars.com/api/?name=<?php echo urlencode($app['student_name']); ?>&background=random" class="rounded-circle" width="40">
                                <div>
                                    <h6 class="mb-0 fw-bold"><?php echo $app['student_name']; ?></h6>
                                    <p class="small text-muted mb-0"><?php echo $app['pos']; ?></p>
                                </div>
                            </div>
                            <div class="dropdown">
                                <button class="btn btn-sm btn-light" type="button" data-bs-toggle="dropdown"><i class="bi bi-three-dots-vertical"></i></button>
                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item" href="<?php echo $app['cv_url'] ?? '#'; ?>"><i class="bi bi-eye"></i> View CV</a></li>
                                    <li><a class="dropdown-item text-success" href="all_applications.php?id=<?php echo $app['id']; ?>&status=Approved"><i class="bi bi-check-circle"></i> Approve</a></li>
                                    <li><a class="dropdown-item text-danger" href="all_applications.php?id=<?php echo $app['id']; ?>&status=Rejected"><i class="bi bi-x-circle"></i> Reject</a></li>
                                </ul>
                            </div>
                        </div>
                    <?php endforeach; endif; ?>
                    <a href="all_applications.php" class="btn btn-light w-100 mt-2">View All Applications</a>
                </div>
            </div>
        </div>
    </main>
</div>

<script>
const ctx = document.getElementById('appsChart').getContext('2d');
new Chart(ctx, {
    type: 'bar',
    data: {
        labels: <?php echo json_encode(array_column($chart_data, 'title')); ?>,
        datasets: [{
            label: '# of Applications',
            data: <?php echo json_encode(array_column($chart_data, 'count')); ?>,
            backgroundColor: '#4f46e5',
            borderRadius: 8,
            barThickness: 25
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: { legend: { display: false } },
        scales: {
            y: { beginAtZero: true, grid: { display: false } },
            x: { grid: { display: false } }
        }
    }
});
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
