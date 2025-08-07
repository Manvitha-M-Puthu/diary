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
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h3 class="mb-0">
                        <i class="bi bi-plus-circle"></i> Create New Entry
                    </h3>
                    <p class="mb-0 text-white-50">Capture your thoughts and memories</p>
                </div>
                <div class="card-body">
                    <?php if ($msg): ?>
                    <div class="alert alert-danger d-flex align-items-center" role="alert">
                        <i class="bi bi-exclamation-triangle me-2"></i>
                        <?php echo $msg; ?>
                    </div>
                    <?php endif; ?>

                    <form method="post" id="entryForm">
                        <div class="mb-4">
                            <label for="title" class="form-label">
                                <i class="bi bi-type"></i> Title
                            </label>
                            <input name="title" 
                                   id="title"
                                   class="form-control form-control-lg" 
                                   placeholder="Give your entry a memorable title..."
                                   required
                                   maxlength="255">
                            <div class="form-text">
                                <i class="bi bi-lightbulb"></i> 
                                Try something descriptive like "My Trip to Paris" or "Thoughts on Today"
                            </div>
                        </div>

                        <div class="mb-4">
                            <label for="entry_date" class="form-label">
                                <i class="bi bi-calendar-date"></i> Entry Date
                            </label>
                            <input name="entry_date" 
                                   type="date" 
                                   id="entry_date"
                                   class="form-control" 
                                   required
                                   value="<?php echo date('Y-m-d'); ?>"
                                   max="<?php echo date('Y-m-d'); ?>">
                            <div class="form-text">
                                <i class="bi bi-info-circle"></i> 
                                When did this happen? You can backdate entries if needed.
                            </div>
                        </div>

                        <div class="mb-4">
                            <label for="content" class="form-label">
                                <i class="bi bi-journal-text"></i> Your Story
                            </label>
                            <textarea name="content" 
                                      id="content"
                                      class="form-control" 
                                      rows="12"
                                      placeholder="Start writing your story here... What happened today? How did it make you feel? What are you grateful for?"
                                      required></textarea>
                            <div class="form-text d-flex justify-content-between">
                                <span>
                                    <i class="bi bi-chat-quote"></i> 
                                    Express yourself freely - this is your safe space
                                </span>
                                <span id="charCount" class="text-muted">0 characters</span>
                            </div>
                        </div>

                        <!-- Mood Selector (Optional Enhancement) -->
                        <div class="mb-4">
                            <label class="form-label">
                                <i class="bi bi-emoji-smile"></i> How are you feeling? (Optional)
                            </label>
                            <div class="d-flex gap-2 flex-wrap">
                                <input type="radio" class="btn-check" name="mood" id="happy" value="happy">
                                <label class="btn btn-outline-success" for="happy">😊 Happy</label>

                                <input type="radio" class="btn-check" name="mood" id="excited" value="excited">
                                <label class="btn btn-outline-warning" for="excited">🤩 Excited</label>

                                <input type="radio" class="btn-check" name="mood" id="calm" value="calm">
                                <label class="btn btn-outline-info" for="calm">😌 Calm</label>

                                <input type="radio" class="btn-check" name="mood" id="thoughtful" value="thoughtful">
                                <label class="btn btn-outline-secondary" for="thoughtful">🤔 Thoughtful</label>

                                <input type="radio" class="btn-check" name="mood" id="sad" value="sad">
                                <label class="btn btn-outline-primary" for="sad">😢 Sad</label>

                                <input type="radio" class="btn-check" name="mood" id="stressed" value="stressed">
                                <label class="btn btn-outline-danger" for="stressed">😰 Stressed</label>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between align-items-center">
                            <a href="dashboard.php" class="btn btn-outline-secondary">
                                <i class="bi bi-arrow-left"></i> Cancel
                            </a>
                            
                            <div class="d-flex gap-2">
                                <button type="button" class="btn btn-outline-primary" onclick="saveDraft()">
                                    <i class="bi bi-save"></i> Save Draft
                                </button>
                                <button type="submit" class="btn btn-primary btn-lg">
                                    <i class="bi bi-check-circle"></i> Save Entry
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Writing Tips -->
            <div class="card mt-4" style="background: rgba(255, 255, 255, 0.1); border: none;">
                <div class="card-body text-white">
                    <h5><i class="bi bi-lightbulb"></i> Writing Tips</h5>
                    <div class="row">
                        <div class="col-md-6">
                            <ul class="list-unstyled">
                                <li class="mb-2"><i class="bi bi-check2 text-success"></i> Write about your day's highlights</li>
                                <li class="mb-2"><i class="bi bi-check2 text-success"></i> Include your emotions and feelings</li>
                                <li class="mb-2"><i class="bi bi-check2 text-success"></i> Mention people who made your day special</li>
                            </ul>
                        </div>
                        <div class="col-md-6">
                            <ul class="list-unstyled">
                                <li class="mb-2"><i class="bi bi-check2 text-success"></i> Note any lessons learned</li>
                                <li class="mb-2"><i class="bi bi-check2 text-success"></i> Write about your goals and aspirations</li>
                                <li class="mb-2"><i class="bi bi-check2 text-success"></i> Be honest and authentic</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Character counter
