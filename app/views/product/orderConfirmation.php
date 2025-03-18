<?php include 'app/views/shares/header.php'; ?>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-lg border-0 rounded-lg animate__animated animate__fadeIn">
                <div class="card-body text-center p-5">
                    <div class="success-animation mb-4">
                        <i class="fas fa-check-circle text-success display-1 animate__animated animate__bounceIn"></i>
                    </div>
                    <h1 class="display-4 fw-bold text-success mb-4">Đặt hàng thành công!</h1>
                    <p class="lead mb-4">Cảm ơn bạn đã đặt hàng. Đơn hàng của bạn đã được xử lý thành công.</p>
                    <div class="d-grid gap-3 col-lg-6 mx-auto">
                        <a href="/webbanhang/Product" class="btn btn-primary btn-lg">
                            <i class="fas fa-shopping-cart me-2"></i>Tiếp tục mua sắm
                        </a>
                        <a href="/webbanhang/Product/orderHistory" class="btn btn-outline-secondary btn-lg">
                            <i class="fas fa-history me-2"></i>Xem lịch sử đơn hàng
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .success-animation {
        animation: scale-up 0.5s ease-in-out;
    }
    
    @keyframes scale-up {
        0% {
            transform: scale(0);
        }
        70% {
            transform: scale(1.2);
        }
        100% {
            transform: scale(1);
        }
    }

    .card {
        transition: transform 0.3s ease;
    }
    
    .card:hover {
        transform: translateY(-5px);
    }

    .btn {
        transition: all 0.3s ease;
    }

    .btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    }
</style>

<!-- Add Font Awesome and Animate.css in header -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">

<?php include 'app/views/shares/footer.php'; ?>