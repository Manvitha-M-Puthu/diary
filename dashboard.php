<?php
require 'config.php';
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// Fetch recent diary entries (last 5)
$stmt = $pdo->prepare("SELECT * FROM diary_entries WHERE user_id = ? ORDER BY created_at DESC LIMIT 5");
$stmt->execute([$_SESSION['user_id']]);
$entries = $stmt->fetchAll();
?>

<?php include 'header.php'; ?>
<div class="container">
    <h2>Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?>!</h2>
    <a href="add_entry.php" class="btn btn-primary">Add New Entry</a> 
    <a href="entries.php" class="btn btn-info">View All Entries</a>
    <a href="logout.php" class="btn btn-danger">Logout</a>
    <h3>Your Recent Entries</h3>
    <ul>
    <?php foreach ($entries as $entry): ?>
        <li>
            <b><?php echo htmlspecialchars($entry['title']); ?></b> (<?php echo $entry['entry_date']; ?>)
            <a href="edit_entry.php?id=<?php echo $entry['id']; ?>">Edit</a> |
            <a href="delete_entry.php?id=<?php echo $entry['id']; ?>" onclick="return confirm('Delete?')">Delete</a>
        </li>
    <?php endforeach; ?>
    </ul>
</div>
<?php include 'footer.php'; ?>
