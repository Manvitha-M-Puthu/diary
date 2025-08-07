<?php
require 'config.php';
$msg = '';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = trim($_POST["email"]);
    $password = $_POST["password"];

    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();
    if ($user && password_verify($password, $user['password'])) {
        $_SESSION["user_id"] = $user["id"];
        $_SESSION["username"] = $user["username"];
        header("Location: dashboard.php");
        exit;
    } else {
        $msg = "Invalid email or password!";
    }
}
?>

<?php include 'header.php'; ?>

<div class="container">
    <div class="row justify-content-center align-items-center min-vh-100">
        <div class="col-md-5 col-lg-4">
            <div class="card border-0 shadow-lg">
                <div class="card-body p-5">
                    <div class="text-center mb-4">
                        <div class="mb-3">
                            <i class="bi bi-journal-bookmark" style="font-size: 3rem; color: var(--primary-color);"></i>
                        </div>
                        <h2 class="fw-bold mb-2">Welcome Back</h2>
                        <p class="text-muted">Sign in to continue your journey</p>
                    </div>

                    <?php if ($msg): ?>
                    <div class="alert alert-danger d-flex align-items-center" role="alert">
                        <i class="bi bi-exclamation-triangle me-2"></i>
                        <?php echo $msg; ?>
                    </div>
                    <?php endif; ?>

                    <form method="post">
                        <div class="mb-3">
                            <label for="email" class="form-label">Email Address</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light">
                                    <i class="bi bi-envelope"></i>
                                </span>
                                <input name="email" 
                                       type="email" 
                                       id="email"
                                       class="form-control" 
                                       placeholder="Enter your email" 
                                       required
                                       value="<?php echo isset($email) ? htmlspecialchars($email) : ''; ?>">
                            </div>
                        </div>

                        <div class="mb-4">
                            <label for="password" class="form-label">Password</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light">
                                    <i class="bi bi-lock"></i>
                                </span>
                                <input name="password" 
                                       type="password" 
                                       id="password"
                                       class="form-control" 
                                       placeholder="Enter your password" 
                                       required>
                                <button type="button" class="btn btn-outline-secondary" onclick="togglePassword()">
                                    <i class="bi bi-eye" id="toggleIcon"></i>
                                </button>
                            </div>
                        </div>

                        <div class="d-grid mb-4">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="bi bi-box-arrow-in-right me-2"></i>
                                Sign In
                            </button>
                        </div>
                    </form>

                    <div class="text-center">
                        <p class="mb-0">Don't have an account? 
                            <a href="register.php" class="text-primary text-decoration-none fw-semibold">Sign Up</a>
                        </p>
                    </div>
                </div>
            </div>

            <!-- Features Section -->
            <div class="card border-0 bg-transparent text-white mt-4">
                <div class="card-body text-center">
                    <h5 class="mb-3">Why Choose Our Diary?</h5>
                    <div class="row g-3">
                        <div class="col-4">
                            <i class="bi bi-shield-check" style="font-size: 2rem;"></i>
                            <p class="small mt-2 mb-0">Secure</p>
                        </div>
                        <div class="col-4">
                            <i class="bi bi-phone" style="font-size: 2rem;"></i>
                            <p class="small mt-2 mb-0">Mobile Ready</p>
                        </div>
                        <div class="col-4">
                            <i class="bi bi-cloud" style="font-size: 2rem;"></i>
                            <p class="small mt-2 mb-0">Cloud Sync</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function togglePassword() {
    const passwordField = document.getElementById('password');
    const toggleIcon = document.getElementById('toggleIcon');
    
    if (passwordField.type === 'password') {
        passwordField.type = 'text';
        toggleIcon.className = 'bi bi-eye-slash';
    } else {
        passwordField.type = 'password';
        toggleIcon.className = 'bi bi-eye';
    }
}

// Add focus effects
document.querySelectorAll('.form-control').forEach(input => {
    input.addEventListener('focus', function() {
        this.closest('.input-group').style.transform = 'scale(1.02)';
        this.closest('.input-group').style.transition = 'transform 0.3s ease';
    });
    
    input.addEventListener('blur', function() {
        this.closest('.input-group').style.transform = 'scale(1)';
    });
});
</script>

<?php include 'footer.php'; ?>