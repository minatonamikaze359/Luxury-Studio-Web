<?php
// index.php - Luxury Studio Homepage
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta name="monetag" content="ddbc39aa345ccc34fc5d2dce63768928">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Luxury Studio | Premier Web Development & Design</title>
    
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
            padding: 140px 0 100px;
            background: radial-gradient(circle at top right, rgba(212, 175, 55, 0.08), transparent 60%);
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
            font-size: 4rem;
            line-height: 1.1;
            margin-bottom: 25px;
            background: linear-gradient(180deg, #FFFFFF 0%, #A0A0AB 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .hero p {
            font-size: 1.25rem;
            color: var(--text-muted);
            max-width: 750px;
            margin: 0 auto 40px;
            font-weight: 300;
        }

        .hero-buttons {
            display: flex;
            justify-content: center;
            gap: 20px;
        }

        .secondary-button {
            background: transparent;
            color: var(--text-main);
            border: 1px solid var(--border-color);
            padding: 12px 28px;
            border-radius: 4px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 10px;
        }

        .secondary-button:hover {
            border-color: var(--accent-gold);
            color: var(--accent-gold);
        }

        /* Features Bar */
        .features-bar {
            padding: 40px 0;
            border-top: 1px solid var(--border-color);
            border-bottom: 1px solid var(--border-color);
            background: rgba(255, 255, 255, 0.01);
            margin-bottom: 60px;
        }

        .features-flex {
            display: flex;
            justify-content: space-around;
            flex-wrap: wrap;
            gap: 20px;
            text-align: center;
        }

        .feature-item {
            display: flex;
            align-items: center;
            gap: 15px;
            color: var(--text-muted);
        }

        .feature-item i {
            color: var(--accent-gold);
            font-size: 1.5rem;
        }

        /* Services & Pricing */
        .pricing {
            padding: 100px 0;
        }

        .section-title {
            text-align: center;
            margin-bottom: 60px;
        }

        .section-title h2 {
            font-size: 2.8rem;
            margin-bottom: 15px;
            color: var(--text-main);
        }

        .section-title p {
            color: var(--text-muted);
            max-width: 600px;
            margin: 0 auto;
        }

        .pricing-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
            gap: 30px;
        }

        .price-card {
            background: var(--bg-card);
            border: 1px solid var(--border-color);
            border-radius: 8px;
            padding: 40px 30px;
            display: flex;
            flex-direction: column;
            position: relative;
            transition: transform 0.3s ease, border-color 0.3s ease;
        }

        .price-card.featured {
            border-color: var(--accent-gold);
            background: linear-gradient(180deg, rgba(212, 175, 55, 0.05) 0%, var(--bg-card) 100%);
        }

        .price-card:hover {
            transform: translateY(-10px);
            border-color: var(--accent-gold);
        }

        .card-badge {
            position: absolute;
            top: -12px;
            right: 20px;
            background: var(--gradient-gold);
            color: #000;
            font-size: 0.75rem;
            font-weight: 800;
            padding: 4px 12px;
            border-radius: 20px;
            text-transform: uppercase;
        }

        .price-card h3 {
            font-size: 1.8rem;
            margin-bottom: 10px;
        }

        .price-card .price {
            font-size: 3rem;
            font-weight: 700;
            color: var(--accent-gold);
            margin: 20px 0;
            font-family: 'Playfair Display', serif;
        }

        .price-card .price span {
            font-size: 1rem;
            color: var(--text-muted);
            font-family: 'Plus Jakarta Sans', sans-serif;
        }

        .price-card ul {
            list-style: none;
            margin-bottom: 30px;
            flex-grow: 1;
        }

        .price-card ul li {
            padding: 10px 0;
            color: var(--text-muted);
            display: flex;
            align-items: center;
            gap: 10px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.05);
        }

        .price-card ul li i {
            color: var(--accent-gold);
        }

        /* Contact Section */
        .contact {
            padding: 100px 0;
            background: var(--bg-card);
            border-top: 1px solid var(--border-color);
        }

        .contact-wrapper {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 50px;
        }

        .contact-info h3 {
            font-size: 2rem;
            margin-bottom: 20px;
        }

        .contact-info p {
            color: var(--text-muted);
            margin-bottom: 30px;
        }

        .contact-details {
            list-style: none;
        }

        .contact-details li {
            display: flex;
            align-items: center;
            gap: 15px;
            margin-bottom: 20px;
            color: var(--text-main);
        }

        .contact-details i {
            width: 45px;
            height: 45px;
            background: rgba(212, 175, 55, 0.1);
            border: 1px solid var(--border-color);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--accent-gold);
        }

        .whatsapp-btn {
            background: #25D366;
            color: #000;
            font-weight: 700;
            border: none;
            padding: 14px 28px;
            border-radius: 4px;
            text-transform: uppercase;
            letter-spacing: 1px;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }

        .whatsapp-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(37, 211, 102, 0.3);
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
                        <li><a href="index.php" class="active">Home</a></li>
                        <li><a href="about.php">About Us</a></li>
                        <li><a href="tools.php">Tools</a></li>
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
            <span class="hero-badge">Bespoke Web Engineering</span>
            <h1>Crafting Digital Perfection For Global Brands</h1>
            <p>Elevate your online presence with high-performance custom development, conversion-driven UI/UX design, and round-the-clock VIP support.</p>
            <div class="hero-buttons">
                <a href="contact.php" class="cta-gold">Start Your Project</a>
                <a href="https://wa.me/8801796618012" target="_blank" class="secondary-button">
                    <i class="fab fa-whatsapp" style="color: #25D366; font-size: 1.2rem;"></i> WhatsApp Us
                </a>
            </div>
        </div>
    </section>

    <!-- Value Props / Features Bar -->
    <section class="features-bar">
        <div class="container">
            <div class="features-flex">
                <div class="feature-item">
                    <i class="fas fa-headset"></i>
                    <span>24/7 Dedicated VIP Support</span>
                </div>
                <div class="feature-item">
                    <i class="fas fa-bolt"></i>
                    <span>Ultra-Fast Optimization</span>
                </div>
                <div class="feature-item">
                    <i class="fas fa-shield-halved"></i>
                    <span>Enterprise-Grade Security</span>
                </div>
                <div class="feature-item">
                    <i class="fas fa-globe"></i>
                    <span>Global Digital Reach</span>
                </div>
            </div>
        </div>
    </section>

    <!-- Pricing / Packages Section -->
    <section class="pricing">
        <div class="container">
            <div class="section-title">
                <h2>Bespoke Tiers</h2>
                <p>Tailored web solutions engineered to scale your brand and maximize conversion performance.</p>
            </div>
            <div class="pricing-grid">
                
                <!-- Basic Plan -->
                <div class="price-card">
                    <h3>Starter Tier</h3>
                    <p style="color: var(--text-muted); font-size: 0.9rem;">Ideal for clean, high-impact personal or small business landing pages.</p>
                    <div class="price">$200 <span>/ fixed</span></div>
                    <ul>
                        <li><i class="fas fa-check"></i> Custom Responsive Web Design</li>
                        <li><i class="fas fa-check"></i> Essential SEO Setup</li>
                        <li><i class="fas fa-check"></i> Contact Form Integration</li>
                        <li><i class="fas fa-check"></i> High Performance Speed Tuning</li>
                        <li><i class="fas fa-check"></i> Standard Support</li>
                    </ul>
                    <a href="contact.php" class="secondary-button" style="justify-content: center;">Choose Starter</a>
                </div>

                <!-- Medium Plan -->
                <div class="price-card featured">
                    <div class="card-badge">Most Popular</div>
                    <h3>Growth Tier</h3>
                    <p style="color: var(--text-muted); font-size: 0.9rem;">Perfect for business growth with advanced UI and fast functionality.</p>
                    <div class="price">$400 <span>/ fixed</span></div>
                    <ul>
                        <li><i class="fas fa-check"></i> Everything in Starter</li>
                        <li><i class="fas fa-check"></i> Up to 5 Custom Pages</li>
                        <li><i class="fas fa-check"></i> Advanced UI/UX Animations</li>
                        <li><i class="fas fa-check"></i> E-Commerce / Payment Setup</li>
                        <li><i class="fas fa-check"></i> Priority Support</li>
                    </ul>
                    <a href="contact.php" class="cta-gold" style="text-align: center;">Choose Growth</a>
                </div>

                <!-- God Level Plan -->
                <div class="price-card">
                    <h3>God Level Tier</h3>
                    <p style="color: var(--text-muted); font-size: 0.9rem;">Unmatched bespoke engineering, web apps, and full VIP integration.</p>
                    <div class="price">$800+ <span>/ bespoke</span></div>
                    <ul>
                        <li><i class="fas fa-check"></i> Unlimited Custom Pages</li>
                        <li><i class="fas fa-check"></i> Bespoke Web App Architecture</li>
                        <li><i class="fas fa-check"></i> Virtual Telecom Integration</li>
                        <li><i class="fas fa-check"></i> VIP 24/7 Dedicated Manager</li>
                        <li><i class="fas fa-check"></i> Continuous Security & Maintenance</li>
                    </ul>
                    <a href="contact.php" class="secondary-button" style="justify-content: center;">Choose God Level</a>
                </div>

            </div>
        </div>
    </section>

    <!-- Direct Contact Block -->
    <section class="contact">
        <div class="container">
            <div class="contact-wrapper">
                <div class="contact-info">
                    <h3>Direct Onboarding</h3>
                    <p>Connect with our digital specialists immediately to discuss your custom project requirements.</p>
                    <ul class="contact-details">
                        <li>
                            <i class="fas fa-envelope"></i>
                            <span>support@luxurystudioweb.com
