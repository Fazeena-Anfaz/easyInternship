<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EasyIntern | Find Your Dream Internship</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/saas-style.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
</head>
<body style="background: white;">

    <!-- Mini Navbar -->
    <nav class="navbar navbar-expand-lg py-3">
        <div class="container">
            <a class="navbar-brand fw-bold text-primary" href="#">EasyIntern</a>
            <div class="ms-auto d-flex align-items-center gap-3">
                <a href="login.php" class="text-decoration-none text-dark fw-medium">Login</a>
                <a href="register.php" class="btn-indigo text-decoration-none">Sign Up</a>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <header class="py-5" style="background: linear-gradient(to bottom, #f5f7ff 0%, #ffffff 100%);">
        <div class="container py-5 text-center">
            <div class="badge bg-primary-light text-primary px-3 py-2 rounded-pill mb-4 mb-lg-5">Trusted by 500+ Companies</div>
            <h1 class="display-3 fw-bold mb-4" style="color: #1f2937;">Unlock Your <span class="text-primary">Career Potential</span></h1>
            <p class="lead text-muted mb-5 mx-auto" style="max-width: 600px;">The ultimate SaaS platform for students to find smart internship recommendations and for companies to manage talent efficiently.</p>
            <div class="d-flex justify-content-center gap-3">
                <a href="register.php?role=Student" class="btn-indigo py-3 px-5">Join as Student</a>
                <a href="register.php?role=Company" class="btn btn-outline-secondary py-3 px-5 rounded-4">Hire Talent</a>
            </div>
        </div>
    </header>

    <!-- Features Section -->
    <section class="py-5">
        <div class="container">
            <div class="row g-4">
                <div class="col-md-4">
                    <div class="p-4 text-center">
                        <div class="company-logo mx-auto mb-3" style="font-size: 1.5rem;"><i class="bi bi-magic"></i></div>
                        <h4 class="fw-bold">Smart Matching</h4>
                        <p class="text-muted">Our AI suggests internships based on your skill set automatically.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="p-4 text-center">
                        <div class="company-logo mx-auto mb-3" style="font-size: 1.5rem; color: var(--secondary);"><i class="bi bi-graph-up"></i></div>
                        <h4 class="fw-bold">Track Applications</h4>
                        <p class="text-muted">Monitor your application status with a beautiful real-time timeline.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="p-4 text-center">
                        <div class="company-logo mx-auto mb-3" style="font-size: 1.5rem; color: #f59e0b;"><i class="bi bi-shield-check"></i></div>
                        <h4 class="fw-bold">Secure Verification</h4>
                        <p class="text-muted">Trusted platform for verified companies and verified students.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Stats Section -->
    <section class="py-5 bg-light">
        <div class="container">
            <div class="row text-center">
                <div class="col-md-3">
                    <h2 class="fw-bold">2.5k+</h2>
                    <p class="text-muted">Students</p>
                </div>
                <div class="col-md-3">
                    <h2 class="fw-bold">480+</h2>
                    <p class="text-muted">Companies</p>
                </div>
                <div class="col-md-3">
                    <h2 class="fw-bold">1.2k+</h2>
                    <p class="text-muted">Hired</p>
                </div>
                <div class="col-md-3">
                    <h2 class="fw-bold">95%</h2>
                    <p class="text-muted">Success Rate</p>
                </div>
            </div>
        </div>
    </section>

    <footer class="py-5 mt-5 border-top">
        <div class="container text-center">
            <p class="text-muted">&copy; 2026 EasyIntern SaaS Platform. Empowering Next Gen Talent.</p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
