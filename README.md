# 🍕 CraveCart - Modern Food Delivery Web Application

> A professionally built, fully functional food delivery web application with modern UI/UX, complete admin panel, and seamless user experience. Built with Core PHP, MySQL, and Bootstrap 5.

![CraveCart](https://img.shields.io/badge/CraveCart-v1.0.0-blue?style=for-the-badge)
![PHP Version](https://img.shields.io/badge/PHP-7.4%2B-green?style=for-the-badge)
![MySQL](https://img.shields.io/badge/MySQL-5.7%2B-blue?style=for-the-badge)
![Bootstrap](https://img.shields.io/badge/Bootstrap-5.3-purple?style=for-the-badge)
![License](https://img.shields.io/badge/License-MIT-green?style=for-the-badge)
![Status](https://img.shields.io/badge/Status-Active-success?style=for-the-badge)

---

## 📋 Quick Navigation

- [🎯 Features](#features)
- [🛠️ Tech Stack](#tech-stack)
- [📥 Installation](#installation)
- [🗄️ Database Setup](#database-setup)
- [⚙️ Configuration](#configuration)
- [🚀 Running Application](#running-application)
- [🔐 Login Credentials](#login-credentials)
- [✅ Testing Guide](#testing-guide)
- [📁 Project Structure](#project-structure)
- [🔌 API Endpoints](#api-endpoints)
- [🐛 Troubleshooting](#troubleshooting)
- [🎨 Design Features](#design-features)
- [📱 Features Overview](#features-overview)
- [🔒 Security](#security)
- [📞 Support](#support)

---

## 🎯 Features

### 👤 User Side Features

#### Authentication & Account Management
CraveCart provides complete user authentication with secure password hashing using PHP's `password_hash()` function. Users can create accounts with their full name, email, phone number, and delivery address. The registration process includes password confirmation validation to ensure secure passwords. Once registered, users are automatically logged in and directed to their dashboard. The login system remembers users and maintains secure sessions.

**User Features Include:**
- **User Registration** - Complete signup with validation
- **Secure Login** - Session-based authentication with password hashing
- **Profile Management** - View and update user information
- **Address Book** - Save delivery addresses
- **Order History** - Complete order tracking and details
- **Order Cancellation** - Cancel orders before preparation
- **Favorites** - Save favorite restaurants and items

#### Restaurant & Menu Browsing
Users can browse through a beautiful collection of restaurants with real images, ratings, delivery times, and phone numbers. The restaurant listing includes advanced filtering options and search functionality. Each restaurant has a dedicated page showing the complete menu with food categories, descriptions, prices, and high-quality images.

**Browsing Features:**
- **Restaurant Listings** - Browse all active restaurants with ratings
- **Advanced Search** - Find restaurants by name or cuisine type
- **Category Filtering** - Filter by restaurant type or cuisine
- **Detailed Restaurant Pages** - Full menu with categories and descriptions
- **Restaurant Information** - Address, phone, ratings, delivery time
- **Real Food Images** - All menu items have professional food images
- **Availability Status** - Know which items are available

#### Shopping Cart & Checkout
The shopping cart system is built with smooth AJAX functionality. Users can add items to cart, adjust quantities, remove items, and see real-time price updates. The cart persists in user sessions and displays a badge count in the navbar. The checkout process is streamlined with pre-filled user information and flexible payment options.

**Shopping Features:**
- **Add to Cart** - AJAX-powered, no page reload
- **Quantity Control** - Increase/decrease items with +/- buttons
- **Remove Items** - Remove items from cart with confirmation
- **Real-time Updates** - Cart total updates instantly
- **Cart Persistence** - Cart saved in secure sessions
- **Cart Badge** - Shows item count in navbar
- **Clear Cart** - Option to clear entire cart
- **Price Calculation** - Automatic total with delivery fee and tax

#### Checkout & Order Placement
The checkout process is secure and user-friendly. It includes delivery address confirmation, payment method selection, and order review before final submission. The system calculates totals including delivery fees and taxes automatically.

**Checkout Features:**
- **Delivery Address Confirmation** - Pre-filled from profile, editable
- **Payment Methods** - Cash on Delivery, Credit Card, Debit Card
- **Order Review** - See all items, quantities, and final price
- **Special Instructions** - Add notes for delivery person
- **Real-time Calculations** - Subtotal, delivery fee, tax, total
- **Order Confirmation** - Instant order success page with order ID
- **Receipt Generation** - Email receipt of order details

#### Order Tracking & Dashboard
The user dashboard provides a comprehensive overview of account activity and order history. Users can view all their orders with status updates, filter by status, view detailed order information, and access their profile settings.

**Dashboard Features:**
- **Statistics** - Total orders, total spent, member since date
- **Order History** - All orders with status and date
- **Order Details Modal** - View complete order information
- **Status Tracking** - Pending, Preparing, Delivered, Cancelled
- **Re-order** - Quick reorder from previous orders
- **Profile Management** - Update personal information
- **Address Management** - Add/edit delivery addresses

### 🛠️ Admin Panel Features

#### Restaurant Management Module
The admin panel includes a complete restaurant management system. Admins can add new restaurants with name, description, address, phone, rating, and delivery time. Each restaurant can have a professional image uploaded. The list view shows all restaurants with options to edit or delete. Real-time validation ensures data quality.

**Restaurant Module:**
- **Add Restaurant** - Create new restaurant with all details
- **Upload Image** - Support for jpg, png, webp, avif formats
- **Edit Restaurant** - Update any restaurant information
- **Delete Restaurant** - Remove restaurants from system
- **View List** - All restaurants in table format with search
- **Toggle Active Status** - Enable/disable restaurants
- **Image Management** - Automatic image resizing and optimization

#### Menu Items Management
Admins can manage menu items for each restaurant. The system allows adding items with name, description, price, category, and image. Items can be edited or deleted. The menu management interface supports filtering by restaurant and category. Real-time updates ensure consistency.

**Menu Module:**
- **Add Menu Item** - Create item for specific restaurant
- **Select Restaurant** - Assign item to restaurant
- **Set Category** - Organize items by category (Main Course, Appetizer, Dessert, etc.)
- **Price Management** - Set and update menu item prices
- **Upload Image** - Add professional food images
- **Edit Item** - Update any menu item details
- **Delete Item** - Remove items from menu
- **Availability Toggle** - Mark items as available/unavailable
- **Bulk Import** - Import multiple items at once

#### Order Management System
The admin order management system provides complete visibility into all orders. Admins can view order details including items, quantities, customer information, delivery address, and total amount. Order status can be updated from Pending to Preparing to Delivered. The system logs all status changes with timestamps.

**Orders Module:**
- **View All Orders** - Complete order list with filters
- **Order Details** - See all items, quantities, and customer info
- **Update Status** - Change from Pending → Preparing → Delivered
- **Status Timestamps** - Track when each status was updated
- **Search Orders** - Find orders by order ID or customer name
- **Filter by Status** - View orders by current status
- **Delete Orders** - Remove cancelled or test orders
- **Order Statistics** - Total revenue, orders per day, etc.

#### User Management
Admins can view all registered users with their information including name, email, phone, registration date, and total orders. The user list provides insights into user engagement and can help with customer management.

**Users Module:**
- **View All Users** - Complete user list with details
- **User Details** - Name, email, phone, address, registration date
- **Order Count** - See how many orders each user has placed
- **Total Spent** - See user lifetime spending
- **Search Users** - Find users by name or email
- **User Activity** - Last order date and time

#### Dashboard & Analytics
The admin dashboard provides a comprehensive overview with key statistics, recent orders, and system health indicators. It includes total user count, total restaurants, total orders, and revenue metrics. The dashboard is mobile-responsive and updates in real-time.

**Dashboard Features:**
- **Statistics Cards** - Users, Restaurants, Orders, Revenue
- **Recent Orders** - Latest orders with status
- **Revenue Chart** - Visual representation of sales
- **Top Restaurants** - Most ordered restaurants
- **User Growth** - New users over time
- **Quick Actions** - Fast access to common tasks
- **Notifications** - New orders, user signups
- **System Status** - Database and server status

### 🎨 Design & User Experience Features

#### Modern 2026 UI Design
CraveCart features a premium, modern interface built with the latest design trends. The color scheme uses a beautiful primary blue (#3858e9) with complementary colors. Typography uses the modern Poppins font from Google Fonts. The design includes glassmorphism effects, smooth animations, and professional gradients.

**Design Elements:**
- **Color Scheme** - Primary blue (#3858e9) with professional gradients
- **Typography** - Poppins font from Google Fonts (300-700 weights)
- **Glassmorphism** - Frosted glass effect on cards and overlays
- **Soft Shadows** - Professional shadow effects for depth
- **Smooth Animations** - Fade in, slide, bounce animations
- **Hover Effects** - Interactive visual feedback
- **Gradients** - Beautiful gradient backgrounds and buttons
- **Spacing** - Professional whitespace and padding
- **Border Radius** - Modern rounded corners (12-20px)

#### Hero Section & Slider
The home page features a stunning hero section with a 5-slide carousel. Each slide has a full-width background image with overlay gradient. The slides include compelling headlines, subheadlines, and call-to-action buttons. Slide transitions are smooth with fade effects. Navigation dots allow users to jump to specific slides.

**Hero Features:**
- **5 Slide Carousel** - Auto-play with manual navigation
- **Full-width Images** - Professional food photography
- **Overlay Gradients** - Beautiful color overlays
- **Animated Text** - Fade in text animations
- **CTA Buttons** - Clear call-to-action on each slide
- **Auto-advance** - 5 second slide interval
- **Manual Navigation** - Click dots to select slides
- **Responsive** - Works on all screen sizes

#### Responsive Design
CraveCart is fully responsive with proper layouts for mobile (320px), tablet (768px), and desktop (1024px+). The Bootstrap 5 grid system ensures perfect alignment. Navigation adapts with a hamburger menu on mobile. Images scale properly. All forms are touch-friendly with adequate spacing.

**Responsive Features:**
- **Mobile First Design** - Optimized for small screens
- **Tablet Optimization** - Perfect layout on tablets
- **Desktop Full** - Enhanced experience on large screens
- **Flexible Grid** - Bootstrap responsive grid system
- **Mobile Menu** - Hamburger menu for navigation
- **Touch Friendly** - Large buttons and tap targets
- **Image Scaling** - Proper image sizing for all devices
- **Font Scaling** - Readable text on all screen sizes

#### Interactive Elements
The interface includes smooth AJAX functionality for cart operations without page reloads. Toast notifications provide instant feedback. Modals display order details. Forms have real-time validation. Loading states show progress during operations.

**Interactivity Features:**
- **AJAX Operations** - No page reload for cart actions
- **Toast Notifications** - Success/error messages
- **Loading States** - Spinners during operations
- **Modal Dialogs** - View details in beautiful modals
- **Form Validation** - Real-time input validation
- **Keyboard Navigation** - Accessible navigation
- **Focus Management** - Proper focus for accessibility
- **Lazy Loading** - Images load as needed

#### Search & Filtering
Users can search for restaurants and menu items with real-time filtering. The search highlights relevant results. Category filters allow browsing by type. Search is case-insensitive and includes partial matching.

**Search Features:**
- **Real-time Search** - Results update as you type
- **Multi-field Search** - Search by name, description, category
- **Category Filters** - Filter by restaurant type
- **Advanced Filters** - Rating, delivery time, price range
- **Highlight Results** - Found items highlighted
- **Clear Filters** - Reset all filters easily
- **Search History** - Remember previous searches
- **Suggestions** - Autocomplete suggestions

---

## 🛠️ Tech Stack

### Frontend Technologies

| Technology | Version | Purpose |
|-----------|---------|---------|
| **HTML5** | Latest | Semantic markup & structure |
| **CSS3** | Latest | Styling with modern features |
| **Bootstrap 5** | 5.3.2 | Responsive grid & components |
| **JavaScript (Vanilla)** | ES6+ | AJAX & interactivity |
| **jQuery** | 3.7.1 | DOM manipulation & AJAX |
| **Bootstrap Icons** | 1.11.3 | Professional icon set |
| **Google Fonts** | - | Poppins font family |

### Backend Technologies

| Technology | Version | Purpose |
|-----------|---------|---------|
| **PHP** | 7.4+ | Server-side logic |
| **MySQL** | 5.7+ | Database management |
| **Apache** | 2.4+ | Web server |
| **Apache Modules** | - | mod_rewrite for clean URLs |

### Tools & Platforms

| Tool | Purpose |
|------|---------|
| **XAMPP** | Local development environment |
| **phpMyAdmin** | Database management |
| **Git** | Version control |
| **GitHub** | Code repository |
| **Unsplash** | Free high-quality images |
| **VS Code** | Code editor |

### Supported Image Formats

- JPG / JPEG - Compressed photos
- PNG - Transparent images
- WebP - Modern format
- AVIF - Next-gen format
- Upload limit: 5MB per file

---

## 📥 Installation Guide

### Prerequisites - What You Need Before Starting

Before installing CraveCart, ensure your computer has the following:

1. **XAMPP** (8.0 or higher) - Local PHP environment with Apache and MySQL
2. **Web Browser** - Chrome, Firefox, Safari, or Edge (latest version)
3. **Text Editor** - VS Code or any modern code editor
4. **Git** (optional) - For cloning from GitHub
5. **Administrator Access** - Needed for folder permissions
6. **Internet Connection** - For downloading and initial setup

### Step 1: Install XAMPP

#### For Windows Users

XAMPP is a cross-platform package containing Apache, MySQL, and PHP. Follow these steps:

1. **Download XAMPP**
   - Visit the official website: `https://www.apachefriends.org/`
   - Click the "Download" button
   - Select "XAMPP for Windows" (version 8.2 or higher is recommended)
   - The file will be named something like `xampp-windows-x64-8.2.0-installer.exe`

2. **Run the Installer**
   - Double-click the downloaded `.exe` file
   - Windows may show a "User Account Control" dialog - click "Yes" to allow
   - The XAMPP Setup Wizard will open

3. **Follow Installation Steps**
   - Read the welcome message and click "Next"
   - On "Select Components" screen, ensure these are checked:
     - ✅ Apache
     - ✅ MySQL
     - ✅ PHP
     - ✅ phpMyAdmin
     - (Other components are optional)
   - Click "Next"

4. **Choose Installation Folder**
   - Default location is `C:\xampp` (recommended)
   - Click "Next" to proceed

5. **Complete Installation**
   - Let the installer copy all files (may take 2-3 minutes)
   - Installation progress bar will fill
   - Click "Finish" when complete

6. **XAMPP Control Panel**
   - A shortcut will appear on your desktop
   - You can also find it in Windows Start Menu → XAMPP Control Panel

#### For Mac Users

1. **Download XAMPP**
   - Visit `https://www.apachefriends.org/`
   - Select "XAMPP for macOS"
   - Download the `.dmg` file

2. **Install from DMG**
   - Open the downloaded `.dmg` file
   - A Finder window appears with XAMPP folder
   - Drag XAMPP folder to "Applications" folder
   - Wait for copy to complete

3. **Launch XAMPP**
   - Open Applications folder
   - Find and open "XAMPP" folder
   - Double-click "manager-osx" application
   - XAMPP Control Panel will open

#### For Linux Users

1. **Download XAMPP**
   ```bash
   wget https://www.apachefriends.org/xampp-files/8.2.0/xampp-linux-x64-8.2.0-installer.tar.gz



    Extract XAMPP

    Bash

    sudo tar xvfz xampp-linux-x64-8.2.0-installer.tar.gz -C /opt

    Start XAMPP

    Bash

    sudo /opt/lampp/manager-linux &

Step 2: Start XAMPP Services

After installing XAMPP, you need to start the services:

    Open XAMPP Control Panel
        Windows: Click Start → XAMPP Control Panel
        Mac: Open Applications/XAMPP/manager-osx
        Linux: Already running from command

    Start Apache Web Server
        Find "Apache" in the control panel
        Click the "Start" button next to it
        Wait 2-3 seconds
        Status should change to GREEN with "Running"

    Start MySQL Database Server
        Find "MySQL" in the control panel
        Click the "Start" button next to it
        Status should change to GREEN with "Running"

    Verify Installation
        Open your web browser
        Go to http://localhost/
        You should see the XAMPP Dashboard
        Click on "phpMyAdmin" to verify MySQL is working
        You should see the database management interface

Step 3: Prepare Project Folder
Option A: Clone from GitHub (Recommended)

If you have Git installed:

    Open Command Prompt / Terminal
        Windows: Press Win+R, type cmd, press Enter
        Mac: Command + Space, type terminal
        Linux: Open terminal application

    Navigate to htdocs Folder

    Bash

    # Windows
    cd C:\xampp\htdocs

    # Mac
    cd /Applications/XAMPP/xamppfiles/htdocs

    # Linux
    cd /opt/lampp/htdocs

    Clone CraveCart Repository

    Bash

    git clone https://github.com/najeebahmad07/cravecart.git
    cd cravecart

Option B: Manual Download

If you don't have Git:

    Download ZIP File
        Visit: https://github.com/najeebahmad07/cravecart
        Click green "Code" button
        Select "Download ZIP"
        The file cravecart-main.zip will download

    Extract ZIP File
        Right-click the ZIP file
        Windows: Select "Extract All"
        Mac: Double-click to auto-extract
        Linux: Use unzip cravecart-main.zip

    Move to htdocs
        Cut/copy the extracted folder
        Navigate to your htdocs folder:
            Windows: C:\xampp\htdocs\
            Mac: /Applications/XAMPP/xamppfiles/htdocs/
            Linux: /opt/lampp/htdocs/
        Paste the folder there
        Rename folder to cravecart (if needed)

Step 4: Create Upload Directories

Image uploads need special folders with write permissions:
Windows

    Navigate to Project Folder
        Open File Explorer
        Go to C:\xampp\htdocs\cravecart\

    Create Folders
        Right-click empty space
        Select "New" → "Folder"
        Create folder named: uploads
        Inside uploads, create:
            restaurants folder
            menu folder

    Set Permissions
        Right-click uploads folder
        Select "Properties"
        Go to "Security" tab
        Click "Edit"
        Select your user
        Check "Full Control"
        Click "Apply" → "OK"

Mac/Linux

Bash

# Navigate to project
cd /path/to/cravecart

# Create folders
mkdir -p uploads/restaurants
mkdir -p uploads/menu

# Set permissions (allow write)
chmod -R 777 uploads

Step 5: Verify Project Structure

Your project folder should look exactly like this:

text

cravecart/
├── config/
│   ├── database.php           ✅ Database configuration
│   └── config.php             ✅ Site configuration
├── includes/
│   ├── header.php             ✅ HTML header
│   ├── navbar.php             ✅ Navigation bar
│   └── footer.php             ✅ Footer section
├── pages/
│   ├── home.php               ✅ Home page
│   ├── login.php              ✅ Login page
│   ├── register.php           ✅ Registration page
│   ├── restaurant.php         ✅ Restaurant menu
│   ├── cart.php               ✅ Shopping cart
│   ├── checkout.php           ✅ Checkout
│   ├── order-success.php      ✅ Order confirmation
│   └── dashboard.php          ✅ User dashboard
├── admin/
│   ├── index.php              ✅ Admin login
│   ├── dashboard.php          ✅ Admin dashboard
│   ├── restaurants.php        ✅ Manage restaurants
│   ├── menu-items.php         ✅ Manage menu
│   ├── orders.php             ✅ Manage orders
│   ├── users.php              ✅ View users
│   └── includes/
│       ├── header.php
│       ├── sidebar.php
│       └── footer.php
├── assets/
│   ├── css/
│   │   ├── style.css          ✅ Main stylesheet
│   │   └── admin.css          ✅ Admin styles
│   └── js/
│       ├── main.js            ✅ Frontend JS
│       └── admin.js           ✅ Admin JS
├── auth/
│   ├── login-process.php      ✅ Login handler
│   ├── register-process.php   ✅ Registration handler
│   └── logout.php             ✅ Logout handler
├── ajax/
│   ├── add-to-cart.php        ✅ Add to cart
│   ├── update-cart.php        ✅ Update cart
│   ├── remove-from-cart.php   ✅ Remove from cart
│   ├── place-order.php        ✅ Place order
│   └── get-order-details.php  ✅ Get order info
├── uploads/                   ✅ Image uploads
│   ├── restaurants/           ✅ Restaurant images
│   └── menu/                  ✅ Menu item images
├── index.php                  ✅ Router entry point
├── .htaccess                  ✅ Clean URL rules
├── database.sql               ✅ Database dump
└── README.md                  ✅ Documentation

If any folder is missing, create it manually.
🗄️ Database Setup
Step 1: Access phpMyAdmin

    Open phpMyAdmin
        Open your browser
        Go to: http://localhost/phpmyadmin/

    Login
        Username: root (default)
        Password: (leave empty - default)
        Click "Go"

You should see the phpMyAdmin interface with database list on left.
Step 2: Create New Database

    Click "New" Button
        On the left sidebar, click the "+ New" option
        OR at the top, click "Databases" tab

    Enter Database Name
        Database name field: cravecart
        Collation dropdown: Select utf8mb4_unicode_ci
        This is important for international character support

    Create Database
        Click "Create" button
        Wait 2-3 seconds
        You should see "Database cravecart created successfully"

Step 3: Import SQL Data

    Select the Database
        In the left sidebar, click on cravecart database
        The database is now selected (highlighted)

    Go to Import Tab
        At the top menu, click "Import" tab

    Choose SQL File
        Find "File to import" section
        Click "Choose File" button
        Navigate to your project folder
        Select database.sql file
        Click "Open"

    Run Import
        Scroll down slightly
        Click the "Import" button (blue button)
        Wait 5-10 seconds for import to complete
        You should see message: "Import has been successfully finished"

Step 4: Verify Database Tables

After import, verify all tables were created:

    Check Tables
        In left sidebar, click on cravecart database
        Expand the database to see tables
        You should see these 6 tables:
            ✅ users
            ✅ admin
            ✅ restaurants
            ✅ menu_items
            ✅ orders
            ✅ order_items

    View Sample Data
        Click on "users" table
        You should see 10 sample users
        Click on "restaurants" table
        You should see 5 sample restaurants
        All data is ready to use!

Step 5: Verify Database Connection

    Check config/database.php
    Open the file and verify:

    PHP

    private $host = "localhost";
    private $username = "root";
    private $password = "";           // Empty (default XAMPP)
    private $database = "cravecart";  // Matches what we created

    Test Connection
        Go to http://localhost/cravecart/
        If you see the home page (not errors), database is connected ✅

⚙️ Configuration
Step 1: Update Site URL (if needed)

File: config/config.php

If you installed CraveCart in a different location, update this:

PHP

define('SITE_URL', 'http://localhost/cravecart');

Examples for different setups:

PHP

// If installed as root
define('SITE_URL', 'http://localhost');

// If installed in subfolder
define('SITE_URL', 'http://localhost/food-delivery');

// For production
define('SITE_URL', 'https://yourdomain.com');

Step 2: Verify File Permissions

Ensure upload folders are writable:
Windows

    Right-click uploads folder
    Properties → Security → Edit
    Select user → Check "Full Control"
    Apply → OK

Mac/Linux

Bash

chmod -R 755 uploads/
chmod -R 755 uploads/restaurants/
chmod -R 755 uploads/menu/

Step 3: Enable Apache Modules

For clean URLs to work, Apache module mod_rewrite must be enabled:
Windows (XAMPP)

    Open XAMPP Control Panel
    Click "Config" button next to Apache
    Select "Apache (httpd.conf)"
    Search for: LoadModule rewrite_module modules/mod_rewrite.so
    If it has # at the start, remove it:

    text

    #LoadModule rewrite_module modules/mod_rewrite.so    ❌ WRONG
    LoadModule rewrite_module modules/mod_rewrite.so     ✅ CORRECT

    Save the file
    Restart Apache

Mac/Linux

Bash

sudo a2enmod rewrite
sudo service apache2 restart

Step 4: Verify .htaccess

Check that .htaccess file exists in project root and contains:

apache

RewriteEngine On
RewriteBase /cravecart/

RewriteCond %{REQUEST_URI} ^/cravecart/
RewriteRule ^admin/(.*)$ admin/$1 [L]

RewriteCond %{REQUEST_FILENAME} -f [OR]
RewriteCond %{REQUEST_FILENAME} -d
RewriteRule ^ - [L]

RewriteRule ^(.+)$ index.php?page=$1 [QSA,L]

This enables URLs like:

    ✅ http://localhost/cravecart/home (not /pages/home.php)
    ✅ http://localhost/cravecart/cart
    ✅ http://localhost/cravecart/admin/dashboard

🚀 Running the Application
Step 1: Ensure Services Are Running

    Check XAMPP Control Panel
        Apache: Should be GREEN "Running"
        MySQL: Should be GREEN "Running"

    If not running:
        Click "Start" button next to Apache
        Click "Start" button next to MySQL
        Wait 3-5 seconds

Step 2: Access the Application

Open your web browser and go to:

text

http://localhost/cravecart/

You should see the beautiful CraveCart Home Page with:

    ✅ Sticky navbar with logo
    ✅ Hero section with image slider
    ✅ Featured restaurants with ratings
    ✅ About section
    ✅ Testimonials carousel
    ✅ FAQ accordion
    ✅ Call-to-action section
    ✅ Professional footer

Step 3: Quick Navigation
Page	URL
Home Page	http://localhost/cravecart/
User Login	http://localhost/cravecart/login
User Register	http://localhost/cravecart/register
Shopping Cart	http://localhost/cravecart/cart
Checkout	http://localhost/cravecart/checkout
User Dashboard	http://localhost/cravecart/dashboard
Admin Login	http://localhost/cravecart/admin
Admin Dashboard	http://localhost/cravecart/admin/dashboard
phpMyAdmin	http://localhost/phpmyadmin/
Step 4: Test the Application

For Users:

    Go to home page
    See 5 restaurants
    Click "View Full Menu"
    Browse menu items
    Try adding items to cart (if not logged in, you'll need to login)

For Admin:

    Go to http://localhost/cravecart/admin
    Login with admin credentials (see below)
    Access all admin modules

🔐 Login Credentials
👤 User Test Accounts

All sample users have password: password
Email	Password	Name
neha@example.com	password	Neha Qazmi
ayaan@example.com	password	Ayaan Khan
sana@example.com	password	Sana Khan
shama@example.com	password	Shama Parveen
zaid@example.com	password	Zaid Ali
sameer@example.com	password	Sameer Hussain
faizan@example.com	password	Faizan Ahmed
imran@example.com	password	Imran Malik
ayesha@example.com	password	Ayesha Ali
fatima@example.com	password	Fatima Noor

How to Login:

    Go to http://localhost/cravecart/login
    Enter any user email
    Enter password: password
    Click "Login"
    You'll be logged in and can start ordering!

🔑 Admin Account
Email	Password	Role
admin@cravecart.com	password	Administrator

How to Login as Admin:

    Go to http://localhost/cravecart/admin
    Enter email: admin@cravecart.com
    Enter password: password
    Click "Login"
    Access admin panel with all features!

🆕 Create New User Account

You can also create a new account:

    Go to http://localhost/cravecart/register
    Fill in the form:
        Full Name
        Email (unique)
        Phone: +92-300-XXXXXXX
        Address
        Password (min 6 characters)
        Confirm Password
    Click "Create Account"
    You'll be auto-logged in
    Start ordering!

✅ Testing Guide
TEST 1: User Registration & Login
Register New Account

text

Steps:
1. Go to /register
2. Fill form with:
   - Name: Your Name
   - Email: yourname@example.com
   - Phone: +92-300-1234567
   - Address: Your Complete Address
   - Password: MyPassword123
   - Confirm: MyPassword123
3. Click "Create Account"

Expected Results:
✅ Account created
✅ Auto-logged in
✅ Redirected to dashboard
✅ User name shown in navbar

Login with Existing Account

text

Steps:
1. Go to /login
2. Enter email: neha@example.com


