<?php
require 'config.php';
if (!isset($_SESSION['user_id'])) { header("Location: login.php"); exit; }

$id = (int)$_GET['id'];
$stmt = $pdo->prepare("SELECT * FROM diary_entries WHERE id = ? AND user_id = ?");
$stmt->execute([$id, $_SESSION['user_id']]);
$entry = $stmt->fetch();

if (!$entry) { 
    header("Location: entries.php");
    exit;
}

// Get previous and next entries
$prev_stmt = $pdo->prepare("SELECT id, title FROM diary_entries WHERE user_id = ? AND entry_date < ? ORDER BY entry_date DESC LIMIT 1");
$prev_stmt->execute([$_SESSION['user_id'], $entry['entry_date']]);
$prev_entry = $prev_stmt->fetch();

$next_stmt = $pdo->prepare("SELECT id, title FROM diary_entries WHERE user_id = ? AND entry_date > ? ORDER BY entry_date ASC LIMIT 1");
$next_stmt->execute([$_SESSION['user_id'], $entry['entry_date']]);
$next_entry = $next_stmt->fetch();
?>

<?php include 'header.php'; ?>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <!-- Entry Header -->
            <div class="card mb-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div>
                            <div class="d-flex align-items-center mb-2">
                                <span class="badge bg-primary me-2">
                                    <i class="bi bi-calendar3"></i>
                                    <?php echo date('F j, Y', strtotime($entry['entry_date'])); ?>
                                </span>
                                <small class="text-muted">
                                    <i class="bi bi-clock"></i>
                                    Created <?php echo date('M j, Y \a\t g:i A', strtotime($entry['created_at'])); ?>
                                </small>
                            </div>
                            <h1 class="display-6 mb-0">
                                <?php echo htmlspecialchars($entry['title']); ?>
                            </h1>
                        </div>
                        <div class="dropdown">
                            <button class="btn btn-outline-secondary btn-sm" data-bs-toggle="dropdown">
                                <i class="bi bi-three-dots"></i>
                            </button>
                            <ul class="dropdown-menu">
                                <li>
                                    <a class="dropdown-item" href="edit_entry.php?id=<?php echo $entry['id']; ?>">
                                        <i class="bi bi-pencil"></i> Edit Entry
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="#" onclick="printEntry()">
                                        <i class="bi bi-printer"></i> Print
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="#" onclick="shareEntry()">
                                        <i class="bi bi-share"></i> Share
                                    </a>
                                </li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <a class="dropdown-item text-danger" 
                                       href="delete_entry.php?id=<?php echo $entry['id']; ?>"
                                       onclick="return confirm('Are you sure you want to delete this entry?')">
                                        <i class="bi bi-trash"></i> Delete Entry
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Entry Content -->
            <div class="card mb-4" id="entryContent">
                <div class="card-body">
                    <div class="entry-content">
                        <?php echo nl2br(htmlspecialchars($entry['content'])); ?>
                    </div>
                </div>
            </div>

            <!-- Entry Stats -->
            <div class="card mb-4" style="background: rgba(255, 255, 255, 0.95);">
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-md-3">
                            <div class="stat-item">
                                <i class="bi bi-type text-primary" style="font-size: 1.5rem;"></i>
                                <div class="mt-2">
                                    <strong><?php echo str_word_count($entry['content']); ?></strong>
                                    <br><small class="text-muted">Words</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="stat-item">
                                <i class="bi bi-textarea-resize text-success" style="font-size: 1.5rem;"></i>
                                <div class="mt-2">
                                    <strong><?php echo strlen($entry['content']); ?></strong>
                                    <br><small class="text-muted">Characters</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="stat-item">
                                <i class="bi bi-book text-info" style="font-size: 1.5rem;"></i>
                                <div class="mt-2">
                                    <strong><?php echo ceil(str_word_count($entry['content']) / 200); ?></strong>
                                    <br><small class="text-muted">Min Read</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="stat-item">
                                <i class="bi bi-calendar-heart text-warning" style="font-size: 1.5rem;"></i>
                                <div class="mt-2">
                                    <strong><?php echo floor((time() - strtotime($entry['created_at'])) / (60*60*24)); ?></strong>
                                    <br><small class="text-muted">Days Ago</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Navigation -->
            <div class="card mb-4">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-md-4">
                            <?php if ($prev_entry): ?>
                            <a href="view_entry.php?id=<?php echo $prev_entry['id']; ?>" class="btn btn-outline-primary">
                                <i class="bi bi-chevron-left"></i> Previous Entry
                                <br><small class="text-muted"><?php echo htmlspecialchars(substr($prev_entry['title'], 0, 30)) . (strlen($prev_entry['title']) > 30 ? '...' : ''); ?></small>
                            </a>
                            <?php endif; ?>
                        </div>
                        <div class="col-md-4 text-center">
                            <a href="entries.php" class="btn btn-primary">
                                <i class="bi bi-grid"></i> All Entries
                            </a>
                        </div>
                        <div class="col-md-4 text-end">
                            <?php if ($next_entry): ?>
                            <a href="view_entry.php?id=<?php echo $next_entry['id']; ?>" class="btn btn-outline-primary">
                                Next Entry <i class="bi bi-chevron-right"></i>
                                <br><small class="text-muted"><?php echo htmlspecialchars(substr($next_entry['title'], 0, 30)) . (strlen($next_entry['title']) > 30 ? '...' : ''); ?></small>
                            </a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-center gap-3">
                        <a href="edit_entry.php?id=<?php echo $entry['id']; ?>" class="btn btn-primary">
                            <i class="bi bi-pencil"></i> Edit This Entry
                        </a>
                        <a href="add_entry.php" class="btn btn-outline-primary">
                            <i class="bi bi-plus-circle"></i> New Entry
                        </a>
                        <button onclick="toggleReadingMode()" class="btn btn-outline-secondary" id="readingModeBtn">
                            <i class="bi bi-book"></i> Reading Mode
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Reading Mode Overlay -->
<div id="readingMode" class="reading-mode" style="display: none;">
    <div class="reading-content">
        <div class="reading-header">
            <h2><?php echo htmlspecialchars($entry['title']); ?></h2>
            <p class="text-muted">
                <?php echo date('F j, Y', strtotime($entry['entry_date'])); ?>
            </p>
            <button onclick="toggleReadingMode()" class="btn-close-reading">
                <i class="bi bi-x-lg"></i>
            </button>
        </div>
        <div class="reading-body">
            <?php echo nl2br(htmlspecialchars($entry['content'])); ?>
        </div>
    </div>
