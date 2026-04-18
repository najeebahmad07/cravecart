<?php
$pageTitle = "CraveCart - Order Food Online";
require_once '../config/config.php';
require_once '../includes/header.php';
require_once '../includes/navbar.php';

// Fetch restaurants
$restaurants_query = "SELECT * FROM restaurants WHERE is_active = 1 ORDER BY rating DESC";
$restaurants_result = $conn->query($restaurants_query);

// Get total counts for stats
$total_restaurants = $conn->query("SELECT COUNT(*) as count FROM restaurants WHERE is_active = 1")->fetch_assoc()['count'];
$total_orders = $conn->query("SELECT COUNT(*) as count FROM orders")->fetch_assoc()['count'];
$total_users = $conn->query("SELECT COUNT(*) as count FROM users")->fetch_assoc()['count'];
?>

<!-- Internal CSS -->
<style>
:root {
    --primary-color: #3858e9;
    --primary-dark: #2642c7;
    --primary-light: #e8edff;
    --secondary-color: #ff6b6b;
    --success-color: #10b981;
    --warning-color: #f59e0b;
    --dark-color: #1a1a2e;
    --light-bg: #f8fafc;
    --white: #ffffff;
    --gradient-1: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    --gradient-2: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
    --gradient-3: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
    --gradient-4: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);
    --shadow-sm: 0 2px 4px rgba(0,0,0,0.1);
    --shadow-md: 0 4px 6px rgba(0,0,0,0.1);
    --shadow-lg: 0 10px 25px rgba(0,0,0,0.15);
    --shadow-xl: 0 20px 40px rgba(0,0,0,0.1);
}

* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

body {
    font-family: 'Poppins', sans-serif;
    overflow-x: hidden;
}

/* HERO SLIDER */
.hero-slider {
    position: relative;
    height: 100vh;
    overflow: hidden;
    margin-top: 70px;
}

.hero-slide {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-size: cover;
    background-position: center;
    background-attachment: fixed;
    opacity: 0;
    transition: all 1.5s ease-in-out;
    z-index: 1;
}

.hero-slide.active {
    opacity: 1;
    z-index: 2;
}

.hero-slide::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: linear-gradient(45deg,
        rgba(56, 88, 233, 0.8) 0%,
        rgba(38, 66, 199, 0.6) 50%,
        rgba(255, 107, 107, 0.7) 100%);
    z-index: 1;
}

.hero-content {
    position: relative;
    z-index: 3;
    height: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    text-align: center;
    color: white;
}

.hero-title {
    font-size: 4rem;
    font-weight: 800;
    margin-bottom: 1.5rem;
    text-shadow: 2px 2px 20px rgba(0,0,0,0.3);
    animation: slideInUp 1s ease 0.5s both;
}

.hero-subtitle {
    font-size: 1.5rem;
    margin-bottom: 2rem;
    opacity: 0.95;
    font-weight: 300;
    animation: slideInUp 1s ease 0.7s both;
}

.hero-buttons {
    animation: slideInUp 1s ease 0.9s both;
}

.hero-btn {
    padding: 1rem 3rem;
    font-size: 1.1rem;
    font-weight: 600;
    border-radius: 50px;
    border: none;
    margin: 0 1rem;
    cursor: pointer;
    transition: all 0.3s ease;
    text-decoration: none;
    display: inline-block;
    position: relative;
    overflow: hidden;
}

.hero-btn-primary {
    background: rgba(255,255,255,0.2);
    color: white;
    border: 2px solid rgba(255,255,255,0.3);
    backdrop-filter: blur(10px);
}

.hero-btn-primary:hover {
    background: white;
    color: var(--primary-color);
    transform: translateY(-3px);
    box-shadow: 0 15px 35px rgba(0,0,0,0.2);
}

.hero-btn-secondary {
    background: var(--secondary-color);
    color: white;
    border: 2px solid transparent;
}

.hero-btn-secondary:hover {
    background: transparent;
    border-color: white;
    transform: translateY(-3px);
    box-shadow: 0 15px 35px rgba(0,0,0,0.2);
}

.slider-navigation {
    position: absolute;
    bottom: 30px;
    left: 50%;
    transform: translateX(-50%);
    z-index: 4;
    display: flex;
    gap: 15px;
}

.slider-dot {
    width: 15px;
    height: 15px;
    border-radius: 50%;
    background: rgba(255,255,255,0.5);
    cursor: pointer;
    transition: all 0.3s ease;
}

.slider-dot.active {
    background: white;
    transform: scale(1.2);
}

/* MODERN RESTAURANT CARDS */
.restaurant-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
    gap: 2rem;
    padding: 2rem 0;
}

.restaurant-card {
    background: white;
    border-radius: 20px;
    overflow: hidden;
    box-shadow: var(--shadow-md);
    transition: all 0.4s ease;
    position: relative;
    height: 100%;
}

.restaurant-card:hover {
    transform: translateY(-10px) scale(1.02);
    box-shadow: var(--shadow-xl);
}

.restaurant-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: var(--gradient-1);
    opacity: 0;
    transition: opacity 0.3s ease;
    z-index: 1;
    border-radius: 20px;
}

.restaurant-card:hover::before {
    opacity: 0.1;
}

.card-image-wrapper {
    position: relative;
    height: 250px;
    overflow: hidden;
}

.card-image {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.4s ease;
}

.restaurant-card:hover .card-image {
    transform: scale(1.1);
}

