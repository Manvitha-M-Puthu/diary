<?php
require 'config.php';
if (!isset($_SESSION['user_id'])) { header("Location: login.php"); exit; }

$msg = '';
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = trim($_POST["title"]);
    $content = trim($_POST["content"]);
    $date = $_POST["entry_date"];

    if ($title && $content && $date) {
        $stmt = $pdo->prepare("INSERT INTO diary_entries (user_id, title, content, entry_date) VALUES (?, ?, ?, ?)");
        if ($stmt->execute([$_SESSION['user_id'], $title, $content, $date])) {
            header("Location: dashboard.php");
            exit;
        } else {
            $msg = "Failed to add entry.";
        }
    } else {
        $msg = "All fields required!";
    }
}
?>

<?php include 'header.php'; ?>
<div class="container">
    <h2>New Diary Entry</h2>
    <form method="post">
        <div><?php echo $msg; ?></div>
        <input name="title" placeholder="Title" required class="form-control"><br>
        <textarea name="content" placeholder="Content" required class="form-control"></textarea><br>
        <input name="entry_date" type="date" required class="form-control"><br>
        <button type="submit" class="btn btn-primary">Save</button>
    </form>
    <a href="dashboard.php">Back to dashboard</a>
</div>
<?php include 'footer.php'; ?>
