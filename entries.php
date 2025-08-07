<?php
require 'config.php';
if (!isset($_SESSION['user_id'])) { header("Location: login.php"); exit; }

$page = max(1, (int)($_GET['page'] ?? 1));
$per_page = 10;
$offset = ($page - 1) * $per_page;

$total = $pdo->prepare("SELECT COUNT(*) FROM diary_entries WHERE user_id = ?");
$total->execute([$_SESSION['user_id']]);
$total_entries = $total->fetchColumn();

$stmt = $pdo->prepare("SELECT * FROM diary_entries WHERE user_id = ? ORDER BY entry_date DESC LIMIT $per_page OFFSET $offset");
$stmt->execute([$_SESSION['user_id']]);
$entries = $stmt->fetchAll();

$pages = ceil($total_entries / $per_page);
?>

<?php include 'header.php'; ?>
<div class="container">
    <h2>All Diary Entries</h2>
    <table class="table table-bordered">
        <tr><th>Date</th><th>Title</th><th>Actions</th></tr>
        <?php foreach ($entries as $entry): ?>
        <tr>
            <td><?php echo $entry['entry_date']; ?></td>
            <td><?php echo htmlspecialchars($entry['title']); ?></td>
            <td>
                <a href="edit_entry.php?id=<?php echo $entry['id']; ?>">Edit</a>
                <a href="delete_entry.php?id=<?php echo $entry['id']; ?>" onclick="return confirm('Delete?')">Delete</a>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>
    <nav>
        <ul class="pagination">
        <?php for ($i=1; $i<=$pages; $i++): ?>
            <li class="page-item<?php if($i == $page) echo ' active'; ?>">
                <a class="page-link" href="?page=<?php echo $i; ?>"><?php echo $i; ?></a>
            </li>
        <?php endfor; ?>
        </ul>
    </nav>
    <a href="dashboard.php">Back to Dashboard</a>
</div>
<?php include 'footer.php'; ?>
