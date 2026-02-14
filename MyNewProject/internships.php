<?php
require 'includes/db.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$search = $_GET['q'] ?? '';
$duration = $_GET['duration'] ?? '';

$sql = "SELECT * FROM internships WHERE status = 'Active'";
$params = [];

if ($search) {
    $sql .= " AND (title LIKE :q OR company_name LIKE :q OR requirements LIKE :q)";
    $params['q'] = "%$search%";
}

if ($duration) {
    $sql .= " AND duration = :duration";
    $params['duration'] = $duration;
}

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$internships = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Find Internships | EasyIntern</title>
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
            <a href="internships.php" class="nav-item active"><i class="bi bi-search"></i> Find Internships</a>
            <a href="applications.php" class="nav-item"><i class="bi bi-briefcase"></i> My Applications</a>
            <a href="profile.php" class="nav-item"><i class="bi bi-person"></i> Profile</a>
            <a href="notifications.php" class="nav-item"><i class="bi bi-bell"></i> Notifications</a>
            <div class="mt-auto"><a href="logout.php" class="nav-item text-danger"><i class="bi bi-box-arrow-left"></i> Logout</a></div>
        </nav>
    </aside>

    <main class="main-content">
        <header class="top-navbar mb-4">
            <h4 class="mb-0 fw-bold me-auto">Explore Internships</h4>
            <div class="d-flex align-items-center gap-2">
                <img src="https://ui-avatars.com/api/?name=<?php echo urlencode($_SESSION['name']); ?>&background=4f46e5&color=fff" class="rounded-circle" width="32">
                <span class="fw-semibold"><?php echo htmlspecialchars($_SESSION['name']); ?></span>
            </div>
        </header>

        <!-- Search & Filter -->
        <div class="glass-card mb-4">
            <form action="internships.php" method="GET" class="row g-3">
                <div class="col-md-6">
                    <div class="input-group">
                        <span class="input-group-text bg-light border-0"><i class="bi bi-search text-muted"></i></span>
                        <input type="text" name="q" class="form-control bg-light border-0 py-2" placeholder="Search by title, company or skill..." value="<?php echo htmlspecialchars($search); ?>">
                    </div>
                </div>
                <div class="col-md-4">
                    <select name="duration" class="form-select bg-light border-0 py-2">
                        <option value="">Any Duration</option>
                        <option value="3 Months" <?php if($duration == '3 Months') echo 'selected'; ?>>3 Months</option>
                        <option value="6 Months" <?php if($duration == '6 Months') echo 'selected'; ?>>6 Months</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn-indigo w-100 py-2">Search</button>
                </div>
            </form>
        </div>

        <!-- Internship Grid -->
        <div class="row g-4">
            <?php if(empty($internships)): ?>
                <div class="col-12 text-center py-5">
                    <i class="bi bi-search fs-1 text-muted"></i>
                    <p class="text-muted mt-3">No internships found matching your criteria.</p>
                </div>
            <?php else: foreach($internships as $intern): ?>
                <div class="col-md-6 col-xl-4">
                    <div class="intern-card h-100 d-flex flex-column">
                        <div class="d-flex align-items-center gap-3 mb-3">
                            <div class="company-logo"><?php echo substr($intern['company_name'], 0, 1); ?></div>
                            <div>
                                <h6 class="mb-0 fw-bold"><?php echo htmlspecialchars($intern['title']); ?></h6>
                                <p class="small text-muted mb-0"><?php echo htmlspecialchars($intern['company_name']); ?></p>
                            </div>
                        </div>
                        <p class="small text-muted mb-4 flex-grow-1"><?php echo htmlspecialchars(substr($intern['description'], 0, 100)); ?>...</p>
                        <div class="mb-3">
                            <span class="badge bg-light text-primary me-2"><i class="bi bi-clock"></i> <?php echo $intern['duration']; ?></span>
                            <span class="badge bg-light text-danger"><i class="bi bi-calendar-event"></i> <?php echo $intern['deadline']; ?></span>
                        </div>
                        <div class="d-flex gap-2">
                            <a href="internship_details.php?id=<?php echo $intern['id']; ?>" class="btn btn-sm btn-outline-secondary w-100">Details</a>
                            <a href="apply.php?id=<?php echo $intern['id']; ?>" class="btn btn-sm btn-indigo w-100">Apply</a>
                        </div>
                    </div>
                </div>
            <?php endforeach; endif; ?>
        </div>
    </main>
</div>

</body>
</html>
