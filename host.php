<?php
// LUXURY HOST - Multi-file Web Hosting Platform
session_start();

// Admin Configuration
define('ADMIN_EMAIL', 'minatotechx@gmail.com');
define('ADMIN_PASSWORD', 'Whitexminato5151@');

$is_admin = isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true;

// Handle Admin Authentication
if (isset($_POST['action']) && $_POST['action'] === 'login') {
    $email = trim($_POST['admin_email'] ?? '');
    $password = $_POST['admin_password'] ?? '';

    if ($email === ADMIN_EMAIL && $password === ADMIN_PASSWORD) {
        $_SESSION['admin_logged_in'] = true;
        $_SESSION['success'] = "Executive Clearance Verified. Welcome back, Administrator.";
    } else {
        $_SESSION['error'] = "Authentication Failed: Invalid credentials.";
    }
    header("Location: host.php");
    exit;
}

// Handle Admin Logout
if (isset($_GET['action']) && $_GET['action'] === 'logout') {
    unset($_SESSION['admin_logged_in']);
    $_SESSION['success'] = "Executive session closed securely.";
    header("Location: host.php");
    exit;
}

// Handle Admin Delete Project Request
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'delete_site') {
    if (!$is_admin) {
        $_SESSION['error'] = "Unauthorized operation attempt.";
        header("Location: host.php");
        exit;
    }

    $target = trim($_POST['target_filename'] ?? '');

    if (empty($target) || strpos($target, '..') !== false || strpos($target, '/') !== false || strpos($target, '\\') !== false) {
        $_SESSION['error'] = "Invalid deployment signature.";
    } else {
        $path = 'sites/' . $target;
        if (file_exists($path)) {
            if (is_dir($path)) {
                deleteDirectory($path);
                $_SESSION['success'] = "Deployment '$target' permanently purged.";
            } else {
                unlink($path);
                $_SESSION['success'] = "Deployment file '$target' removed.";
            }
        } else {
            $_SESSION['error'] = "Target deployment does not exist.";
        }
    }
    header("Location: host.php");
    exit;
}

// Handle Multi-file Project Creation
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'create_project') {
    $project_name = cleanCustomUrl($_POST['project_name'] ?? '');
    $filenames = $_POST['filename'] ?? [];
    $contents = $_POST['file_content'] ?? [];

    if (empty($project_name)) {
        $_SESSION['error'] = "Please provide a valid suite identifier.";
        header("Location: host.php");
        exit;
    }

    $project_dir = 'sites/' . $project_name;

    if (file_exists($project_dir)) {
        $_SESSION['error'] = "The suite signature '$project_name' is already reserved.";
        header("Location: host.php");
        exit;
    }

    // Validate filenames and extensions (Only HTML and CSS allowed)
    $has_index = false;
    $valid_files = [];

    foreach ($filenames as $index => $filename) {
        $filename = trim($filename);
        if (empty($filename)) continue;

        // Force lowercase and remove dangerous characters
        $filename = preg_replace('/[^a-zA-Z0-9._-]/', '', $filename);
        $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));

        if (!in_array($ext, ['html', 'css'])) {
            $_SESSION['error'] = "Security Violation: Only .html and .css assets are allowed! Rejected: $filename";
            header("Location: host.php");
            exit;
        }

        if ($filename === 'index.html') {
            $has_index = true;
        }

        $valid_files[] = [
            'name' => $filename,
            'content' => $contents[$index] ?? ''
        ];
    }

    if (!$has_index) {
        $_SESSION['error'] = "Compliance Error: Workspace must contain an 'index.html' entry file.";
        header("Location: host.php");
        exit;
    }

    // Initialize environment directory if missing
    if (!is_dir('sites')) {
        mkdir('sites', 0777, true);
    }

    // Create custom repository workspace
    mkdir($project_dir, 0777, true);

    // Save files into workspace
    foreach ($valid_files as $file) {
        file_put_contents($project_dir . '/' . $file['name'], $file['content']);
    }

    $_SESSION['success'] = "Luxury Suite '$project_name' compiled and provisioned successfully!";
    $_SESSION['file_url'] = getWebsiteUrl($project_name);
    header("Location: host.php");
    exit;
}

