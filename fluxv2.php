<?php
// fluxv2.php - Fast & Reliable AI Image Generator
if (isset($_GET['action']) && $_GET['action'] === 'generate') {
    header('Content-Type: application/json');
    
    $prompt = trim($_GET['prompt'] ?? '');
    if (empty($prompt)) {
        echo json_encode(['status' => 'error', 'message' => 'Please enter a prompt.']);
        exit;
    }

    // Direct, ultra-fast Flux model endpoint (Pollinations / Flux Engine)
    $seed = rand(100000, 999999);
    $imageUrl = "https://image.pollinations.ai/prompt/" . urlencode($prompt) . "?model=flux&seed=" . $seed . "&width=1024&height=1024&nologo=true";

    // Fetch binary data directly to proxy and serve as Base64 (Bypasses CORS & cross-site block)
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $imageUrl);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_TIMEOUT, 20);
    curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64)');

    $imageData = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($httpCode === 200 && !empty($imageData)) {
        $base64 = 'data:image/jpeg;base64,' . base64_encode($imageData);
        echo json_encode([
            'status' => 'success',
            'image' => $base64
        ]);
    } else {
        echo json_encode([
            'status' => 'error',
            'message' => 'Failed to reach image generator server. Try again in a moment.'
        ]);
    }
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="monetag" content="ddbc39aa345ccc34fc5d2dce63768928">
    <title>FLUX AI Image Generator | Luxury Studio Web</title>
    <link rel="icon" type="image/png" href="https://files.catbox.moe/a6xpxg.png">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --bg-gradient: linear-gradient(135deg, #0d0e15 0%, #1a1c29 100%);
            --card-bg: rgba(255, 255, 255, 0.03);
            --card-border: rgba(212, 175, 55, 0.2);
            --accent-glow: linear-gradient(135deg, #BF953F, #FCF6BA, #B38728);
            --text-primary: #f8fafc;
            --text-secondary: #94a3b8;
        }

        * { box-sizing: border-box; margin: 0; padding: 0; font-family: 'Plus Jakarta Sans', sans-serif; }

        body {
            background: var(--bg-gradient);
            color: var(--text-primary);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .generator-container {
            width: 100%;
            max-width: 800px;
            background: var(--card-bg);
            backdrop-filter: blur(20px);
            border: 1px solid var(--card-border);
            border-radius: 24px;
            overflow: hidden;
            box-shadow: 0 20px 50px rgba(0,0,0,0.7);
        }

        .header {
            padding: 20px 28px;
            background: rgba(0,0,0,0.4);
            border-bottom: 1px solid var(--card-border);
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .brand { display: flex; align-items: center; gap: 14px; }
        .avatar-icon {
            width: 44px; height: 44px; border-radius: 14px;
            background: var(--accent-glow);
            display: flex; align-items: center; justify-content: center;
            font-size: 20px; color: #000;
        }

        .content-body { padding: 30px 28px; display: flex; flex-direction: column; gap: 24px; }

        .input-group {
            display: flex; gap: 12px; background: rgba(255,255,255,0.08);
            padding: 10px 14px; border-radius: 16px; border: 1px solid var(--card-border);
        }

        .input-group input {
            flex: 1; background: transparent; border: none; outline: none;
            color: #fff; font-size: 0.95rem;
        }

        .generate-btn {
            background: var(--accent-glow); border: none; color: #000;
            padding: 12px 24px; border-radius: 12px; cursor: pointer;
            font-weight: 700; transition: transform 0.2s;
        }

        .generate-btn:disabled { opacity: 0.5; cursor: not-allowed; }

        .image-preview-box {
            width: 100%; min-height: 350px; background: rgba(0,0,0,0.3);
            border: 1px dashed var(--card-border); border-radius: 16px;
            display: flex; flex-direction: column; align-items: center; justify-content: center;
            padding: 16px; text-align: center;
        }

        .result-image { max-width: 100%; max-height: 500px; border-radius: 12px; display: none; }
        .spinner { display: none; flex-direction: column; align-items: center; gap: 12px; color: #D4AF37; }
        .spinner i { font-size: 2rem; animation: spin 1s linear infinite; }
        @keyframes spin { 100% { transform: rotate(360deg); } }

        .error-box {
            display: none; color: #ef4444; background: rgba(239, 68, 68, 0.1);
            padding: 12px 18px; border-radius: 10px; border: 1px solid rgba(239, 68, 68, 0.3);
            font-size: 0.9rem; margin-top: 10px; width: 100%; text-align: center;
        }

        .image-actions { display: none; justify-content: center; margin-top: 12px; }
        .download-btn {
            background: rgba(255,255,255,0.1); color: #fff; border: 1px solid var(--card-border);
            padding: 10px 20px; border-radius: 10px; text-decoration: none; font-size: 0.9rem;
            cursor: pointer;
        }
    </style>
</head>
<body>

    <div class="generator-container">
        <div class="header">
            <div class="brand">
                <div class="avatar-icon"><i class="fa-solid fa-wand-magic-sparkles"></i></div>
                <div>
                    <h2>FLUX Generator</h2>
                    <p style="font-size:0.8rem; color:var(--text-secondary);">Luxury Studio Web</p>
                </div>
            </div>
            <a href="https://luxurystudioweb.com/tools.php" style="color:var(--text-secondary); text-decoration:none;"><i class="fa-solid fa-arrow-left"></i> Back</a>
        </div>

        <div class="content-body">
            <form id="generateForm" class="input-group">
                <input type="text" id="promptInput" placeholder="Describe your image prompt..." required>
                <button type="submit" class="generate-btn" id="generateBtn">Generate</button>
            </form>

            <div class="image-preview-box">
                <div id="placeholder" style="color:var(--text-secondary);">
                    <i class="fa-solid fa-image" style="font-size:2.5rem; color:#D4AF37;"></i>
                    <p style="margin-top:8px;">Your generated image will appear here</p>
                </div>

                <div class="spinner" id="spinner">
                    <i class="fa-solid fa-circle-notch"></i>
                    <p>Generating artwork with FLUX AI...</p>
                </div>

                <img src="" alt="Generated Artwork" class="result-image" id="resultImg">
                <div class="error-box" id="errorBox"></div>
            </div>

            <div class="image-actions" id="imageActions">
                <a href="#" download="flux-artwork.jpg" id="downloadBtn" class="download-btn">
                    <i class="fa-solid fa-download"></i> Download Artwork
                </a>
            </div>
        </div>
    </div>

    <script>
        const form = document.getElementById('generateForm');
        const promptInput = document.getElementById('promptInput');
        const generateBtn = document.getElementById('generateBtn');
        const placeholder = document.getElementById('placeholder');
        const spinner = document.getElementById('spinner');
        const resultImg = document.getElementById('resultImg');
        const errorBox = document.getElementById('errorBox');
        const imageActions = document.getElementById('imageActions');
        const downloadBtn = document.getElementById('downloadBtn');

        let lastAdTime = 0;
        const AD_COOLDOWN = 30000; // 30 seconds
        const MONETAG_DIRECT_LINK = "https://omg10.com/4/11541646";

        downloadBtn.addEventListener('click', function() {
            const now = Date.now();
            if (now - lastAdTime >= AD_COOLDOWN) {
                lastAdTime = now;
                window.open(MONETAG_DIRECT_LINK, '_blank');
            }
        });

        form.addEventListener('submit', async function(e) {
            e.preventDefault();
            const prompt = promptInput.value.trim();
            if(!prompt) return;

            generateBtn.disabled = true;
            placeholder.style.display = 'none';
            resultImg.style.display = 'none';
            errorBox.style.display = 'none';
            imageActions.style.display = 'none';
            spinner.style.display = 'flex';

            try {
                const res = await fetch('fluxv2.php?action=generate&prompt=' + encodeURIComponent(prompt));
                const data = await res.json();

                if(data.status === 'success' && data.image) {
                    resultImg.src = data.image;
                    downloadBtn.href = data.image;
                    
                    spinner.style.display = 'none';
                    resultImg.style.display = 'block';
                    imageActions.style.display = 'flex';
                } else {
                    throw new Error(data.message || 'Generation failed.');
                }
            } catch(err) {
                spinner.style.display = 'none';
                errorBox.textContent = err.message;
                errorBox.style.display = 'block';
            } finally {
                generateBtn.disabled = false;
            }
        });
    </script>
</body>
</html>