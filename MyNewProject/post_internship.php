<?php
require 'includes/db.php';
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Company') {
    header("Location: login.php");
    exit();
}

$company_id = $_SESSION['user_id'];
$company_name = $_SESSION['company_name'] ?? $_SESSION['name'];
$msg = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $requirements = $_POST['requirements'];
    $duration = $_POST['duration'];
    $deadline = $_POST['deadline'];

    try {
        $stmt = $pdo->prepare("INSERT INTO internships (company_id, title, description, requirements, duration, deadline, company_name) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([$company_id, $title, $description, $requirements, $duration, $deadline, $company_name]);
        $msg = "Internship posted successfully!";
    } catch (PDOException $e) {
        $msg = "Error: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Post Internship | EasyIntern</title>
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
            <a href="post_internship.php" class="nav-item active"><i class="bi bi-plus-circle"></i> Post New</a>
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
            <h4 class="mb-0 fw-bold me-auto">Post New Internship</h4>
            <div class="d-flex align-items-center gap-2">
                <span class="fw-semibold"><?php echo htmlspecialchars($company_name); ?></span>
                <img src="https://ui-avatars.com/api/?name=<?php echo urlencode($company_name); ?>&background=10b981&color=fff" class="rounded-circle" width="32">
            </div>
        </header>

        <div class="glass-card" style="max-width: 800px;">
            <?php if ($msg): ?>
                <div class="alert alert-info border-0"><?php echo $msg; ?></div>
            <?php endif; ?>

            <form action="post_internship.php" method="POST">
                <div class="mb-3">
                    <label class="form-label fw-bold">Internship Title</label>
                    <input type="text" name="title" class="form-control bg-light border-0" placeholder="e.g. Software Engineer Intern" required>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold">Description</label>
                    <textarea name="description" class="form-control bg-light border-0" rows="5" placeholder="Describe the role and responsibilities..." required></textarea>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold">Requirements (Comma separated)</label>
                    <input type="text" name="requirements" class="form-control bg-light border-0" placeholder="e.g. PHP, Laravel, MySQL, Bootstrap">
                </div>
                <div class="row mb-4">
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Duration</label>
                        <select name="duration" class="form-select bg-light border-0">
                            <option value="3 Months">3 Months</option>
                            <option value="6 Months">6 Months</option>
                            <option value="1 Year">1 Year</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Deadline</label>
                        <input type="date" name="deadline" class="form-control bg-light border-0" required>
                    </div>
                </div>
                <button type="submit" class="btn-indigo px-5 py-2">Post Internship</button>
            </form>
        </div>
    </main>
</div>

</body>
</html>
