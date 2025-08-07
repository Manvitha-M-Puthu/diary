<?php
require 'config.php';
$msg = '';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = trim($_POST["username"]);
    $email = trim($_POST["email"]);
    $password = $_POST["password"];

    if ($username && $email && $password) {
        // Check if user exists
        $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->execute([$email]);
        if ($stmt->fetch()) {
            $msg = "Email already registered!";
        } else {
            // Hash the password
            $hash = password_hash($password, PASSWORD_DEFAULT);
            $sql = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";
            if ($pdo->prepare($sql)->execute([$username, $email, $hash])) {
                header("Location: login.php");
                exit;
            } else {
                $msg = "Registration failed!";
            }
        }
    } else {
        $msg = "All fields required!";
    }
}
?>

<!-- HTML Registration Form -->
<?php include 'header.php'; ?>
<div class="container">
    <h2>Register</h2>
    <form method="post">
        <div><?php echo $msg; ?></div>
        <input name="username" placeholder="Username" required class="form-control"><br>
        <input name="email" type="email" placeholder="Email" required class="form-control"><br>
        <input name="password" type="password" placeholder="Password" required class="form-control"><br>
        <button type="submit" class="btn btn-primary">Register</button>
    </form>
    <a href="login.php">Login</a>
</div>
<?php include 'footer.php'; ?>