.card-badge {
    position: absolute;
    top: 15px;
    right: 15px;
    background: rgba(255,255,255,0.95);
    backdrop-filter: blur(10px);
    padding: 0.5rem 1rem;
    border-radius: 25px;
    font-weight: 600;
    color: var(--warning-color);
    z-index: 2;
}

.card-overlay {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: linear-gradient(to top, rgba(0,0,0,0.7) 0%, transparent 70%);
    opacity: 0;
    transition: opacity 0.3s ease;
    z-index: 2;
}

.restaurant-card:hover .card-overlay {
    opacity: 1;
}

.card-quick-view {
    position: absolute;
    bottom: 20px;
    left: 50%;
    transform: translateX(-50%) translateY(20px);
    opacity: 0;
    transition: all 0.3s ease;
    z-index: 3;
}

.restaurant-card:hover .card-quick-view {
    opacity: 1;
    transform: translateX(-50%) translateY(0);
}

.card-body {
    padding: 2rem;
    position: relative;
    z-index: 2;
}

.card-title {
    font-size: 1.5rem;
    font-weight: 700;
    margin-bottom: 0.5rem;
    color: var(--dark-color);
}

.card-description {
    color: #6b7280;
    margin-bottom: 1.5rem;
    line-height: 1.6;
    font-size: 0.95rem;
}

.card-info {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 1.5rem;
    flex-wrap: wrap;
    gap: 1rem;
}

.info-item {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    font-size: 0.9rem;
    color: #6b7280;
}

.info-icon {
    color: var(--primary-color);
}

.card-button {
    width: 100%;
    padding: 1rem;
    background: var(--gradient-1);
    color: white;
    border: none;
    border-radius: 15px;
    font-weight: 600;
    font-size: 1.1rem;
    cursor: pointer;
    transition: all 0.3s ease;
    text-decoration: none;
    display: inline-block;
    text-align: center;
}

.card-button:hover {
    transform: translateY(-2px);
    box-shadow: 0 10px 25px rgba(56, 88, 233, 0.3);
    color: white;
}

/* STATS SECTION */
.stats-section {
    background: var(--gradient-1);
    color: white;
    padding: 5rem 0;
    position: relative;
    overflow: hidden;
}

.stats-section::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><circle cx="50" cy="50" r="2" fill="white" opacity="0.1"/></svg>');
    background-size: 50px 50px;
    animation: float 20s infinite linear;
}

.stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 2rem;
}

.stat-item {
    text-align: center;
    padding: 2rem;
    border-radius: 20px;
    background: rgba(255,255,255,0.1);
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255,255,255,0.2);
    transition: transform 0.3s ease;
}

.stat-item:hover {
    transform: translateY(-5px);
}

.stat-number {
    font-size: 3rem;
    font-weight: 800;
    margin-bottom: 0.5rem;
    background: linear-gradient(45deg, #fff, #f0f8ff);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}

.stat-label {
    font-size: 1.1rem;
    opacity: 0.9;
    font-weight: 500;
}

/* FEATURES SECTION */
.features-section {
    padding: 6rem 0;
    background: var(--light-bg);
}

.features-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
    gap: 3rem;
    margin-top: 3rem;
}

.feature-card {
    background: white;
    padding: 3rem 2rem;
    border-radius: 20px;
    text-align: center;
    box-shadow: var(--shadow-md);
    transition: all 0.4s ease;
    position: relative;
    overflow: hidden;
}

.feature-card:hover {
    transform: translateY(-10px);
    box-shadow: var(--shadow-xl);
}

.feature-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 4px;
    background: var(--gradient-1);
}

.feature-icon {
    width: 80px;
    height: 80px;
    margin: 0 auto 2rem;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 2rem;
    position: relative;
    transition: all 0.3s ease;
}

