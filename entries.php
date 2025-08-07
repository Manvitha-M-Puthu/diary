<?php
require 'config.php';
if (!isset($_SESSION['user_id'])) { header("Location: login.php"); exit; }

$page = max(1, (int)($_GET['page'] ?? 1));
$per_page = 8;
$offset = ($page - 1) * $per_page;

// Search functionality
$search = $_GET['search'] ?? '';
$search_query = '';
$search_params = [$_SESSION['user_id']];

if ($search) {
    $search_query = " AND (title LIKE ? OR content LIKE ?)";
    $search_params[] = "%$search%";
    $search_params[] = "%$search%";
}

$total = $pdo->prepare("SELECT COUNT(*) FROM diary_entries WHERE user_id = ? $search_query");
$total->execute($search_params);
$total_entries = $total->fetchColumn();

$stmt = $pdo->prepare("SELECT * FROM diary_entries WHERE user_id = ? $search_query ORDER BY entry_date DESC LIMIT $per_page OFFSET $offset");
$stmt->execute($search_params);
$entries = $stmt->fetchAll();

$pages = ceil($total_entries / $per_page);
?>

<?php include 'header.php'; ?>

<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="text-white mb-1">
                <i class="bi bi-journal-text"></i> My Diary Entries
            </h2>
            <p class="text-white-50 mb-0">
                <?php echo $total_entries; ?> entries • Your personal journey
            </p>
        </div>
        <a href="add_entry.php" class="btn btn-primary">
            <i class="bi bi-plus-circle"></i> New Entry
        </a>
    </div>

    <!-- Search and Filters -->
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" class="row g-3 align-items-end">
                <div class="col-md-6">
                    <label for="search" class="form-label">Search Entries</label>
                    <div class="input-group">
                        <span class="input-group-text">
                            <i class="bi bi-search"></i>
                        </span>
                        <input type="text" 
                               name="search" 
                               id="search"
                               class="form-control" 
                               placeholder="Search titles and content..."
                               value="<?php echo htmlspecialchars($search); ?>">
                        <?php if ($search): ?>
                        <a href="entries.php" class="btn btn-outline-secondary">
                            <i class="bi bi-x"></i>
                        </a>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="col-md-3">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-search"></i> Search
                    </button>
                </div>
                <div class="col-md-3 text-end">
                    <div class="btn-group" role="group">
                        <input type="radio" class="btn-check" name="view" id="grid" value="grid" checked>
                        <label class="btn btn-outline-primary" for="grid">
                            <i class="bi bi-grid"></i>
                        </label>
                        <input type="radio" class="btn-check" name="view" id="list" value="list">
                        <label class="btn btn-outline-primary" for="list">
                            <i class="bi bi-list"></i>
                        </label>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <?php if ($search && $total_entries === 0): ?>
    <div class="card">
        <div class="card-body text-center py-5">
            <i class="bi bi-search" style="font-size: 4rem; color: var(--text-secondary);"></i>
            <h4 class="mt-3">No entries found</h4>
            <p class="text-muted">Try adjusting your search terms or <a href="entries.php">view all entries</a></p>
        </div>
    </div>
    <?php elseif (empty($entries)): ?>
    <div class="card">
        <div class="card-body text-center py-5">
            <i class="bi bi-journal-x" style="font-size: 4rem; color: var(--text-secondary);"></i>
            <h4 class="mt-3">No entries yet</h4>
            <p class="text-muted mb-4">Start documenting your journey today!</p>
            <a href="add_entry.php" class="btn btn-primary">
                <i class="bi bi-plus-circle"></i> Create First Entry
            </a>
        </div>
    </div>
    <?php else: ?>
    
    <!-- Grid View -->
    <div id="gridView" class="view-container">
        <div class="row g-4">
            <?php foreach ($entries as $entry): ?>
            <div class="col-md-6 col-lg-4">
                <div class="card h-100 entry-card">
                    <div class="card-body d-flex flex-column">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <span class="badge bg-primary">
                                <i class="bi bi-calendar3"></i>
                                <?php echo date('M j, Y', strtotime($entry['entry_date'])); ?>
                            </span>
                            <div class="dropdown">
                                <button class="btn btn-link btn-sm text-muted" data-bs-toggle="dropdown">
                                    <i class="bi bi-three-dots"></i>
                                </button>
                                <ul class="dropdown-menu">
                                    <li>
                                        <a class="dropdown-item" href="view_entry.php?id=<?php echo $entry['id']; ?>">
                                            <i class="bi bi-eye"></i> View
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item" href="edit_entry.php?id=<?php echo $entry['id']; ?>">
                                            <i class="bi bi-pencil"></i> Edit
                                        </a>
                                    </li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li>
                                        <a class="dropdown-item text-danger" 
                                           href="delete_entry.php?id=<?php echo $entry['id']; ?>"
                                           onclick="return confirm('Are you sure?')">
                                            <i class="bi bi-trash"></i> Delete
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        
                        <h5 class="card-title">
                            <?php echo htmlspecialchars($entry['title']); ?>
                        </h5>
                        
                        <p class="card-text text-muted flex-grow-1">
                            <?php echo htmlspecialchars(substr($entry['content'], 0, 120)) . '...'; ?>
                        </p>
                        
                        <div class="mt-auto">
                            <div class="d-flex justify-content-between align-items-center">
                                <small class="text-muted">
                                    <i class="bi bi-clock"></i>
                                    <?php echo date('g:i A', strtotime($entry['created_at'])); ?>
                                </small>
                                <a href="view_entry.php?id=<?php echo $entry['id']; ?>" class="btn btn-sm btn-primary">
                                    Read More <i class="bi bi-arrow-right"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- List View -->
    <div id="listView" class="view-container d-none">
        <div class="card">
            <div class="card-body p-0">
                <?php foreach ($entries as $index => $entry): ?>
                <div class="entry-item-list p-4 <?php echo $index > 0 ? 'border-top' : ''; ?>">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <div class="d-flex align-items-center mb-2">
                                <span class="badge bg-light text-dark me-2">
                                    <?php echo date('M j, Y', strtotime($entry['entry_date'])); ?>
                                </span>
                                <small class="text-muted">
                                    <i class="bi bi-clock"></i>
                                    <?php echo date('g:i A', strtotime($entry['created_at'])); ?>
                                </small>
                            </div>
                            <h5 class="mb-2">
                                <a href="view_entry.php?id=<?php echo $entry['id']; ?>" class="text-decoration-none">
                                    <?php echo htmlspecialchars($entry['title']); ?>
                                </a>
                            </h5>
                            <p class="text-muted mb-0">
                                <?php echo htmlspecialchars(substr($entry['content'], 0, 200)) . '...'; ?>
                            </p>
                        </div>
                        <div class="col-md-4 text-end">
                            <div class="btn-group">
                                <a href="view_entry.php?id=<?php echo $entry['id']; ?>" class="btn btn-sm btn-outline-primary">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <a href="edit_entry.php?id=<?php echo $entry['id']; ?>" class="btn btn-sm btn-outline-secondary">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <a href="delete_entry.php?id=<?php echo $entry['id']; ?>" 
                                   class="btn btn-sm btn-outline-danger"
                                   onclick="return confirm('Are you sure?')">
                                    <i class="bi bi-trash"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>

    <!-- Pagination -->
    <?php if ($pages > 1): ?>
    <nav class="mt-5">
        <ul class="pagination justify-content-center">
            <?php if ($page > 1): ?>
            <li class="page-item">
                <a class="page-link" href="?page=<?php echo $page-1; ?>&search=<?php echo urlencode($search); ?>">
                    <i class="bi bi-chevron-left"></i> Previous
                </a>
            </li>
            <?php endif; ?>
            
            <?php
            $start = max(1, $page - 2);
            $end = min($pages, $page + 2);
            
            for ($i = $start; $i <= $end; $i++): ?>
            <li class="page-item<?php echo $i == $page ? ' active' : ''; ?>">
                <a class="page-link" href="?page=<?php echo $i; ?>&search=<?php echo urlencode($search); ?>">
                    <?php echo $i; ?>
                </a>
            </li>
            <?php endfor; ?>
            
            <?php if ($page < $pages): ?>
            <li class="page-item">
                <a class="page-link" href="?page=<?php echo $page+1; ?>&search=<?php echo urlencode($search); ?>">
                    Next <i class="bi bi-chevron-right"></i>
                </a>
            </li>
            <?php endif; ?>
        </ul>
        
        <div class="text-center mt-3">
            <small class="text-muted">
                Showing <?php echo $offset + 1; ?>-<?php echo min($offset + $per_page, $total_entries); ?> 
                of <?php echo $total_entries; ?> entries
            </small>
        </div>
    </nav>
    <?php endif; ?>
    
    <?php endif; ?>
