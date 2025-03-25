<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản lý sản phẩm</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Quicksand:wght@400;500;600;700&display=swap" rel="stylesheet">
    <!-- Custom CSS -->
    <style>
        :root {
            --primary-color: #4361ee;
            --primary-light: #4895ef;
            --primary-dark: #3a0ca3;
            --secondary-color: #4cc9f0;
            --success-color: #4ade80;
            --danger-color: #f43f5e;
            --warning-color: #fb923c;
            --gray-100: #f8f9fc;
            --gray-200: #edf2f7;
            --gray-300: #e2e8f0;
            --gray-800: #2d3748;
            --transition: all 0.3s ease;
        }

        html, body {
            height: 100%;
            margin: 0;
        }

        body {
            font-family: 'Quicksand', sans-serif;
            background-color: var(--gray-100);
            color: var(--gray-800);
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        .content-wrapper {
            flex: 1 0 auto;
        }

        footer {
            flex-shrink: 0;
            margin-top: auto !important;
        }

        /* Header styling */
        .navbar {
            background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
            padding: 0.75rem 1rem;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.15);
        }

        .navbar-brand {
            color: white !important;
            font-weight: 700;
            font-size: 1.6rem;
            padding: 0.5rem 1rem;
            position: relative;
        }

        .navbar-brand::after {
            content: '';
            position: absolute;
            bottom: 8px;
            left: 50%;
            transform: translateX(-50%);
            width: 50px;
            height: 3px;
            background-color: var(--secondary-color);
            border-radius: 10px;
            opacity: 0;
            transition: var(--transition);
        }

        .navbar-brand:hover::after {
            opacity: 1;
            width: 70px;
        }

        .navbar-toggler {
            border: none;
            color: white;
            background-color: rgba(255, 255, 255, 0.1);
            border-radius: 8px;
            padding: 0.5rem 0.75rem;
        }

        .navbar-toggler:focus {
            box-shadow: 0 0 0 3px rgba(255, 255, 255, 0.25);
        }

        /* Navigation links */
        .nav-link {
            color: rgba(255, 255, 255, 0.9) !important;
            font-weight: 600;
            padding: 0.85rem 1.2rem;
            border-radius: 8px;
            margin: 0 0.2rem;
            transition: var(--transition);
            position: relative;
            z-index: 1;
        }

        .nav-link::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(255, 255, 255, 0.1);
            border-radius: 8px;
            z-index: -1;
            transform: scale(0.9);
            opacity: 0;
            transition: var(--transition);
        }

        .nav-link:hover::before {
            transform: scale(1);
            opacity: 1;
        }

        .nav-link:hover {
            transform: translateY(-2px);
            color: white !important;
        }

        .nav-link.active {
            background-color: rgba(255, 255, 255, 0.2);
            color: white !important;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        .nav-icon {
            margin-right: 0.5rem;
            font-size: 1.1rem;
        }

        /* Search box */
        .search-box {
            position: relative;
            margin-right: 1.5rem;
            flex-grow: 0.3;
            max-width: 350px;
        }
        
        .search-box form {
            width: 100%;
            position: relative;
        }

        .search-input {
            border-radius: 20px;
            padding: 0.75rem 1rem 0.75rem 2.75rem;
            background-color: rgba(255, 255, 255, 0.15);
            border: 1px solid rgba(255, 255, 255, 0.2);
            color: white;
            font-weight: 500;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            transition: var(--transition);
            width: 100%;
        }

        .search-input:focus {
            background-color: rgba(255, 255, 255, 0.25);
            border-color: rgba(255, 255, 255, 0.3);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.15);
            color: white;
        }

        .search-input::placeholder {
            color: rgba(255, 255, 255, 0.7);
            font-weight: 500;
        }

        .search-icon {
            position: absolute;
            left: 1rem;
            top: 50%;
            transform: translateY(-50%);
            color: rgba(255, 255, 255, 0.8);
            font-size: 1rem;
            transition: var(--transition);
            pointer-events: none;
        }
        
        .search-input:focus + .search-icon {
            color: white;
        }

        /* User profile */
        .user-profile {
            display: flex;
            align-items: center;
            color: white;
            text-decoration: none;
            padding: 0.5rem 1rem;
            border-radius: 30px;
            transition: var(--transition);
            background-color: rgba(255, 255, 255, 0.1);
        }

        .user-profile:hover {
            background-color: rgba(255, 255, 255, 0.2);
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        .user-avatar {
            width: 38px;
            height: 38px;
            border-radius: 50%;
            margin-right: 0.8rem;
            border: 2px solid rgba(255, 255, 255, 0.5);
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            transition: var(--transition);
        }
        
        .user-profile:hover .user-avatar {
            border-color: white;
            transform: scale(1.05);
        }
        
        .user-name {
            font-weight: 600;
            font-size: 0.95rem;
        }
        
        .auth-links {
            display: flex;
            align-items: center;
        }
        
        .auth-links .nav-link {
            margin-left: 0.5rem;
            background-color: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
        
        .auth-links .nav-link:first-child {
            background-color: var(--secondary-color);
            color: var(--primary-dark) !important;
            font-weight: 700;
        }
        
        .auth-links .nav-link:hover {
            background-color: rgba(255, 255, 255, 0.2);
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        /* Dropdown menu */
        .dropdown-menu {
            border: none;
            border-radius: 12px;
            box-shadow: 0 5px 25px rgba(0, 0, 0, 0.12);
            padding: 0.75rem 0;
            margin-top: 0.75rem;
            min-width: 240px;
            animation: dropdownFade 0.3s ease;
        }
        
        @keyframes dropdownFade {
            from {
                opacity: 0;
                transform: translateY(10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .dropdown-header {
            font-weight: 700;
            color: var(--primary-color);
            padding: 0.75rem 1.5rem;
            font-size: 1rem;
        }

        .dropdown-item {
            padding: 0.75rem 1.5rem;
            font-weight: 600;
            color: var(--gray-800);
            transition: var(--transition);
            position: relative;
            z-index: 1;
        }
        
        .dropdown-item::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            height: 100%;
            width: 0;
            background-color: rgba(67, 97, 238, 0.1);
            z-index: -1;
            transition: var(--transition);
        }

        .dropdown-item:hover {
            color: var(--primary-color);
            background-color: transparent;
        }
        
        .dropdown-item:hover::before {
            width: 100%;
        }
        
        .dropdown-item i {
            width: 20px;
            text-align: center;
            margin-right: 0.75rem;
            transition: var(--transition);
        }
        
        .dropdown-item:hover i {
            transform: translateX(3px);
            color: var(--primary-color);
        }

        .dropdown-divider {
            margin: 0.5rem 0;
            opacity: 0.1;
        }

        .badge {
            font-size: 0.7em;
            padding: 0.35em 0.65em;
            margin-left: 0.5em;
            border-radius: 6px;
            font-weight: 700;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }
        
        .badge-admin {
            background-color: var(--danger-color);
            color: white;
        }
        
        /* Mobile optimizations */
        @media (max-width: 992px) {
            .search-box {
                width: 100%;
                max-width: none;
                margin: 1rem 0;
            }
            
            .navbar-collapse {
                background-color: rgba(0, 0, 0, 0.03);
                border-radius: 12px;
                padding: 1rem;
                margin-top: 1rem;
            }
            
            .nav-link {
                margin: 0.25rem 0;
            }
            
            .auth-links {
                flex-direction: column;
                width: 100%;
            }
            
            .auth-links .nav-link {
                margin: 0.25rem 0;
                width: 100%;
                text-align: center;
            }
            
            .user-profile {
                margin: 0.5rem 0;
                justify-content: center;
            }
        }
        
        /* Notification badge */
        .nav-item .notification-badge {
            position: relative;
        }
        
        .notification-badge::after {
            content: '3';
            position: absolute;
            top: -5px;
            right: -5px;
            background-color: var(--danger-color);
            color: white;
            border-radius: 50%;
            width: 18px;
            height: 18px;
            font-size: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg sticky-top">
        <div class="container">
            <!-- Logo -->
            <a class="navbar-brand d-flex align-items-center" href="/webbanhang/Product">
                <i class="fas fa-shopping-bag me-2"></i>
                Shop<span class="text-secondary">PE</span>
            </a>

            <!-- Toggle Button -->
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent" aria-controls="navbarContent" aria-expanded="false" aria-label="Toggle navigation">
                <i class="fas fa-bars"></i>
            </button>

            <!-- Navbar Content -->
            <div class="collapse navbar-collapse" id="navbarContent">
                <!-- Main Navigation -->
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link" href="/webbanhang/Product">
                            <i class="fas fa-home nav-icon"></i>Trang chủ
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/webbanhang/Product">
                            <i class="fas fa-boxes nav-icon"></i>Sản phẩm
                        </a>
                    </li>
                    <?php if (SessionHelper::hasPermission('add_product')): ?>
                    <li class="nav-item">
                        <a class="nav-link" href="/webbanhang/Product/add">
                            <i class="fas fa-plus-circle nav-icon"></i>Thêm mới
                        </a>
                    </li>
                    <?php endif; ?>
                    <?php if (SessionHelper::hasPermission('view_categories')): ?>
                    <li class="nav-item">
                        <a class="nav-link" href="/webbanhang/Category">
                            <i class="fas fa-tags nav-icon"></i>Danh mục
                        </a>
                    </li>
                    <?php endif; ?>
                    <?php if (SessionHelper::hasPermission('view_cart')): ?>
                    <li class="nav-item">
                        <a class="nav-link notification-badge" href="/webbanhang/Product/cart">
                            <i class="fas fa-shopping-cart me-1"></i> Giỏ hàng
                        </a>
                    </li>
                    <?php endif; ?>
                </ul>

                <!-- Search Box -->
                <div class="search-box">
                    <form action="/webbanhang/Product/search" method="GET">
                        <input type="search" name="keyword" class="form-control search-input" placeholder="Tìm kiếm sản phẩm...">
                        <i class="fas fa-search search-icon"></i>
                    </form>
                </div>

                <!-- User Authentication -->
                <div class="d-flex align-items-center">
                    <?php if(isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true): ?>
                        <div class="dropdown">
                            <a href="#" class="user-profile dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                                <img src="https://ui-avatars.com/api/?name=<?php echo urlencode($_SESSION['username']); ?>&background=random&bold=true&color=fff" 
                                     alt="User Avatar" 
                                     class="user-avatar">
                                <span class="user-name d-none d-md-inline">
                                    <?php echo htmlspecialchars($_SESSION['username']); ?>
                                    <?php if($_SESSION['role'] === 'admin'): ?>
                                        <span class="badge badge-admin">Admin</span>
                                    <?php endif; ?>
                                </span>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li><h6 class="dropdown-header">Xin chào, <?php echo htmlspecialchars($_SESSION['fullname'] ?? $_SESSION['username']); ?></h6></li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <a class="dropdown-item" href="/webbanhang/account/profile">
                                        <i class="fas fa-user-circle"></i>Thông tin tài khoản
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="/webbanhang/Product/orders">
                                        <i class="fas fa-shopping-bag"></i>Đơn hàng của tôi
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="/webbanhang/account/settings">
                                        <i class="fas fa-cog"></i>Cài đặt tài khoản
                                    </a>
                                </li>
                                <?php if($_SESSION['role'] === 'admin'): ?>
                                    <li><hr class="dropdown-divider"></li>
                                    <li>
                                        <a class="dropdown-item" href="/webbanhang/admin">
                                            <i class="fas fa-tachometer-alt"></i>Bảng điều khiển
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item" href="/webbanhang/admin/users">
                                            <i class="fas fa-users"></i>Quản lý người dùng
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item" href="/webbanhang/admin/reports">
                                            <i class="fas fa-chart-bar"></i>Báo cáo & Thống kê
                                        </a>
                                    </li>
                                <?php endif; ?>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <a class="dropdown-item text-danger" href="/webbanhang/account/logout">
                                        <i class="fas fa-sign-out-alt"></i>Đăng xuất
                                    </a>
                                </li>
                            </ul>
                        </div>
                    <?php else: ?>
                        <div class="auth-links">
                            <a class="nav-link" href="/webbanhang/account/login">
                                <i class="fas fa-sign-in-alt nav-icon"></i>Đăng nhập
                            </a>
                            <a class="nav-link" href="/webbanhang/account/register">
                                <i class="fas fa-user-plus nav-icon"></i>Đăng ký
                            </a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content Container -->
    <div class="content-wrapper">
        <div class="container py-4">
            <!-- Content will be inserted here -->
        </div>
    </div>

    <!-- Bootstrap Bundle JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Custom JS -->
    <script>
        // Add active class to current nav item
        document.addEventListener('DOMContentLoaded', function() {
            const currentLocation = location.pathname;
            const menuItems = document.querySelectorAll('.nav-link');
            const searchInput = document.querySelector('.search-input');

            menuItems.forEach(item => {
                if(currentLocation.includes(item.getAttribute('href'))) {
                    item.classList.add('active');
                }
            });

            // Focus effects for search
            searchInput.addEventListener('focus', function() {
                this.style.width = '100%';
                this.style.backgroundColor = 'rgba(255,255,255,0.25)';
            });

            searchInput.addEventListener('blur', function() {
                this.style.width = '';
                this.style.backgroundColor = '';
            });
            
            // Tooltip initialization (if needed)
            const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            tooltipTriggerList.map(function (tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl, {
                    boundary: document.body
                });
            });
        });
    </script>
</body>
</html>