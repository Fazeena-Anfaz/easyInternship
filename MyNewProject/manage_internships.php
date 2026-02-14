<?php
require 'includes/db.php';
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Company') {
    header("Location: login.php");
    exit();
}

$company_id = $_SESSION['user_id'];
$company_name = $_SESSION['company_name'] ?? $_SESSION['name'];

// Handle Deletion
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $stmt = $pdo->prepare("DELETE FROM internships WHERE id = ? AND company_id = ?");
    $stmt->execute([$id, $company_id]);
    header("Location: manage_internships.php?msg=deleted");
    exit();
}

$stmt = $pdo->prepare("SELECT * FROM internships WHERE company_id = ? ORDER BY created_at DESC");
$stmt->execute([$company_id]);
$internships = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Internships | EasyIntern</title>
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
            <a href="manage_internships.php" class="nav-item active"><i class="bi bi-list-task"></i> My Internships</a>
            <a href="all_applications.php" class="nav-item"><i class="bi bi-people"></i> Applicants</a>
            <a href="settings.php" class="nav-item"><i class="bi bi-gear"></i> Settings</a>
            <div class="mt-auto">
                <a href="logout.php" class="nav-item text-danger"><i class="bi bi-box-arrow-left"></i> Logout</a>
            </div>
        </nav>
    </aside>

    <main class="main-content">
        <header class="top-navbar mb-4">
            <h4 class="mb-0 fw-bold me-auto">Manage Your Internships</h4>
            <div class="d-flex align-items-center gap-2">
                <span class="fw-semibold"><?php echo htmlspecialchars($company_name); ?></span>
                <img src="https://ui-avatars.com/api/?name=<?php echo urlencode($company_name); ?>&background=10b981&color=fff" class="rounded-circle" width="32">
            </div>
        </header>

        <?php if (isset($_GET['msg']) && $_GET['msg'] == 'deleted'): ?>
            <div class="alert alert-success border-0 py-2 small mb-4">
                Internship deleted successfully!
            </div>
        <?php endif; ?>

        <div class="glass-card">
            <?php if (empty($internships)): ?>
                <div class="text-center py-5">
                    <i class="bi bi-file-earmark-plus fs-1 text-muted"></i>
                    <p class="text-muted mt-3">You haven't posted any internships yet.</p>
                    <a href="post_internship.php" class="btn-indigo text-decoration-none mt-2">Post Your First Internship</a>
                </div>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="bg-light">
                            <tr>
                                <th class="border-0">Title</th>
                                <th class="border-0">Status</th>
                                <th class="border-0">Deadline</th>
                                <th class="border-0 text-end">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($internships as $intern): ?>
                                <tr>
                                    <td>
                                        <div class="fw-bold"><?php echo htmlspecialchars($intern['title']); ?></div>
                                        <div class="small text-muted"><?php echo $intern['duration']; ?></div>
                                    </td>
                                    <td>
                                        <?php if ($intern['status'] == 'Active'): ?>
                                            <span class="badge bg-success-subtle text-success border-0">Active</span>
                                        <?php else: ?>
                                            <span class="badge bg-danger-subtle text-danger border-0">Expired</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="small text-muted"><?php echo date('M d, Y', strtotime($intern['deadline'])); ?></td>
                                    <td class="text-end">
                                        <a href="internship_details.php?id=<?php echo $intern['id']; ?>" class="btn btn-sm btn-light me-1"><i class="bi bi-eye"></i></a>
                                        <a href="manage_internships.php?delete=<?php echo $intern['id']; ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Are you sure you want to delete this?')"><i class="bi bi-trash"></i></a>
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
