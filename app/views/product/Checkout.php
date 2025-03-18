<?php include 'app/views/shares/header.php'; ?>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-lg border-0 rounded-lg animate__animated animate__fadeIn">
                <div class="card-header bg-primary text-white text-center py-4">
                    <h2 class="mb-0"><i class="fas fa-shopping-cart me-2"></i>Thanh toán</h2>
                </div>
                <div class="card-body p-4">
                    <form method="POST" action="/webbanhang/Product/processCheckout" class="needs-validation" novalidate>
                        <div class="mb-4">
                            <label for="name" class="form-label fw-bold"><i class="fas fa-user me-2"></i>Họ tên:</label>
                            <input type="text" id="name" name="name" class="form-control form-control-lg" required>
                            <div class="invalid-feedback">Vui lòng nhập họ tên</div>
                        </div>
                        
                        <div class="mb-4">
                            <label for="phone" class="form-label fw-bold"><i class="fas fa-phone me-2"></i>Số điện thoại:</label>
                            <input type="tel" id="phone" name="phone" class="form-control form-control-lg" 
                                   pattern="[0-9]{10}" required>
                            <div class="invalid-feedback">Vui lòng nhập số điện thoại hợp lệ (10 số)</div>
                        </div>
                        
                        <div class="mb-4">
                            <label for="address" class="form-label fw-bold"><i class="fas fa-map-marker-alt me-2"></i>Địa chỉ:</label>
                            <textarea id="address" name="address" class="form-control form-control-lg" 
                                    rows="3" required></textarea>
                            <div class="invalid-feedback">Vui lòng nhập địa chỉ</div>
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="fas fa-credit-card me-2"></i>Xác nhận thanh toán
                            </button>
                            <a href="/webbanhang/Product/cart" class="btn btn-outline-secondary btn-lg">
                                <i class="fas fa-arrow-left me-2"></i>Quay lại giỏ hàng
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add these scripts before closing body tag -->
<script>
    // Form validation
    (function () {
        'use strict'
        var forms = document.querySelectorAll('.needs-validation')
        Array.prototype.slice.call(forms).forEach(function (form) {
            form.addEventListener('submit', function (event) {
                if (!form.checkValidity()) {
                    event.preventDefault()
                    event.stopPropagation()
                }
                form.classList.add('was-validated')
            }, false)
        })
    })()

    // Input animation
    document.querySelectorAll('.form-control').forEach(input => {
        input.addEventListener('focus', function() {
            this.parentElement.classList.add('shadow-sm');
        });
        input.addEventListener('blur', function() {
            this.parentElement.classList.remove('shadow-sm');
        });
    });
</script>

<!-- Add these styles in header -->
<style>
    .form-control:focus {
        border-color: #0d6efd;
        box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
    }
    
    .form-control {
        transition: all 0.3s ease-in-out;
    }
    
    .card {
        transition: transform 0.3s ease-in-out;
    }
    
    .card:hover {
        transform: translateY(-5px);
    }
    
    .btn {
        transition: all 0.3s ease;
    }
</style>

<?php include 'app/views/shares/footer.php'; ?>