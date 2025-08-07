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

<!-- HTML Login Form -->
<?php include 'header.php'; ?>
<div class="container">
    <h2>Login</h2>
    <form method="post">
        <div><?php echo $msg; ?></div>
        <input name="email" type="email" placeholder="Email" required class="form-control"><br>
        <input name="password" type="password" placeholder="Password" required class="form-control"><br>
        <button type="submit" class="btn btn-success">Login</button>
    </form>
    <a href="register.php">Register</a>
</div>
<?php include 'footer.php'; ?>
