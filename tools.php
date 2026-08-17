<?php
// tools.php - Luxury Studio Digital Tools
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Digital Tools & Utilities | Luxury Studio</title>
    
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="https://files.catbox.moe/a6xpxg.png">
    
    <!-- Font Awesome & Custom Google Fonts -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700&family=Playfair+Display:ital,wght@0,600;0,800;1,400&display=swap" rel="stylesheet">
    
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        :root {
            --bg-dark: #0A0A0C;
            --bg-card: #121216;
            --accent-gold: #D4AF37;
            --accent-gold-light: #F3E5AB;
            --text-main: #EDEDED;
            --text-muted: #A0A0AB;
            --border-color: rgba(212, 175, 55, 0.15);
            --gradient-gold: linear-gradient(135deg, #BF953F, #FCF6BA, #B38728, #FBF5B7, #AA771C);
        }

        body {
            background-color: var(--bg-dark);
            color: var(--text-main);
            font-family: 'Plus Jakarta Sans', sans-serif;
            line-height: 1.6;
            overflow-x: hidden;
        }

        h1, h2, h3, .logo-text {
            font-family: 'Playfair Display', serif;
        }

        .container {
            width: 90%;
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
        }

        /* Header Styles */
        header {
            background: rgba(10, 10, 12, 0.85);
            backdrop-filter: blur(15px);
            border-bottom: 1px solid var(--border-color);
            padding: 18px 0;
            position: sticky;
            top: 0;
            z-index: 1000;
        }

        .header-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .logo {
            display: flex;
            align-items: center;
            gap: 12px;
            font-size: 1.6rem;
            font-weight: 800;
            color: var(--text-main);
            text-decoration: none;
            letter-spacing: 1px;
        }

        .logo img {
            height: 40px;
            width: auto;
            object-fit: contain;
        }

        nav ul {
            display: flex;
            list-style: none;
            gap: 35px;
            align-items: center;
        }

        nav a {
            color: var(--text-muted);
            text-decoration: none;
            font-weight: 500;
            font-size: 0.95rem;
            transition: color 0.3s ease;
        }

        nav a:hover, nav a.active {
            color: var(--accent-gold);
        }

        .cta-gold {
            background: var(--gradient-gold);
            color: #000;
            border: none;
            padding: 12px 28px;
            border-radius: 4px;
            font-weight: 700;
            font-size: 0.85rem;
            text-transform: uppercase;
            letter-spacing: 1px;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-block;
        }

        .cta-gold:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(212, 175, 55, 0.3);
        }

        /* Hero Section */
        .hero {
            padding: 100px 0 60px;
            background: radial-gradient(circle at top center, rgba(212, 175, 55, 0.08), transparent 60%);
            text-align: center;
        }

        .hero-badge {
            display: inline-block;
            padding: 6px 16px;
            background: rgba(212, 175, 55, 0.1);
            border: 1px solid var(--border-color);
            border-radius: 30px;
            color: var(--accent-gold);
            font-size: 0.85rem;
            text-transform: uppercase;
            letter-spacing: 2px;
            margin-bottom: 25px;
        }

        .hero h1 {
            font-size: 3.5rem;
            line-height: 1.1;
            margin-bottom: 20px;
            background: linear-gradient(180deg, #FFFFFF 0%, #A0A0AB 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .hero p {
            font-size: 1.15rem;
            color: var(--text-muted);
            max-width: 650px;
            margin: 0 auto;
            font-weight: 300;
        }

        /* Tools Section */
        .tools-section {
            padding: 60px 0 100px;
        }

        .tools-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
            gap: 30px;
        }

        .tool-card {
            background: var(--bg-card);
            border: 1px solid var(--border-color);
            border-radius: 8px;
            padding: 40px 30px;
            display: flex;
            flex-direction: column;
            align-items: flex-start;
            position: relative;
            transition: transform 0.3s ease, border-color 0.3s ease, box-shadow 0.3s ease;
        }

        .tool-card:hover {
            transform: translateY(-8px);
            border-color: var(--accent-gold);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.5);
        }

        .tool-icon {
            width: 60px;
            height: 60px;
            background: rgba(212, 175, 55, 0.1);
            border: 1px solid var(--border-color);
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.8rem;
            color: var(--accent-gold);
            margin-bottom: 25px;
        }

        .tool-card h3 {
            font-size: 1.6rem;
            margin-bottom: 12px;
            color: var(--text-main);
        }

        .tool-card p {
            color: var(--text-muted);
            font-size: 0.95rem;
            margin-bottom: 25px;
            flex-grow: 1;
        }

        .tool-features {
            list-style: none;
            margin-bottom: 30px;
            width: 100%;
        }

        .tool-features li {
            font-size: 0.85rem;
            color: var(--text-muted);
            margin-bottom: 8px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .tool-features li i {
            color: var(--accent-gold);
            font-size: 0.75rem;
        }

        .secondary-button {
            background: transparent;
            color: var(--text-main);
            border: 1px solid var(--border-color);
            padding: 12px 24px;
            border-radius: 4px;
            font-weight: 600;
            font-size: 0.9rem;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 10px;
            width: 100%;
            justify-content: center;
        }

        .secondary-button:hover {
            border-color: var(--accent-gold);
            color: #000;
            background: var(--gradient-gold);
        }

        /* Footer */
        footer {
            background: var(--bg-dark);
            padding: 40px 0;
            border-top: 1px solid var(--border-color);
            text-align: center;
        }

        .social-links {
            display: flex;
            justify-content: center;
            gap: 20px;
            margin-bottom: 20px;
        }

        .social-links a {
            width: 40px;
            height: 40px;
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid var(--border-color);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--text-main);
            text-decoration: none;
            transition: all 0.3s ease;
        }

        .social-links a:hover {
            background: var(--accent-gold);
            color: #000;
        }

        .copyright {
            color: var(--text-muted);
            font-size: 0.9rem;
        }

        @media (max-width: 768px) {
            .hero h1 { font-size: 2.5rem; }
            nav ul { gap: 15px; }
            .header-content { flex-direction: column; gap: 15px; }
        }
    </style>