</div>

<style>
.entry-card {
    transition: all 0.3s ease;
    border: none;
    box-shadow: var(--shadow);
}

.entry-card:hover {
    transform: translateY(-5px);
    box-shadow: var(--shadow-lg);
}

.entry-item-list {
    transition: background-color 0.3s ease;
}

.entry-item-list:hover {
    background-color: var(--secondary-color);
}

.view-container {
    transition: all 0.3s ease;
}
</style>

<script>
// View switcher
document.getElementById('grid').addEventListener('change', function() {
    if (this.checked) {
        document.getElementById('gridView').classList.remove('d-none');
        document.getElementById('listView').classList.add('d-none');
    }
});

document.getElementById('list').addEventListener('change', function() {
    if (this.checked) {
        document.getElementById('listView').classList.remove('d-none');
        document.getElementById('gridView').classList.add('d-none');
    }
});

// Real-time search
let searchTimeout;
document.getElementById('search').addEventListener('input', function() {
    clearTimeout(searchTimeout);
    searchTimeout = setTimeout(() => {
        if (this.value.length >= 3 || this.value.length === 0) {
            this.form.submit();
        }
    }, 500);
});

// Keyboard shortcuts
document.addEventListener('keydown', function(e) {
    // Ctrl/Cmd + K for search focus
    if ((e.ctrlKey || e.metaKey) && e.key === 'k') {
        e.preventDefault();
        document.getElementById('search').focus();
    }
    
    // N for new entry
    if (e.key === 'n' && !e.ctrlKey && !e.metaKey && document.activeElement.tagName !== 'INPUT') {
        e.preventDefault();
        window.location.href = 'add_entry.php';
    }
});
</script>

<?php include 'footer.php'; ?>