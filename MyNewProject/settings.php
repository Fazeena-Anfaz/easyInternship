<?php
require 'includes/db.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$role = $_SESSION['role'];
$msg = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    
    try {
        $stmt = $pdo->prepare("UPDATE users SET name = ?, email = ? WHERE id = ?");
        $stmt->execute([$name, $email, $user_id]);
        $_SESSION['name'] = $name;
        $msg = "Profile updated successfully!";
    } catch (PDOException $e) {
        $msg = "Error updating profile: " . $e->getMessage();
    }
}

$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Account Settings | EasyIntern</title>
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
                <a href="manage_internships.php" class="nav-item"><i class="bi bi-list-task"></i> My Internships</a>
                <a href="all_applications.php" class="nav-item"><i class="bi bi-people"></i> Applicants</a>
                <a href="settings.php" class="nav-item active"><i class="bi bi-gear"></i> Settings</a>
            <?php else: ?>
                <a href="student_dashboard.php" class="nav-item"><i class="bi bi-grid-1x2"></i> Dashboard</a>
                <a href="internships.php" class="nav-item"><i class="bi bi-search"></i> Find Internships</a>
                <a href="applications.php" class="nav-item"><i class="bi bi-briefcase"></i> My Applications</a>
                <a href="profile.php" class="nav-item"><i class="bi bi-person"></i> Profile</a>
                <a href="settings.php" class="nav-item active"><i class="bi bi-gear"></i> Settings</a>
            <?php endif; ?>
            <div class="mt-auto">
                <a href="logout.php" class="nav-item text-danger"><i class="bi bi-box-arrow-left"></i> Logout</a>
            </div>
        </nav>
    </aside>

    <main class="main-content">
        <header class="top-navbar mb-4">
            <h4 class="mb-0 fw-bold me-auto">Settings</h4>
            <div class="d-flex align-items-center gap-2">
                <span class="fw-semibold"><?php echo htmlspecialchars($_SESSION['name']); ?></span>
                <img src="https://ui-avatars.com/api/?name=<?php echo urlencode($_SESSION['name']); ?>&background=4f46e5&color=fff" class="rounded-circle" width="32">
            </div>
        </header>

        <div class="glass-card" style="max-width: 600px;">
            <h5 class="fw-bold mb-4">Profile Information</h5>
            
            <?php if ($msg): ?>
                <div class="alert alert-info border-0 py-2 small mb-4">
                    <?php echo $msg; ?>
                </div>
            <?php endif; ?>

            <form action="settings.php" method="POST">
                <div class="mb-3">
                    <label class="form-label small fw-bold text-muted">Full Name</label>
                    <input type="text" name="name" class="form-control bg-light border-0 py-2" value="<?php echo htmlspecialchars($user['name']); ?>" required>
                </div>
                <div class="mb-3">
                    <label class="form-label small fw-bold text-muted">Email Address</label>
                    <input type="email" name="email" class="form-control bg-light border-0 py-2" value="<?php echo htmlspecialchars($user['email']); ?>" required>
                </div>
                <div class="mb-4">
                    <label class="form-label small fw-bold text-muted">Role</label>
                    <input type="text" class="form-control bg-light border-0 py-2" value="<?php echo $user['role']; ?>" disabled>
                </div>
                <button type="submit" class="btn-indigo px-5 py-2">Update Profile</button>
            </form>
        </div>
    </main>
</div>

</body>
</html>