// Recursive directory cleaner
function deleteDirectory($dir) {
    if (!file_exists($dir)) return true;
    if (!is_dir($dir)) return unlink($dir);
    foreach (scandir($dir) as $item) {
        if ($item == '.' || $item == '..') continue;
        if (!deleteDirectory($dir . DIRECTORY_SEPARATOR . $item)) return false;
    }
    return rmdir($dir);
}

function cleanCustomUrl($url) {
    $url = preg_replace('/[^a-z0-9-]/', '', strtolower($url));
    $url = preg_replace('/-+/', '-', $url);
    return trim($url, '-');
}

function getWebsiteUrl($path) {
    $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' ? 'https' : 'http';
    $host = $_SERVER['HTTP_HOST'];
    $base_url = $protocol . '://' . $host . dirname($_SERVER['PHP_SELF']);
    return rtrim($base_url, '/') . '/view.php?site=' . $path;
}

// Fetch compiled project structures
$deployed_projects = [];
if (is_dir('sites')) {
    foreach (scandir('sites') as $folder) {
        if ($folder !== '.' && $folder !== '..') {
            $path = 'sites/' . $folder;
            if (is_dir($path)) {
                // Count files inside workspace
                $all_files = array_diff(scandir($path), ['.', '..']);
                $file_count = count($all_files);
                
                $total_size = 0;
                foreach ($all_files as $f) {
                    $total_size += filesize($path . '/' . $f);
                }

                $deployed_projects[] = [
                    'name' => $folder,
                    'url' => getWebsiteUrl($folder),
                    'files_count' => $file_count,
                    'size' => $total_size,
                    'time' => filemtime($path)
                ];
            }
        }
    }
    usort($deployed_projects, function($a, $b) { return $b['time'] - $a['time']; });
}

