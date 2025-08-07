<?php
require 'config.php';
if (!isset($_SESSION['user_id'])) { header("Location: login.php"); exit; }

$id = (int)$_GET['id'];
$stmt = $pdo->prepare("SELECT * FROM diary_entries WHERE id = ? AND user_id = ?");
$stmt->execute([$id, $_SESSION['user_id']]);
$entry = $stmt->fetch();
if (!$entry) { die('Entry not found.'); }

$msg = '';
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $title = trim($_POST["title"]);
    $content = trim($_POST["content"]);
    $date = $_POST["entry_date"];
    $stmt = $pdo->prepare("UPDATE diary_entries SET title=?, content=?, entry_date=? WHERE id=? AND user_id=?");
    if ($stmt->execute([$title, $content, $date, $id, $_SESSION['user_id']])) {
        header("Location: entries.php");
        exit;
    } else {
        $msg = "Failed to update entry.";
    }
}
?>

<?php include 'header.php'; ?>
<div class="container">
    <h2>Edit Entry</h2>
    <form method="post">
        <div><?php echo $msg; ?></div>
        <input name="title" value="<?php echo htmlspecialchars($entry['title']); ?>" required class="form-control"><br>
        <textarea name="content" required class="form-control"><?php echo htmlspecialchars($entry['content']); ?></textarea><br>
        <input name="entry_date" type="date" value="<?php echo $entry['entry_date']; ?>" required class="form-control"><br>
        <button type="submit" class="btn btn-primary">Save Changes</button>
    </form>
    <a href="entries.php">Back to Entries</a>
</div>
<?php include 'footer.php'; ?>