</div>

<style>
.entry-content {
    font-size: 1.1rem;
    line-height: 1.8;
    color: var(--text-primary);
}

.stat-item {
    padding: 1rem;
    transition: transform 0.3s ease;
}

.stat-item:hover {
    transform: translateY(-2px);
}

.reading-mode {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.95);
    z-index: 9999;
    overflow-y: auto;
    padding: 2rem;
}

.reading-content {
    max-width: 800px;
    margin: 0 auto;
    background: white;
    padding: 3rem;
    border-radius: 1rem;
    box-shadow: var(--shadow-lg);
    position: relative;
}

.reading-header {
    text-align: center;
    margin-bottom: 2rem;
    padding-bottom: 1rem;
    border-bottom: 1px solid var(--border-color);
}

.reading-header h2 {
    margin-bottom: 0.5rem;
    color: var(--text-primary);
}

.reading-body {
    font-size: 1.2rem;
    line-height: 2;
    color: var(--text-primary);
    text-align: justify;
}

.btn-close-reading {
    position: absolute;
    top: 1rem;
    right: 1rem;
    background: none;
    border: none;
    font-size: 1.5rem;
    color: var(--text-secondary);
    cursor: pointer;
    padding: 0.5rem;
    border-radius: 0.5rem;
    transition: all 0.3s ease;
}

.btn-close-reading:hover {
    background: var(--secondary-color);
    color: var(--text-primary);
}

@media print {
    body * {
        visibility: hidden;
    }
    
    #entryContent, #entryContent * {
        visibility: visible;
    }
    
    #entryContent {
        position: absolute;
        left: 0;
        top: 0;
        width: 100%;
    }
    
    .card {
        border: none !important;
        box-shadow: none !important;
    }
}

@media (max-width: 768px) {
    .reading-mode {
        padding: 1rem;
    }
    
    .reading-content {
        padding: 2rem 1.5rem;
    }
    
    .reading-body {
        font-size: 1.1rem;
        line-height: 1.8;
    }
}
</style>

