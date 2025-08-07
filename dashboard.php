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

// Get statistics
$total_stmt = $pdo->prepare("SELECT COUNT(*) FROM diary_entries WHERE user_id = ?");
$total_stmt->execute([$_SESSION['user_id']]);
$total_entries = $total_stmt->fetchColumn();

$this_month_stmt = $pdo->prepare("SELECT COUNT(*) FROM diary_entries WHERE user_id = ? AND MONTH(created_at) = MONTH(CURRENT_DATE()) AND YEAR(created_at) = YEAR(CURRENT_DATE())");
$this_month_stmt->execute([$_SESSION['user_id']]);
$this_month = $this_month_stmt->fetchColumn();

$this_week_stmt = $pdo->prepare("SELECT COUNT(*) FROM diary_entries WHERE user_id = ? AND YEARWEEK(created_at, 1) = YEARWEEK(CURDATE(), 1)");
$this_week_stmt->execute([$_SESSION['user_id']]);
$this_week = $this_week_stmt->fetchColumn();
?>

<?php include 'header.php'; ?>

<div class="welcome-section">
    <div class="container">
        <h1><i class="bi bi-sun"></i> Welcome back, <?php echo htmlspecialchars($_SESSION['username']); ?>!</h1>
        <p>Ready to capture today's moments? Your thoughts and experiences matter.</p>
    </div>
</div>

<div class="container">
    <!-- Statistics Cards -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon">
                <i class="bi bi-journal-text"></i>
            </div>
            <h3 class="mb-1"><?php echo $total_entries; ?></h3>
            <p class="text-muted mb-0">Total Entries</p>
        </div>
        <div class="stat-card">
            <div class="stat-icon">
                <i class="bi bi-calendar-month"></i>
            </div>
            <h3 class="mb-1"><?php echo $this_month; ?></h3>
            <p class="text-muted mb-0">This Month</p>
        </div>
        <div class="stat-card">
            <div class="stat-icon">
                <i class="bi bi-calendar-week"></i>
            </div>
            <h3 class="mb-1"><?php echo $this_week; ?></h3>
            <p class="text-muted mb-0">This Week</p>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="card">
        <div class="card-header">
            <h4 class="mb-0"><i class="bi bi-lightning-charge"></i> Quick Actions</h4>
        </div>
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-4">
                    <a href="add_entry.php" class="btn btn-primary w-100 py-3">
                        <i class="bi bi-plus-circle me-2"></i>
                        <div>
                            <strong>New Entry</strong>
                            <br><small>Start writing today</small>
                        </div>
                    </a>
                </div>
                <div class="col-md-4">
                    <a href="entries.php" class="btn btn-outline-primary w-100 py-3">
                        <i class="bi bi-journal-text me-2"></i>
                        <div>
                            <strong>All Entries</strong>
                            <br><small>Browse your memories</small>
                        </div>
                    </a>
                </div>
                <div class="col-md-4">
                    <a href="entries.php?search=1" class="btn btn-outline-primary w-100 py-3">
                        <i class="bi bi-search me-2"></i>
                        <div>
                            <strong>Search</strong>
                            <br><small>Find specific entries</small>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Entries -->
    <?php if (!empty($entries)): ?>
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h4 class="mb-0"><i class="bi bi-clock-history"></i> Recent Entries</h4>
            <a href="entries.php" class="btn btn-sm btn-outline-light">View All</a>
        </div>
        <div class="card-body">
            <?php foreach ($entries as $entry): ?>
            <div class="entry-item">
                <div class="d-flex justify-content-between align-items-start">
                    <div class="flex-grow-1">
                        <div class="entry-date">
                            <i class="bi bi-calendar3"></i> <?php echo date('M j, Y', strtotime($entry['entry_date'])); ?>
                            <span class="text-muted ms-2">
                                <i class="bi bi-clock"></i> <?php echo date('g:i A', strtotime($entry['created_at'])); ?>
                            </span>
                        </div>
                        <h5 class="entry-title"><?php echo htmlspecialchars($entry['title']); ?></h5>
                        <p class="text-muted mb-0">
                            <?php echo htmlspecialchars(substr($entry['content'], 0, 150)) . (strlen($entry['content']) > 150 ? '...' : ''); ?>
                        </p>
                    </div>
                </div>
                <div class="entry-actions">
                    <a href="view_entry.php?id=<?php echo $entry['id']; ?>" class="btn btn-sm btn-outline-primary">
                        <i class="bi bi-eye"></i> Read
                    </a>
                    <a href="edit_entry.php?id=<?php echo $entry['id']; ?>" class="btn btn-sm btn-outline-secondary">
                        <i class="bi bi-pencil"></i> Edit
                    </a>
                    <a href="delete_entry.php?id=<?php echo $entry['id']; ?>" 
                       onclick="return confirm('Are you sure you want to delete this entry?')" 
                       class="btn btn-sm btn-outline-danger">
                        <i class="bi bi-trash"></i> Delete
                    </a>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
    <?php else: ?>
    <div class="card">
        <div class="card-body text-center py-5">
            <div class="mb-4">
                <i class="bi bi-journal-x" style="font-size: 4rem; color: var(--text-secondary);"></i>
            </div>
            <h4 class="mb-3">No entries yet</h4>
            <p class="text-muted mb-4">Start your journaling journey by creating your first entry!</p>
            <a href="add_entry.php" class="btn btn-primary btn-lg">
                <i class="bi bi-plus-circle me-2"></i> Create First Entry
            </a>
        </div>
    </div>
    <?php endif; ?>
</div>

<!-- Floating Action Button -->
<a href="add_entry.php" class="floating-action" title="New Entry">
    <i class="bi bi-plus"></i>
</a>

<?php include 'footer.php'; ?>