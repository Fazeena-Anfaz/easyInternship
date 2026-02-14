<?php
require 'includes/db.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Get user and profile details
$stmt = $pdo->prepare("SELECT u.*, sp.* FROM users u LEFT JOIN student_profiles sp ON u.id = sp.user_id WHERE u.id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $skills = $_POST['skills'];
    $bio = $_POST['bio'];
    $location = $_POST['location'];
    
    // Simple completion logic
    $completion = 20; // 20% for basic info
    if($skills) $completion += 30;
    if($bio) $completion += 20;
    if($location) $completion += 30;

    $stmt = $pdo->prepare("UPDATE student_profiles SET skills = ?, bio = ?, location = ?, profile_completion = ? WHERE user_id = ?");
    $stmt->execute([$skills, $bio, $location, $completion, $user_id]);
    
    header("Location: profile.php?msg=updated");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Profile | EasyIntern</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/saas-style.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
</head>
<body>

<div class="dashboard-wrapper">
    <aside class="sidebar">
        <div class="sidebar-logo">
            <?php if ($_SESSION['role'] === 'Company'): ?>
                <i class="bi bi-building-fill-check"></i> TechFlow
            <?php else: ?>
                <i class="bi bi-rocket-takeoff-fill"></i> EasyIntern
            <?php endif; ?>
        </div>
        <nav class="d-flex flex-column h-100">
            <?php if ($_SESSION['role'] === 'Company'): ?>
                <a href="company_dashboard.php" class="nav-item"><i class="bi bi-grid-1x2"></i> Overview</a>
                <a href="post_internship.php" class="nav-item"><i class="bi bi-plus-circle"></i> Post New</a>
                <a href="manage_internships.php" class="nav-item"><i class="bi bi-list-task"></i> My Internships</a>
                <a href="all_applications.php" class="nav-item"><i class="bi bi-people"></i> Applicants</a>
                <a href="settings.php" class="nav-item"><i class="bi bi-gear"></i> Settings</a>
            <?php else: ?>
                <a href="student_dashboard.php" class="nav-item"><i class="bi bi-grid-1x2"></i> Dashboard</a>
                <a href="internships.php" class="nav-item"><i class="bi bi-search"></i> Find Internships</a>
                <a href="applications.php" class="nav-item"><i class="bi bi-briefcase"></i> My Applications</a>
                <a href="profile.php" class="nav-item active"><i class="bi bi-person"></i> Profile</a>
                <a href="notifications.php" class="nav-item"><i class="bi bi-bell"></i> Notifications</a>
            <?php endif; ?>
            <div class="mt-auto"><a href="logout.php" class="nav-item text-danger"><i class="bi bi-box-arrow-left"></i> Logout</a></div>
        </nav>
    </aside>

    <main class="main-content">
        <header class="top-navbar mb-4">
            <h4 class="mb-0 fw-bold me-auto">Profile Settings</h4>
            <div class="d-flex align-items-center gap-2">
                <img src="https://ui-avatars.com/api/?name=<?php echo urlencode($_SESSION['name']); ?>&background=4f46e5&color=fff" class="rounded-circle" width="32">
                <span class="fw-semibold"><?php echo htmlspecialchars($_SESSION['name']); ?></span>
            </div>
        </header>

        <?php if(isset($_GET['msg']) && $_GET['msg'] == 'updated'): ?>
            <div class="alert alert-success border-0 small mb-4"><i class="bi bi-check-circle me-2"></i> Profile updated successfully!</div>
        <?php endif; ?>

        <div class="row">
            <div class="col-lg-8">
                <div class="glass-card">
                    <form action="profile.php" method="POST">
                        <div class="row g-4">
                            <div class="col-md-6">
                                <label class="form-label small fw-bold text-muted">Full Name</label>
                                <input type="text" class="form-control bg-light border-0 py-2" value="<?php echo htmlspecialchars($user['name']); ?>" disabled>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-bold text-muted">Email Address</label>
                                <input type="email" class="form-control bg-light border-0 py-2" value="<?php echo htmlspecialchars($user['email']); ?>" disabled>
                            </div>
                            <div class="col-12">
                                <label class="form-label small fw-bold text-muted">Skills (Comma separated)</label>
                                <input type="text" name="skills" class="form-control bg-light border-0 py-2" placeholder="e.g. PHP, JavaScript, React" value="<?php echo htmlspecialchars($user['skills'] ?? ''); ?>">
                            </div>
                            <div class="col-12">
                                <label class="form-label small fw-bold text-muted">Location</label>
                                <input type="text" name="location" class="form-control bg-light border-0 py-2" placeholder="e.g. New York, NY" value="<?php echo htmlspecialchars($user['location'] ?? ''); ?>">
                            </div>
                            <div class="col-12">
                                <label class="form-label small fw-bold text-muted">Short Bio</label>
                                <textarea name="bio" class="form-control bg-light border-0 py-2" rows="4" placeholder="Tell us about yourself..."><?php echo htmlspecialchars($user['bio'] ?? ''); ?></textarea>
                            </div>
                            <div class="col-12">
                                <button type="submit" class="btn-indigo py-2 px-5">Save Changes</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            
            <div class="col-lg-4">
                <div class="glass-card text-center mb-4">
                    <img src="https://ui-avatars.com/api/?name=<?php echo urlencode($_SESSION['name']); ?>&size=128&background=4f46e5&color=fff" class="rounded-circle mb-3 shadow-sm">
                    <h5 class="fw-bold mb-1"><?php echo htmlspecialchars($user['name']); ?></h5>
                    <p class="text-muted small mb-3"><?php echo htmlspecialchars($user['role']); ?></p>
                    <div class="progress mb-2" style="height: 6px;">
                        <div class="progress-bar" style="width: <?php echo $user['profile_completion']; ?>%"></div>
                    </div>
                    <span class="small text-primary fw-bold"><?php echo $user['profile_completion']; ?>% Profile Complete</span>
                </div>
            </div>
        </div>
    </main>
</div>

</body>
</html>
