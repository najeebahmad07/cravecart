-- CraveCart Database Schema
CREATE DATABASE IF NOT EXISTS cravecart;
USE cravecart;

-- Users Table
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    phone VARCHAR(20),
    password VARCHAR(255) NOT NULL,
    address TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Admin Table
CREATE TABLE admin (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Restaurants Table
CREATE TABLE restaurants (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(200) NOT NULL,
    description TEXT,
    image VARCHAR(255),
    address TEXT,
    phone VARCHAR(20),
    rating DECIMAL(2,1) DEFAULT 4.0,
    delivery_time VARCHAR(50) DEFAULT '30-40 min',
    is_active TINYINT(1) DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Menu Items Table
CREATE TABLE menu_items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    restaurant_id INT NOT NULL,
    name VARCHAR(200) NOT NULL,
    description TEXT,
    price DECIMAL(10,2) NOT NULL,
    image VARCHAR(255),
    category VARCHAR(100),
    is_available TINYINT(1) DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (restaurant_id) REFERENCES restaurants(id) ON DELETE CASCADE
);

-- Orders Table
CREATE TABLE orders (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    total_amount DECIMAL(10,2) NOT NULL,
    delivery_address TEXT NOT NULL,
    phone VARCHAR(20) NOT NULL,
    payment_method VARCHAR(50) DEFAULT 'Cash on Delivery',
    status ENUM('Pending', 'Preparing', 'Delivered', 'Cancelled') DEFAULT 'Pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Order Items Table
CREATE TABLE order_items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT NOT NULL,
    menu_item_id INT NOT NULL,
    restaurant_id INT NOT NULL,
    quantity INT NOT NULL,
    price DECIMAL(10,2) NOT NULL,
    FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE,
    FOREIGN KEY (menu_item_id) REFERENCES menu_items(id) ON DELETE CASCADE,
    FOREIGN KEY (restaurant_id) REFERENCES restaurants(id) ON DELETE CASCADE
);

-- Insert Admin User
INSERT INTO admin (name, email, password) VALUES
('Admin', 'admin@cravecart.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi');
-- Password: password

-- Insert Sample Users
INSERT INTO users (name, email, phone, password, address) VALUES
('Neha Qazmi', 'neha@example.com', '+92-300-1234567', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'House 123, Block A, Gulshan-e-Iqbal, Karachi'),
('Shama Parveen', 'shama@example.com', '+92-301-2345678', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Flat 45, Clifton Heights, Karachi'),
('Sana Khan', 'sana@example.com', '+92-302-3456789', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Plot 67, Defence Phase 5, Karachi'),
('Ayesha Ali', 'ayesha@example.com', '+92-303-4567890', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'House 89, North Nazimabad, Karachi'),
('Fatima Noor', 'fatima@example.com', '+92-304-5678901', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Apartment 12, Bahria Town, Karachi'),
('Ayaan Khan', 'ayaan@example.com', '+92-305-6789012', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Villa 34, DHA Phase 8, Karachi'),
('Zaid Ali', 'zaid@example.com', '+92-306-7890123', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'House 56, Malir Cantt, Karachi'),
('Sameer Hussain', 'sameer@example.com', '+92-307-8901234', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Flat 78, Saddar Town, Karachi'),
('Faizan Ahmed', 'faizan@example.com', '+92-308-9012345', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'House 90, Johar Town, Karachi'),
('Imran Malik', 'imran@example.com', '+92-309-0123456', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Plot 23, FB Area, Karachi');

-- Insert Sample Restaurants
INSERT INTO restaurants (name, description, image, address, phone, rating, delivery_time) VALUES
('Al-Falah Biryani House', 'Authentic Hyderabadi Biryani & Karahi Specialties. Serving the best traditional flavors since 1995.', 'https://images.unsplash.com/photo-1563379091339-03b21ab4a4f8?w=800', 'Shop 12, Main Tariq Road, PECHS, Karachi', '+92-21-34567890', 4.8, '25-35 min'),
('Noor Dhaba', 'Traditional Desi Cuisine & Tandoori Delights. Experience the taste of Punjab in every bite.', 'https://images.unsplash.com/photo-1517248135467-4c7edcad34c4?w=800', 'Near Jinnah Hospital, Stadium Road, Karachi', '+92-21-35678901', 4.6, '30-40 min'),
('Rehmani Tandoor Point', 'Fresh Naan, BBQ & Seekh Kebabs. Grilled to perfection with secret family recipes.', 'https://images.unsplash.com/photo-1552566626-52f8b828add9?w=800', 'Block 7, Shaheed-e-Millat Road, Karachi', '+92-21-36789012', 4.7, '20-30 min'),
('Khan Chicken Corner', 'Crispy Fried Chicken & Burgers. Fast food done right with halal ingredients.', 'https://images.unsplash.com/photo-1555396273-367ea4eb4db5?w=800', 'University Road, Near Samama Shopping Center, Karachi', '+92-21-37890123', 4.5, '15-25 min'),
('Punjabi Zaika Halal Kitchen', 'Homestyle Pakistani Food & Daily Specials. Taste like your mother cooks.', 'https://images.unsplash.com/photo-1414235077428-338989a2e8c0?w=800', 'Bahadurabad Chowrangi, Karachi', '+92-21-38901234', 4.9, '35-45 min');

-- Insert Sample Menu Items
-- Al-Falah Biryani House
INSERT INTO menu_items (restaurant_id, name, description, price, image, category) VALUES
(1, 'Chicken Biryani', 'Aromatic basmati rice with tender chicken pieces, spices & raita', 350.00, 'https://images.unsplash.com/photo-1563379091339-03b21ab4a4f8?w=400', 'Main Course'),
(1, 'Mutton Biryani', 'Premium mutton biryani with fragrant spices and boiled egg', 550.00, 'https://images.unsplash.com/photo-1589302168068-964664d93dc0?w=400', 'Main Course'),
(1, 'Chicken Karahi', 'Traditional chicken karahi cooked in tomato gravy', 850.00, 'https://images.unsplash.com/photo-1603894584373-5ac82b2ae398?w=400', 'Main Course'),
(1, 'Raita', 'Fresh yogurt with cucumber and spices', 80.00, 'https://images.unsplash.com/photo-1626200419199-391ae4be7a41?w=400', 'Side Dish'),

-- Noor Dhaba
(2, 'Daal Makhani', 'Creamy black lentils slow-cooked with butter', 280.00, 'https://images.unsplash.com/photo-1546833999-b9f581a1996d?w=400', 'Main Course'),
(2, 'Butter Chicken', 'Tender chicken in rich tomato butter sauce', 480.00, 'https://images.unsplash.com/photo-1603894584373-5ac82b2ae398?w=400', 'Main Course'),
(2, 'Garlic Naan', 'Freshly baked naan topped with garlic & butter', 60.00, 'https://images.unsplash.com/photo-1619362280286-04faf8f66c6e?w=400', 'Bread'),
(2, 'Paneer Tikka', 'Grilled cottage cheese marinated in spices', 380.00, 'https://images.unsplash.com/photo-1567188040759-fb8a883dc6d8?w=400', 'Appetizer'),

-- Rehmani Tandoor Point
(3, 'Seekh Kebab (6 pcs)', 'Minced meat skewers grilled in tandoor', 420.00, 'https://images.unsplash.com/photo-1529042410759-befb1204b468?w=400', 'BBQ'),
(3, 'Chicken Tikka', 'Boneless chicken pieces marinated and grilled', 450.00, 'https://images.unsplash.com/photo-1599487488170-d11ec9c172f0?w=400', 'BBQ'),
(3, 'Tandoori Roti', 'Whole wheat bread baked in clay oven', 30.00, 'https://images.unsplash.com/photo-1628408891486-c460781bd3aa?w=400', 'Bread'),
(3, 'Mix Grill Platter', 'Assorted BBQ with kebabs, tikka & chops', 1200.00, 'https://images.unsplash.com/photo-1544025162-d76694265947?w=400', 'BBQ'),

-- Khan Chicken Corner
(4, 'Crispy Fried Chicken (4 pcs)', 'Golden fried chicken with secret spices', 380.00, 'https://images.unsplash.com/photo-1626082927389-6cd097cdc6ec?w=400', 'Fast Food'),
(4, 'Zinger Burger', 'Spicy crispy chicken burger with special sauce', 280.00, 'https://images.unsplash.com/photo-1568901346375-23c9450c58cd?w=400', 'Fast Food'),
(4, 'Chicken Nuggets (10 pcs)', 'Crispy chicken nuggets perfect for sharing', 320.00, 'https://images.unsplash.com/photo-1562967914-608f82629710?w=400', 'Fast Food'),
(4, 'French Fries', 'Crispy golden fries with ketchup', 120.00, 'https://images.unsplash.com/photo-1573080496219-bb080dd4f877?w=400', 'Side Dish'),

-- Punjabi Zaika Halal Kitchen
(5, 'Nihari', 'Slow-cooked beef stew with traditional spices', 420.00, 'https://images.unsplash.com/photo-1631452180519-c014fe946bc7?w=400', 'Main Course'),
(5, 'Haleem', 'Rich meat and lentil porridge - signature dish', 350.00, 'https://images.unsplash.com/photo-1645177628172-a94c1f96e6db?w=400', 'Main Course'),
(5, 'Chicken Korma', 'Mild chicken curry with yogurt and spices', 400.00, 'https://images.unsplash.com/photo-1603894584373-5ac82b2ae398?w=400', 'Main Course'),
(5, 'Paratha', 'Flaky layered flatbread cooked on griddle', 40.00, 'https://images.unsplash.com/photo-1628408890895-f4d32d5280a9?w=400', 'Bread');

-- Insert Sample Orders
INSERT INTO orders (user_id, total_amount, delivery_address, phone, status, created_at) VALUES
(1, 730.00, 'House 123, Block A, Gulshan-e-Iqbal, Karachi', '+92-300-1234567', 'Delivered', '2024-01-15 12:30:00'),
(2, 1100.00, 'Flat 45, Clifton Heights, Karachi', '+92-301-2345678', 'Delivered', '2024-01-16 14:20:00'),
(3, 950.00, 'Plot 67, Defence Phase 5, Karachi', '+92-302-3456789', 'Preparing', '2024-01-17 18:45:00'),
(4, 600.00, 'House 89, North Nazimabad, Karachi', '+92-303-4567890', 'Pending', '2024-01-17 19:30:00');

-- Insert Sample Order Items
INSERT INTO order_items (order_id, menu_item_id, restaurant_id, quantity, price) VALUES
(1, 1, 1, 2, 350.00),
(1, 4, 1, 1, 80.00),
(2, 5, 2, 1, 280.00),
(2, 6, 2, 1, 480.00),
(2, 7, 2, 4, 60.00),
(3, 9, 3, 2, 420.00),
(3, 11, 3, 3, 30.00),
(4, 13, 4, 1, 380.00),
(4, 14, 4, 1, 280.00);