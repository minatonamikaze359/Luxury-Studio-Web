<?php
// view.php - Strategic Runtime Server Routing Engine for Multi-file Projects
session_start();

$site_name = isset($_GET['site']) ? trim($_GET['site']) : '';

if (empty($site_name)) {
    header("Location: index.php");
    exit;
}

// Strict Defense Isolation Check against Directory Traversal attacks
if (strpos($site_name, '..') !== false || strpos($site_name, '/') !== false || strpos($site_name, '\\') !== false) {
    http_response_code(403);
    showTerminalError("Access Blocked - Directory structural tampering signatures captured.");
    exit;
}

$project_dir = 'sites/' . $site_name;

if (!is_dir($project_dir)) {
    http_response_code(404);
    showTerminalError("Workspace Missing - The requested project space has not been compiled or has been expunged.");
    exit;
}

// Determine routing path: Serve internal multi-page files or fallback to default index.html
$sub_file = isset($_GET['file']) ? trim($_GET['file']) : 'index.html';

// Sanitize requested asset path
$sub_file = preg_replace('/[^a-zA-Z0-9._-]/', '', $sub_file);

if (empty($sub_file)) {
    $sub_file = 'index.html';
}

$target_file_path = $project_dir . '/' . $sub_file;

if (!file_exists($target_file_path)) {
    http_response_code(404);
    showTerminalError("Asset Module Not Found: The entry point '$sub_file' was not generated within this multi-file environment.");
    exit;
}

$file_ext = strtolower(pathinfo($target_file_path, PATHINFO_EXTENSION));

// Explicit content types matching configuration rule
$mimes = [
    'html' => 'text/html; charset=UTF-8',
    'htm' => 'text/html; charset=UTF-8',
    'css' => 'text/css; charset=UTF-8'
];

if (isset($mimes[$file_ext])) {
    header('Content-Type: ' . $mimes[$file_ext]);
} else {
    header('Content-Type: text/plain; charset=UTF-8');
}

// Intercept and rewrite asset link layers dynamically inside HTML to preserve internal page navigation structures automatically
if ($file_ext === 'html' || $file_ext === 'htm') {
    $content = file_get_contents($target_file_path);
    
    // Automatically capture relative standard targets (href="about.html", href="style.css") and dynamically adapt link matrix to handle sub-routing
    $content = preg_replace_callback('/(href|src)=["\']([^"\']+)["\']/', function($matches) use ($site_name) {
        $attr = $matches[1];
        $url = $matches[2];
        
        // Ignore remote web links and absolute protocols
        if (preg_match('/^(http|https|ftp|mailto|javascript|#):/i', $url) || strpos($url, '//') === 0 || $url[0] === '#') {
            return $matches[0];
        }
        
        return $attr . '="view.php?site=' . $site_name . '&file=' . urlencode($url) . '"';
    }, $content);
    
    echo $content;
} else {
    // Stream raw CSS components immediately
    readfile($target_file_path);
}
exit;

function showTerminalError($message) {
    echo "<!DOCTYPE html>
    <html>
    <head>
        <title>Terminal System Error</title>
        <style>
            body { font-family: monospace; background: #0d1117; color: #ff7b72; padding: 50px; text-align: center; }
            .box { max-width: 650px; margin: 0 auto; background: #161b22; border: 1px solid #30363d; border-radius: 8px; padding: 30px; box-shadow: 0 4px 12px rgba(0,0,0,0.3); }
            h2 { font-size: 1.4rem; margin-bottom: 15px; }
            p { color: #8b949e; line-height: 1.5; font-size: 0.95rem; margin-bottom: 25px; }
            .btn { background: #21262d; border: 1px solid #30363d; padding: 8px 16px; color: #58a6ff; font-weight: bold; border-radius: 6px; text-decoration: none; font-size: 0.85rem; }
            .btn:hover { background: #30363d; }
        </style>
    </head>
    <body>
        <div class='box'>
            <h2>⚠️ [SYSTEM FAULT ALERT]</h2>
            <p>$message</p>
            <a href='index.php' class='btn'>⚡ RETURN TO SOURCE ROOT</a>
        </div>
    </body>
    </html>";
}
?>