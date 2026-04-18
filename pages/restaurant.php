<?php
$pageTitle = "Restaurant Menu - CraveCart";
require_once '../config/config.php';
require_once '../includes/header.php';
require_once '../includes/navbar.php';

$restaurant_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if (!$restaurant_id) {
    echo '<div class="container my-5"><div class="alert alert-danger">Invalid restaurant ID</div></div>';
    require_once '../includes/footer.php';
    exit;
}

// Fetch restaurant details
$restaurant_query = "SELECT * FROM restaurants WHERE id = ? AND is_active = 1";
$stmt = $conn->prepare($restaurant_query);
$stmt->bind_param("i", $restaurant_id);
$stmt->execute();
$restaurant = $stmt->get_result()->fetch_assoc();

if (!$restaurant) {
    echo '<div class="container my-5">
            <div class="alert alert-danger">
                <h4>Restaurant not found</h4>
                <p>The restaurant you are looking for doesn\'t exist or is not available.</p>
                <a href="home.php" class="btn btn-primary">Browse Restaurants</a>
            </div>
          </div>';
    require_once '../includes/footer.php';
    exit;
}

// Fetch menu items
$menu_query = "SELECT * FROM menu_items WHERE restaurant_id = ? AND is_available = 1 ORDER BY category, name";
$stmt = $conn->prepare($menu_query);
$stmt->bind_param("i", $restaurant_id);
$stmt->execute();
$menu_items = $stmt->get_result();

// Group by category
$categorized_items = [];
while ($item = $menu_items->fetch_assoc()) {
    $categorized_items[$item['category']][] = $item;
}

// Function to get proper image path
function getImagePath($image_path, $type = 'menu') {
    if (empty($image_path)) {
        return 'https://images.unsplash.com/photo-1546833999-b9f581a1996d?w=400';
    }

    // Check if it's an external URL
    if (strpos($image_path, 'http') === 0) {
        return $image_path;
    }

    // Check if it's just a filename
    $basename = basename($image_path);
    $upload_dir = ($type === 'restaurant') ? 'uploads/restaurants/' : 'uploads/menu/';

    // Check different possible paths
    if (file_exists($upload_dir . $basename)) {
        return $upload_dir . $basename;
    } elseif (file_exists('../' . $upload_dir . $basename)) {
        return '../' . $upload_dir . $basename;
    } else {
        // Return the path as is (might be a full path from database)
        return $image_path;
    }
}
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
    background-color: var(--light-bg);
}

/* RESTAURANT HEADER */
.restaurant-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 4rem 0;
    margin-top: 70px;
    position: relative;
    overflow: hidden;
}

.restaurant-header::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><circle cx="50" cy="50" r="2" fill="white" opacity="0.1"/></svg>');
    background-size: 50px 50px;
}

.restaurant-header-content {
    position: relative;
    z-index: 2;
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 3rem;
    align-items: center;
}

.restaurant-info-section h1 {
    font-size: 3rem;
    font-weight: 800;
    margin-bottom: 1rem;
    text-shadow: 2px 2px 10px rgba(0,0,0,0.2);
}

.restaurant-info-section p {
    font-size: 1.1rem;
    margin-bottom: 2rem;
    opacity: 0.95;
    line-height: 1.6;
}

.restaurant-details {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 2rem;
    margin-top: 2rem;
}

.detail-item {
    display: flex;
    align-items: center;
    gap: 1rem;
}

.detail-icon {
    width: 50px;
    height: 50px;
    border-radius: 50%;
    background: rgba(255,255,255,0.2);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
}

.detail-text h6 {
    font-weight: 600;
    margin-bottom: 0.2rem;
}

.detail-text p {
    font-size: 0.95rem;
    opacity: 0.9;
}

.restaurant-image-section {
    position: relative;
}

.restaurant-image {
    width: 100%;
    height: 400px;
    object-fit: cover;
    border-radius: 20px;
    box-shadow: 0 20px 60px rgba(0,0,0,0.3);
}

/* CONTAINER */
.container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 2rem;
}