function formatSize($bytes) {
    if ($bytes == 0) return '0 B';
    $units = ['B', 'KB', 'MB'];
    $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
    return round($bytes / pow(1024, $pow), 2) . ' ' . $units[$pow];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LUXURY HOST — Executive Multi-Page Hosting</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@500;700&family=Plus+Jakarta+Sans:wght@300;400;500;600;700&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet">
    <style>
        :root {
            --bg-primary: #090a0f;
            --bg-card: #12151e;
            --bg-input: #0b0d14;
            --gold-primary: #d4af37;
            --gold-hover: #f3e5ab;
            --gold-gradient: linear-gradient(135deg, #bf953f 0%, #fcf6ba 25%, #b38728 50%, #fbf5b7 75%, #aa771c 100%);
            --border-color: rgba(212, 175, 55, 0.2);
            --border-hover: rgba(212, 175, 55, 0.5);
            --text-primary: #f0f4f8;
            --text-muted: #8a94a6;
            --danger: #e63946;
            --success: #2a9d8f;
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { 
            background-color: var(--bg-primary); 
            color: var(--text-primary); 
            font-family: 'Plus Jakarta Sans', sans-serif; 
            padding: 40px 20px;
            min-height: 100vh;
            background-image: radial-gradient(circle at 50% 0%, rgba(212, 175, 55, 0.08) 0%, transparent 70%);
        }

        .container { max-width: 1240px; margin: 0 auto; }

        /* Header Styling */
        .header { 
            background: var(--bg-card); 
            padding: 40px; 
            border-radius: 16px; 
            border: 1px solid var(--border-color); 
            margin-bottom: 30px; 
            position: relative; 
            text-align: center;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.4);
            backdrop-filter: blur(10px);
        }
        .header h1 { 
            font-family: 'Cinzel', serif;
            background: var(--gold-gradient);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            font-size: 2.6rem; 
            font-weight: 700; 
            letter-spacing: 2px; 
            display: flex; 
            align-items: center; 
            justify-content: center; 
            gap: 16px; 
        }
        .header p { color: var(--text-muted); margin-top: 10px; font-size: 1rem; font-weight: 300; letter-spacing: 0.5px; }

        .admin-badge { 
            position: absolute; 
            top: 20px; 
            right: 20px; 
            background: rgba(230, 57, 70, 0.15); 
            color: #ff6b6b; 
            border: 1px solid rgba(230, 57, 70, 0.4);
            padding: 6px 16px; 
            border-radius: 30px; 
            font-size: 0.75rem; 
            font-weight: 700; 
            letter-spacing: 1px;
        }
        .admin-badge a { color: #ff6b6b; text-decoration: none; margin-left: 8px; border-bottom: 1px dotted; }

        /* Layout Grid */
        .layout { display: grid; grid-template-columns: 7fr 5fr; gap: 30px; }
        @media (max-width: 960px) { .layout { grid-template-columns: 1fr; } }

        /* Panels & Cards */
        .panel { 
            background: var(--bg-card); 
            border: 1px solid var(--border-color); 
            border-radius: 16px; 
            padding: 30px; 
            margin-bottom: 30px; 
            box-shadow: 0 15px 30px rgba(0,0,0,0.3);
        }
        .panel-title { 
            font-family: 'Cinzel', serif;
            font-size: 1.25rem; 
            color: var(--gold-primary); 
            margin-bottom: 24px; 
            display: flex; 
            align-items: center; 
            gap: 12px; 
            border-bottom: 1px solid var(--border-color); 
            padding-bottom: 14px; 
            letter-spacing: 1px;
        }

        .form-group { margin-bottom: 22px; }
        label { display: block; color: var(--gold-primary); font-size: 0.8rem; font-weight: 600; margin-bottom: 8px; letter-spacing: 1px; }
        
        input[type="text"], input[type="email"], input[type="password"], textarea { 
            width: 100%; 
            background: var(--bg-input); 
            border: 1px solid var(--border-color); 
            border-radius: 8px; 
            padding: 12px 16px; 
            color: var(--text-primary); 
            font-size: 0.95rem; 
            transition: all 0.3s ease;
        }
        input:focus, textarea:focus { 
            outline: none; 
            border-color: var(--gold-primary); 
            box-shadow: 0 0 12px rgba(212, 175, 55, 0.2); 
        }

        /* Module Entry Cards */
        .file-entry { 
            background: rgba(11, 13, 20, 0.7); 
            border: 1px solid var(--border-color); 
            border-radius: 12px; 
            padding: 20px; 
            margin-bottom: 18px; 
        }
        .file-header-row { display: flex; gap: 12px; margin-bottom: 12px; align-items: center; }
        .code-textarea { 
            font-family: 'JetBrains Mono', monospace; 
            min-height: 180px; 
            font-size: 0.88rem; 
            resize: vertical; 
            line-height: 1.5;
        }

        /* Buttons */
        .btn { 
            display: inline-flex; 
            align-items: center; 
            justify-content: center; 
            gap: 10px; 
            padding: 12px 22px; 
            font-size: 0.85rem; 
            font-weight: 600; 
            border-radius: 8px; 
            cursor: pointer; 
            border: 1px solid transparent; 
            text-decoration: none; 
            transition: all 0.3s ease;
            letter-spacing: 0.5px;
        }
        .btn-primary { 
            background: var(--gold-gradient); 
            color: #0d0f14; 
            font-weight: 700;
            box-shadow: 0 4px 15px rgba(212, 175, 55, 0.2);
        }
        .btn-primary:hover { 
            filter: brightness(1.15); 
            transform: translateY(-1px);
        }
        .btn-secondary { 
            background: rgba(255, 255, 255, 0.03); 
            color: var(--text-primary); 
            border-color: var(--border-color); 
        }
        .btn-secondary:hover { 
            background: rgba(212, 175, 55, 0.1); 
            border-color: var(--gold-primary); 
            color: var(--gold-primary);
        }
        .btn-danger { 
            background: rgba(230, 57, 70, 0.15); 
            color: #ff6b6b; 
            border: 1px solid rgba(230, 57, 70, 0.4);
        }
        .btn-danger:hover { 
            background: var(--danger); 
            color: #fff; 
        }
        
        .remove-file-btn { 
            background: none; 
            border: none; 
            color: #ff6b6b; 
            cursor: pointer; 
            font-size: 0.85rem; 
            display: flex; 
            align-items: center; 
            gap: 6px; 
            margin-left: auto; 
            transition: 0.2s;
        }
        .remove-file-btn:hover { color: #e63946; }

        /* Project Items */
        .project-item { 
            display: flex; 
            align-items: center; 
            justify-content: space-between; 
            padding: 18px; 
            border: 1px solid var(--border-color); 
            background: var(--bg-input); 
            border-radius: 10px; 
            margin-bottom: 14px; 
            transition: 0.3s;
        }
        .project-item:hover {
            border-color: var(--gold-primary);
            box-shadow: 0 4px 12px rgba(0,0,0,0.3);
        }
        .project-meta { font-size: 0.78rem; color: var(--text-muted); margin-top: 6px; display: flex; gap: 14px; }
        .project-name-link { color: var(--gold-primary); font-weight: 600; text-decoration: none; font-size: 1rem; }
        .project-name-link:hover { color: var(--gold-hover); text-decoration: underline; }

        /* Messages & Badges */
        .msg { padding: 18px; border-radius: 10px; margin-bottom: 24px; font-size: 0.95rem; line-height: 1.5; }
        .msg-success { background: rgba(42, 157, 143, 0.15); border: 1px solid var(--success); color: #70e0d0; }
        .msg-error { background: rgba(230, 57, 70, 0.15); border: 1px solid var(--danger); color: #ff8585; }

        .repo-badge { 
            background: rgba(212, 175, 55, 0.1); 
            color: var(--gold-primary); 
            font-size: 0.7rem; 
            padding: 4px 10px; 
            border-radius: 20px; 
            border: 1px solid var(--border-color); 
            font-weight: 700; 
            letter-spacing: 0.5px;
        }
        .rules-callout { 
            background: rgba(212, 175, 55, 0.05); 
            border: 1px solid var(--border-color); 
            border-radius: 10px; 
            padding: 16px; 
            margin-bottom: 20px; 
            font-size: 0.85rem; 
            color: #e2c068; 
            line-height: 1.5;
        }

        .toast { 
            position: fixed; 
            bottom: 30px; 
            right: 30px; 
            background: var(--gold-gradient); 
            color: #0b0d14; 
            font-weight: 700; 
            padding: 14px 28px; 
            border-radius: 8px; 
            display: none; 
            z-index: 9999; 
            box-shadow: 0 10px 25px rgba(0,0,0,0.5);
        }
        footer { 
            text-align: center; 
            color: var(--text-muted); 
            margin-top: 50px; 
            font-size: 0.85rem; 
            border-top: 1px solid var(--border-color); 
            padding-top: 25px; 
            letter-spacing: 1px;
        }
    </style>
</head>
<body>

    <div class="toast" id="toast"><i class="fas fa-check-circle"></i> URL Copied to Clipboard</div>

    <div class="container">
        <div class="header">
            <?php if ($is_admin): ?>
                <div class="admin-badge"><i class="fas fa-crown"></i> EXECUTIVE CLEARANCE | <a href="host.php?action=logout">LOGOUT</a></div>
            <?php endif; ?>
            <h1><i class="fas fa-gem"></i> LUXURY HOST</h1>
            <p>Deploy multi-page interactive web suites inside elite cloud workspaces.</p>
        </div>

        <?php if (isset($_SESSION['success'])): ?>
            <div class="msg msg-success">
                <strong>Deployment Provisioned</strong><br><?php echo $_SESSION['success']; ?>
                <?php if (isset($_SESSION['file_url'])): ?>
                    <div style="margin-top: 14px; display: flex; gap: 10px;">
                        <a href="<?php echo $_SESSION['file_url']; ?>" target="_blank" class="btn btn-primary" style="padding: 8px 16px;"><i class="fas fa-external-link-alt"></i> Access Suite</a>
                        <button onclick="copyRaw('<?php echo $_SESSION['file_url']; ?>')" class="btn btn-secondary" style="padding: 8px 16px;"><i class="fas fa-copy"></i> Copy Direct Link</button>
                    </div>
                <?php endif; ?>
                <?php unset($_SESSION['success'], $_SESSION['file_url']); ?>
            </div>
        <?php endif; ?>

        <?php if (isset($_SESSION['error'])): ?>
            <div class="msg msg-error">
                <strong>System Exception:</strong> <?php echo $_SESSION['error']; ?>
                <?php unset($_SESSION['error']); ?>
            </div>
        <?php endif; ?>

        <div class="layout">
            <div>
                <div class="panel">
                    <div class="panel-title"><i class="fas fa-layer-group"></i> Initialize Luxury Workspace</div>
                    
                    <div class="rules-callout">
                        <i class="fas fa-shield-alt"></i> <strong>Deployment Standard:</strong> Only <code>.html</code> and <code>.css</code> files are approved. Your suite <strong>MUST</strong> include an <code>index.html</code> entry file to enable hosting execution.
                    </div>

                    <form method="POST" id="repo-form">
                        <input type="hidden" name="action" value="create_project">
                        
                        <div class="form-group">
                            <label>SUITE / PROJECT IDENTIFIER</label>
                            <input type="text" name="project_name" placeholder="luxury-portfolio-v1" required>
                        </div>

                        <div id="files-container">
                            <div class="file-entry" id="default-index-file">
                                <div class="file-header-row">
                                    <div style="width: 240px;">
                                        <input type="text" name="filename[]" value="index.html" readonly style="background: rgba(212,175,55,0.05); cursor: not-allowed; font-weight: bold; color: var(--gold-primary);">
                                    </div>
                                    <span class="repo-badge">ENTRY POINT REQUIRED</span>
                                </div>
                                <textarea name="file_content[]" class="code-textarea" placeholder="" required><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Luxury Web Workspace</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h1>✨ Luxury Suite Online</h1>
    <p>Welcome to your customized LUXURY HOST environment.</p>
    <a href="about.html">Explore Platform →</a>
</body>
</html></textarea>
                            </div>
                        </div>

                        <div style="display: flex; gap: 14px; margin-top: 24px;">
                            <button type="button" class="btn btn-secondary" onclick="addNewFileEntry()"><i class="fas fa-plus"></i> Add Module File</button>
                            <button type="submit" class="btn btn-primary" style="margin-left: auto;"><i class="fas fa-paper-plane"></i> Deploy Suite</button>
                        </div>
                    </form>
                </div>
            </div>

            <div>
                <div class="panel">
                    <div class="panel-title"><i class="fas fa-server"></i> Active Workspaces</div>
                    
                    <div style="max-height: 480px; overflow-y: auto; padding-right: 4px;">
                        <?php if (!empty($deployed_projects)): ?>
                            <?php foreach ($deployed_projects as $project): ?>
                                <div class="project-item">
                                    <div>
                                        <a href="<?php echo $project['url']; ?>" target="_blank" class="project-name-link">
                                            <i class="fas fa-folder-open" style="margin-right: 6px;"></i> <?php echo htmlspecialchars($project['name']); ?>
                                        </a>
                                        <div class="project-meta">
                                            <span><i class="fas fa-file-code"></i> <?php echo $project['files_count']; ?> modules</span>
                                            <span><i class="fas fa-database"></i> <?php echo formatSize($project['size']); ?></span>
                                        </div>
                                    </div>

                                    <div style="display: flex; gap: 8px;">
                                        <?php if ($is_admin): ?>
                                            <form method="POST" style="margin: 0;" onsubmit="return confirm('Confirm permanent deletion of this luxury workspace?');">
                                                <input type="hidden" name="action" value="delete_site">
                                                <input type="hidden" name="target_filename" value="<?php echo htmlspecialchars($project['name']); ?>">
                                                <button type="submit" class="btn btn-danger" style="padding: 8px 12px;" title="Purge Workspace">
                                                    <i class="fas fa-trash-alt"></i>
                                                </button>
                                            </form>
                                        <?php endif; ?>
                                        <button onclick="copyRaw('<?php echo addslashes($project['url']); ?>')" class="btn btn-secondary" style="padding: 8px 12px;" title="Copy Link">
                                            <i class="fas fa-copy"></i>
                                        </button>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <div style="text-align: center; padding: 50px 0; color: var(--text-muted);">
                                <i class="fas fa-box-open" style="font-size: 2.8rem; margin-bottom: 14px; opacity: 0.3;"></i>
                                <p>No deployed workspaces currently active.</p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <?php if (!$is_admin): ?>
                    <div class="panel">
                        <div class="panel-title"><i class="fas fa-lock"></i> Executive Clearance</div>
                        <form method="POST">
                            <input type="hidden" name="action" value="login">
                            <div class="form-group">
                                <label>ADMINISTRATOR EMAIL</label>
                                <input type="email" name="admin_email" required placeholder="admin@domain.com">
                            </div>
                            <div class="form-group">
                                <label>AUTHENTICATION PASSKEY</label>
                                <input type="password" name="admin_password" required placeholder="••••••••••••">
                            </div>
                            <button type="submit" class="btn btn-primary" style="width: 100%;"><i class="fas fa-key"></i> Authenticate Access</button>
                        </form>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <footer>
            <p>ENGINEERED BY <strong>LUXURY HOST PLATFORM</strong></p>
        </footer>
    </div>

    <script>
        // Inject new virtual file modules into workspace dynamically
        function addNewFileEntry() {
            const container = document.getElementById('files-container');
            const fileId = 'file_' + Date.now();
            
            const fileHtml = `
                <div class="file-entry" id="${fileId}">
                    <div class="file-header-row">
                        <div style="width: 250px;">
                            <input type="text" name="filename[]" placeholder="style.css or page.html" required 
                                   onchange="validateFilenameExtension(this)">
                        </div>
                        <button type="button" class="remove-file-btn" onclick="document.getElementById('${fileId}').remove()">
                            <i class="fas fa-trash-alt"></i> Remove Module
                        </button>
                    </div>
                    <textarea name="file_content[]" class="code-textarea" placeholder="/* Enter code content here */" required></textarea>
                </div>
            `;
            container.insertAdjacentHTML('beforeend', fileHtml);
        }

        // Strict client-side sandbox validation
        function validateFilenameExtension(input) {
            const val = input.value.trim().toLowerCase();
            const ext = val.split('.').pop();
            
            if (ext !== 'html' && ext !== 'css') {
                alert('Workspace Validation Failure:\nOnly explicit .html and .css file types are allowed.');
                input.value = '';
                input.focus();
            }
        }

        function copyRaw(text) {
            navigator.clipboard.writeText(text).then(() => {
                const toast = document.getElementById('toast');
                toast.style.display = 'block';
                setTimeout(() => { toast.style.display = 'none'; }, 2000);
            });
        }
    </script>
</body>
</html>