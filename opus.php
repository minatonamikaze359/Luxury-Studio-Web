<?php
// Handle AJAX API request
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'chat') {
    header('Content-Type: application/json');
    
    $userPrompt = trim($_POST['prompt'] ?? '');
    if (empty($userPrompt)) {
        echo json_encode(['status' => 'error', 'message' => 'Prompt cannot be empty.']);
        exit;
    }

    $apiUrl = 'https://apis.davidcyril.name.ng/ai/claude-opus-4.5?prompt=' . urlencode($userPrompt);

    // Fetch response via cURL
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $apiUrl);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 30);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    
    $response = curl_exec($ch);
    $curlError = curl_error($ch);
    curl_close($ch);

    if ($curlError) {
        echo json_encode(['status' => 'error', 'message' => 'Network error: ' . $curlError]);
        exit;
    }

    // Process API output
    $data = json_decode($response, true);
    
    // Specifically extract the 'data' key from the JSON structure
    $aiReply = '';
    if (is_array($data)) {
        $aiReply = $data['data'] ?? $data['result'] ?? $data['response'] ?? $data['message'] ?? $data['content'] ?? 'No response received.';
    } else {
        $aiReply = $response;
    }

    echo json_encode([
        'status' => 'success',
        'reply' => $aiReply
    ]);
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Claude Opus 4.5 AI | Luxury Studio Web</title>
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="https://files.catbox.moe/a6xpxg.png">
    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --bg-gradient: linear-gradient(135deg, #0d0e15 0%, #1a1c29 100%);
            --card-bg: rgba(255, 255, 255, 0.03);
            --card-border: rgba(212, 175, 55, 0.2);
            --accent-glow: linear-gradient(135deg, #BF953F, #FCF6BA, #B38728);
            --user-bubble: linear-gradient(135deg, #BF953F 0%, #AA771C 100%);
            --ai-bubble: rgba(255, 255, 255, 0.06);
            --text-primary: #f8fafc;
            --text-secondary: #94a3b8;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: 'Plus Jakarta Sans', sans-serif;
        }

        body {
            background: var(--bg-gradient);
            color: var(--text-primary);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .chat-container {
            width: 100%;
            max-width: 900px;
            height: 85vh;
            background: var(--card-bg);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid var(--card-border);
            border-radius: 24px;
            display: flex;
            flex-direction: column;
            box-shadow: 0 20px 50px rgba(0, 0, 0, 0.7);
            overflow: hidden;
        }

        /* Header */
        .chat-header {
            padding: 20px 28px;
            background: rgba(0, 0, 0, 0.4);
            border-bottom: 1px solid var(--card-border);
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .chat-header .brand {
            display: flex;
            align-items: center;
            gap: 14px;
        }

        .avatar-icon {
            width: 44px;
            height: 44px;
            border-radius: 14px;
            background: var(--accent-glow);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
            color: #000;
            box-shadow: 0 4px 15px rgba(212, 175, 55, 0.3);
        }

        .brand-info h2 {
            font-size: 1.15rem;
            font-weight: 700;
            color: #ffffff;
            letter-spacing: -0.3px;
        }

        .brand-info p {
            font-size: 0.8rem;
            color: var(--text-secondary);
        }

        .header-actions a {
            color: var(--text-secondary);
            text-decoration: none;
            font-size: 0.9rem;
            padding: 8px 16px;
            border-radius: 10px;
            border: 1px solid var(--card-border);
            transition: all 0.3s ease;
        }

        .header-actions a:hover {
            color: #fff;
            background: rgba(255, 255, 255, 0.08);
            border-color: #D4AF37;
        }

        /* Chat Messages Box */
        .chat-box {
            flex: 1;
            padding: 24px;
            overflow-y: auto;
            display: flex;
            flex-direction: column;
            gap: 18px;
            scroll-behavior: smooth;
        }

        .chat-box::-webkit-scrollbar {
            width: 6px;
        }
        .chat-box::-webkit-scrollbar-thumb {
            background: rgba(212, 175, 55, 0.3);
            border-radius: 10px;
        }

        .message {
            display: flex;
            gap: 12px;
            max-width: 80%;
            animation: fadeIn 0.3s ease-in-out;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .message.user {
            align-self: flex-end;
            flex-direction: row-reverse;
        }

        .message.ai {
            align-self: flex-start;
        }

        .message-icon {
            width: 34px;
            height: 34px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 14px;
            flex-shrink: 0;
        }

        .message.user .message-icon {
            background: rgba(255, 255, 255, 0.15);
            color: #fff;
        }

        .message.ai .message-icon {
            background: var(--accent-glow);
            color: #000;
        }

        .message-content {
            padding: 14px 18px;
            border-radius: 18px;
            font-size: 0.95rem;
            line-height: 1.6;
            white-space: pre-wrap;
            word-break: break-word;
        }

        .message.user .message-content {
            background: var(--user-bubble);
            border-bottom-right-radius: 4px;
            color: #000;
            font-weight: 500;
        }

        .message.ai .message-content {
            background: var(--ai-bubble);
            border: 1px solid var(--card-border);
            border-bottom-left-radius: 4px;
            color: var(--text-primary);
        }

        /* Typing Indicator */
        .typing {
            display: none;
            align-items: center;
            gap: 5px;
            padding: 12px 18px;
            background: var(--ai-bubble);
            border: 1px solid var(--card-border);
            border-radius: 18px;
            border-bottom-left-radius: 4px;
            width: fit-content;
        }

        .typing span {
            width: 7px;
            height: 7px;
            background: #D4AF37;
            border-radius: 50%;
            animation: blink 1.4s infinite ease-in-out both;
        }

        .typing span:nth-child(1) { animation-delay: -0.32s; }
        .typing span:nth-child(2) { animation-delay: -0.16s; }

        @keyframes blink {
            0%, 80%, 100% { opacity: 0.3; transform: scale(0.8); }
            40% { opacity: 1; transform: scale(1.1); }
        }

        /* Input Area Fixes */
        .chat-input-area {
            padding: 18px 24px;
            background: rgba(0, 0, 0, 0.4);
            border-top: 1px solid var(--card-border);
        }

        .input-form {
            display: flex;
            gap: 12px;
            align-items: center;
            background: rgba(255, 255, 255, 0.08);
            padding: 10px 14px;
            border-radius: 16px;
            border: 1px solid var(--card-border);
            transition: border-color 0.3s ease;
        }

        .input-form:focus-within {
            border-color: #D4AF37;
        }

        /* Fixed Textarea Styles for full visibility */
        .input-form textarea {
            flex: 1;
            background: transparent !important;
            border: none !important;
            outline: none !important;
            color: #ffffff !important;
            font-size: 0.95rem;
            line-height: 1.5;
            resize: none;
            max-height: 120px;
            height: 24px;
            padding: 2px 0;
            display: block;
            width: 100%;
        }

        .input-form textarea::placeholder {
            color: var(--text-secondary);
            opacity: 0.8;
        }

        .send-btn {
            background: var(--accent-glow);
            border: none;
            color: #000;
            width: 42px;
            height: 42px;
            border-radius: 12px;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 16px;
            transition: all 0.3s ease;
            box-shadow: 0 4px 12px rgba(212, 175, 55, 0.2);
            flex-shrink: 0;
        }

        .send-btn:hover {
            transform: scale(1.05);
            box-shadow: 0 6px 18px rgba(212, 175, 55, 0.4);
        }

        .send-btn:disabled {
            opacity: 0.5;
            cursor: not-allowed;
            transform: none;
        }

        @media (max-width: 600px) {
            .chat-container {
                height: 95vh;
                border-radius: 16px;
            }
            .message {
                max-width: 90%;
            }
        }
    </style>
</head>
<body>

    <div class="chat-container">
        <!-- Header -->
        <div class="chat-header">
            <div class="brand">
                <div class="avatar-icon">
                    <i class="fa-solid fa-brain"></i>
                </div>
                <div class="brand-info">
                    <h2>Claude Opus 4.5</h2>
                    <p>Luxury Studio Web AI Tool</p>
                </div>
            </div>
            <div class="header-actions">
                <a href="https://luxurystudioweb.com/tools.php"><i class="fa-solid fa-arrow-left"></i> Back to Tools</a>
            </div>
        </div>

        <!-- Chat Box -->
        <div class="chat-box" id="chatBox">
            <!-- Initial AI Welcome Message -->
            <div class="message ai">
                <div class="message-icon"><i class="fa-solid fa-robot"></i></div>
                <div class="message-content">Hello! I am Claude Opus 4.5 AI. How can I assist you today?</div>
            </div>

            <!-- Typing Indicator Container -->
            <div class="typing" id="typingIndicator">
                <span></span><span></span><span></span>
            </div>
        </div>

        <!-- Input Area -->
        <div class="chat-input-area">
            <form class="input-form" id="chatForm">
                <textarea id="promptInput" rows="1" placeholder="Type your message here..." required></textarea>
                <button type="submit" class="send-btn" id="sendBtn">
                    <i class="fa-solid fa-paper-plane"></i>
                </button>
            </form>
        </div>
    </div>

    <script>
        const chatForm = document.getElementById('chatForm');
        const promptInput = document.getElementById('promptInput');
        const chatBox = document.getElementById('chatBox');
        const typingIndicator = document.getElementById('typingIndicator');
        const sendBtn = document.getElementById('sendBtn');

        // Auto-expand textarea
        promptInput.addEventListener('input', function() {
            this.style.height = '24px';
            this.style.height = Math.min(this.scrollHeight, 120) + 'px';
        });

        // Submit on Enter (Shift+Enter for newline)
        promptInput.addEventListener('keydown', function(e) {
            if (e.key === 'Enter' && !e.shiftKey) {
                e.preventDefault();
                chatForm.dispatchEvent(new Event('submit'));
            }
        });

        function appendMessage(sender, text) {
            const messageDiv = document.createElement('div');
            messageDiv.classList.add('message', sender);

            const iconDiv = document.createElement('div');
            iconDiv.classList.add('message-icon');
            iconDiv.innerHTML = sender === 'user' ? '<i class="fa-solid fa-user"></i>' : '<i class="fa-solid fa-robot"></i>';

            const contentDiv = document.createElement('div');
            contentDiv.classList.add('message-content');
            contentDiv.textContent = text;

            messageDiv.appendChild(iconDiv);
            messageDiv.appendChild(contentDiv);

            chatBox.insertBefore(messageDiv, typingIndicator);
            chatBox.scrollTop = chatBox.scrollHeight;
        }

        chatForm.addEventListener('submit', async function(e) {
            e.preventDefault();
            const prompt = promptInput.value.trim();
            if (!prompt) return;

            // Render User Message
            appendMessage('user', prompt);
            promptInput.value = '';
            promptInput.style.height = '24px';

            // Disable input & show loading state
            sendBtn.disabled = true;
            typingIndicator.style.display = 'flex';
            chatBox.scrollTop = chatBox.scrollHeight;

            try {
                const formData = new FormData();
                formData.append('action', 'chat');
                formData.append('prompt', prompt);

                const response = await fetch('opus.php', {
                    method: 'POST',
                    body: formData
                });

                const data = await response.json();

                if (data.status === 'success') {
                    appendMessage('ai', data.reply);
                } else {
                    appendMessage('ai', 'Error: ' + (data.message || 'Something went wrong.'));
                }
            } catch (err) {
                appendMessage('ai', 'Failed to communicate with server.');
            } finally {
                typingIndicator.style.display = 'none';
                sendBtn.disabled = false;
                chatBox.scrollTop = chatBox.scrollHeight;
            }
        });
    </script>
</body>
</html>