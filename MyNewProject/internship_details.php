<?php
require 'includes/db.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$id = $_GET['id'] ?? null;
if (!$id) {
    header("Location: index.php");
    exit();
}

$stmt = $pdo->prepare("SELECT * FROM internships WHERE id = ?");
$stmt->execute([$id]);
$intern = $stmt->fetch();

if (!$intern) {
    die("Internship not found.");
}

$role = $_SESSION['role'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($intern['title']); ?> | EasyIntern</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/saas-style.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
</head>
<body>

<div class="dashboard-wrapper">
    <aside class="sidebar">
        <div class="sidebar-logo">
            <?php if ($role === 'Company'): ?>
                <i class="bi bi-building-fill-check"></i> TechFlow
            <?php else: ?>
                <i class="bi bi-rocket-takeoff-fill"></i> EasyIntern
            <?php endif; ?>
        </div>
        <nav class="d-flex flex-column h-100">
            <?php if ($role === 'Company'): ?>
                <a href="company_dashboard.php" class="nav-item"><i class="bi bi-grid-1x2"></i> Overview</a>
                <a href="post_internship.php" class="nav-item"><i class="bi bi-plus-circle"></i> Post New</a>
                <a href="manage_internships.php" class="nav-item active"><i class="bi bi-list-task"></i> My Internships</a>
                <a href="all_applications.php" class="nav-item"><i class="bi bi-people"></i> Applicants</a>
            <?php else: ?>
                <a href="student_dashboard.php" class="nav-item"><i class="bi bi-grid-1x2"></i> Dashboard</a>
                <a href="internships.php" class="nav-item active"><i class="bi bi-search"></i> Find Internships</a>
                <a href="applications.php" class="nav-item"><i class="bi bi-briefcase"></i> My Applications</a>
            <?php endif; ?>
            <div class="mt-auto">
                <a href="logout.php" class="nav-item text-danger"><i class="bi bi-box-arrow-left"></i> Logout</a>
            </div>
        </nav>
    </aside>

    <main class="main-content">
        <header class="top-navbar mb-4">
            <h4 class="mb-0 fw-bold me-auto">Internship Details</h4>
            <div class="d-flex align-items-center gap-2">
                <span class="fw-semibold"><?php echo htmlspecialchars($_SESSION['name']); ?></span>
                <img src="https://ui-avatars.com/api/?name=<?php echo urlencode($_SESSION['name']); ?>&background=4f46e5&color=fff" class="rounded-circle" width="32">
            </div>
        </header>

        <div class="row g-4">
            <div class="col-lg-8">
                <div class="glass-card mb-4">
                    <div class="d-flex align-items-center gap-4 mb-4">
                        <div class="company-logo" style="width: 60px; height: 60px; font-size: 1.5rem;">
                            <?php echo substr($intern['company_name'], 0, 1); ?>
                        </div>
                        <div>
                            <h3 class="fw-bold mb-1"><?php echo htmlspecialchars($intern['title']); ?></h3>
                            <p class="text-muted mb-0"><?php echo htmlspecialchars($intern['company_name']); ?></p>
                        </div>
                    </div>
                    
                    <h5 class="fw-bold mb-3">Job Description</h5>
                    <p class="text-muted" style="white-space: pre-line;"><?php echo htmlspecialchars($intern['description']); ?></p>

                    <h5 class="fw-bold mb-3 mt-4">Requirements</h5>
                    <div class="d-flex flex-wrap gap-2">
                        <?php 
                        $reqs = explode(',', $intern['requirements']);
                        foreach($reqs as $req): if(trim($req)):
                        ?>
                            <span class="badge bg-light text-primary border px-3 py-2"><?php echo htmlspecialchars(trim($req)); ?></span>
                        <?php endif; endforeach; ?>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="glass-card mb-4">
                    <h5 class="fw-bold mb-4">Summary</h5>
                    <div class="mb-3">
                        <label class="small text-muted d-block">Duration</label>
                        <span class="fw-bold text-dark"><i class="bi bi-clock me-2 text-primary"></i> <?php echo $intern['duration']; ?></span>
                    </div>
                    <div class="mb-3">
                        <label class="small text-muted d-block">Deadline</label>
                        <span class="fw-bold text-dark"><i class="bi bi-calendar-event me-2 text-danger"></i> <?php echo date('M d, Y', strtotime($intern['deadline'])); ?></span>
                    </div>
                    <div class="mb-4">
                        <label class="small text-muted d-block">Date Posted</label>
                        <span class="fw-bold text-dark"><i class="bi bi-send me-2 text-secondary"></i> <?php echo date('M d, Y', strtotime($intern['created_at'])); ?></span>
                    </div>

                    <?php if ($role === 'Student'): ?>
                        <a href="apply.php?id=<?php echo $intern['id']; ?>" class="btn-indigo w-100 py-3 text-center text-decoration-none">Apply Now</a>
                    <?php else: ?>
                        <a href="manage_internships.php" class="btn btn-outline-secondary w-100 py-3">Back to Management</a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </main>
</div>

</body>
</html>
