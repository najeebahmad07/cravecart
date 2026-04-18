<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $pageTitle ?? 'CraveCart - Food Delivery'; ?></title>

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <!-- Google Fonts - Poppins -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'><text y='.9em' font-size='90'>🍕</text></svg>">

    <!-- Custom CSS -->
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
            --shadow-sm: 0 2px 4px rgba(0,0,0,0.1);
            --shadow-md: 0 4px 6px rgba(0,0,0,0.1);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Poppins', sans-serif;
            color: var(--dark-color);
        }

        /* HEADER STYLES */
        .header-top {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 0.8rem 0;
            font-size: 0.9rem;
        }

        .header-top a {
            color: white;
            text-decoration: none;
            transition: all 0.3s ease;
        }

        .header-top a:hover {
            opacity: 0.8;
        }

        .contact-info {
            display: flex;
            gap: 2rem;
            flex-wrap: wrap;
        }

        .contact-item {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .contact-item i {
            font-size: 1rem;
        }

        /* NAVBAR STYLES */
        .navbar {
            background: white;
            box-shadow: var(--shadow-sm);
            padding: 1rem 0;
            transition: all 0.3s ease;
        }

        .navbar.scrolled {
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
            padding: 0.8rem 0;
        }

        .navbar-brand {
            font-size: 1.8rem;
            font-weight: 800;
            color: var(--primary-color) !important;
            transition: all 0.3s ease;
        }

        .navbar-brand:hover {
            transform: scale(1.05);
        }

        .navbar-brand span {
            background: var(--gradient-1);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .nav-link {
            font-weight: 500;
            color: var(--dark-color) !important;
            margin: 0 0.5rem;
            padding: 0.5rem 1rem !important;
            border-radius: 8px;
            transition: all 0.3s ease;
            position: relative;
        }

        .nav-link:hover {
            background-color: var(--primary-light);
            color: var(--primary-color) !important;
        }

        .nav-link::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 50%;
            width: 0;
            height: 2px;
            background: var(--primary-color);
            transition: all 0.3s ease;
            transform: translateX(-50%);
        }

        .nav-link:hover::after {
            width: 60%;
        }

        .cart-icon {
            position: relative;
            font-size: 1.3rem;
            transition: all 0.3s ease;
        }

        .cart-icon:hover {
            color: var(--primary-color);
            transform: scale(1.1);
        }

        .cart-badge {
            position: absolute;
            top: -8px;
            right: -8px;
            background: var(--secondary-color);
            color: white;
            width: 24px;
            height: 24px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.75rem;
            font-weight: 700;
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.1); }
        }

        .btn-signup {
            background: var(--gradient-1);
            color: white;
            border: none;
            border-radius: 25px;
            padding: 0.6rem 1.5rem;
            font-weight: 600;
            transition: all 0.3s ease;
            margin-left: 1rem;
        }

        .btn-signup:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 25px rgba(56, 88, 233, 0.3);
            color: white;
        }

        .user-menu {
            display: flex;
            align-items: center;
            gap: 1.5rem;
        }

        .user-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: var(--primary-light);
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--primary-color);
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .user-avatar:hover {
            background: var(--primary-color);
            color: white;
        }

        .dropdown-menu {
            border: none;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.15);
            padding: 0.5rem 0;
            margin-top: 0.5rem;
        }

        .dropdown-item {
            padding: 0.8rem 1.5rem;
            transition: all 0.3s ease;
            border-radius: 8px;
            margin: 0 0.5rem;
        }

        .dropdown-item:hover {
            background-color: var(--primary-light);
            color: var(--primary-color);
        }

        .dropdown-item i {
            margin-right: 0.7rem;
            width: 20px;
        }

        /* RESPONSIVE */
        @media (max-width: 991px) {
            .header-top {
                display: none;
            }

            .navbar-collapse {
                background: white;
                padding: 1rem;
                border-radius: 12px;
                margin-top: 1rem;
                box-shadow: var(--shadow-md);
            }

            .nav-link {
                padding: 0.5rem 0 !important;
                margin: 0.3rem 0;
            }

            .nav-link::after {
                display: none;
            }

            .btn-signup {
                margin-left: 0;
                margin-top: 1rem;
                width: 100%;
                text-align: center;
            }
        }
    </style>
</head>
<body>
    <!-- HEADER TOP -->
    <div class="header-top">
        <div class="container">
            <div class="d-flex justify-content-between align-items-center">
                <div class="contact-info">
                    <div class="contact-item">
                        <i class="bi bi-telephone"></i>
                        <a href="tel:+91 88820 82994">+91 88820 82994</a>
                    </div>
                    <div class="contact-item">
                        <i class="bi bi-envelope"></i>
                        <a href="mailto:info@cravecart.com">info@cravecart.com</a>
                    </div>
                    <div class="contact-item">
                        <i class="bi bi-clock"></i>
                        <span>Open 11:00 AM - 11:00 PM</span>
                    </div>
                </div>
                <div class="d-flex gap-3">
                    <a href="#" title="Facebook"><i class="bi bi-facebook"></i></a>
                    <a href="#" title="Instagram"><i class="bi bi-instagram"></i></a>
                    <a href="#" title="Twitter"><i class="bi bi-twitter"></i></a>
                    <a href="#" title="WhatsApp"><i class="bi bi-whatsapp"></i></a>
                </div>
            </div>
        </div>
    </div>