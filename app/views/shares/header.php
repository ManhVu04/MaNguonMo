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
    <!-- Custom CSS -->
    <style>
        :root {
            --primary-color: #4e73df;
            --secondary-color: #858796;
            --success-color: #1cc88a;
        }

        body {
            font-family: 'Segoe UI', Roboto, 'Helvetica Neue', sans-serif;
            background-color: #f8f9fc;
        }

        .navbar {
            background: linear-gradient(to right, var(--primary-color), #224abe);
            padding: 1rem;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }

        .navbar-brand {
            color: white !important;
            font-weight: 700;
            font-size: 1.5rem;
            padding: 0.5rem 1rem;
        }

        .nav-link {
            color: rgba(255,255,255,0.9) !important;
            font-weight: 500;
            padding: 0.7rem 1.2rem;
            border-radius: 5px;
            transition: all 0.3s;
        }

        .nav-link:hover {
            background-color: rgba(255,255,255,0.1);
            transform: translateY(-2px);
        }

        .nav-link.active {
            background-color: rgba(255,255,255,0.2);
            color: white !important;
        }

        .nav-icon {
            margin-right: 0.5rem;
            font-size: 1.1rem;
        }

        .search-box {
            position: relative;
            margin-right: 1rem;
        }

        .search-input {
            border-radius: 20px;
            padding-left: 2.5rem;
            background-color: rgba(255,255,255,0.1);
            border: 1px solid rgba(255,255,255,0.2);
            color: white;
        }

        .search-input::placeholder {
            color: rgba(255,255,255,0.7);
        }

        .search-icon {
            position: absolute;
            left: 1rem;
            top: 50%;
            transform: translateY(-50%);
            color: rgba(255,255,255,0.7);
        }

        .user-profile {
            display: flex;
            align-items: center;
            color: white;
            text-decoration: none;
            padding: 0.5rem 1rem;
            border-radius: 5px;
            transition: all 0.3s;
        }

        .user-profile:hover {
            background-color: rgba(255,255,255,0.1);
        }

        .user-avatar {
            width: 35px;
            height: 35px;
            border-radius: 50%;
            margin-right: 0.8rem;
            border: 2px solid rgba(255,255,255,0.3);
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg sticky-top">
        <div class="container-fluid">
            <!-- Logo -->
            <a class="navbar-brand d-flex align-items-center" href="/webbanhang/Product">
                <i class="fas fa-store-alt me-2"></i>
                Quản lý sản phẩm
            </a>

            <!-- Toggle Button -->
            <button class="navbar-toggler text-white" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent">
                <i class="fas fa-bars"></i>
            </button>

            <!-- Navbar Content -->
            <div class="collapse navbar-collapse" id="navbarContent">
                <!-- Main Navigation -->
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link <?php echo !isset($url[1]) ? 'active' : ''; ?>" href="/webbanhang/Product">
                            <i class="fas fa-boxes nav-icon"></i>Sản phẩm
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?php echo isset($url[1]) && $url[1] == 'add' ? 'active' : ''; ?>" href="/webbanhang/Product/add">
                            <i class="fas fa-plus-circle nav-icon"></i>Thêm mới
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/webbanhang/Category">
                            <i class="fas fa-tags nav-icon"></i>Danh mục
                        </a>
                    </li>
                </ul>

                <!-- Search Box -->
                <div class="search-box">
                    <i class="fas fa-search search-icon"></i>
                    <input type="search" class="form-control search-input" placeholder="Tìm kiếm...">
                </div>

                <!-- User Profile -->
                <div class="d-flex align-items-center">
                    <a href="#" class="user-profile">
                        <img src="https://ui-avatars.com/api/?name=Admin&background=random" alt="User Avatar" class="user-avatar">
                        <span class="d-none d-md-inline">Admin</span>
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content Container -->
    <div class="container-fluid py-4">

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
                if(item.getAttribute('href') === currentLocation) {
                    item.classList.add('active');
                }
            });

            // Animate search on focus
            searchInput.addEventListener('focus', function() {
                this.style.width = '300px';
                this.style.backgroundColor = 'rgba(255,255,255,0.2)';
            });

            searchInput.addEventListener('blur', function() {
                this.style.width = '';
                this.style.backgroundColor = '';
            });
        });
    </script>
</body>
</html>