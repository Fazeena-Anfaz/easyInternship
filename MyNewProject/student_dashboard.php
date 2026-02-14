<?php
require 'includes/db.php';
session_start();

// Mock user for testing if session is empty
if (!isset($_SESSION['user_id'])) {
    $_SESSION['user_id'] = 1;
    $_SESSION['role'] = 'Student';
    $_SESSION['name'] = 'Alex Chen';
}

$user_id = $_SESSION['user_id'];

// Get profile progress
$stmt = $pdo->prepare("SELECT * FROM student_profiles WHERE user_id = ?");
$stmt->execute([$user_id]);
$profile = $stmt->fetch();
$progress = $profile['profile_completion'] ?? 70; // Mock 70%

// Get Applications for timeline
$stmt = $pdo->prepare("SELECT a.*, i.title, i.company_name FROM applications a JOIN internships i ON a.internship_id = i.id WHERE a.student_id = ? ORDER BY applied_at DESC");
$stmt->execute([$user_id]);
$applications = $stmt->fetchAll();

// Get Recommendations (Mock logic based on skills)
$skills = $profile['skills'] ?? 'PHP, JavaScript, CSS';
$skill_arr = explode(',', $skills);
$recommendations = [];
if (!empty($skill_arr)) {
    $sql = "SELECT * FROM internships WHERE ";
    $clauses = [];
    foreach($skill_arr as $s) {
        $clauses[] = "requirements LIKE '%" . trim($s) . "%'";
    }
    $sql .= implode(' OR ', $clauses) . " LIMIT 3";
    $recommendations = $pdo->query($sql)->fetchAll();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Dashboard | EasyIntern</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/saas-style.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
</head>
<body>

<div class="dashboard-wrapper">
    <!-- Sidebar -->
    <aside class="sidebar">
        <div class="sidebar-logo">
            <i class="bi bi-rocket-takeoff-fill"></i> EasyIntern
        </div>
        <nav class="d-flex flex-column h-100">
            <a href="student_dashboard.php" class="nav-item active"><i class="bi bi-grid-1x2"></i> Dashboard</a>
            <a href="internships.php" class="nav-item"><i class="bi bi-search"></i> Find Internships</a>
            <a href="applications.php" class="nav-item"><i class="bi bi-briefcase"></i> My Applications</a>
            <a href="profile.php" class="nav-item"><i class="bi bi-person"></i> Profile</a>
            <a href="notifications.php" class="nav-item"><i class="bi bi-bell"></i> Notifications</a>
            
            <div class="mt-auto">
                <a href="logout.php" class="nav-item text-danger"><i class="bi bi-box-arrow-left"></i> Logout</a>
            </div>
        </nav>
    </aside>

    <!-- Main -->
    <main class="main-content">
        <!-- Top Nav -->
        <header class="top-navbar mb-4">
            <div class="notification-bell mx-3">
                <i class="bi bi-bell fs-5"></i>
                <span class="notification-count">3</span>
            </div>
            <div class="d-flex align-items-center gap-2">
                <img src="https://ui-avatars.com/api/?name=<?php echo urlencode($_SESSION['name']); ?>&background=4f46e5&color=fff" class="rounded-circle" width="32">
                <span class="fw-semibold"><?php echo htmlspecialchars($_SESSION['name']); ?></span>
            </div>
        </header>

        <!-- Profile Progress -->
        <div class="glass-card mb-4 animate-fade">
            <div class="d-flex justify-content-between align-items-center mb-2">
                <h6 class="mb-0 fw-bold">Profile Completion</h6>
                <span class="small text-primary fw-bold"><?php echo $progress; ?>%</span>
            </div>
            <div class="progress">
                <div class="progress-bar" style="width: <?php echo $progress; ?>%"></div>
            </div>
            <p class="small text-muted mt-2 mb-0">Complete your profile to unlock more accurate recommendations!</p>
        </div>

        <div class="row">
            <!-- Left Side -->
            <div class="col-lg-8">
                <!-- Recommendations -->
                <h5 class="fw-bold mb-4">Recommended for You</h5>
                <div class="row g-4 mb-5">
                    <?php if(empty($recommendations)): ?>
                        <div class="col-12"><p class="text-muted">No recommendations yet. Update your skills!</p></div>
                    <?php else: foreach($recommendations as $intern): ?>
                        <div class="col-md-6">
                            <div class="intern-card h-100">
                                <div class="d-flex align-items-center gap-3 mb-3">
                                    <div class="company-logo"><?php echo substr($intern['company_name'], 0, 1); ?></div>
                                    <div>
                                        <h6 class="mb-0 fw-bold"><?php echo $intern['title']; ?></h6>
                                        <p class="small text-muted mb-0"><?php echo $intern['company_name']; ?></p>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <span class="badge bg-light text-primary me-2"><i class="bi bi-clock"></i> <?php echo $intern['duration']; ?></span>
                                    <span class="badge bg-light text-danger"><i class="bi bi-calendar-event"></i> <?php echo $intern['deadline']; ?></span>
                                </div>
                                <a href="apply.php?id=<?php echo $intern['id']; ?>" class="btn btn-sm btn-indigo w-100">Apply Now</a>
                            </div>
                        </div>
                    <?php endforeach; endif; ?>
                </div>

                <!-- Timeline / Tracking -->
                <h5 class="fw-bold mb-4">Application Tracking</h5>
                <div class="glass-card">
                    <?php if(empty($applications)): ?>
                        <p class="text-muted">You haven't applied to any internships yet.</p>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Position</th>
                                        <th>Company</th>
                                        <th>Status</th>
                                        <th>Timeline</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach($applications as $app): ?>
                                        <tr>
                                            <td class="fw-medium"><?php echo $app['title']; ?></td>
                                            <td class="text-muted"><?php echo $app['company_name']; ?></td>
                                            <td>
                                                <?php if($app['status'] == 'Pending'): ?>
                                                    <span class="badge bg-primary-light text-primary">Pending</span>
                                                <?php elseif($app['status'] == 'Approved'): ?>
                                                    <span class="badge bg-success-subtle text-success">Approved</span>
                                                <?php else: ?>
                                                    <span class="badge bg-danger-subtle text-danger">Rejected</span>
                                                <?php endif; ?>
                                            </td>
                                            <td class="small text-muted"><?php echo date('M d', strtotime($app['applied_at'])); ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Right Sidebar -->
            <div class="col-lg-4">
                <div class="glass-card mb-4 bg-primary text-white">
                    <h6 class="fw-bold mb-3">CV Optimization AI</h6>
                    <p class="small opacity-75">Our AI suggests your CV is missing keywords for "Web Developer" roles.</p>
                    <button class="btn btn-sm btn-light text-primary fw-bold">Optimize Now</button>
                </div>

                <div class="glass-card">
                    <h6 class="fw-bold mb-4">Upcoming Deadlines</h6>
                    <div class="d-flex align-items-start gap-3 mb-3">
                        <div class="bg-primary-light p-2 rounded text-primary"><i class="bi bi-clock-history"></i></div>
                        <div>
                            <p class="mb-0 small fw-bold">Google SWE Intern</p>
                            <p class="mb-0 x-small text-muted">2 days remaining</p>
                        </div>
                    </div>
                    <div class="d-flex align-items-start gap-3">
                        <div class="bg-primary-light p-2 rounded text-primary"><i class="bi bi-clock-history"></i></div>
                        <div>
                            <p class="mb-0 small fw-bold">Meta FE Intern</p>
                            <p class="mb-0 x-small text-muted">5 days remaining</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
