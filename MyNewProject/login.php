<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | EasyIntern</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/saas-style.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
</head>
<body class="bg-light">

<section class="container py-5 d-flex justify-content-center align-items-center" style="min-height: 100vh;">
    <div class="glass-card shadow-lg" style="max-width: 450px; width: 100%; background: white;">
        <div class="text-center mb-4">
            <h2 class="fw-bold text-primary"><i class="bi bi-rocket-takeoff"></i> EasyIntern</h2>
            <p class="text-muted">Welcome back! Please login to your account.</p>
        </div>
        
        <?php if(isset($_GET['error'])): ?>
            <div class="alert alert-danger border-0 small py-2">
                <i class="bi bi-exclamation-circle-fill me-2"></i> Invalid email or password.
            </div>
        <?php endif; ?>

        <?php if(isset($_GET['msg']) && $_GET['msg'] == 'registered'): ?>
            <div class="alert alert-success border-0 small py-2">
                <i class="bi bi-check-circle-fill me-2"></i> Account created! You can now login.
            </div>
        <?php endif; ?>

        <form action="auth_action.php?action=login" method="POST">
            <div class="mb-3">
                <label class="form-label small fw-bold text-muted">Email Address</label>
                <div class="input-group">
                    <span class="input-group-text bg-light border-0"><i class="bi bi-envelope text-muted"></i></span>
                    <input type="email" name="email" class="form-control bg-light border-0 py-3" placeholder="name@company.com" required>
                </div>
            </div>
            <div class="mb-4">
                <div class="d-flex justify-content-between">
                    <label class="form-label small fw-bold text-muted">Password</label>
                    <a href="#" class="small text-primary text-decoration-none">Forgot?</a>
                </div>
                <div class="input-group">
                    <span class="input-group-text bg-light border-0"><i class="bi bi-lock text-muted"></i></span>
                    <input type="password" name="password" class="form-control bg-light border-0 py-3" placeholder="••••••••" required>
                </div>
            </div>
            <button type="submit" class="btn-indigo w-100 py-3 shadow-sm">Sign In</button>
        </form>
        
        <div class="text-center mt-4">
            <p class="small text-muted">Don't have an account? <a href="register.php" class="text-primary fw-bold text-decoration-none">Create one</a></p>
        </div>
    </div>
</section>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