const contentTextarea = document.getElementById('content');
const charCount = document.getElementById('charCount');

contentTextarea.addEventListener('input', function() {
    const count = this.value.length;
    charCount.textContent = count + ' characters';
    
    if (count > 1000) {
        charCount.classList.add('text-success');
        charCount.classList.remove('text-muted');
    } else {
        charCount.classList.remove('text-success');
        charCount.classList.add('text-muted');
    }
});

// Auto-save draft functionality (using memory storage since localStorage not available)
let autoSaveData = {};

function saveDraft() {
    autoSaveData.title = document.getElementById('title').value;
    autoSaveData.content = document.getElementById('content').value;
    autoSaveData.date = document.getElementById('entry_date').value;
    autoSaveData.mood = document.querySelector('input[name="mood"]:checked')?.value || '';
    
    // Show confirmation
    const btn = event.target;
    const originalText = btn.innerHTML;
    btn.innerHTML = '<i class="bi bi-check"></i> Draft Saved!';
    btn.classList.remove('btn-outline-primary');
    btn.classList.add('btn-success');
    
    setTimeout(() => {
        btn.innerHTML = originalText;
        btn.classList.add('btn-outline-primary');
        btn.classList.remove('btn-success');
    }, 2000);
}

// Load draft on page load if available
window.addEventListener('load', function() {
    if (autoSaveData.title) {
        if (confirm('You have an unsaved draft. Would you like to restore it?')) {
            document.getElementById('title').value = autoSaveData.title || '';
            document.getElementById('content').value = autoSaveData.content || '';
            document.getElementById('entry_date').value = autoSaveData.date || '';
            if (autoSaveData.mood) {
                document.getElementById(autoSaveData.mood).checked = true;
            }
        }
    }
});

// Auto-save every 30 seconds
setInterval(() => {
    const title = document.getElementById('title').value;
    const content = document.getElementById('content').value;
    
    if (title.trim() || content.trim()) {
        saveDraft();
    }
}, 30000);

// Form validation
document.getElementById('entryForm').addEventListener('submit', function(e) {
    const title = document.getElementById('title').value.trim();
    const content = document.getElementById('content').value.trim();
    
    if (title.length < 3) {
        e.preventDefault();
        alert('Title must be at least 3 characters long.');
        return;
    }
    
    if (content.length < 10) {
        e.preventDefault();
        alert('Content must be at least 10 characters long.');
        return;
    }
});

// Add writing prompts
const prompts = [
    "What made you smile today?",
    "Describe a moment that surprised you.",
    "What are you grateful for right now?",
    "What challenge did you overcome today?",
    "Who had a positive impact on your day?",
    "What did you learn about yourself today?",
    "Describe the most beautiful thing you saw today.",
    "What are you looking forward to tomorrow?"
];

function addPrompt() {
    const randomPrompt = prompts[Math.floor(Math.random() * prompts.length)];
    const content = document.getElementById('content');
    if (content.value.trim() === '') {
        content.value = randomPrompt + '\n\n';
        content.focus();
    }
}

// Add prompt button
const contentLabel = document.querySelector('label[for="content"]');
const promptBtn = document.createElement('button');
promptBtn.type = 'button';
promptBtn.className = 'btn btn-sm btn-outline-secondary ms-2';
promptBtn.innerHTML = '<i class="bi bi-question-circle"></i> Get Prompt';
promptBtn.onclick = addPrompt;
contentLabel.appendChild(promptBtn);
</script>

<?php include 'footer.php'; ?>