/* SEARCH & FILTER */
.search-filter-section {
    background: white;
    padding: 2rem 0;
    margin-bottom: 3rem;
    box-shadow: var(--shadow-sm);
    position: sticky;
    top: 70px;
    z-index: 10;
}

.search-filter-content {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 2rem;
    align-items: center;
}

.search-box {
    position: relative;
}

.search-box input {
    width: 100%;
    padding: 1rem 1.5rem;
    border: 2px solid #e5e7eb;
    border-radius: 50px;
    font-size: 1rem;
    transition: all 0.3s ease;
}

.search-box input:focus {
    outline: none;
    border-color: var(--primary-color);
    box-shadow: 0 0 0 3px rgba(56, 88, 233, 0.1);
}

.search-box i {
    position: absolute;
    right: 20px;
    top: 50%;
    transform: translateY(-50%);
    color: var(--primary-color);
}

.filter-buttons {
    display: flex;
    gap: 1rem;
    flex-wrap: wrap;
}

.filter-btn {
    padding: 0.7rem 1.5rem;
    border: 2px solid #e5e7eb;
    background: white;
    border-radius: 25px;
    cursor: pointer;
    transition: all 0.3s ease;
    font-weight: 500;
    font-size: 0.95rem;
}

.filter-btn:hover,
.filter-btn.active {
    background: var(--primary-color);
    color: white;
    border-color: var(--primary-color);
}

/* MENU SECTION */
.menu-section {
    padding: 3rem 0;
}

.category-group {
    margin-bottom: 4rem;
}

.category-title {
    font-size: 2rem;
    font-weight: 800;
    margin-bottom: 2rem;
    color: var(--dark-color);
    padding-bottom: 1rem;
    border-bottom: 3px solid var(--primary-color);
    display: inline-block;
}

.menu-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
    gap: 2.5rem;
}

/* MENU ITEM CARD */
.menu-item-card {
    background: white;
    border-radius: 20px;
    overflow: hidden;
    box-shadow: var(--shadow-md);
    transition: all 0.4s ease;
    display: flex;
    flex-direction: column;
    height: 100%;
}

.menu-item-card:hover {
    transform: translateY(-10px);
    box-shadow: var(--shadow-xl);
}

.menu-item-image {
    width: 100%;
    height: 220px;
    object-fit: cover;
    transition: transform 0.4s ease;
}

.menu-item-card:hover .menu-item-image {
    transform: scale(1.1);
}

.menu-item-body {
    padding: 2rem;
    flex: 1;
    display: flex;
    flex-direction: column;
}

.menu-item-name {
    font-size: 1.3rem;
    font-weight: 700;
    color: var(--dark-color);
    margin-bottom: 0.7rem;
}

.menu-item-description {
    color: #6b7280;
    font-size: 0.95rem;
    margin-bottom: 1.5rem;
    line-height: 1.5;
    flex-grow: 1;
}

.menu-item-info {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 1.5rem;
}

.menu-item-price {
    font-size: 1.5rem;
    font-weight: 800;
    color: var(--primary-color);
}

.menu-item-category {
    display: inline-block;
    padding: 0.4rem 1rem;
    background: var(--primary-light);
    color: var(--primary-color);
    border-radius: 20px;
    font-size: 0.85rem;
    font-weight: 600;
}

.menu-item-footer {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 1rem;
}

.quantity-control {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    background: var(--light-bg);
    border-radius: 50px;
    padding: 0.4rem 0.8rem;
}

.quantity-control button {
    width: 30px;
    height: 30px;
    border-radius: 50%;
    background: white;
    border: 1px solid #e5e7eb;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.3s ease;
    font-weight: 600;
    color: var(--primary-color);
}

.quantity-control button:hover {
    background: var(--primary-color);
    color: white;
    border-color: var(--primary-color);
}

.quantity-display {
    text-align: center;
    font-weight: 600;
    min-width: 25px;
}

.add-to-cart-btn {
    width: 100%;
    padding: 0.8rem;
    background: var(--gradient-1);
    color: white;
    border: none;
    border-radius: 12px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
}