.feature-card:nth-child(1) .feature-icon {
    background: linear-gradient(135deg, #667eea, #764ba2);
}

.feature-card:nth-child(2) .feature-icon {
    background: linear-gradient(135deg, #f093fb, #f5576c);
}

.feature-card:nth-child(3) .feature-icon {
    background: linear-gradient(135deg, #4facfe, #00f2fe);
}

.feature-card:nth-child(4) .feature-icon {
    background: linear-gradient(135deg, #43e97b, #38f9d7);
}

.feature-icon i {
    color: white;
    animation: bounce 2s infinite;
}

.feature-card:hover .feature-icon {
    transform: scale(1.1) rotateY(360deg);
}

.feature-title {
    font-size: 1.5rem;
    font-weight: 700;
    margin-bottom: 1rem;
    color: var(--dark-color);
}

.feature-description {
    color: #6b7280;
    line-height: 1.6;
}

/* HOW IT WORKS */
.how-it-works {
    padding: 6rem 0;
    background: white;
}

.steps-container {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 3rem;
    margin-top: 3rem;
    position: relative;
}

.step-item {
    text-align: center;
    position: relative;
}

.step-number {
    width: 80px;
    height: 80px;
    border-radius: 50%;
    background: var(--gradient-1);
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 2rem;
    font-weight: 800;
    margin: 0 auto 2rem;
    position: relative;
    z-index: 2;
}

.step-item::after {
    content: '';
    position: absolute;
    top: 40px;
    left: 100%;
    width: 100%;
    height: 2px;
    background: linear-gradient(90deg, var(--primary-color), transparent);
    z-index: 1;
}

.step-item:last-child::after {
    display: none;
}

.step-title {
    font-size: 1.3rem;
    font-weight: 700;
    margin-bottom: 1rem;
    color: var(--dark-color);
}

.step-description {
    color: #6b7280;
    line-height: 1.6;
}

/* TESTIMONIALS */
.testimonials-section {
    padding: 6rem 0;
    background: var(--light-bg);
    position: relative;
    overflow: hidden;
}

.testimonials-carousel {
    position: relative;
    max-width: 1200px;
    margin: 0 auto;
}

.testimonial-track {
    display: flex;
    transition: transform 0.5s ease;
}

.testimonial-item {
    min-width: 100%;
    padding: 0 2rem;
}

.testimonial-card {
    background: white;
    border-radius: 20px;
    padding: 3rem;
    text-align: center;
    box-shadow: var(--shadow-lg);
    position: relative;
    max-width: 600px;
    margin: 0 auto;
}

.testimonial-card::before {
    content: '"';
    position: absolute;
    top: -20px;
    left: 50%;
    transform: translateX(-50%);
    font-size: 8rem;
    color: var(--primary-light);
    font-family: Georgia, serif;
    line-height: 1;
}

.testimonial-avatar {
    width: 100px;
    height: 100px;
    border-radius: 50%;
    margin: 0 auto 2rem;
    border: 4px solid var(--primary-light);
    object-fit: cover;
}

.testimonial-text {
    font-size: 1.2rem;
    line-height: 1.8;
    color: var(--dark-color);
    margin-bottom: 2rem;
    font-style: italic;
}

.testimonial-author {
    font-weight: 700;
    color: var(--dark-color);
    margin-bottom: 0.5rem;
}

.testimonial-role {
    color: #6b7280;
    font-size: 0.9rem;
}

.testimonial-rating {
    color: #fbbf24;
    font-size: 1.5rem;
    margin: 1rem 0;
}

.carousel-controls {
    text-align: center;
    margin-top: 3rem;
}

.carousel-btn {
    background: var(--primary-color);
    color: white;
    border: none;
    width: 50px;
    height: 50px;
    border-radius: 50%;
    margin: 0 1rem;
    cursor: pointer;
    transition: all 0.3s ease;
    font-size: 1.2rem;
}

.carousel-btn:hover {
    background: var(--primary-dark);
    transform: scale(1.1);
}

.carousel-indicators {
    display: flex;
    justify-content: center;
    gap: 10px;
    margin-top: 2rem;
}

.carousel-dot {
    width: 12px;
    height: 12px;
    border-radius: 50%;
    background: #d1d5db;
    cursor: pointer;
    transition: all 0.3s ease;
}

.carousel-dot.active {
    background: var(--primary-color);
    transform: scale(1.2);
}

/* FAQ SECTION */
.faq-section {
    padding: 6rem 0;
    background: white;
}

.faq-container {
    max-width: 800px;
    margin: 0 auto;
    margin-top: 3rem;
}

.faq-item {
    background: white;
    border-radius: 15px;
    margin-bottom: 1rem;
    box-shadow: var(--shadow-sm);
    border: 1px solid #e5e7eb;
    overflow: hidden;
    transition: all 0.3s ease;
}

.faq-item:hover {
    box-shadow: var(--shadow-md);
}

.faq-question {
    padding: 2rem;
    cursor: pointer;
    display: flex;
    justify-content: space-between;
    align-items: center;
    background: var(--light-bg);
    transition: all 0.3s ease;
}

.faq-question:hover {
    background: var(--primary-light);
}

.faq-question h3 {
    font-size: 1.2rem;
    font-weight: 600;
    color: var(--dark-color);
    margin: 0;
}

.faq-toggle {
    width: 30px;
    height: 30px;
    border-radius: 50%;
    background: var(--primary-color);
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.2rem;
    transition: transform 0.3s ease;
}

.faq-item.active .faq-toggle {
    transform: rotate(45deg);
}

.faq-answer {
    max-height: 0;
    overflow: hidden;
    transition: max-height 0.3s ease;
    background: white;
}

.faq-item.active .faq-answer {
    max-height: 200px;
}

.faq-answer-content {
    padding: 0 2rem 2rem;
    color: #6b7280;
    line-height: 1.6;
}

/* CTA SECTIONS */
.cta-section {
    padding: 6rem 0;
    background: var(--gradient-2);
    color: white;
    text-align: center;
    position: relative;
    overflow: hidden;
}

.cta-section::before {
    content: '';
    position: absolute;
    top: -50%;
    left: -50%;
    width: 200%;
    height: 200%;
    background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><polygon points="50,0 61,35 100,35 69,57 80,91 50,70 20,91 31,57 0,35 39,35" fill="white" opacity="0.1"/></svg>');
    background-size: 100px 100px;
    animation: rotate 60s infinite linear;
}

.cta-content {
    position: relative;
    z-index: 2;
}

.cta-title {
    font-size: 3rem;
    font-weight: 800;
    margin-bottom: 1.5rem;
}

.cta-subtitle {
    font-size: 1.3rem;
    margin-bottom: 2.5rem;
    opacity: 0.9;
}

.cta-buttons {
    display: flex;
    gap: 1.5rem;
    justify-content: center;
    flex-wrap: wrap;
}

/* NEWSLETTER SECTION */
.newsletter-section {
    padding: 5rem 0;
    background: var(--dark-color);
    color: white;
    text-align: center;
}

.newsletter-form {
    max-width: 500px;
    margin: 2rem auto 0;
    display: flex;
    gap: 1rem;
}

.newsletter-input {
    flex: 1;
    padding: 1rem 1.5rem;
    border: none;
    border-radius: 50px;
    font-size: 1.1rem;
    outline: none;
}

.newsletter-btn {
    padding: 1rem 2rem;
    background: var(--secondary-color);
    color: white;
    border: none;
    border-radius: 50px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
}

.newsletter-btn:hover {
    background: #ff5252;
    transform: translateX(-2px);
}

/* SECTION HEADERS */
.section-header {
    text-align: center;
    margin-bottom: 3rem;
}

.section-title {
    font-size: 3rem;
    font-weight: 800;
    color: var(--dark-color);
    margin-bottom: 1rem;
}

.section-subtitle {
    font-size: 1.2rem;
    color: #6b7280;
    max-width: 600px;
    margin: 0 auto;
    line-height: 1.6;
}

/* ANIMATIONS */
@keyframes slideInUp {
    from {
        opacity: 0;
        transform: translateY(30px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

@keyframes bounce {
    0%, 20%, 50%, 80%, 100% {
        transform: translateY(0);
    }
    40% {
        transform: translateY(-10px);
    }
    60% {
        transform: translateY(-5px);
    }
}

@keyframes float {
    from {
        transform: translateX(0);
    }
    to {
        transform: translateX(100px);
    }
}

@keyframes rotate {
    from {
        transform: rotate(0deg);
    }
    to {
        transform: rotate(360deg);
    }
}

@keyframes fadeIn {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.fade-in {
    animation: fadeIn 0.6s ease forwards;
}

/* RESPONSIVE DESIGN */
@media (max-width: 768px) {
    .hero-title {
        font-size: 2.5rem;
    }

    .hero-subtitle {
        font-size: 1.2rem;
    }

    .hero-btn {
        padding: 0.8rem 2rem;
        margin: 0.5rem;
        font-size: 1rem;
    }

    .section-title {
        font-size: 2.5rem;
    }

    .cta-title {
        font-size: 2.5rem;
    }

    .restaurant-grid {
        grid-template-columns: 1fr;
        gap: 1.5rem;
    }

    .features-grid {
        grid-template-columns: 1fr;
        gap: 2rem;
    }

    .steps-container {
        grid-template-columns: 1fr;
        gap: 2rem;
    }

    .step-item::after {
        display: none;
    }

    .newsletter-form {
        flex-direction: column;
        gap: 1rem;
    }

    .cta-buttons {
        flex-direction: column;
        align-items: center;
    }
}

@media (max-width: 480px) {
    .hero-title {
        font-size: 2rem;
    }

    .section-title {
        font-size: 2rem;
    }

    .cta-title {
        font-size: 2rem;
    }

    .feature-card,
    .testimonial-card {
        padding: 2rem 1.5rem;
    }
}

/* UTILITY CLASSES */
.container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 2rem;
}

.text-gradient {
    background: var(--gradient-1);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}

.glass-effect {
    background: rgba(255, 255, 255, 0.1);
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255, 255, 255, 0.2);
}

.py-5 {
    padding-top: 3rem;
    padding-bottom: 3rem;
}
</style>

<!-- HERO SLIDER -->
<section class="hero-slider">
    <div class="hero-slide active" style="background-image: url('https://images.unsplash.com/photo-1504674900247-0877df9cc836?w=1920');">
        <div class="hero-content">
            <div class="container">
                <h1 class="hero-title">Delicious Food<br><span class="text-gradient">Delivered Fast</span></h1>
                <p class="hero-subtitle">Order from your favorite restaurants and enjoy fresh, hot meals delivered to your doorstep in under 30 minutes</p>
                <div class="hero-buttons">
                    <a href="#restaurants" class="hero-btn hero-btn-primary">
                        <i class="bi bi-basket me-2"></i>Explore Restaurants
                    </a>
                    <?php if (!isLoggedIn()): ?>
                    <a href="register.php" class="hero-btn hero-btn-secondary">
                        <i class="bi bi-person-plus me-2"></i>Join Now
                    </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <div class="hero-slide" style="background-image: url('https://images.unsplash.com/photo-1571091718767-18b5b1457add?w=1920');">
        <div class="hero-content">
            <div class="container">
                <h1 class="hero-title">Premium Quality<br><span class="text-gradient">Restaurant Food</span></h1>
                <p class="hero-subtitle">Experience the finest cuisine from top-rated restaurants, prepared with love and delivered with care</p>
                <div class="hero-buttons">
                    <a href="#restaurants" class="hero-btn hero-btn-primary">
                        <i class="bi bi-star me-2"></i>View Top Rated
                    </a>
                    <a href="#about" class="hero-btn hero-btn-secondary">
                        <i class="bi bi-info-circle me-2"></i>Learn More
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="hero-slide" style="background-image: url('https://images.unsplash.com/photo-1555939594-58d7cb561ad1?w=1920');">
        <div class="hero-content">
            <div class="container">
                <h1 class="hero-title">Safe & Hygienic<br><span class="text-gradient">Food Delivery</span></h1>
                <p class="hero-subtitle">Health and safety first! All our partner restaurants follow strict hygiene protocols for your wellbeing</p>
                <div class="hero-buttons">
                    <a href="#features" class="hero-btn hero-btn-primary">
                        <i class="bi bi-shield-check me-2"></i>Safety First
                    </a>
                    <a href="home.php#restaurants" class="hero-btn hero-btn-secondary">
                        <i class="bi bi-shop me-2"></i>Order Now
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="hero-slide" style="background-image: url('https://images.unsplash.com/photo-1517248135467-4c7edcad34c4?w=1920');">
        <div class="hero-content">
            <div class="container">
                <h1 class="hero-title">24/7 Customer<br><span class="text-gradient">Support</span></h1>
                <p class="hero-subtitle">Need help? Our dedicated support team is available around the clock to assist you with any queries</p>
                <div class="hero-buttons">
                    <a href="tel:+923001234567" class="hero-btn hero-btn-primary">
                        <i class="bi bi-telephone me-2"></i>Call Support
                    </a>
                    <a href="#faq" class="hero-btn hero-btn-secondary">
                        <i class="bi bi-question-circle me-2"></i>FAQ
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="hero-slide" style="background-image: url('https://images.unsplash.com/photo-1565299624946-b28f40a0ca4b?w=1920');">
        <div class="hero-content">
            <div class="container">
                <h1 class="hero-title">Special Offers &<br><span class="text-gradient">Discounts</span></h1>
                <p class="hero-subtitle">Enjoy amazing deals and discounts on your favorite meals. Save more, eat more, smile more!</p>
                <div class="hero-buttons">
                    <a href="register.php" class="hero-btn hero-btn-primary">
                        <i class="bi bi-gift me-2"></i>Get 20% Off
                    </a>
                    <a href="#restaurants" class="hero-btn hero-btn-secondary">
                        <i class="bi bi-arrow-right me-2"></i>Browse Deals
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="slider-navigation">
        <div class="slider-dot active" data-slide="0"></div>
        <div class="slider-dot" data-slide="1"></div>
        <div class="slider-dot" data-slide="2"></div>
        <div class="slider-dot" data-slide="3"></div>
        <div class="slider-dot" data-slide="4"></div>
    </div>
</section>

<!-- STATS SECTION -->
<section class="stats-section">
    <div class="container">
        <div class="stats-grid">
            <div class="stat-item">
                <div class="stat-number" data-count="<?php echo $total_restaurants; ?>">0</div>
                <div class="stat-label">Premium Restaurants</div>
            </div>
            <div class="stat-item">
                <div class="stat-number" data-count="<?php echo $total_orders; ?>">0</div>
                <div class="stat-label">Orders Delivered</div>
            </div>
            <div class="stat-item">
                <div class="stat-number" data-count="<?php echo $total_users; ?>">0</div>
                <div class="stat-label">Happy Customers</div>
            </div>
            <div class="stat-item">
                <div class="stat-number" data-count="25">0</div>
                <div class="stat-label">Minutes Avg Delivery</div>
            </div>
        </div>
    </div>
</section>

<!-- FEATURED RESTAURANTS -->
<section id="restaurants" class="py-5" style="padding: 6rem 0;">
    <div class="container">
        <div class="section-header">
            <h2 class="section-title">Featured <span class="text-gradient">Restaurants</span></h2>
            <p class="section-subtitle">Discover our handpicked selection of top-rated restaurants offering the finest cuisine in your area</p>
        </div>

        <div class="restaurant-grid">
            <?php if ($restaurants_result && $restaurants_result->num_rows > 0): ?>
                <?php while ($restaurant = $restaurants_result->fetch_assoc()): ?>
                <div class="restaurant-card fade-in">
                    <div class="card-image-wrapper">
                        <?php
                        // Handle restaurant images - Support both external URLs and uploaded images
                        $restaurant_image = $restaurant['image'];

                        // Check if it's an external URL
                        if (strpos($restaurant_image, 'http') === 0) {
                            $image_src = $restaurant_image;
                        }
                        // Check if it's a filename for uploaded images
                        elseif (!empty($restaurant_image)) {
                            // Try different possible paths
                            if (file_exists('../uploads/restaurants/' . basename($restaurant_image))) {
                                $image_src = '../uploads/restaurants/' . basename($restaurant_image);
                            } elseif (file_exists('uploads/restaurants/' . basename($restaurant_image))) {
                                $image_src = 'uploads/restaurants/' . basename($restaurant_image);
                            } else {
                                // If file doesn't exist, use the full path from database
                                $image_src = '../' . $restaurant_image;
                            }
                        } else {
                            // Fallback to default image
                            $image_src = 'https://images.unsplash.com/photo-1517248135467-4c7edcad34c4?w=800';
                        }
                        ?>
                        <img src="<?php echo htmlspecialchars($image_src); ?>"
                             alt="<?php echo htmlspecialchars($restaurant['name']); ?>"
                             class="card-image"
                             onerror="this.src='https://images.unsplash.com/photo-1517248135467-4c7edcad34c4?w=800'">
                        <div class="card-badge">
                            <i class="bi bi-star-fill"></i> <?php echo number_format($restaurant['rating'], 1); ?>
                        </div>
                        <div class="card-overlay"></div>
                        <div class="card-quick-view">
                            <a href="restaurant.php?id=<?php echo $restaurant['id']; ?>" class="hero-btn hero-btn-primary" style="padding: 0.8rem 2rem; font-size: 1rem;">
                                Quick View
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        <h3 class="card-title"><?php echo htmlspecialchars($restaurant['name']); ?></h3>
                        <p class="card-description"><?php echo htmlspecialchars(substr($restaurant['description'], 0, 100)); ?>...</p>
                        <div class="card-info">
                            <div class="info-item">
                                <i class="bi bi-clock info-icon"></i>
                                <span><?php echo htmlspecialchars($restaurant['delivery_time']); ?></span>
                            </div>
                            <div class="info-item">
                                <i class="bi bi-telephone info-icon"></i>
                                <span><?php echo htmlspecialchars(substr($restaurant['phone'], 0, 12)); ?></span>
                            </div>
                        </div>
                        <a href="restaurant.php?id=<?php echo $restaurant['id']; ?>" class="card-button">
                            <i class="bi bi-eye me-2"></i>View Full Menu
                        </a>
                    </div>
                </div>
                <?php endwhile; ?>
            <?php else: ?>
                <div class="col-12 text-center py-5">
                    <p class="text-muted">No restaurants available at the moment</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>

<!-- FEATURES SECTION -->
<section id="features" class="features-section">
    <div class="container">
        <div class="section-header">
            <h2 class="section-title">Why Choose <span class="text-gradient">CraveCart?</span></h2>
            <p class="section-subtitle">We're revolutionizing food delivery with cutting-edge technology and unmatched service quality</p>
        </div>

        <div class="features-grid">
            <div class="feature-card fade-in">
                <div class="feature-icon">
                    <i class="bi bi-lightning-charge-fill"></i>
                </div>
                <h3 class="feature-title">Lightning Fast Delivery</h3>
                <p class="feature-description">Get your favorite meals delivered in under 30 minutes with our optimized delivery network and real-time tracking.</p>
            </div>

            <div class="feature-card fade-in">
                <div class="feature-icon">
                    <i class="bi bi-shield-check"></i>
                </div>
                <h3 class="feature-title">100% Safe & Hygienic</h3>
                <p class="feature-description">All partner restaurants follow strict hygiene protocols. Your health and safety are our top priorities.</p>
            </div>

            <div class="feature-card fade-in">
                <div class="feature-icon">
                    <i class="bi bi-wallet2"></i>
                </div>
                <h3 class="feature-title">Multiple Payment Options</h3>
                <p class="feature-description">Pay your way! Cash on delivery, credit cards, debit cards, and digital wallets - we accept them all.</p>
            </div>

            <div class="feature-card fade-in">
                <div class="feature-icon">
                    <i class="bi bi-headset"></i>
                </div>
                <h3 class="feature-title">24/7 Customer Support</h3>
                <p class="feature-description">Our dedicated support team is always here to help. Reach out anytime for assistance or queries.</p>
            </div>
        </div>
    </div>
</section>

<!-- HOW IT WORKS -->
<section class="how-it-works">
    <div class="container">
        <div class="section-header">
            <h2 class="section-title">How It <span class="text-gradient">Works</span></h2>
            <p class="section-subtitle">Ordering delicious food has never been easier. Follow these simple steps and enjoy your meal!</p>
        </div>

        <div class="steps-container">
            <div class="step-item fade-in">
                <div class="step-number">1</div>
                <h3 class="step-title">Choose Your Restaurant</h3>
                <p class="step-description">Browse through our wide selection of premium restaurants and discover your favorite cuisine.</p>
            </div>

            <div class="step-item fade-in">
                <div class="step-number">2</div>
                <h3 class="step-title">Select Your Meals</h3>
                <p class="step-description">Pick your favorite dishes from the menu and customize them to your taste preferences.</p>
            </div>

            <div class="step-item fade-in">
                <div class="step-number">3</div>
                <h3 class="step-title">Secure Checkout</h3>
                <p class="step-description">Complete your order with our secure payment system and provide delivery details.</p>
            </div>

            <div class="step-item fade-in">
                <div class="step-number">4</div>
                <h3 class="step-title">Enjoy Your Meal</h3>
                <p class="step-description">Sit back and relax while we prepare and deliver your delicious meal to your doorstep.</p>
            </div>
        </div>
    </div>
</section>

<!-- TESTIMONIALS -->
<section class="testimonials-section">
    <div class="container">
        <div class="section-header">
            <h2 class="section-title">What Our <span class="text-gradient">Customers Say</span></h2>
            <p class="section-subtitle">Don't just take our word for it. Here's what our satisfied customers have to say about their experience</p>
        </div>

        <div class="testimonials-carousel">
            <div class="testimonial-track" id="testimonialTrack">
                <div class="testimonial-item">
                    <div class="testimonial-card">
                        <img src="https://randomuser.me/api/portraits/women/1.jpg" alt="Neha Qazmi" class="testimonial-avatar">
                        <p class="testimonial-text">"CraveCart has completely transformed my food ordering experience. The delivery is always on time, the food arrives hot and fresh, and the variety of restaurants is incredible. I can't imagine ordering food any other way!"</p>
                        <div class="testimonial-rating">★★★★★</div>
                        <h4 class="testimonial-author">Neha Qazmi</h4>
                        <p class="testimonial-role">Regular Customer</p>
                    </div>
                </div>

                <div class="testimonial-item">
                    <div class="testimonial-card">
                        <img src="https://randomuser.me/api/portraits/men/2.jpg" alt="Ayaan Khan" class="testimonial-avatar">
                        <p class="testimonial-text">"As a busy professional, CraveCart is a lifesaver! The app is super user-friendly, the delivery tracking is accurate, and I love the variety of cuisines available. Plus, their customer service is outstanding!"</p>
                        <div class="testimonial-rating">★★★★★</div>
                        <h4 class="testimonial-author">Ayaan Khan</h4>
                        <p class="testimonial-role">Software Engineer</p>
                    </div>
                </div>

                <div class="testimonial-item">
                    <div class="testimonial-card">
                        <img src="https://randomuser.me/api/portraits/women/3.jpg" alt="Sana Ahmed" class="testimonial-avatar">
                        <p class="testimonial-text">"The quality of food and service is exceptional. I've tried many food delivery services, but CraveCart stands out with their attention to detail and commitment to customer satisfaction. Highly recommended!"</p>
                        <div class="testimonial-rating">★★★★★</div>
                        <h4 class="testimonial-author">Sana Ahmed</h4>
                        <p class="testimonial-role">Food Enthusiast</p>
                    </div>
                </div>
            </div>

            <div class="carousel-controls">
                <button class="carousel-btn" id="prevBtn">
                    <i class="bi bi-chevron-left"></i>
                </button>
                <button class="carousel-btn" id="nextBtn">
                    <i class="bi bi-chevron-right"></i>
                </button>
            </div>

            <div class="carousel-indicators">
                <div class="carousel-dot active" data-slide="0"></div>
                <div class="carousel-dot" data-slide="1"></div>
                <div class="carousel-dot" data-slide="2"></div>
            </div>
        </div>
    </div>
</section>

<!-- CTA SECTION -->
<section class="cta-section">
    <div class="container">
        <div class="cta-content">
            <h2 class="cta-title">Ready to Satisfy Your Cravings?</h2>
            <p class="cta-subtitle">Join thousands of food lovers who trust CraveCart for their daily dose of delicious meals</p>
            <div class="cta-buttons">
                <?php if (!isLoggedIn()): ?>
                <a href="register.php" class="hero-btn hero-btn-primary">
                    <i class="bi bi-person-plus me-2"></i>Sign Up & Get 20% Off
                </a>
                <a href="login.php" class="hero-btn hero-btn-secondary">
                    <i class="bi bi-box-arrow-in-right me-2"></i>Login to Order
                </a>
                <?php else: ?>
                <a href="#restaurants" class="hero-btn hero-btn-primary">
                    <i class="bi bi-basket me-2"></i>Browse Restaurants
                </a>
                <a href="cart.php" class="hero-btn hero-btn-secondary">
                    <i class="bi bi-cart3 me-2"></i>View Cart
                </a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>

<!-- FAQ SECTION -->
<section id="faq" class="faq-section">
    <div class="container">
        <div class="section-header">
            <h2 class="section-title">Frequently Asked <span class="text-gradient">Questions</span></h2>
            <p class="section-subtitle">Got questions? We've got answers! Find everything you need to know about ordering with CraveCart</p>
        </div>

        <div class="faq-container">
            <div class="faq-item">
                <div class="faq-question">
                    <h3>How long does delivery usually take?</h3>
                    <div class="faq-toggle">+</div>
                </div>
                <div class="faq-answer">
                    <div class="faq-answer-content">
                        Our average delivery time is 25-40 minutes, depending on your location and the restaurant's preparation time. You can track your order in real-time through our app and receive notifications at every step of the delivery process.
                    </div>
                </div>
            </div>

            <div class="faq-item">
                <div class="faq-question">
                    <h3>What payment methods do you accept?</h3>
                    <div class="faq-toggle">+</div>
                </div>
                <div class="faq-answer">
                    <div class="faq-answer-content">
                        We accept multiple payment methods including Cash on Delivery, Credit/Debit Cards, and various digital wallets. All online transactions are secured with industry-standard encryption to protect your financial information.
                    </div>
                </div>
            </div>

            <div class="faq-item">
                <div class="faq-question">
                    <h3>Is there a minimum order amount?</h3>
                    <div class="faq-toggle">+</div>
                </div>
                <div class="faq-answer">
                    <div class="faq-answer-content">
                        Minimum order amounts vary by restaurant and are clearly displayed on each restaurant's page. Most restaurants have a minimum order of Rs. 300-500, but this can vary based on location and restaurant policy.
                    </div>
                </div>
            </div>

            <div class="faq-item">
                <div class="faq-question">
                    <h3>Can I cancel or modify my order?</h3>
                    <div class="faq-toggle">+</div>
                </div>
                <div class="faq-answer">
                    <div class="faq-answer-content">
                        Yes, you can cancel or modify your order before the restaurant starts preparing it. Once preparation begins, cancellation may not be possible. You can contact our customer support team for assistance with any changes.
                    </div>
                </div>
            </div>

            <div class="faq-item">
                <div class="faq-question">
                    <h3>Do you have any special offers or discounts?</h3>
                    <div class="faq-toggle">+</div>
                </div>
                <div class="faq-answer">
                    <div class="faq-answer-content">
                        Absolutely! We regularly offer special discounts, seasonal promotions, and loyalty rewards. New customers get 20% off their first order, and we have weekend deals, festival offers, and exclusive discounts for frequent customers.
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- NEWSLETTER SECTION -->
<section class="newsletter-section">
    <div class="container">
        <div class="section-header" style="color: white;">
            <h2 class="section-title" style="color: white;">Stay Updated with <span class="text-gradient">Special Offers</span></h2>
            <p class="section-subtitle" style="color: rgba(255,255,255,0.8);">Subscribe to our newsletter and be the first to know about exclusive deals, new restaurants, and exciting updates</p>
        </div>

        <form class="newsletter-form" onsubmit="subscribeNewsletter(event)">
            <input type="email" class="newsletter-input" placeholder="Enter your email address" required>
            <button type="submit" class="newsletter-btn">
                <i class="bi bi-envelope me-2"></i>Subscribe Now
            </button>
        </form>

        <p style="margin-top: 1rem; opacity: 0.7; font-size: 0.9rem;">
            🎉 Get exclusive deals • 🍕 New restaurant alerts • 📱 App updates • 💰 Special discounts
        </p>
    </div>
</section>

<!-- JAVASCRIPT -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Hero Slider
    let currentSlide = 0;
    const slides = document.querySelectorAll('.hero-slide');
    const dots = document.querySelectorAll('.slider-dot');
    const totalSlides = slides.length;

    function showSlide(index) {
        slides.forEach((slide, i) => {
            slide.classList.toggle('active', i === index);
        });
        dots.forEach((dot, i) => {
            dot.classList.toggle('active', i === index);
        });
    }

    function nextSlide() {
        currentSlide = (currentSlide + 1) % totalSlides;
        showSlide(currentSlide);
    }

    // Auto slide
    setInterval(nextSlide, 5000);

    // Dot navigation
    dots.forEach((dot, index) => {
        dot.addEventListener('click', () => {
            currentSlide = index;
            showSlide(currentSlide);
        });
    });

    // Stats Animation
    function animateStats() {
        const statNumbers = document.querySelectorAll('.stat-number');
        statNumbers.forEach(stat => {
            const target = parseInt(stat.dataset.count);
            let current = 0;
            const increment = target / 100;
            const timer = setInterval(() => {
                current += increment;
                if (current >= target) {
                    stat.textContent = target + (target > 50 ? '+' : '');
                    clearInterval(timer);
                } else {
                    stat.textContent = Math.floor(current) + (target > 50 ? '+' : '');
                }
            }, 20);
        });
    }

    // Trigger stats animation when in view
    const statsSection = document.querySelector('.stats-section');
    const observerOptions = {
        threshold: 0.5
    };

    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                animateStats();
                observer.unobserve(entry.target);
            }
        });
    }, observerOptions);

    if (statsSection) {
        observer.observe(statsSection);
    }

    // Testimonial Carousel
    let currentTestimonial = 0;
    const testimonialTrack = document.getElementById('testimonialTrack');
    const testimonialDots = document.querySelectorAll('.carousel-dot');
    const totalTestimonials = 3;

    function showTestimonial(index) {
        if (testimonialTrack) {
            testimonialTrack.style.transform = `translateX(-${index * 100}%)`;
        }
        testimonialDots.forEach((dot, i) => {
            dot.classList.toggle('active', i === index);
        });
    }

    const prevBtn = document.getElementById('prevBtn');
    const nextBtn = document.getElementById('nextBtn');

    if (prevBtn) {
        prevBtn.addEventListener('click', () => {
            currentTestimonial = currentTestimonial > 0 ? currentTestimonial - 1 : totalTestimonials - 1;
            showTestimonial(currentTestimonial);
        });
    }

    if (nextBtn) {
        nextBtn.addEventListener('click', () => {
            currentTestimonial = (currentTestimonial + 1) % totalTestimonials;
            showTestimonial(currentTestimonial);
        });
    }

    testimonialDots.forEach((dot, index) => {
        dot.addEventListener('click', () => {
            currentTestimonial = index;
            showTestimonial(currentTestimonial);
        });
    });

    // Auto testimonial rotation
    setInterval(() => {
        currentTestimonial = (currentTestimonial + 1) % totalTestimonials;
        showTestimonial(currentTestimonial);
    }, 6000);

    // FAQ Accordion
    const faqItems = document.querySelectorAll('.faq-item');

    faqItems.forEach(item => {
        const question = item.querySelector('.faq-question');
        question.addEventListener('click', () => {
            // Close other items
            faqItems.forEach(otherItem => {
                if (otherItem !== item) {
                    otherItem.classList.remove('active');
                }
            });

            // Toggle current item
            item.classList.toggle('active');
        });
    });

    // Scroll animations
    const fadeElements = document.querySelectorAll('.fade-in');

    const fadeObserver = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.style.animation = 'fadeIn 0.6s ease forwards';
                fadeObserver.unobserve(entry.target);
            }
        });
    }, {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    });

    fadeElements.forEach(element => {
        element.style.opacity = '0';
        fadeObserver.observe(element);
    });
});

// Newsletter subscription
function subscribeNewsletter(event) {
    event.preventDefault();
    const email = event.target.querySelector('input[type="email"]').value;

    // Simulate subscription
    alert(`Thank you for subscribing with ${email}! You'll receive exclusive offers soon. 🎉`);
    event.target.reset();
}

// Smooth scroll for anchor links
document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function (e) {
        const href = this.getAttribute('href');
        const target = document.querySelector(href);

        if (target) {
            e.preventDefault();
            target.scrollIntoView({
                behavior: 'smooth',
                block: 'start'
            });
        }
    });
});
</script>

<?php require_once '../includes/footer.php'; ?>