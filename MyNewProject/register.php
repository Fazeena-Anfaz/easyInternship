<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up | EasyIntern</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/saas-style.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
</head>
<body class="bg-light">

<section class="container py-5 d-flex justify-content-center align-items-center" style="min-height: 100vh;">
    <div class="glass-card shadow-lg" style="max-width: 500px; width: 100%; background: white;">
        <div class="text-center mb-4">
            <h2 class="fw-bold text-primary"><i class="bi bi-rocket-takeoff"></i> EasyIntern</h2>
            <p class="text-muted">Start your journey with us today.</p>
        </div>
        
        <?php if(isset($_GET['error'])): ?>
            <div class="alert alert-danger border-0 small py-2">
                <i class="bi bi-exclamation-circle-fill me-2"></i> <?php echo htmlspecialchars($_GET['error']); ?>
            </div>
        <?php endif; ?>

        <form action="auth_action.php?action=register" method="POST">
            <div class="mb-3">
                <label class="form-label small fw-bold text-muted">Full Name</label>
                <div class="input-group">
                    <span class="input-group-text bg-light border-0"><i class="bi bi-person text-muted"></i></span>
                    <input type="text" name="name" class="form-control bg-light border-0 py-3" placeholder="John Doe" required>
                </div>
            </div>
            <div class="mb-3">
                <label class="form-label small fw-bold text-muted">Email Address</label>
                <div class="input-group">
                    <span class="input-group-text bg-light border-0"><i class="bi bi-envelope text-muted"></i></span>
                    <input type="email" name="email" class="form-control bg-light border-0 py-3" placeholder="john@example.com" required>
                </div>
            </div>
            <div class="mb-3">
                <label class="form-label small fw-bold text-muted">Password</label>
                <div class="input-group">
                    <span class="input-group-text bg-light border-0"><i class="bi bi-lock text-muted"></i></span>
                    <input type="password" name="password" class="form-control bg-light border-0 py-3" placeholder="At least 8 characters" required>
                </div>
            </div>
            <div class="mb-4">
                <label class="form-label small fw-bold text-muted">I am a...</label>
                <select name="role" class="form-select bg-light border-0 py-3 shadow-none fw-medium text-muted">
                    <option value="Student">Student (Finding Internships)</option>
                    <option value="Company">Company (Hiring Talent)</option>
                </select>
            </div>
            <button type="submit" class="btn-indigo w-100 py-3 shadow-sm">Create Account</button>
        </form>
        
        <div class="text-center mt-4">
            <p class="small text-muted">Already have an account? <a href="login.php" class="text-primary fw-bold text-decoration-none">Sign In</a></p>
        </div>
    </div>
</section>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