.add-to-cart-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 10px 25px rgba(56, 88, 233, 0.3);
}

.add-to-cart-btn:disabled {
    opacity: 0.6;
    cursor: not-allowed;
}

/* LOGIN PROMPT */
.login-prompt {
    display: flex;
    align-items: center;
    justify-content: center;
    height: 100%;
    padding: 2rem;
    background: var(--primary-light);
    border-radius: 12px;
}

.login-prompt a {
    padding: 0.8rem 2rem;
    background: var(--primary-color);
    color: white;
    text-decoration: none;
    border-radius: 25px;
    font-weight: 600;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.login-prompt a:hover {
    background: var(--primary-dark);
    transform: translateY(-2px);
}

/* RESTAURANT INFO CARD */
.restaurant-info-card {
    background: white;
    border-radius: 20px;
    padding: 3rem;
    margin-top: 3rem;
    box-shadow: var(--shadow-lg);
    text-align: center;
}

.info-stats {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
    gap: 2rem;
    margin-top: 2rem;
}

.info-stat {
    padding: 2rem;
    border-radius: 15px;
    background: var(--light-bg);
}

.info-stat h6 {
    font-weight: 600;
    margin-bottom: 0.5rem;
    color: var(--dark-color);
}

.info-stat p {
    color: #6b7280;
    font-size: 0.9rem;
}

/* FLOATING CART */
.floating-cart {
    position: fixed;
    bottom: 30px;
    right: 30px;
    z-index: 100;
    animation: slideInRight 0.5s ease;
}

.floating-cart-btn {
    width: 70px;
    height: 70px;
    border-radius: 50%;
    background: var(--gradient-1);
    color: white;
    border: none;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.8rem;
    box-shadow: var(--shadow-xl);
    transition: all 0.3s ease;
    position: relative;
}

.floating-cart-btn:hover {
    transform: scale(1.1);
}

.floating-cart-badge {
    position: absolute;
    top: -5px;
    right: -5px;
    width: 30px;
    height: 30px;
    border-radius: 50%;
    background: var(--secondary-color);
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 0.9rem;
    font-weight: 700;
}

/* EMPTY STATE */
.empty-state {
    text-align: center;
    padding: 5rem 2rem;
}

.empty-state i {
    font-size: 5rem;
    color: var(--primary-light);
    margin-bottom: 1.5rem;
}

.empty-state h4 {
    color: var(--dark-color);
    margin-bottom: 1rem;
}

.empty-state p {
    color: #6b7280;
    margin-bottom: 2rem;
}

/* ANIMATIONS */
@keyframes slideInRight {
    from {
        opacity: 0;
        transform: translateX(50px);
    }
    to {
        opacity: 1;
        transform: translateX(0);
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

/* BREADCRUMB */
.breadcrumb {
    padding: 1rem 0;
    background: transparent;
    margin-bottom: 2rem;
}

.breadcrumb-item {
    color: #6b7280;
}

.breadcrumb-item.active {
    color: var(--dark-color);
    font-weight: 600;
}

/* RESPONSIVE */
@media (max-width: 768px) {
    .restaurant-header {
        padding: 2rem 0;
    }

    .restaurant-header-content {
        grid-template-columns: 1fr;
        gap: 2rem;
    }

    .restaurant-info-section h1 {
        font-size: 2rem;
    }

    .restaurant-details {
        grid-template-columns: 1fr;
    }

    .restaurant-image {
        height: 250px;
    }

    .search-filter-content {
        grid-template-columns: 1fr;
        gap: 1rem;
    }

    .menu-grid {
        grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
        gap: 1.5rem;
    }

    .floating-cart {
        bottom: 20px;
        right: 20px;
    }

    .floating-cart-btn {
        width: 60px;
        height: 60px;
        font-size: 1.5rem;
    }
}

@media (max-width: 480px) {
    .restaurant-image-section {
        display: none;
    }

    .restaurant-info-section h1 {
        font-size: 1.5rem;
    }

    .menu-grid {
        grid-template-columns: 1fr;
    }

    .filter-buttons {
        flex-direction: column;
    }

    .filter-btn {
        width: 100%;
    }
}
</style>

<!-- RESTAURANT HEADER -->
<section class="restaurant-header">
    <div class="container">
        <div class="restaurant-header-content">
            <!-- Restaurant Info -->
            <div class="restaurant-info-section">
                <h1><?php echo htmlspecialchars($restaurant['name']); ?></h1>
                <p><?php echo htmlspecialchars($restaurant['description']); ?></p>

                <div class="restaurant-details">
                    <div class="detail-item">
                        <div class="detail-icon">
                            <i class="bi bi-star-fill"></i>
                        </div>
                        <div class="detail-text">
                            <h6>Rating</h6>
                            <p><?php echo number_format($restaurant['rating'], 1); ?>/5.0</p>
                        </div>
                    </div>

                    <div class="detail-item">
                        <div class="detail-icon">
                            <i class="bi bi-clock"></i>
                        </div>
                        <div class="detail-text">
                            <h6>Delivery Time</h6>
                            <p><?php echo htmlspecialchars($restaurant['delivery_time']); ?></p>
                        </div>
                    </div>

                    <div class="detail-item">
                        <div class="detail-icon">
                            <i class="bi bi-telephone"></i>
                        </div>
                        <div class="detail-text">
                            <h6>Phone</h6>
                            <p><?php echo htmlspecialchars($restaurant['phone']); ?></p>
                        </div>
                    </div>

                    <div class="detail-item">
                        <div class="detail-icon">
                            <i class="bi bi-geo-alt"></i>
                        </div>
                        <div class="detail-text">
                            <h6>Location</h6>
                            <p><?php echo htmlspecialchars(substr($restaurant['address'], 0, 30)); ?>...</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Restaurant Image -->
            <div class="restaurant-image-section">
                <?php
                $rest_image = getImagePath($restaurant['image'], 'restaurant');
                ?>
                <img src="<?php echo htmlspecialchars($rest_image); ?>"
                     alt="<?php echo htmlspecialchars($restaurant['name']); ?>"
                     class="restaurant-image"
                     onerror="this.src='https://images.unsplash.com/photo-1517248135467-4c7edcad34c4?w=800'">
            </div>
        </div>
    </div>
</section>

<!-- SEARCH & FILTER -->
<section class="search-filter-section">
    <div class="container">
        <div class="search-filter-content">
            <!-- Search -->
            <div class="search-box">
                <input type="text" id="menuSearch" placeholder="Search menu items...">
                <i class="bi bi-search"></i>
            </div>

            <!-- Category Filter -->
            <div class="filter-buttons" id="categoryFilters">
                <button class="filter-btn active" data-category="all">All Items</button>
                <?php foreach (array_keys($categorized_items) as $category): ?>
                <button class="filter-btn" data-category="<?php echo htmlspecialchars(strtolower($category)); ?>">
                    <?php echo htmlspecialchars($category); ?>
                </button>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</section>

<!-- MENU ITEMS -->
<section class="menu-section">
    <div class="container">
        <?php if (!empty($categorized_items)): ?>
            <?php foreach ($categorized_items as $category => $items): ?>
            <div class="category-group" data-category="<?php echo htmlspecialchars(strtolower($category)); ?>">
                <h2 class="category-title">
                    <i class="bi bi-bookmark-fill me-2"></i><?php echo htmlspecialchars($category); ?>
                </h2>

                <div class="menu-grid">
                    <?php foreach ($items as $item):
                        $item_image = getImagePath($item['image'], 'menu');
                    ?>
                    <div class="menu-item-card fade-in menu-item" data-name="<?php echo htmlspecialchars(strtolower($item['name'])); ?>">
                        <img src="<?php echo htmlspecialchars($item_image); ?>"
                             alt="<?php echo htmlspecialchars($item['name']); ?>"
                             class="menu-item-image"
                             onerror="this.src='https://images.unsplash.com/photo-1546833999-b9f581a1996d?w=400'">

                        <div class="menu-item-body">
                            <h5 class="menu-item-name"><?php echo htmlspecialchars($item['name']); ?></h5>
                            <p class="menu-item-description"><?php echo htmlspecialchars($item['description']); ?></p>

                            <div class="menu-item-info">
                                <span class="menu-item-price">Rs. <?php echo number_format($item['price'], 2); ?></span>
                                <span class="menu-item-category"><?php echo htmlspecialchars($item['category']); ?></span>
                            </div>

                            <div class="menu-item-footer">
                                <div class="quantity-control">
                                    <button class="qty-btn qty-decrease" data-id="<?php echo $item['id']; ?>">−</button>
                                    <span class="quantity-display qty-<?php echo $item['id']; ?>">1</span>
                                    <button class="qty-btn qty-increase" data-id="<?php echo $item['id']; ?>">+</button>
                                </div>

                                <?php if (isLoggedIn()): ?>
                                <button class="add-to-cart-btn"
                                        data-id="<?php echo $item['id']; ?>"
                                        data-name="<?php echo htmlspecialchars($item['name']); ?>"
                                        data-price="<?php echo $item['price']; ?>"
                                        data-restaurant="<?php echo $restaurant_id; ?>"
                                        onclick="addToCart(this)">
                                    <i class="bi bi-cart-plus"></i> Add
                                </button>
                                <?php else: ?>
                                <div class="login-prompt">
                                    <a href="login.php">
                                        <i class="bi bi-box-arrow-in-right"></i> Login
                                    </a>
                                </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="empty-state">
                <i class="bi bi-basket"></i>
                <h4>No Menu Items Available</h4>
                <p>This restaurant hasn't added menu items yet. Please check back later.</p>
                <a href="home.php" class="btn btn-primary">Browse Other Restaurants</a>
            </div>
        <?php endif; ?>
    </div>
</section>

<!-- RESTAURANT INFO CARD -->
<?php if (!empty($categorized_items)): ?>
<section class="py-5">
    <div class="container">
        <div class="restaurant-info-card">
            <h3>About <?php echo htmlspecialchars($restaurant['name']); ?></h3>
            <p class="text-muted mt-3" style="max-width: 600px; margin-left: auto; margin-right: auto;">
                <?php echo htmlspecialchars($restaurant['description']); ?>
            </p>

            <div class="info-stats">
                <div class="info-stat">
                    <h6>Rating</h6>
                    <p><?php echo number_format($restaurant['rating'], 1); ?> ⭐</p>
                </div>
                <div class="info-stat">
                    <h6>Delivery Time</h6>
                    <p><?php echo htmlspecialchars($restaurant['delivery_time']); ?></p>
                </div>
                <div class="info-stat">
                    <h6>Menu Items</h6>
                    <p><?php echo array_sum(array_map('count', $categorized_items)); ?> items</p>
                </div>
                <div class="info-stat">
                    <h6>Availability</h6>
                    <p>Open Now 🟢</p>
                </div>
            </div>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- FLOATING CART (If user logged in and has items) -->
<?php if (isLoggedIn()): ?>
<div class="floating-cart" id="floatingCart" style="display: none;">
    <a href="cart.php" class="floating-cart-btn">
        <i class="bi bi-cart3"></i>
        <span class="floating-cart-badge" id="cartBadge">0</span>
    </a>
</div>
<?php endif; ?>

<!-- JAVASCRIPT -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Quantity controls
    const qtyDecreaseButtons = document.querySelectorAll('.qty-decrease');
    const qtyIncreaseButtons = document.querySelectorAll('.qty-increase');

    qtyDecreaseButtons.forEach(btn => {
        btn.addEventListener('click', function() {
            const id = this.dataset.id;
            const display = document.querySelector('.qty-' + id);
            let qty = parseInt(display.textContent);
            if (qty > 1) {
                display.textContent = qty - 1;
            }
        });
    });

    qtyIncreaseButtons.forEach(btn => {
        btn.addEventListener('click', function() {
            const id = this.dataset.id;
            const display = document.querySelector('.qty-' + id);
            let qty = parseInt(display.textContent);
            display.textContent = qty + 1;
        });
    });

    // Search functionality
    const searchInput = document.getElementById('menuSearch');
    if (searchInput) {
        searchInput.addEventListener('keyup', function() {
            const searchTerm = this.value.toLowerCase();
            const menuItems = document.querySelectorAll('.menu-item');

            menuItems.forEach(item => {
                const itemName = item.dataset.name || '';
                const itemText = item.textContent.toLowerCase();

                if (searchTerm === '' || itemName.includes(searchTerm) || itemText.includes(searchTerm)) {
                    item.style.display = 'block';
                } else {
                    item.style.display = 'none';
                }
            });
        });
    }

    // Category filter
    const categoryFilters = document.querySelectorAll('.filter-btn');
    categoryFilters.forEach(btn => {
        btn.addEventListener('click', function() {
            const category = this.dataset.category;

            // Update active button
            categoryFilters.forEach(b => b.classList.remove('active'));
            this.classList.add('active');

            // Show/hide categories
            const categories = document.querySelectorAll('.category-group');
            categories.forEach(cat => {
                if (category === 'all' || cat.dataset.category === category) {
                    cat.style.display = 'block';
                } else {
                    cat.style.display = 'none';
                }
            });
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
        threshold: 0.1
    });

    fadeElements.forEach(element => {
        element.style.opacity = '0';
        fadeObserver.observe(element);
    });
});

// Add to cart function
function addToCart(button) {
    const itemId = button.dataset.id;
    const itemName = button.dataset.name;
    const itemPrice = button.dataset.price;
    const restaurantId = button.dataset.restaurant;
    const quantity = parseInt(document.querySelector('.qty-' + itemId).textContent);

    // Get quantity input
    const qtyDisplay = document.querySelector('.qty-' + itemId);
    const quantity_final = parseInt(qtyDisplay.textContent);

    // Show loading state
    const originalText = button.innerHTML;
    button.disabled = true;
    button.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span>Adding...';

    // Send AJAX request
    fetch('../ajax/add-to-cart.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: `item_id=${itemId}&restaurant_id=${restaurantId}`,
        credentials: 'same-origin'
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Show success message
            showToast('✓ ' + itemName + ' added to cart!', 'success');

            // Update cart badge
            const cartBadge = document.getElementById('cartBadge');
            if (cartBadge) {
                cartBadge.textContent = data.cart_count;
                document.getElementById('floatingCart').style.display = 'block';
            }

            // Reset button
            button.innerHTML = '<i class="bi bi-check-circle-fill me-1"></i>Added!';
            button.style.background = 'linear-gradient(135deg, #43e97b 0%, #38f9d7 100%)';

            setTimeout(() => {
                button.innerHTML = originalText;
                button.disabled = false;
                button.style.background = '';
                // Reset quantity to 1
                document.querySelector('.qty-' + itemId).textContent = '1';
            }, 2000);
        } else {
            showToast('Error: ' + (data.message || 'Failed to add item'), 'danger');
            button.disabled = false;
            button.innerHTML = originalText;
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showToast('Connection error. Please try again.', 'danger');
        button.disabled = false;
        button.innerHTML = originalText;
    });
}

// Toast notification
function showToast(message, type = 'success') {
    const toast = document.createElement('div');
    toast.className = `toast align-items-center text-white bg-${type} border-0 position-fixed bottom-0 end-0 m-3`;
    toast.style.zIndex = '9999';
    toast.setAttribute('role', 'alert');
    toast.innerHTML = `
        <div class="d-flex">
            <div class="toast-body">
                ${message}
            </div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" onclick="this.closest('.toast').remove()"></button>
        </div>
    `;

    document.body.appendChild(toast);
    const bsToast = new bootstrap.Toast(toast);
    bsToast.show();

    setTimeout(() => {
        toast.remove();
    }, 4000);
}

// Smooth scroll for links
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