<script>
function toggleReadingMode() {
    const readingMode = document.getElementById('readingMode');
    const btn = document.getElementById('readingModeBtn');
    
    if (readingMode.style.display === 'none') {
        readingMode.style.display = 'block';
        document.body.style.overflow = 'hidden';
        btn.innerHTML = '<i class="bi bi-x-circle"></i> Exit Reading';
    } else {
        readingMode.style.display = 'none';
        document.body.style.overflow = 'auto';
        btn.innerHTML = '<i class="bi bi-book"></i> Reading Mode';
    }
}

function printEntry() {
    window.print();
}

function shareEntry() {
    if (navigator.share) {
        navigator.share({
            title: '<?php echo addslashes($entry['title']); ?>',
            text: 'Check out my diary entry: <?php echo addslashes(substr($entry['title'], 0, 100)); ?>',
            url: window.location.href
        });
    } else {
        // Fallback: copy to clipboard
        navigator.clipboard.writeText(window.location.href).then(() => {
            const btn = event.target;
            const originalText = btn.innerHTML;
            btn.innerHTML = '<i class="bi bi-check"></i> Link Copied!';
            setTimeout(() => {
                btn.innerHTML = originalText;
            }, 2000);
        });
    }
}

// Keyboard shortcuts
document.addEventListener('keydown', function(e) {
    // ESC to exit reading mode
    if (e.key === 'Escape') {
        const readingMode = document.getElementById('readingMode');
        if (readingMode.style.display === 'block') {
            toggleReadingMode();
        }
    }
    
    // E for edit
    if (e.key === 'e' && !e.ctrlKey && !e.metaKey && document.activeElement.tagName !== 'INPUT') {
        window.location.href = 'edit_entry.php?id=<?php echo $entry['id']; ?>';
    }
    
    // R for reading mode
    if (e.key === 'r' && !e.ctrlKey && !e.metaKey && document.activeElement.tagName !== 'INPUT') {
        toggleReadingMode();
    }
    
    // Arrow keys for navigation
    if (e.key === 'ArrowLeft' && e.altKey) {
        <?php if ($prev_entry): ?>
        window.location.href = 'view_entry.php?id=<?php echo $prev_entry['id']; ?>';
        <?php endif; ?>
    }
    
    if (e.key === 'ArrowRight' && e.altKey) {
        <?php if ($next_entry): ?>
        window.location.href = 'view_entry.php?id=<?php echo $next_entry['id']; ?>';
        <?php endif; ?>
    }
});

// Auto-scroll to content on page load
window.addEventListener('load', function() {
    // Add smooth reading experience
    const content = document.querySelector('.entry-content');
    content.style.opacity = '0';
    content.style.transform = 'translateY(20px)';
    content.style.transition = 'all 0.8s ease';
    
    setTimeout(() => {
        content.style.opacity = '1';
        content.style.transform = 'translateY(0)';
    }, 300);
});

// Show keyboard shortcuts help
let shortcutsTimeout;
document.addEventListener('keydown', function(e) {
    if (e.key === '?' && e.shiftKey) {
        clearTimeout(shortcutsTimeout);
        showShortcutsHelp();
    }
});

function showShortcutsHelp() {
    const helpText = `
    Keyboard Shortcuts:
    • E - Edit entry
    • R - Reading mode
    • ESC - Exit reading mode
    • Alt + ← → - Navigate between entries
    • ? - Show this help
    `;
    
    const helpDiv = document.createElement('div');
    helpDiv.style.cssText = `
        position: fixed;
        top: 20px;
        right: 20px;
        background: rgba(0,0,0,0.9);
        color: white;
        padding: 1rem;
        border-radius: 0.5rem;
        font-family: monospace;
        font-size: 0.9rem;
        white-space: pre-line;
        z-index: 10000;
        animation: fadeInOut 4s ease-in-out;
    `;
    helpDiv.textContent = helpText;
    
    const style = document.createElement('style');
    style.textContent = `
        @keyframes fadeInOut {
            0%, 100% { opacity: 0; transform: translateX(100%); }
            20%, 80% { opacity: 1; transform: translateX(0); }
        }
    `;
    document.head.appendChild(style);
    document.body.appendChild(helpDiv);
    
    setTimeout(() => {
        helpDiv.remove();
        style.remove();
    }, 4000);
}
</script>

<?php include 'footer.php'; ?>