</span>
                        </li>
                        <li>
                            <i class="fab fa-whatsapp"></i>
                            <span>+880 1796-618012</span>
                        </li>
                        <li>
                            <i class="fas fa-clock"></i>
                            <span>24/7 VIP Assistance</span>
                        </li>
                    </ul>
                </div>
                <div>
                    <!-- WhatsApp Communication Box -->
                    <div style="background: rgba(255,255,255,0.02); padding: 35px; border-radius: 8px; border: 1px solid var(--border-color); text-align: center;">
                        <h4 style="margin-bottom: 15px; font-size: 1.3rem;">Chat With Us</h4>
                        <p style="color: var(--text-muted); margin-bottom: 25px;">Fast-track your onboarding and receive an instant project estimate via WhatsApp.</p>
                        <a href="https://wa.me/8801796618012" target="_blank" class="whatsapp-btn" style="width: 100%;">
                            <i class="fab fa-whatsapp" style="font-size: 1.4rem;"></i> Connect via WhatsApp
                        </a>
                    </div>
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
                <a href="mailto:support@luxurystudioweb.com
" aria-label="Email"><i class="fas fa-envelope"></i></a>
            </div>
            <p class="copyright">&copy; <?php echo date("Y"); ?> Luxury Studio. All rights reserved. Premium Web Development.</p>
        </div>
    </footer>

</body>
</html>