</head>
<body>

    <!-- Header / Navigation -->
    <header>
        <div class="container">
            <div class="header-content">
                <a href="index.php" class="logo">
                    <img src="https://files.catbox.moe/a6xpxg.png" alt="Luxury Studio Logo">
                    <span>Luxury Studio</span>
                </a>
                <nav>
                    <ul>
                        <li><a href="index.php">Home</a></li>
                        <li><a href="about.php">About Us</a></li>
                        <li><a href="tools.php" class="active">Tools</a></li>
                        <li><a href="contact.php">Contact</a></li>
                        <li><a href="contact.php" class="cta-gold">GET STARTED</a></li>
                    </ul>
                </nav>
            </div>
        </div>
    </header>

    <!-- Hero Section -->
    <section class="hero">
        <div class="container">
            <span class="hero-badge">Exclusive Utilities</span>
            <h1>Digital Tools & Applications</h1>
            <p>Explore our suite of high-performance tools, bots, and free web utilities crafted for seamless online operations.</p>
        </div>
    </section>

    <!-- Tools Showcase Section -->
    <section class="tools-section">
        <div class="container">
            <div class="tools-grid">
                
                <!-- FLUX V2 AI Tool Card -->
                <div class="tool-card">
                    <div class="tool-icon">
                        <i class="fas fa-wand-magic-sparkles"></i>
                    </div>
                    <h3>FLUX V2 AI</h3>
                    <p>Next-generation AI image generation engine designed to transform high-detail prompts into ultra-realistic visuals instantly.</p>
                    <ul class="tool-features">
                        <li><i class="fas fa-check"></i> Powered by FLUX V2 Architecture</li>
                        <li><i class="fas fa-check"></i> High-Resolution Output</li>
                        <li><i class="fas fa-check"></i> Advanced Prompt Precision</li>
                    </ul>
                    <a href="https://luxurystudioweb.com/fluxv2.php" target="_blank" class="secondary-button">
                        Launch Generator <i class="fas fa-arrow-right"></i>
                    </a>
                </div>

                <!-- Claude Opus 4.5 AI Tool Card -->
                <div class="tool-card">
                    <div class="tool-icon">
                        <i class="fas fa-brain"></i>
                    </div>
                    <h3>Claude Opus 4.5 AI</h3>
                    <p>An advanced AI assistant designed for intelligent conversations, code generation, reasoning, and real-time query resolution.</p>
                    <ul class="tool-features">
                        <li><i class="fas fa-check"></i> Powered by Claude Opus 4.5</li>
                        <li><i class="fas fa-check"></i> Fast AJAX Response Engine</li>
                        <li><i class="fas fa-check"></i> Interactive Chat Interface</li>
                    </ul>
                    <a href="https://luxurystudioweb.com/opus.php" target="_blank" class="secondary-button">
                        Launch AI <i class="fas fa-arrow-right"></i>
                    </a>
                </div>

                <!-- GPT-5.5 AI Tool Card -->
                <div class="tool-card">
                    <div class="tool-icon">
                        <i class="fas fa-microchip"></i>
                    </div>
                    <h3>GPT-5.5 AI</h3>
                    <p>High-speed, intelligent AI conversational tool powered by GPT-5.5 for solving complex queries, creative writing, and tasks.</p>
                    <ul class="tool-features">
                        <li><i class="fas fa-check"></i> Powered by GPT-5.5 Model</li>
                        <li><i class="fas fa-check"></i> Fast AJAX Response Engine</li>
                        <li><i class="fas fa-check"></i> Interactive Chat Interface</li>
                    </ul>
                    <a href="https://luxurystudioweb.com/gpt5.php" target="_blank" class="secondary-button">
                        Launch AI <i class="fas fa-arrow-right"></i>
                    </a>
                </div>

                <!-- YouTube Downloader Tool Card -->
                <div class="tool-card">
                    <div class="tool-icon">
                        <i class="fab fa-youtube"></i>
                    </div>
                    <h3>YouTube Downloader</h3>
                    <p>Fast, high-quality video and audio extractor for downloading YouTube media effortlessly without speed limits.</p>
                    <ul class="tool-features">
                        <li><i class="fas fa-check"></i> High-Resolution MP4 Downloads</li>
                        <li><i class="fas fa-check"></i> MP3 Audio Extraction</li>
                        <li><i class="fas fa-check"></i> Fast Processing Engine</li>
                    </ul>
                    <a href="https://luxurystudioweb.com/ytd.php" target="_blank" class="secondary-button">
                        Launch Tool <i class="fas fa-arrow-right"></i>
                    </a>
                </div>

                <!-- Free Web Host Tool Card -->
                <div class="tool-card">
                    <div class="tool-icon">
                        <i class="fas fa-server"></i>
                    </div>
                    <h3>Free Web Hosting</h3>
                    <p>Deploy your lightweight websites and web projects instantly with our optimized, high-uptime free server infrastructure.</p>
                    <ul class="tool-features">
                        <li><i class="fas fa-check"></i> High Uptime & Speed</li>
                        <li><i class="fas fa-check"></i> Quick File Upload</li>
                        <li><i class="fas fa-check"></i> Free Web Space</li>
                    </ul>
                    <a href="https://luxurystudioweb.com/host.php" target="_blank" class="secondary-button">
                        Access Hosting <i class="fas fa-arrow-right"></i>
                    </a>
                </div>

                <!-- Mini Bot Tool Card -->
                <div class="tool-card">
                    <div class="tool-icon">
                        <i class="fas fa-robot"></i>
                    </div>
                    <h3>Mini Bot Assistant</h3>
                    <p>An interactive, automated AI-powered chatbot designed to handle instant queries, tasks, and quick automation flows.</p>
                    <ul class="tool-features">
                        <li><i class="fas fa-check"></i> Instant Response Time</li>
                        <li><i class="fas fa-check"></i> Smart Task Automation</li>
                        <li><i class="fas fa-check"></i> User-Friendly Interface</li>
                    </ul>
                    <a href="https://luxurystudioweb.com/Mini-Bot/main.html" target="_blank" class="secondary-button">
                        Open Mini Bot <i class="fas fa-arrow-right"></i>
                    </a>
                </div>

            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer>
        <div class="container">
            <div class="social-links">
                <a href="https://www.facebook.com/profile.php?id=61592768320197" target="_blank" aria-label="Facebook"><i class="fab fa-facebook-f"></i></a>
                <a href="https://www.instagram.com/luxurystudiowebdev" target="_blank" aria-label="Instagram"><i class="fab fa-instagram"></i></a>
                <a href="https://wa.me/8801796618012" target="_blank" aria-label="WhatsApp"><i class="fab fa-whatsapp"></i></a>
                <a href="mailto:support@luxurystudioweb.com" aria-label="Email"><i class="fas fa-envelope"></i></a>
            </div>
            <p class="copyright">&copy; <?php echo date("Y"); ?> Luxury Studio. All rights reserved. Premium Web Development.</p>
        </div>
    </footer>

</body>
</html>